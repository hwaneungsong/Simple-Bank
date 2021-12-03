<?php
require_once(__DIR__ . "/../partials/nav.php");

if (!has_role("Admin")){
    //this will redirect to login and kill the rest of the script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: " . get_url("login.php")));
}

if(isset($_POST["save"])){
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
        flash("Created successfully with id: " . $dv->lastInsertId());
    }
    else{
        $e = $stmt->$errorInfo();
        flash("Error creating: " . var_export($e, true));
    }
}
?>

<div class="container-fluid">
    <h1>Create Account</h1>
    <form method="POST" onsubmit="return validate(this)">
        <div class="mb-3">
            <label class="form-label" for="account">Account Number</label>
            <input class="form-control" type="number" name="account" value="<?php echo $result["account"]?>" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="account_type">Account Type</label>
            <input class="form-control" />
        </div>
    </form>
</div>

<?php
require_once(__DIR__ . "/../../partials/footer.php");
?>
