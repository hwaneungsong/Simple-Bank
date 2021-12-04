<?php
require_once(__DIR__ . "/../../partials/nav.php");

if(isset($_GET["id"])){
    $id = $_GET["id"];
    echo var_export("This is the get id + " .$id, true);
}

if(isset($_POST["save"])){
    $account_number = $_POST["account"];
    $account_type = $_POST["account_type"]; 
    $user= get_user_id();
    $balance = $_POST["balance"];
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Accounts (account, account_type, user_id, balance) VALUES(:account, :account_type, :user, :balance)");
    $r = $stmt->execute([
        ":account" => $account_number,
        ":account_type"=> $account_type,
        ":user" => $user,
        ":balance" => $balance
    ]);

    if($r){
      flash("Created successfully with id: " . $db->lastInsertId());
    }
    else{
      $e = $stmt->errorInfo();
      flash("Error creating: " . var_export($e, true));
    }

}
?>

<div class="container-fluid">
<form method="POST">
  <label> Account Number </label>
  <input type="number" name="account" value="<?php echo $result["account"];?>" />
  <label>Account Type</label>
  <select name="account_type">
    <option value = "checking">checking</option>
    <option value =  "saving">saving</option>
  </select>
  <label>Balance</label>
  <input type="number" min="5.00" name="balance" value="<?php echo $result["balance"];?>" />
	<input type="submit" name="save" value="Create"/>
</form>
