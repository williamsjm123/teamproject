<?php

  require("config.php");
  $mysqli = new mysqli($db_server, $db_user, $db_password, $db_database);
  if($mysqli->connect_errno){ die("Could not connect to database: " . $mysqli->connect_errno); }

  $stmt = $mysqli->prepare("INSERT INTO TeamMember (`firstName`, `lastName`, `dateOfBirth`, `gender`, `userName`, `status`, `userPassword`) VALUES (?, ?, ?, ?, ?, ?, ?)");

  $firstName = $_POST["firstName"];
  $lastName = $_POST["lastName"];
  $dateOfBirth = strtotime($_POST["dateOfBirth"]);
  $gender = $_POST["gender"];
  $userName = $_POST["userName"];
  $status = $_POST["status"];
  $userPassword = $_POST["userPassword"];

  $stmt->bind_param("sssssss", $firstName, $lastName, date("Y-m-d", $dateOfBirth), $gender, $userName, $status, $userPassword);
  $success = $stmt->execute();

?>
<html lang="en">
<head>
  <title>Team member</title>
  <?php include("templates/html_head.php") ?>
  <meta http-equiv="refresh" content="1;url=members.php?" />
</head>

<body>
  <div>
    <?php include("templates/navbar.php"); ?>

    <div class="ui two column centered grid">
      <div class="column">
        <h2 class="ui header">
          <div class="content">
            <?php echo($success ? "Team member added!" : "Error adding team member") ?>
          </div>
        </h2>
      </div>
    </div>
  </div>
</body>
<html>
