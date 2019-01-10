<?php

  require("config.php");
  $mysqli = new mysqli($db_server, $db_user, $db_password, $db_database);
  if($mysqli->connect_errno){ die("Could not connect to database: " . $mysqli->connect_errno); }

  $projectId = 1;

  $stmt = $mysqli->prepare("SELECT projectId, deadline, projectName, requirements, completed FROM Project WHERE projectId=1");


  //Variables to put the result of the query into
  $project["projectId"];
  $project["deadline"];
  $project["projectName"];
  $project["requirements"];
  $project["completed"];

  //Tells mysql server to execute prepared statement
  $stmt->execute();

  //Variables to store properties in when fetch() is called
  $stmt->bind_result($project["projectId"], $project["deadline"], $project["projectName"], $project["requirements"], $project["completed"]);

  //Store the properties from the query in the variables bound above
  $stmt->fetch();


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Edit Project</title>
  <?php include("templates/html_head.php") ?>
</head>

<body>
  <div>
    <?php include("templates/navbar.php"); ?>

    <div class="ui two column centered grid">
      <div class="column">
        <h2 class="ui header">
          <i class="edit outline icon"></i>
          <div class="content">
            Project Management System
            <div class="sub header">
              Please fill in the following information to edit the project.
            </div>
          </div>
        </h2>

        <div class="ui center aligned segment">
          <form method="POST" action="editproject_results.php" class="ui form">
            <h4 class="ui dividing header">Edit Project</h4>
            <div class="inline field">
              <label>Project name</label>
              <input type="text" name="projectName" placeholder="Type Project Name" value="<?php echo($project["projectName"]); ?>"/>
            </div>

            <div class="inline field">
              <label>Description</label>
              <input type="text" name="requirements" placeholder="Type project description" value="<?php echo($project["requirements"]); ?>"/>
            </div>

            <div class="inline field">
              <label>Deadline</label>
              <input type="date" name="deadline" placeholder="Type project deadline" value="<?php echo($project["deadline"]); ?>"/>
            </div>
            <input type="hidden" name="id" value="<?php echo($projectId); ?>" />
            <button type="submit" class="ui button">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
