<?php
require_once (__DIR__ . "/../../partials/nav.php");
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}

$check = true;
$results = [];
$results1 = [];

if(isset($_GET["id"])){
    $transId = $_GET["id"];
    $_SESSION["transId"] = $transId;
}
else{
    $check = false;
    flash("Id is not set in url");
}

if(isset($_GET["page"])) {
    $page = (int)$_GET["page"];
}
else{
    $page = 1;
}

$numPerPage = 5;
$numRecords = 0;

if(isset($transId)){
    $db = getDB();
    $stmt = $db->prepare("SELECT src, Accounts.id, Accounts.account, diff, reason, memo FROM Transactions JOIN Accounts on Accounts.id = Transactions.dest WHERE src =:id ORDER BY Transactions.created DESC LIMIT 10");
    $r = $stmt->execute([":id" => $transId]);
    if ($r){
        $results1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else{
        $e = $stmt->errorInfo();
        flash("There was a problem fetching the results." . var_export($e, true));
        $check = false;
    }
}

?>

<form method="POST">
    <label><strong>Filter Transactions</strong></label>
    <br>
    <label>START:<br></label>
    <input type="date" name="dateStart" />
    <br>
    <label>END:<br></label>
    <input type="date" name="dateTo"/>
    <label>Transaction Type: <br></label>
    <select name="action_type">
        <option value="deposit">Deposit</option>
        <option value="withdraw">Withdraw</option>
        <option value="transfer">Transfer</option>
    </select>
    <input type="submit" name="save" value="Filter" />
</form>
<div class="bodyMain">
    <h1><strong>List Transactions</strong></h1>
    <div class="results">
        <?php if(count($results1) > 0): ?>
            <div class="list-group">
                <?php foreach ($results1 as $r): ?>
                    <div class="list-group-item">
                        <div>
                            <div>Destination Account ID:</div>
                            <div><?php echo($r["account"]); ?></div>
                        </div>
                        <div>
                            <div>Transaction Type:</div>
                            <div><?php echo($r["reason"]); ?></div>
                        </div>
                        <div>
                            <div>Amount Moved:</div>
                            <div><?php echo($r["diff"]); ?></div>
                        </div>
                        <div>
                            <div>Memo:</div>
                            <div><?php echo($r["memo"]); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div> No top 10 transactions listed</div>
        <?php endif; ?>
    </div>
<?php

if(isset($transId) && isset($_POST["save"])) {
    $db = getDB();

    $type = $_POST["reason"];
    $_SESSION["type"] = $type;
    $resultPage = [];

    $stmt = $db->prepare("SELECT COUNT(*) AS total FROM Transactions WHERE reason=:type AND src=:id");
    $r = $stmt->execute([":id" => $transId, ":type" => $type]);
    $resultPage = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($resultPage) {
        $numRecords = (int)$resultPage["total"];
    }

    $numRecords = (int)$numRecords;
    $numLinks = ceil($numRecords / $numPerPage); 
    $offset = ($page - 1) * $numPerPage;

    $_SESSION["numRecords"] = $numRecords;

}

if(isset($_POST["save"])) {

    $startDate = $_POST["dateStart"];
    $endDate = $_POST["dateTo"];
    $type = $_POST["action_type"];
    $save = $_POST["save"];

    $startDate = (string)$startDate . ' 00:00:00';
    $endDate = (string)$endDate . ' 00:00:00';

    $_SESSION["dateStart"] = $startDate;
    $_SESSION["dateTo"] = $endDate;
    $_SESSION["type"] = $type;
    $_SESSION["save"] = $save;

    $stmt = $db->prepare("SELECT src, Accounts.id, Accounts.account, diff, reason, memo FROM Transactions JOIN Accounts on Accounts.id = Transactions.src WHERE src =:id AND reason=:reason AND created BETWEEN :startDate AND :endDate LIMIT :offset, :count");
    $stmt->bindValue(":startDate", $startDate, PDO::PARAM_STR);
    $stmt->bindValue(":endDate", $endDate, PDO::PARAM_STR);
    $stmt->bindValue(":action_type", $type, PDO::PARAM_STR);
    $stmt->bindValue(":id", $transId, PDO::PARAM_INT);
    $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
    $stmt->bindValue(":count", $numPerPage, PDO::PARAM_INT);
    $r = $stmt->execute();
    if ($r){
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else{
        $e = $stmt->errorInfo();
        flash("There was a problem fetching the results." . var_export($e, true));
        $check = false;
    }

}

else if(!isset($_POST["save"]) && isset($_GET["page"])){
    if(isset($_SESSION["save"])) {
        $db = getDB();

        $startDate = $_SESSION["dateStart"];
        $endDate = $_SESSION["dateTo"];
        $type = $_SESSION["type"];

        $page = $_GET["page"];
        $numPerPage = 5;
        $numRecords = $_SESSION["numRecords"];
        $numLinks = ceil($numRecords / $numPerPage);
        $offset = ($page-1) * $numPerPage;

        $transId = $_SESSION["transId"];


        $stmt = $db->prepare("SELECT src, Accounts.id, Accounts.account, diff, reason, memo FROM Transactions JOIN Accounts on Accounts.id = Transactions.src WHERE src =:id AND reason=:reason AND created BETWEEN :startDate AND :endDate LIMIT :offset, :count");
        $stmt->bindValue(":startDate", $startDate, PDO::PARAM_STR);
        $stmt->bindValue(":endDate", $endDate, PDO::PARAM_STR);
        $stmt->bindValue(":action_type", $type, PDO::PARAM_STR);
        $stmt->bindValue(":id", $transId, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindValue(":count", $numPerPage, PDO::PARAM_INT);
        $r = $stmt->execute();
        if ($r){
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        else{
            $e = $stmt->errorInfo();
            flash("There was a problem fetching the results." . var_export($e, true));
            $check = false;
        }

    }
}


?>

<div class="BodyMain">
    <div class="results">
        <h1><strong>Filtered Transactions</strong></h1>
        <?php if(count($results) > 0): ?>
            <div class="list-group">
                <?php foreach ($results as $r): ?>
                    <div class="list-group-item">
                        <div>
                            <div>Destination Account ID:</div>
                            <div><?php echo($r["account"]); ?></div>
                        </div>
                        <div>
                            <div>Transaction Type:</div>
                            <div><?php echo($r["reason"]); ?></div>
                        </div>
                        <div>
                            <div>Amount Moved:</div>
                            <div><?php echo($r["diff"]); ?></div>
                        </div>
                        <div>
                            <div>Memo:</div>
                            <div><?php echo($r["memo"]); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No Results</p>
        <?php endif; ?>
    </div>
</div>
<?php if(isset($numLinks)):?>
<div>
    <nav aria-label="Filtered">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php echo ($page-1) < 1?"disabled":"";?>">
                <a class="page-link" href="?id=<?php echo $transId;?>&page=<?php echo $page-1;?>" tabindex="-1">Previous</a>
            </li>
            <?php for($i = 0; $i < $numLinks; $i++):?>
                <li class="page-item <?php echo ($page-1) == $i?"active":"";?>">
                    <a class="page-link" href="?id=<?php echo $transId;?>&page=<?php echo ($i+1);?>"><?php echo ($i+1);?></a></li>
            <?php endfor; ?>
            <li class="page-item <?php echo ($page+1) >= $numLinks?"disabled":"";?>">
                <a class="page-link" href="?id=<?php echo $transId;?>&page=<?php echo $page+1;?>">Next</a>
            </li>
        </ul>
    </nav>
</div>
<?php endif;?>

<?php require(__DIR__ . "/../../partials/footer.php");
