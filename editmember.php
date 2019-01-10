<?php

  require("config.php");
  $mysqli = new mysqli($db_server, $db_user, $db_password, $db_database);
  if($mysqli->connect_errno){ die("Could not connect to database: " . $mysqli->connect_errno); }

  $userID = $_GET["id"];

  $stmt = $mysqli->prepare("SELECT userID, firstName, lastName, dateOfBirth, gender, userName, status, userPassword FROM TeamMember WHERE `userID`=?");

  $stmt->bind_param("i", $userID);

  //Variables to put the result of the query into
  $member["userID"];
  $member["firstName"];
  $member["lastName"];
  $member["dateOfBirth"];
  $member["gender"];
  $member["userName"];
  $member["status"];
  $member["userPassword"];

  //Tells mysql server to execute prepared statement
  $stmt->execute();

  //Variables to store properties in when fetch() is called
  $stmt->bind_result($member["userID"], $member["firstName"], $member["lastName"], $member["dateOfBirth"], $member["gender"], $member["userName"], $member["status"], $member["userPassword"]);

  //Store the properties from the query in the variables bound above
  $stmt->fetch();

?>
<html lang="en">
<head>
  <title>Edit Member</title>
  <?php include("templates/html_head.php") ?>
</head>

<body>
  <div>
    <?php include("templates/navbar.php"); ?>

    <div class="ui two column centered grid">
      <div class="column">
        <h2 class="ui header">
          <i class="user icon"></i>
          <div class="content">
            Edit Member
            <div class="sub header">
              Please fill in the following information to edit the member.
            </div>
          </div>
        </h2>

        <div class="ui center aligned segment">
          <form method="POST" action="editmember_result.php" class="ui form">
            <h4 class="ui dividing header">Edit Team Member</h4>
            <div class="inline field">
              <label>First name</label>
              <input type="text" name="firstName" placeholder="Type first Name" value="<?php echo $member["firstName"] ?>" />
            </div>

            <div class="inline field">
              <label>Last name</label>
              <input type="text" name="lastName" placeholder="Type last Name" value="<?php echo $member["lastName"] ?>" />
            </div>

            <div class="inline field">
              <label>Gender</label>
              <input type="text" name="gender" placeholder="Type gender" value="<?php echo $member["gender"] ?>"/>
            </div>

            <div class="inline field">
              <label>Date of birth</label>
              <input type="date" name="dateOfBirth" placeholder="Type date of birth" value="<?php echo $member["dateOfBirth"] ?>"/>
            </div>

            <div class="inline field">
              <label>Status</label>
              <input type="text" name="status" placeholder="Type status" value="<?php echo $member["status"] ?>"/>
            </div>

            <div class="inline field">
              <label>User name</label>
              <input type="text" name="userName" placeholder="Type username" value="<?php echo $member["userName"] ?>"/>
            </div>

            <div class="inline field">
              <label>Password</label>
              <input type="password" name="userPassword" placeholder="Type password" value="<?php echo $member["userPassword"] ?>"/>
            </div>

            <input type="hidden" name="id" value="<?php echo($member["userID"]); ?>" />
            <button class="ui button" type="submit">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
<html>
