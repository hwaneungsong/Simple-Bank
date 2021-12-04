<?php
require_once(__DIR__ . "/../../partials/nav.php");
if(!is_logged_in()) {
    //this will redirect to login and kill the rest of this script
    flash("You don't have premission to access this page");
    die(header("Location: login.php"));
}

$user = get_user_id();
if(isset($user)){
$result = [];
$db = getDB();
$stmt = $db->prepare(
    "SELECT Accounts.user_id as UserID, Accounts.id as AccID, account, account_type, balance FROM Accounts WHERE Accounts.user_id = :q LIMIT 5"
);
$r = $stmt->execute([":q"=>$user]);
    if($r){
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
    flash("There was a problem listing your accounts");
    }
}
?>

<div class="container-fluid">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $r): ?>
                <div class="list-group-item">
                    <div>
                        <div<strong>Account Number:</strong></div>
                        <div><?php echo($r["account"]); ?></div>
                    </div>
                    <div>
                        <div><strong>Account Type:</strong></div>
                        <div><?php echo($r["account_type"]); ?></div>
                    </div>
                    <div>
                        <div><strong>Balance:</strong></div>
                        <div><?php echo($r["balance"]); ?></div>
                    </div>
                    <div>
                        <a type="button" href="<?php echo get_url('my_transactions.php?id=' . $r["AccID"]); ?>">View Transaction History</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>    
</div>

<?php 
require_once(__DIR__ . "/../../partials/footer.php");
?>