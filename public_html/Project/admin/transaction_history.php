<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../../partials/nav.php"); 


if (!has_role("Admin")) {
    flash("You don't have permission to view this page", "warning");
    die(header("Location: $BASE_PATH" . "home.php"));
}

if(isset($_GET["id"])){
    $account_id = $_GET["id"];
}

$db = getDB();

if(isset($account_id)){
    $stmt = $db->prepare("SELECT diff, reason, memo, created FROM Transactions WHERE src=:account_id LIMIT 10");
    $r = $stmt->execute(["account_id" => $account_id]);
    if ($r) {
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $e = $stmt->errorInfo();
        flash("There was an error fetching transaction info " . var_export($e, true));
    }
}
?>
    <h3>Transaction History</h3>
    <div class="results">
        <?php if (count($transactions) > 0): ?>
            <div class="list-group">
                <?php foreach ($transactions as $t): ?>
                    <div class="list-group-item">
                        <div>
                            <div>Amount:</div>
                            <div><?php se($t["diff"]); ?></div>
                        </div>
                        <div>
                            <div>Action Type:</div>
                            <div><?php se($t["reason"]); ?></div>
                        </div>
                        <div>
                            <div>Memo:</div>
                            <div><?php se($t["memo"]); ?></div>
                        </div>
                        <div>
                            <div>Created:</div>
                            <div><?php se($t["created"]); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No results</p>
        <?php endif; ?>
    </div>
<?php require(__DIR__ . "/../../../partials/flash.php");