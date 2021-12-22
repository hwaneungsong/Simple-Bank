<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../../partials/nav.php");

if (!has_role("Admin")) {
    flash("You don't have permission to view this page", "warning");
    die(header("Location: $BASE_PATH" . "home.php"));
}
?>

<form method="POST">
    <label>Account Number</label>
    <input type="text" name="account"/>
    <input type="submit" name="search" value="Search"/>
</form>

<?php
$results = [];
if(isset($_POST["search"])){
    $accountNumber = $_POST["account"];
    $db = getDB();
    $stmt = $db->prepare("SELECT id, account, user_id, account_type, created, modified, APY, active FROM Accounts WHERE account=:account");
    $r = $stmt->execute([
        ":account"=>$accountNumber
    ]);
    if($r){
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        flash("There was a problem fetching the results");
    }
}
?>

<h3>Account Info</h3>
    <div class="results">
        <?php if (count($results) > 0): ?>
            <div class="list-group">
                <?php foreach ($results as $r): ?>
                    <div class="list-group-item">
                        <div>
                            <div>Account ID:</div>
                            <div><?php se($r["id"]); ?></div>
                        </div>
                        <div>
                            <div>Account Number:</div>
                            <div><?php se($r["account"]); ?></div>
                        </div>
                        <div>
                            <div>Created:</div>
                            <div><?php se($r["created"]); ?></div>
                        </div>
                        <div>
                            <div>Last Modified:</div>
                            <div><?php se($r["modified"]); ?></div>
                        </div>
                        <div>
                            <div>APY:</div>
                            <div><?php se($r["APY"]); ?></div>
                        </div>
                        <div>
                            <div>Active:</div>
                            <div><?php se($r["active"]); ?></div>
                        </div>
                            <a type="button" href="transaction_history.php?id=<?php se($r['id']);?>">View Transaction History</a>
                            <a type="button" href="freeze_account.php?id=<?php se($r['id']);?>">Freeze This Account</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No results</p>
        <?php endif; ?>
    </div>


<?php require(__DIR__ . "/../../../partials/footer.php");