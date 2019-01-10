<?php
     /*Delete current working task if it exists*/

     require("config.php");
     $mysqli = new mysqli($db_server, $db_user, $db_password, $db_database);
     if($mysqli->connect_errno){ die("Could not connect to database: " . $mysqli->connect_errno); }

     $taskID = $_GET["taskid"];
     $userID = $_GET["userid"];
     
     $stmtDeleteCurrentTask = $mysqli->prepare("UPDATE TeamMember SET taskId = null WHERE TeamMember.userID = ?");
     $stmtDeleteCurrentTask->bind_param("i", $userID);
     $stmtDeleteCurrentTask->execute();
     $success = $stmtDeleteCurrentTask->execute();
     /*End*/

     /*Create new current working task*/
     $stmtSetCurrentTask = $mysqli->prepare("UPDATE TeamMember SET taskId = ? WHERE TeamMember.userID = ?");
     $stmtSetCurrentTask->bind_param("ii", $taskID, $userID);
     $stmtSetCurrentTask->execute();
     $success = $stmtSetCurrentTask->execute();
     /*End*/


?>
<html lang="en">
<head>
  <title>something</title>
  <?php include("templates/html_head.php") ?>
  <meta http-equiv="refresh" content="1;url=memberdetails.php?id=<?php echo($userID); ?>" />
</head>

<body>
  <div>
    <?php include("templates/navbar.php"); ?>

    <div class="ui two column centered grid">
      <div class="column">
        <h2 class="ui header">
          <div class="content">
            <?php echo($success ? "something" : "something error") ?>
          </div>
        </h2>
      </div>
    </div>
  </div>
</body>
<html>
