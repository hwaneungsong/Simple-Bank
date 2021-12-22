<?php
require_once(__DIR__ . "/../../partials/nav.php");

if(!is_logged_in()){
    //this will redirect to login and kill the rest of the script
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}

$db = getDB();
$user = get_user_id();
$active_check = "true";

$stmt = $db->prepare("SELECT account from Accounts WHERE user_id=:id AND active = :bool LIMIT 10");
$r = $stmt->execute([
    ":id"=>$user,
    ":bool"=>$active_check
]);
$accs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$check = true;

if(isset($_POST["save"])){
    $closeAcc = $_POST["account"];

    $results = [];

    $stmt = $db->prepare("SELECT id, balance FROM Accounts WHERE account=:src");
    $r = $stmt->execute([
        ":src"=>$closeAcc
    ]);
    $results = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$r){
        $e = $stmt->errorInfo();
        flash("Error accessing the Source Account Balance: " . var_export($e, true));
        $check = false;
    }

    $balance = $results["balance"];
    $sourceID = $results["id"];

    if($check){
        if($balance != 0){
            flash("Balance must be $0.00 to close your account.");
            $check = false;
        }
    }

    $active = false;

    if($check){
        $stmt = $db->prepare("UPDATE Accounts SET active=:notActive WHERE id=:id");
        $r = $stmt->execute([
            ":notActive"=>$active,
            "id"=>$sourceID
        ]);

        if(!$r){
            $e = $stmt->errorInfo();
            flash("Error closing the account: " . var_export($e, true));
            $check = false;
        }
    }
    if($check){
        flash("Your account has successfully been closed!");
    }
}
?>

<h1>Close Account</h1>
<form method = "POST">
    <label>Choose Account to Close:</label>
    <select name="account" placeholder="Account">
        <?php foreach ($accs as $acc): ?>
            <option value="<?php se($acc["account"]); ?>"
            ><?php se($acc["account"]); ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" name="save" value="Close"/>
</form>

<?php
require_once(__DIR__ . "/../../partials/footer.php");