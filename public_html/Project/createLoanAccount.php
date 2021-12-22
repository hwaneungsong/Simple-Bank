<?php
require_once(__DIR__ . "/../../partials/nav.php");

if(!is_logged_in()){
    //this will redirect to login and kill the rest of the script
    flash("You don't have permission to access this page");
    die(header("Location: " . get_url("login.php")));
}

$db = getDB();
$user = get_user_id();
$stmt = $db->prepare("SELECT account from Accounts WHERE user_id=:id LIMIT 10");
$r = $stmt->execute([":id" => $user]);
$accs = $stmt->fetchALL(PDO::FETCH_ASSOC);

$check = true;
if(isset($_POST["save"])){
    $worldBalance = 0;
    $balance = $_POST["balance"];
    $account_type = "loan";
    
    $apy = $_POST["apy"];
    $apy = (int)$apy;
    $apy2 = $apy/100;

    $externalAccount = $_POST["account_source"];

    //getting external account info for loan to be deposited
    $resultsExternal = [];
    $stmt = $db->prepare("SELECT id, balance FROM Accounts WHERE account_source=:src");
    $r = $stmt->execute([":src" => $externalAccount]);
    $resultsExternal = $stmt->fetch(PDO::FETCH_ASSOC);

    $srcExternalBalance = $resultsExternal["balance"];
    $srcIDExternal = $resultsExternal["id"];

    if(!$r){
        $e = $stmt->errorInfo();
        flash("Error accessing the Source Account Balance: " . var_export($e, true));
        $check = false;
    }

    //creating and storing account number
    $accNum = get_random_str(12);
    $user = get_user_id();
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Accounts(account, account_type, balance, apy, user_id) VALUES(:account, :account_type, :balance, :apy, :user)");
    $r = $stmt->execute([
        ":account"=>$accNum,
        ":account_type"=>$account_type,
        ":balance"=>$balance,
        ":apy"=>$apy2,
        ":user"=>$user
    ]);
    if($r){
        flash("Created successfully with id: " . $db->lastInsertID());
    }
    else{
        $e = $stmt->errorInfo();
        flash("Error creating new Loan Account: " . var_export($e, true));
        $check = false;
    }

    if($check){
        $worldID = 1;
        $db = getDB();

        $stmt = $db->prepare("SELECT balance FROM Accounts WHERE id=:id");
        $r = $stmt->execute([":id" => $worldID]);
        $worldBalance = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$r){
            $e = $stmt->errorInfo();
            flash("Error accessing the World Account Balance: " . var_export($e, true));
            $check = false;
        }

        $worldBalance = (int)$worldBalance;
        $balance = (int)$balance;

        $updateWorldBalance = $worldBalance - ($balance);

        $stmt = $db->prepare("UPDATE Accounts SET balance=:updateWorldBalance WHERE id=:id");
        $r = $stmt->execute([
            ":updateWorldBalance"=>$updateWorldBalance,
            ":id"=>$worldID
        ]);
        if(!$r){
            $e = $stmt->errorInfo();
            flash("Error updating World Balance: " . var_export($e, true));
            $check = false;
        }

        //creating transaction between new loan account and world account
        if($check){
            $reason = "loan";
            $memo = "N.L.A.C";
            $worldAmount = $balance * -1;
            $result = [];

            $stmt = $db->prepare("SELECT id FROM Accounts WHERE account=:accNum");
            $r = $stmt->execute([":accNum"=>$accNum]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $sourceID = $result["id"];

            if(!$r){
                $e = $stmt->errorInfo();
                flash("Failed to process transaction for World Account: " . var_export($e, true));
                $check = false;
            }
        

            if($check){
                $stmt = $db->prepare("INSERT INTO Transactions(src, dest, reason, diff, memo, expected_total) VALUES(:src, :dest, :type, :diff, :memo, :expected)");
                $r = $stmt->execute([
                    ":src"=>$sourceID,
                    ":dest"=>$worldID,
                    ":type"=>$reason,
                    ":diff"=>$balance,
                    ":memo"=>$memo,
                    ":expected"=>$balance
                ]);
                if(!$r){
                    $e = $stmt->errorInfo();
                    flash("Failed to process transaction for Source Account: " . var_export($e, true));
                    $check = false;
                }
            }

            $memoLoan = "loan fund";
            $srcExternalBalance = (int)$srcExternalBalance;

            $srcExternalExpected = $srcExternalBalance + ($balance);

            $stmt = $db->prepare("UPDATE Accounts SET balance=:srcExternalExpected WHERE id=:id");
            $r = $stmt->execute([
                ":srcExternalExpected" => $srcExternalExpected,
                ":id" => $srcIDExternal
            ]);

            //creating transaction between loan account and source destination
            if($check){
                $stmt = $db->prepare("INSERT INTO Transaction(src, dest, reason, diff, memo, expected_total) VALUES (:src, :dest, :type, :diff, :memo, :expected)");
                $r = $stmt->execute([
                    ":src" => $sourceID,
                    ":dest" => $srcIDExternal,
                    ":type" => $reason,
                    ":diff" => $balance,
                    ":memo" => $memoLoan,
                    ":expected" => $srcExternalExpected
                ]);
                if(!$r){
                    $e = $stmt->errorInfo();
                    flash("Failed to process transaction for Source Account: " . var_export($e, true));
                    $check = false;
                }
            }
        }
        header("Location: accounts.php");
    }
}
?>

<div class="container-fluid">
    <h1>Create Loan</h1>
    <form method = "POST" onsubmit="return validate(this)">
        <div class="mb-3">
            <label class="form-label" for="destinationAccount">Choose Destination Account</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <select name="account_source" placeholder="Account Source">
                        <?php foreach ($accs as $acc): ?>
                            <option value="<?php se($acc["account"]); ?>"><?php se($acc["account"]); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" class="form-control" id="loan" min="500.00" placeholder="Loan Amount" aria-describedby="loanHelp">
                    <small id="loanHelp" class="form-text text-muted">Minimum $500.00 required</small>
                    <input type="number" class="form-control" id="apy" min="2" max="5" placeholder="APY" aria-describedby="apyHelp">
                </div>
            </div>
        <button type="submit" name="save" value="create" class="btn btn-primary">Create</button>
        <div class="mb-3">
            <div class="list-group">
            <div>   
                <a href="create_accounts.php">Create Checking Account</a>
                <a href="createSavingsAccount.php">Create Savings Account</a>
            </div>
        </div>
    </form>
</div>

<?php
require_once(__DIR__ . "/../../partials/footer.php");
?>