<?php
    require("config.php");
    $mysqli = new mysqli($db_server, $db_user, $db_password, $db_database);
    if($mysqli->connect_errno){ die("Could not connect to database: " . $mysqli->connect_errno); }

    $taskID = $_GET["id"];

    $stmt = $mysqli->prepare("SELECT taskId, name, description, startDate, deadline, completed, newDeadline FROM Task WHERE `taskId`=?");

    $stmt->bind_param("i", $taskID);

    //Variables to put the result of the query into
    $task["id"];
    $task["name"];
    $task["description"];
    $task["startDate"];
    $task["deadline"];
    $task["completed"];
    $task["newDeadline"];

    //Tells mysql server to execute prepared statement
    $stmt->execute();

    //Variables to store properties in when fetch() is called
    $stmt->bind_result($task["id"], $task["name"], $task["description"], $task["startDate"], $task["deadline"], $task["completed"], $task["newDeadline"]);

    //Store the properties from the query int he variables bound above
    $stmt->fetch();

    /*Get prerequisite tasks*/
    $mysqli2 = new mysqli($db_server, $db_user, $db_password, $db_database);
    if($mysqli2->connect_errno){ die("Could not connect to database: " . $mysqli2->connect_errno); }

    $stmtGetPrereq = $mysqli2->prepare("SELECT Task.taskId, Task.name, Task.completed FROM Task, Prerequisite WHERE Task.taskId = Prerequisite.prereqTask_id AND Prerequisite.primaryTask_id=?");

    $stmtGetPrereq->bind_param("i", $taskID);

    $pretask["taskId"];
    $pretask["name"];
    $pretask["completed"];

    $stmtGetPrereq->execute();

    $stmtGetPrereq->bind_result($pretask["taskId"], $pretask["name"], $pretask["completed"]);

    $preTaskList = Array();

    //fetch - returns true if there is another row in the results
    while($stmtGetPrereq->fetch()){
        $thisPreTask = Array(
            "taskId" => $pretask["taskId"],
            "name" => $pretask["name"],
            "completed" => $pretask["completed"]
        );
        array_push($preTaskList, $thisPreTask);
    }
    /*End get prereq tasks*/

    $completedDisabled = false;

    foreach($preTaskList as $taskToCheck){
        if($taskToCheck["completed"] == 0){
            $completedDisabled = true;
        }
    }





?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Edit Task</title>
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
            Edit Task
            <div class="sub header">
              Please fill in the following information to edit the task.
            </div>
          </div>
        </h2>

        <div class="ui center aligned segment">
          <form method="POST" action="edittask_result.php" class="ui form">
            <h4 class="ui dividing header">Edit Task</h4>
            <div class="inline field">
              <label>Task Name</label>
              <input type="text" name="name" placeholder="Type Task Name" value="<?php echo($task["name"]); ?>" />
            </div>

            <div class="inline field">
              <label>Task Description</label>
              <input type="text" name="description" placeholder="Type Task Description" value="<?php echo($task["description"]); ?>" />
            </div>

            <div class="inline field">
              <label>Start Date</label>
              <input type="date" name="startDate" value="<?php echo($task["startDate"]); ?>" />
            </div>

            <div class="inline field">
              <label>Deadline</label>
              <input type="date" name="deadline" value="<?php echo($task["deadline"]); ?>" />
            </div>

            <div class="inline field">
              <label>New Deadline</label>
              <input type="date" name="newDeadline" value="<?php echo($task["newDeadline"]); ?>" />
            </div>

            <div class="inline field">
              <div class="ui checkbox">
                <input type="checkbox" <?php if($completedDisabled == true){ echo("disabled");}?> name="completed" value="yes" <?php echo($task["completed"] == 1 ? "checked" : ""); ?>>
                <label>Completed</label>
              </div>
            </div>
            <?php if($completedDisabled == true) {
              echo("<p> (All prerequisite tasks must be completed first)</p>");
            } ?>
            <input type="hidden" name="id" value="<?php echo($task["id"]); ?>" />
            <button type="submit" class="ui button">Save Task</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
