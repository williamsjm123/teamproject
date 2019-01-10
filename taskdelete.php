<?php
// TODO

  require("config.php");
  $mysqli = new mysqli($db_server, $db_user, $db_password, $db_database);
  if($mysqli->connect_errno){ die("Could not connect to database: " . $mysqli->connect_errno); }

  $taskId = $_GET["id"];
  $stmt = $mysqli->prepare("DELETE FROM Task WHERE `taskId`=?");
  $stmt->bind_param("i", $taskId);

  //Tells mysql server to execute prepared statement
  $stmt->execute();
  $success = $stmt->execute();

?>
<html lang="en">
<head>
  <title>Task deleted</title>
  <?php include("templates/html_head.php") ?>
  <meta http-equiv="refresh" content="1;url=tasks.php?" />
</head>

<body>
  <div>
    <?php include("templates/navbar.php"); ?>

    <div class="ui two column centered grid">
      <div class="column">
        <h2 class="ui header">
          <div class="content">
            <?php echo($success ? "Task deleted!" : "Error deleting task") ?>
          </div>
        </h2>
      </div>
    </div>
  </div>
</body>
<html>
