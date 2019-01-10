<?php

  require("config.php");

  $mysqli = new mysqli($db_server, $db_user, $db_password, $db_database);
  if($mysqli->connect_errno){ die("Could not connect to database: " . $mysqli->connect_errno); }

  $stmt = $mysqli->prepare("UPDATE Task SET `name`=?, `description`=?, `startDate`=?, `deadline`=?, `completed`=?, `newDeadline`=? WHERE `taskId`=?");

  $taskID = $_POST["id"];
  $taskName = $_POST["name"];
  $taskDescription = $_POST["description"];
  $taskStart = strtotime($_POST["startDate"]);
  $taskDeadline = strtotime($_POST["deadline"]);
  $taskCompleted = 0;
  if($_POST["completed"] === "yes"){ $taskCompleted = 1; }
  $taskNewDeadline = strtotime($_POST["newDeadline"]);

  $stmt->bind_param("ssssisi", $taskName, $taskDescription, date("Y-m-d", $taskStart), date("Y-m-d", $taskDeadline), $taskCompleted, date("Y-m-d", $taskNewDeadline), $taskID);
  $success = $stmt->execute();

?>
<html lang="en">
<head>
  <title>Edit Task</title>
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
            <?php echo($success ? "Task updated!" : "Error saving task") ?>
          </div>
        </h2>
      </div>
    </div>
  </div>
</body>
<html>
