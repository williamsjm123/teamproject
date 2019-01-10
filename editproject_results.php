<?php

  require("config.php");

  $mysqli = new mysqli($db_server, $db_user, $db_password, $db_database);
  if($mysqli->connect_errno){ die("Could not connect to database: " . $mysqli->connect_errno); }

  $stmt = $mysqli->prepare("UPDATE Project SET `projectName`=?, `deadline`=?, `requirements`=? WHERE projectId=1");

  $projectName = $_POST["projectName"];
  $deadline = strtotime($_POST["deadline"]);
  $requirements = $_POST["requirements"];

  $stmt->bind_param("sss", $projectName, date("Y-m-d", $deadline), $requirements);
  $success = $stmt->execute();

?>
<html lang="en">
<head>
  <title>Update Project</title>
  <?php include("templates/html_head.php") ?>
  <meta http-equiv="refresh" content="1;url=index.php" />
</head>

<body>
  <div>
    <?php include("templates/navbar.php"); ?>

    <div class="ui two column centered grid">
      <div class="column">
        <h2 class="ui header">
          <div class="content">
            <?php echo($success ? "Project updated!" : "Error adding project") ?>
          </div>
        </h2>
      </div>
    </div>
  </div>
</body>
<html>
