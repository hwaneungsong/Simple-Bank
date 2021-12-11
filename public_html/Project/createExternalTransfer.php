<?php
require_once (__DIR__ . "/../../partials/nav.php");

if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
$db = getDB();
$user = get_user_id();
$closedCheck = 'true';
$stmt = $db->prepare("SELECT account from Accounts WHERE user_id=:id LIMIT 10");
$r = $stmt->execute([":id" => $user]);
$accs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Create External Transfer</h3>
<form method = "POST">
    <label>Choose Source Account</label>
    <select name="account_source" placeholder="Account Source">
        <?php foreach ($accs as $acc): ?>
            <option value="<?php se($acc["account"]); ?>"
            ><?php se($acc["account"]); ?></option>
        <?php endforeach; ?>
    </select>
    <label>Enter Destination Account Information</label>
    <input type="number" minlength="4" maxlength="4" placeholder="Last four digits of ACC #" name="accNumber"/>
    <input type="text" name="lastName" placeholder="Destination Account Holders Last Name"/>
    <input type="number" min="0.00" placeholder="Amount" name="amount"/>
    <input type="text" placeholder= "Memo" name="memo"/>
    <input type="submit" name="save" value="Transfer"/>
</form>

<?php
$type = "ext-transfer";
//$worldID = 1;
$check = true;
if(isset($_POST["save"])){
    $src = $_POST["account_source"];
    $dest = $_POST["accNumber"];
    $amount = $_POST["amount"];
    $destName = $_POST["lastName"];
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
    $stmt = $db->prepare("SELECT acc.id, acc.balance from Accounts as acc INNER JOIN Users WHERE acc.account like :dest AND acc.user_id = Users.id AND Users.lastName=:lastName");
    $r = $stmt->execute([":dest" => "%$dest", ":lastName" => $destName]);
    $destResults = $stmt->fetch(PDO::FETCH_ASSOC);

    $destBalance = $destResults["balance"];
    $destID = $destResults["id"];

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

            $destExpect = $destBalance + $amount;
            $destAmount = $amount;
        }
    }

    if($check){
        $stmt = $db->prepare("INSERT INTO Transactions (src, dest, diff, reason, memo) VALUES(:src, :dest, :diff, :type, :memo)");
        $r = $stmt->execute([
            ":src" => $srcID,
            ":dest" => $destID,
            ":reason" => $type,
            ":diff" => $srcAmount,
            ":memo" => $memo,
        ]);
        if (!$r) {
            $e = $stmt->errorInfo();
            flash("Failed to write transaction for Source Account: " . var_export($e, true));
            $check = false;
        }
    }

    if($check){
        $stmt = $db->prepare("INSERT INTO Transactions (src, dest, reason, diff, memo) VALUES(:src, :dest, :type, :diff, :memo)");
        $r = $stmt->execute([
            ":src" => $destID,
            ":dest" => $srcID,
            ":reason" => $type,
            ":diff" => $destAmount,
            ":memo" => $memo,
        ]);
        if (!$r) {
            $e = $stmt->errorInfo();
            flash("Failed to process transaction for Dest Account: " . var_export($e, true));
            $check = false;
        }
    }

    //updating dest and source balances

    if($check){
        $destBalanceSum = [];
        $stmt = $db->prepare("SELECT SUM(amount) as total from Transactions WHERE src=:id");
        $r = $stmt->execute([":id" => $destID]);
        $destBalanceSum = $stmt->fetch(PDO::FETCH_ASSOC);
        $destBalFinal = $destBalanceSum["total"];

        $srcBalanceSum = [];
        $stmt = $db->prepare("SELECT SUM(amount) as total from Transactions WHERE src=:id");
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