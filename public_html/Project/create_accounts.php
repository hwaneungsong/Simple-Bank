<?php
require_once(__DIR__ . "/../../partials/nav.php");

if (!is_logged_in()){
    //this will redirect to login and kill the rest of the script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: " . get_url("login.php")));
}

$check = true;

if(isset($_POST["save"])){
    $worldBalance = 0;
    $balance = $_POST["balance"];
    $account_type = "checking";

    $accNum = get_random_str(12);
    $user = get_user_id();
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Accounts(account, account_type, balance, user_id) VALUES(:account, :account_type, :balance, :user)");
    $r = $stmt->execute([
        ":account"=>$accNum,
        ":account_type"=>$account_type,
        ":balance"=>$balance,
        ":user"=>$user
    ]);

    if($r) {
        flash("Account created successfully with Number: " . $accNum);
        die(header("Location: accounts.php"));
    } else {
        $e = $stmt->errorInfo();
        flash("Error creating new checking account: " . var_export($e, true));
        $check = false;
    }
    
    if($check){

        //Updating balance for world account

        $worldID = 1;
        $db = getDB();

        $stmt = $db->prepare("SELECT balance from Accounts WHERE id = :id");
        $r = $stmt->execute([":id" => $worldID]);
        $worldBalance = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$r) {
            $e = $stmt->errorInfo();
            flash("Error accessing the World Account balance: " . var_export($e, true));
            $check = false;
        }

        $worldBalance = (int)$worldBalance;
        $balance = (int)$balance;

        $updateWorldBalance = $worldBalance - $balance;

        //creating transaction between new account and world account

        if($check){
            $action_type = "deposit";
            $memo = "N.A.C";
            $worldAmount = $balance * -1;
            $result = [];


            $stmt = $db->prepare("SELECT id from Accounts WHERE account =:accNum");
            $r = $stmt->execute([":accNum" => $accNum]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $sourceID = $result["id"];


            //$sourceID = $db->lastInsertId();

            if(!$r){
                $e = $stmt->errorInfo();
                flash("Error getting Account ID: " . var_export($e, true));
                $check = false;
            }
            if($check) {
                $stmt = $db->prepare("INSERT INTO Transactions (src, dest, reason, diff, memo) VALUES(:src, :dest, :type, :amount, :memo)");
                $r = $stmt->execute([
                    ":src" => $worldID,
                    ":dest" => $sourceID,
                    ":type" => $action_type,
                    ":amount" => $worldAmount,
                    ":memo" => $memo,
                ]);
                if (!$r) {
                    $e = $stmt->errorInfo();
                    flash("Failed to process transaction for World Account: " . var_export($e, true));
                    $check = false;
                }
            }

            if($check) {
                $stmt = $db->prepare("INSERT INTO Transactions (src, dest, reason, diff, memo) VALUES(:src, :dest, :type, :amount, :memo)");
                $r = $stmt->execute([
                    ":src" => $sourceID,
                    ":dest" => $worldID,
                    ":type" => $action_type,
                    ":amount" => $balance,
                    ":memo" => $memo,
                ]);
                if (!$r) {
                    $e = $stmt->errorInfo();
                    flash("Failed to process transaction for Source Account: " . var_export($e, true));
                    $check = false;
                }
            }

            if($check){
                $worldBalanceSum = [];
                $stmt = $db->prepare("SELECT SUM(amount) as total from Transactions WHERE src=:id");
                $r = $stmt->execute([":id" => $worldID]);
                $worldBalanceSum = $stmt->fetch(PDO::FETCH_ASSOC);
                $worldBalFinal = $worldBalanceSum["total"];

                //world
                $stmt = $db->prepare("UPDATE Accounts set balance=:updateWorldBalance WHERE id=:id");
                $r = $stmt->execute([
                    ":updateWorldBalance" => $worldBalFinal,
                    ":id" => $worldID
                ]);

                if(!$r){
                    $e = $stmt->errorInfo();
                    flash("Error updating World balance: " . var_export($e, true));
                    $check = false;
                }
            }
        }
    }

    header("Location: accounts.php");


}

?>

<div class="container-fluid">
    <h1>Create Checking Account</h1>
    <form method="POST" onsubmit="return validate(this)">
        <div class="mb-3"> 
            <label class="form-label" for="deposit">Deposit</label>
            <div class="input group">
                <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                </div>
                <input type="number" class="form-control" id="deposit" min="5.00" name="balance" step="0.01" placeholder="5.00" aria-describedby="depositHelp"/>
            </div>
            <small id="depositHelp" class="form-text text-muted">Minimum $5.00 deposit required</small>
        </div>
        <button type="submit" name="save" value="create" class="btn btn-primary">Create</button>
        <div class="mb-3">
            <div class="list-group">
                <div>
                    <a href="createSavingsAccount.php">Create Savings Account</a>
                    <a href="#">Create Loan</a>
                </div>
            </div>
        </div>
    </form>
</div>

<?php
require_once(__DIR__ . "/../../partials/footer.php");
?>
