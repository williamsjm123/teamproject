<?php

  require("config.php");

  $mysqli = new mysqli($db_server, $db_user, $db_password, $db_database);
  if($mysqli->connect_errno){ die("Could not connect to database: " . $mysqli->connect_errno); }

  $stmt = $mysqli->prepare("INSERT INTO Assign (`taskID`, `userID`) VALUES (?, ?)");


  $taskID = $_GET["taskid"];
  $userID = $_GET["userid"];

  $stmt->bind_param("ii", $taskID, $userID);

  $success = $stmt->execute();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Assign Member</title>
  <?php include("templates/html_head.php") ?>
  <meta http-equiv="refresh" content="1;url=taskdetails.php?id=<?php echo($taskID) ?>" />
</head>

<body>
  <div>
    <?php include("templates/navbar.php"); ?>

    <div class="ui two column centered grid">
      <div class="column">
        <h2 class="ui header">
          <div class="content">
            <?php echo($success ? "Member Assigned!" : "Error assigning member to task") ?>
          </div>
        </h2>
      </div>
    </div>
  </div>
</body>
</html>
