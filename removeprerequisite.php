<?php

  require("config.php");
  $mysqli = new mysqli($db_server, $db_user, $db_password, $db_database);
  if($mysqli->connect_errno){ die("Could not connect to database: " . $mysqli->connect_errno); }

  $stmt = $mysqli->prepare("DELETE FROM Prerequisite WHERE `primaryTask_id`=? AND `prereqTask_id`=?");

  $primaryTaskid = $_GET["primarytaskid"];
  $prereqTaskid = $_GET["prereqtaskid"];

  $stmt->bind_param("ii", $primaryTaskid, $prereqTaskid);
  $success = $stmt->execute();

?>
<html lang="en">
<head>
  <title>Remove Prerequisite Task</title>
  <?php include("templates/html_head.php") ?>
  <meta http-equiv="refresh" content="1;url=taskdetails.php?id=<?php echo($primaryTaskid); ?>" />
</head>

<body>
  <div>
    <?php include("templates/navbar.php"); ?>

    <div class="ui two column centered grid">
      <div class="column">
        <h2 class="ui header">
          <div class="content">
            <?php echo($success ? "Prerequisite task removed!" : "Error removing prerequisite task") ?>
          </div>
        </h2>
      </div>
    </div>
  </div>
</body>
<html>
