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
$stmt = $db->prepare("SELECT account from Accounts WHERE user_id=:id");
$r = $stmt->execute([":id" => $user]);
$accs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Create Transaction</h3>
<form method = "POST">
    <label>Choose Account</label>
    <select name="account" placeholder="Account Source">
        <?php foreach ($accs as $acc): ?>
            <option value="<?php se($acc["account"]); ?>"
            ><?php se($acc["account"]); ?></option>
        <?php endforeach; ?>
    </select>
    <label>Transaction Type</label>
    <select name="reason">
        <option value="deposit">Deposit</option>
        <option value="withdraw">Withdraw</option>
    </select>
    <input type="number" min="0.00" placeholder="Amount" name="amount"/>
    <input type="text" placeholder= "Memo" name="memo"/>
    <input type="submit" name="save" value="Create"/>
</form>

<?php
$worldID = 1;
$check = true;
if(isset($_POST["save"])){
    $src = $_POST["account"];
    $type = $_POST["reason"];
    $amount = $_POST["amount"];
    $memo = $_POST["memo"];
    $db = getDB();

    //database variable setters
    $srcBalance = 0;
    $srcAmount = 0;
    $srcExpect = 0;

    $worldBalance = 0;
    $worldAmount = 0;
    $worldExpect = 0;

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

    //world account balance

    $stmt = $db->prepare("SELECT balance from Accounts WHERE id=:id");
    $r = $stmt->execute([":id" => $worldID]);
    $worldBalance = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$r) {
        $e = $stmt->errorInfo();
        flash("Error accessing the World Account Balance: " . var_export($e, true));
        $check = false;
    }

    $amount = (int)$amount;
    $srcBalance = (int)$srcBalance;
    $worldBalance = (int)$worldBalance;

    //checking to see if action_type is withdraw or deposit

    if($check){
        if($type == "withdraw"){
            if($amount > $srcBalance){
                $check = false;
                flash("Please enter valid amount to withdraw");
            }
            else{
                $srcExpect = $srcBalance - $amount;
                $srcAmount = $amount * -1;

                $worldExpect = $worldBalance + $amount;
                $worldAmount = $amount;
            }
        }
        elseif ($type == "deposit"){
            $srcExpect = $srcBalance + $amount;
            $srcAmount = $amount;

            $worldExpect = $worldBalance - $amount;
            $worldAmount = $amount * -1;
        }
    }

    if($check){
        $stmt = $db->prepare("INSERT INTO Transactions (src, dest, reason, diff, memo) VALUES(:src, :dest, :type, :diff, :memo)");
        $r = $stmt->execute([
            ":src" => $srcID,
            ":dest" => $worldID,
            ":type" => $type,
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
            ":src" => $worldID,
            ":dest" => $srcID,
            ":type" => $type,
            ":diff" => $worldAmount,
            ":memo" => $memo,
        ]);
        if (!$r) {
            $e = $stmt->errorInfo();
            flash("Failed to process transaction for World Account: " . var_export($e, true));
            $check = false;
        }
    }

    //updating world and source balances

    if($check){
        $worldBalanceSum = [];
        $stmt = $db->prepare("SELECT SUM(diff) as total from Transactions WHERE src=:id");
        $r = $stmt->execute([":id" => $worldID]);
        $worldBalanceSum = $stmt->fetch(PDO::FETCH_ASSOC);
        $worldBalFinal = $worldBalanceSum["total"];

        $srcBalanceSum = [];
        $stmt = $db->prepare("SELECT SUM(diff) as total from Transactions WHERE src=:id");
        $r = $stmt->execute([":id" => $srcID]);
        $srcBalanceSum = $stmt->fetch(PDO::FETCH_ASSOC);
        $srcBalFinal = $srcBalanceSum["total"];

        //world
        $stmt = $db->prepare("UPDATE Accounts set balance=:worldUpdate WHERE id=:id");
        $r = $stmt->execute([
            ":worldUpdate" => $worldBalFinal,
            ":id" => $worldID
        ]);

        if(!$r){
            $e = $stmt->errorInfo();
            flash("Error updating World balance: " . var_export($e, true));
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