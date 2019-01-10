<?php

  require("config.php");
  $mysqli = new mysqli($db_server, $db_user, $db_password, $db_database);
  if($mysqli->connect_errno){ die("Could not connect to database: " . $mysqli->connect_errno); }

  $stmt = $mysqli->prepare("INSERT INTO Task (`name`, `description`, `startDate`, `deadline`, `completed`) VALUES (?, ?, ?, ?, ?)");

  $taskName = $_POST["name"];
  $taskDescription = $_POST["description"];
  $taskStart = strtotime($_POST["startDate"]);
  $taskDeadline = strtotime($_POST["deadline"]);
  $taskCompleted = 0;

  $stmt->bind_param("ssssi", $taskName, $taskDescription, date("Y-m-d", $taskStart), date("Y-m-d", $taskDeadline), $taskCompleted);

  $success = $stmt->execute();

?>
<html lang="en">
<head>
  <title>Create Task</title>
  <?php include("templates/html_head.php") ?>
  <meta http-equiv="refresh" content="1;url=tasks.php" />
</head>

<body>
  <div>
    <?php include("templates/navbar.php"); ?>

    <div class="ui two column centered grid">
      <div class="column">
        <h2 class="ui header">
          <div class="content">
            <?php echo($success ? "Task added!" : "Error adding task") ?>
          </div>
        </h2>
      </div>
    </div>
  </div>
</body>
<html>
