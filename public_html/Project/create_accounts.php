<?php
require_once(__DIR__ . "/../../partials/nav.php");

if (!has_role("Admin")){
    //this will redirect to login and kill the rest of the script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: " . get_url("login.php")));
}

if(isset($_POST["save"])){
    
    $db = getDB();
    $check = $db->prepare('SELECT account FROM Accounts WHERE account = :q AND active = 1');
    do{
        $account = get_random_str(12);
        $check->execute([':q'=>$account]);
    } while ($check->rowCount() > 0);

    $account_type = $_POST["account_type"];

    $balance = $_POST["balance"];
    if($balance < 5){
        flash("Minimum balance not deposited");
        die(header("Location: create_accounts.php"));
    }

    if($account_type == "savings"){
        $apy = $balance/10000;
    } else {
        $apy = 0;
    }

    $user = get_user_id();
    $stmt = $db->prepare(
        "INSERT INTO Accounts (account, user_id, account_type, balance) VALUES (:account, :user, :account_type, :balance)"
    );
    $r = $stmt->execute([
        ":account"=>$account,
        ":account_type"=>$account_type,
        ":user"=>$user,
        ":balance"=>$balance,
    ]);
    if($r) {
        flash("Account created successfully with Number: " . $account);
        die(header("Location: accounts.php"));
    } else {
        flash("Error creating account!");
    }
    /*
    $account = $_POST["account"];
    $account_type = $_POST["account_type"];
    $user = get_user_id();
    $balance = $_POST["balance"];
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Accounts (account, account_type, user_id, balance) VALUES(:account, :account_type, :user, :balance)");
    $r = $stmt->execute([
        ":account" => $account,
        ":account_type" => $account_type,
        ":user" => $user,
        ":balance" => $balance
    ]);

    if($r){
        flash("Created successfully with id: " . $db->lastInsertId());
    }
    else{
        $e = $stmt->$errorInfo();
        flash("Error creating: " . var_export($e, true));
    }
    */
}
?>

<div class="container-fluid">
    <h1>Create Account</h1>
    <form method="POST" onsubmit="return validate(this)">
        <div class="mb-3">
            <label class="form-label" for="account_type">Account Type</label>
            <select class="form-control" id="account_type" name="account_type">
                <option value="checking">Checking</option>
                <option value="savings">Savings</option>
            </select>
        </div>
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
    </form>
</div>

<?php
require_once(__DIR__ . "/../../partials/footer.php");
?>
