<?php
require_once (__DIR__ . "/../../partials/nav.php");

if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
$db = getDB();
$user = get_user_id();
$stmt = $db->prepare("SELECT account, account_type from Accounts WHERE Accounts.user_id=:q");
$r = $stmt->execute([":q" => $user]);
$accs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Create Transfer</h3>
<form method = "POST">
    <label>Choose Source Account</label>
    <select name="account_source" placeholder="Account Source">
        <?php foreach ($accs as $acc): ?>
                <?php if($acc["account_type"] != "loan"):?>
                <option value="<?php echo($acc["account"]); ?>"
                ><?php echo($acc["account"]); ?></option>
                <?php endif ?>
        <?php endforeach; ?>
    </select>
    <label>Choose Destination Account</label>
    <select name="account_destination" placeholder="Account Destination">
        <?php foreach ($accs as $acc): ?>
            <option value="<?php echo($acc["account"]); ?>"
            ><?php echo($acc["account"]); ?></option>
        <?php endforeach; ?>
    </select>
    <input type="number" min="0.00" placeholder="Amount" name="amount"/>
    <input type="text" placeholder= "Memo" name="memo"/>
    <input type="submit" name="save" value="Transfer"/>
</form>

<?php
$type = "transfer";
//$worldID = 1;
$check = true;
if(isset($_POST["save"])){
    $src = $_POST["account_source"];
    $dest = $_POST["account_destination"];
    $amount = $_POST["amount"];
    $memo = $_POST["memo"];
    $db = getDB();

    //database variable setters
    $srcBalance = 0;
    $srcAmount = 0;
    $srcExpect = 0;

    $destBalance = 0;
    $destAmount = 0;
    $destExpect = 0;

    //source account balance
    $results = [];
    $stmt = $db->prepare("SELECT id, balance from Accounts WHERE account=:src");
    $r = $stmt->execute([":src" => $src]);
    $results = $stmt->fetch(PDO::FETCH_ASSOC);

    $srcBalance = $results["balance"];
    $srcID = $results["id"];

    if (!$r) {
        $e = $stmt->errorInfo();
        flash("Error accessing the Source Account Balance: " . var_export($e, true));
        $check = false;
    }

    //dest account balance
    $destResults = [];
    $stmt = $db->prepare("SELECT id, balance, account_type from Accounts WHERE account=:dest");
    $r = $stmt->execute([":dest" => $dest]);
    $destResults = $stmt->fetch(PDO::FETCH_ASSOC);

    $destBalance = $destResults["balance"];
    $destID = $destResults["id"];
    $destType = $destResults["account_type"];

    if (!$r){
        $e = $stmt->errorInfo();
        flash("Error accessing the Dest Account Balance: " . var_export($e, true));
        $check = false;
    }

    $amount = (int)$amount;
    $srcBalance = (int)$srcBalance;
    $destBalance = (int)$destBalance;

    //setting up transfer values

    if($check){
        if($amount > $srcBalance){
            $check = false;
            flash("Please enter valid amount to transfer");
        }
        else{
            $srcExpect = $srcBalance - $amount;
            $srcAmount = $amount * -1;

            //will not add to balance of loan account only savings and and checking accounts
            if($destType != "loan") {
                $destExpect = $destBalance + $amount;
                $destAmount = $amount;
            }
            //modified to take money out of loan account to lower the balance.
            else{
                $destExpect = $destBalance - $amount;
                $destAmount = $amount;
            }
        }
    }

    if($check){
        $stmt = $db->prepare("INSERT INTO Transactions (src, dest, diff, reason, memo) VALUES(:src, :dest,:diff, :type, :memo)");
        $r = $stmt->execute([
            ":src" => $srcID,
            ":dest" => $destID,
            ":type" => $type,
            ":diff" => $srcAmount,
            ":memo" => $memo,
        ]);
    }

    if($check){
        $stmt = $db->prepare("INSERT INTO Transactions (src, dest, reason, diff, memo) VALUES(:src, :dest, :type, :diff, :memo)");
        $r = $stmt->execute([
            ":src" => $destID,
            ":dest" => $srcID,
            ":type" => $type,
            ":diff" => $destAmount,
            ":memo" => $memo,
        ]);
    }

    //updating dest and source balances

    if($check){
        $destBalanceSum = [];
        $stmt = $db->prepare("SELECT SUM(diff) as total from Transactions WHERE src=:id");
        $r = $stmt->execute([":id" => $destID]);
        $destBalanceSum = $stmt->fetch(PDO::FETCH_ASSOC);
        $destBalFinal = $destBalanceSum["total"];

        $srcBalanceSum = [];
        $stmt = $db->prepare("SELECT SUM(diff) as total from Transactions WHERE src=:id");
        $r = $stmt->execute([":id" => $srcID]);
        $srcBalanceSum = $stmt->fetch(PDO::FETCH_ASSOC);
        $srcBalFinal = $srcBalanceSum["total"];

        //dest
        $stmt = $db->prepare("UPDATE Accounts set balance=:destUpdate WHERE id=:id");
        $r = $stmt->execute([
            ":destUpdate" => $destBalFinal,
            ":id" => $destID
        ]);

        if(!$r){
            $e = $stmt->errorInfo();
            flash("Error updating Dest balance: " . var_export($e, true));
            $check = false;
        }

        //source
        $stmt = $db->prepare("UPDATE Accounts set balance=:srcUpdate WHERE id=:id");
        $r = $stmt->execute([
            ":srcUpdate" => $srcBalFinal,
            ":id" => $srcID
        ]);

        if(!$r){
            $e = $stmt->errorInfo();
            flash("Error updating Source balance: " . var_export($e, true));
            $check = false;
        }
    }
    if($check){
        flash("Transaction processed successfully!");
    }

}
require(__DIR__ . "/../../partials/footer.php");
?>