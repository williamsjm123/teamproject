<?php

  require("config.php");
  $mysqli = new mysqli($db_server, $db_user, $db_password, $db_database);
  if($mysqli->connect_errno){ die("Could not connect to database: " . $mysqli->connect_errno); }

  $result = $mysqli->query("SELECT userID, firstName, lastName, dateOfBirth, gender, userName, status, userPassword FROM TeamMember");

  $members = array();

  while($row = $result->fetch_assoc()){
    $thisMember = array(
      "userID" => $row["userID"],
      "firstName" => $row["firstName"],
      "lastName" => $row["lastName"],
      "dateOfBirth" => $row["dateOfBirth"],
      "gender" => $row["gender"],
      "userName" => $row["userName"],
      "status" => $row["status"],
      "userPassword" => $row["userPassword"]
    );

    array_push($members, $thisMember);
  }


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Members</title>
  <?php include("templates/html_head.php") ?>
  <style>
  .ui.list >.item {
    padding-top: 1rem;
    padding-bottom: 1rem;
    position: relative;
  }

  .delete-member {
    position: absolute;
    right: 0;
    top: 1rem;
  }

  .ui.list >.item:first-child .delete-member {
    top: 0.2rem;
  }
  </style>
</head>

<body>
  <div>
    <?php include("templates/navbar.php"); ?>

    <div class="ui two column centered grid">
      <div class="column">
        <h2 class="ui header">
          <i class="users icon"></i>
          <div class="content">
            Member List
            <div class="sub header">
              This page shows all members working on the project
            </div>
          </div>
        </h2>

        <a class="ui icon button header-action" data-tooltip="Create new member" href="createmember.php">
          <i class="add icon"></i>
        </a>

        <div class="ui segment">
          <div class="ui divided list">
            <?php foreach ($members as $member) { ?>
            <div class="item">
              <i class="user icon"></i>
              <div class="content">
                <a href="memberdetails.php?id=<?php echo($member["userID"]); ?>" class="header">
                  <?php echo($member["firstName"]) ?>
                </a>
                <div class="description">
                  <?php echo($member["status"]) ?>
                </div>

                <a href="memberdelete.php?id=<?php echo($member["userID"]); ?>" class="delete-member">
                  <i class="alternate trash icon"></i>
                </a>
              </div>
            </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
