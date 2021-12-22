<?php
require_once(__DIR__ . "/../../../partials/nav.php");

if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>

    <form method="POST">
        <label>Account Type</label>
        <select name="account_type">
            <option value="checking"> Checking</option>
            <option value="savings"> Savings</option>
            <option value="loan"> Loan</option>
        </select>
        <label>Balance</label>
        <input type="number" name="balance" min="5"/>
        <label>APY: Checking = 0%, Savings = 1%, Loan = 10%</label>
        <input type="submit" name="save" value="Create"/>
    </form>

<?php
if(isset($_GET["id"])){
    $user = $_GET["id"];
}
$i = 0;
while ($i < 100){
    if(isset($_POST["save"]) && isset($user)) {
        $account_number = get_random_str(12);
        $account_type = $_POST["account_type"];
        $apy = 0;
        if ($account_type == "savings") {
            $apy = .01;
        } else if ($account_type == "loan"){
            $apy = .1;
        }
        $balance = $_POST["balance"];
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO Accounts (account, account_type, user_id, apy) VALUES(:account, :account_type, :user, :apy)");
        $r = $stmt->execute([
            ":account" => $account_number,
            ":account_type" => $account_type,
            ":user" => $user,
            ":apy" => $apy
        ]);
        if ($r) {
            $new_id = $db->lastInsertId();
            flash("Created successfully with id: " . $new_id);
            $world_id = 2;
            $db = getDB();
            $stmt = $db->prepare("SELECT id FROM Accounts WHERE account= '000000000000'");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $world_id = $result["id"];
            die(header("Location: accounts.php"));
            break;
        } else {
            $e = $stmt->errorInfo();
        }
    }
    $i++;
}
?>
<?php require(__DIR__ . "/../../../partials/flash.php");