<?php
// TODO

  require("config.php");
  $mysqli = new mysqli($db_server, $db_user, $db_password, $db_database);
  if($mysqli->connect_errno){ die("Could not connect to database: " . $mysqli->connect_errno); }

  $taskID = $_GET["taskid"];
  $userID = $_GET["userid"];

  $stmt = $mysqli->prepare("DELETE FROM Assign WHERE `taskID`=? AND `userID`=?");
  $stmt->bind_param("ii", $taskID, $userID);

  //Tells mysql server to execute prepared statement
  $stmt->execute();
  $success = $stmt->execute();

?>
<html lang="en">
<head>
  <title>Member unassigned</title>
  <?php include("templates/html_head.php") ?>
  <meta http-equiv="refresh" content="1;url=taskdetails.php?id=<?php echo($taskID); ?>" />
</head>

<body>
  <div>
    <?php include("templates/navbar.php"); ?>

    <div class="ui two column centered grid">
      <div class="column">
        <h2 class="ui header">
          <div class="content">
            <?php echo($success ? "Member unassigned!" : "Unassign error") ?>
          </div>
        </h2>
      </div>
    </div>
  </div>
</body>
<html>
