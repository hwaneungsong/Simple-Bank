<?php
require_once(__DIR__ . "/../../partials/nav.php");

if(!is_logged_in()){
    //this will redirect to login and kill the rest of the script
    flash("You must be logged in to vaccess this page.");
    die(header("Location: login.php"));
}

$check = true;

if(isset($_POST["save"])){
    $worldBalance = 0;
    $balance = $_POST["balance"];
    $account_type = "saving";
    $apy = 0.03;

    $accNum = get_random_str(12);
    $user = get_user_id();
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Accounts(account, account_type, balance, apy, user_id) VALUES(:account, :account_type, :balance, :apy, :user)");
    $r = $stmt->execute([
        ":account"=>$accNum,
        ":account_type"=>$account_type,
        ":balance"=>$balance,
        ":apy"=>$apy,
        ":user"=>$user
    ]);
    if($r){
        flash("Account created successfully with Number: " . $account);
        die(header("Location: accounts.php"));
    } else {
        flash("Error creating account!");
    }

    if($check){
        $worldID = 1;
        $db = getDB();
        $stmt = $db->prepare("SELECT balance from Accounts WHERE id= :id");
        $r = $stmt->execute([":id"=>$worldID]);
        $worldBalance = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if(!$r){
        $e = $stmt->errorInfo();
        flash("Error accessing the World Account Balance: " . var_export($e, true));
        $checl = false;
    }

    $worldBalance = (int)$worldBalance;
    $balance = (int)$balance;
    $updateWorldBalance = $worldBalance - $balance;

    if($check){
        $reason = "deposit";
        $memo = "N.S.A.C";
        $worldAmount = $balance * -1;
        $result = [];

        $stmt = $db->prepare("SELECT id from Accounts WHERE account = :accNum");
        $r = $stmt->execute([":accNum"=>$accNum]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $sourceID = $result["id"];

        if(!$r){
            $e = $stmt->errorInfo();
            flash("Error getting Account ID:" . var_export($e, true));
            $check = false;
        }

        if($check){
            $stmt = $db->prepare("INSERT INTO Transactions(src, dest, reason, diff, memo, expected_total) VALUES(:src, :dest, :type, :diff, :memo, :expected)");
            $r = $stmt->execute([
                ":src" => $worldID,
                ":dest" => $sourceID,
                ":type" => $reason,
                ":diff" => $worldAmount,
                ":memo" => $memo,
                ":expected" => $updateWorldBalance
            ]);
            if(!$r) {
                $e = $stmt->errorInfo();
                flash("Failed to process transaction for World Account: " . var_export($e, true));
                $check = false;
            }
        }

        if($check){
            $stmt = $db->prepare("INSERT INTO Transactions(src, dest, reason, diff, memo, expected_total) VALUES(:src, :dest, :type, :diff, :memo, :expected)");
            $r = $stmt->execute([
                ":src" => $sourceID,
                ":dest" => $worldID,
                ":type" => $reason,
                ":diff" => $balance,
                ":memo" => $memo,
                ":expected" => $balance
            ]);
            if(!$r){
                $e = $stmt->errorInfo();
                flash("Failed to process transaction for Source Amount: " . var_export($e, true));
                $check = false;
            }
        }

        if($check){
            $worldBalanceSum = [];
            $stmt = $db->prepare("SELECT SUM(diff) as total from Transactions WHERE src=:id");
            $r = $stmt->execute([":id" => $worldID]);
            $worldBalanceSum = $stmt->fetch(PDO::FETCH_ASSOC);
            $worldBalFinal = $worldBalanceSum["total"];

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
        header("Location: accounts.php");
    }
}
?>

<div class="container-fluid">
    <h1>Create Savings Account</h1>
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
                    <a href="create_accounts.php">Create Checking Account</a>
                    <a href="#">Create Loan</a>
                </div>
            </div>
        </div>
    </form>
</div>

<?php
require_once(__DIR__ . "/../../partials/footer.php");
?>