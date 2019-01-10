<?php

  require("config.php");
  $mysqli = new mysqli($db_server, $db_user, $db_password, $db_database);
  if($mysqli->connect_errno){ die("Could not connect to database: " . $mysqli->connect_errno); }

  $userID = $_GET["id"];
  $stmt = $mysqli->prepare("DELETE FROM TeamMember WHERE `userID`=?");
  $stmt->bind_param("i", $userID);

  //Tells mysql server to execute prepared statement
  $stmt->execute();
  $success = $stmt->execute();

?>
<html lang="en">
<head>
  <title>Team member deleted</title>
  <?php include("templates/html_head.php") ?>
  <meta http-equiv="refresh" content="1;url=members.php" />
</head>

<body>
  <div>
    <?php include("templates/navbar.php"); ?>

    <div class="ui two column centered grid">
      <div class="column">
        <h2 class="ui header">
          <div class="content">
            <?php echo($success ? "Team member deleted!" : "Error deleting team member") ?>
          </div>
        </h2>
      </div>
    </div>
  </div>
</body>
<html>
