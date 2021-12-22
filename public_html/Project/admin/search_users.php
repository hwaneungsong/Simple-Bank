<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../../partials/nav.php");

if (!has_role("Admin")) {
    flash("You don't have permission to view this page", "warning");
    die(header("Location: $BASE_PATH" . "home.php"));
}
?>

<form method="POST">
    <label>First Name</label>
    <input type="text" name="firstName"/>
    <label>Last Name</label>
    <input type="text" name="lastName"/>
    <input type="submit" name="search" value="Search"/>
</form>

<?php
$results = [];
if(isset($_POST["search"])){
    $first_name = $_POST["firstName"];
    $last_name = $_POST["lastName"];
    $db = getDB();
    $stmt = $db->prepare("SELECT id, email, created, username, firstName, lastName, privacy FROM Users Where firstName=:firstName AND lastName=:lastName ");
    $r = $stmt->execute([
        ":firstName"=>$first_name,
        ":lastName"=>$last_name
    ]);
    if($r){
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        flash("There was a problem fetching the results");
    }
}
?>

<h3>User Info</h3>
    <div class="results">
        <?php if (count($results) > 0): ?>
            <div class="list-group">
                <?php foreach ($results as $r): ?>
                    <div class="list-group-item">
                        <div>
                            <div>User ID:</div>
                            <div><?php se($r["id"]); ?></div>
                        </div>
                        <div>
                            <div>Email:</div>
                            <div><?php se($r["email"]); ?></div>
                        </div>
                        <div>
                            <div>Created:</div>
                            <div><?php se($r["created"]); ?></div>
                        </div>
                        <div>
                            <div>Username:</div>
                            <div><?php se($r["username"]); ?></div>
                        </div>
                        <div>
                            <div>First Name:</div>
                            <div><?php se($r["firstName"]); ?></div>
                        </div>
                        <div>
                            <div>Last Name:</div>
                            <div><?php se($r["lastName"]); ?></div>
                        </div>
                        <div>
                            <div>Privacy:</div>
                            <div><?php se($r["privacy"]); ?></div>
                        </div>
                            <a type="button" href="deactivate_user.php?id=<?php se($r['id']);?>">Deactivate This User</a>
                            <a type="button" href="createAccount.php?id=<?php se($r['id']);?>">Make An Account For This User</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No results</p>
        <?php endif; ?>
    </div>


<?php require(__DIR__ . "/../../../partials/footer.php");