<?php
require_once(__DIR__ . "/../../partials/nav.php");

$query = "";
$results = [];
$results2 = [];

if(isset($_GET["user"])){ 
  $user = $_GET["user"];
}
else{
  flash("The id was not pulled");
}
?>

<?php
if (isset($user) && !empty($user)) {
    $db = getDB();
    $stmt=$db->prepare("SELECT diff, reason, created, src, dest, Transactions.id as tranID FROM Transactions as Transactions JOIN Accounts ON Transactions.src = Accounts.id WHERE Accounts.id = :q LIMIT 10");
    $r = $stmt->execute([ ":q" => $user]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        flash("Results are successfull");
    }
    else {
        flash("There was a problem listing your transactions");
        echo var_export($stmt->errorInfo(), true);
    }
}
?>

<h3> Transcations</h3>
<div class="results">
        <?php if (count($results) > 0): ?>
            <div class="list-group">
                <?php foreach ($results as $r): ?>
                    <div class="list-group-item">
                        <div>
                            <div><strong>Action Type:</strong></div>
                            <div><?php echo($r["reason"]); ?></div>
                        </div>
                        <div>
                            <div><strong>Source:</strong></div>
                            <div><?php echo($r["src"]); ?></div>
                        </div>
                        <div>
                            <div><strong>Destination:</strong></div>
                            <div><?php echo($r["dest"]); ?></div>
                        </div>
                        <div>
                            <div><strong>Amount:</strong></div>
                            <div><?php echo($r["diff"]); ?></div>
                        </div>
                        <div>
                            <a type="button" href="#">More Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No results</p>
        <?php endif; ?>
</div>

<?php require(__DIR__ . "/../../partials/footer.php");
