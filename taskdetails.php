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
   
    //Store the properties from the query in the variables bound above
    $stmt->fetch();
    

    /*Get project deadline*/
    $mysqli3 = new mysqli($db_server, $db_user, $db_password, $db_database);
    if($mysqli3->connect_errno){ die("Could not connect to database: " . $mysqli3->connect_errno); }
    $stmt2 = $mysqli3->prepare("SELECT deadline FROM Project WHERE `projectId`=1");
    $projectDeadline["deadline"];
    $stmt2->execute();
    $stmt2->bind_result($projectDeadline["deadline"]);
    $stmt2->fetch();


    /*Get members working on task*/
    $mysqli2 = new mysqli($db_server, $db_user, $db_password, $db_database);
    if($mysqli2->connect_errno){ die("Could not connect to database: " . $mysqli2->connect_errno); }
    
    $stmtGetMembers = $mysqli2->prepare("SELECT TeamMember.userID, TeamMember.firstName, TeamMember.lastName, TeamMember.status FROM TeamMember LEFT JOIN Assign ON TeamMember.userID = Assign.userID WHERE Assign.taskID = ?");

    $stmtGetMembers->bind_param("i", $taskID);

    //Variables to put the result of the query into
    $member["userID"];
    $member["firstName"];
    $member["lastName"];
    $member["status"];

    $stmtGetMembers->execute();

    $stmtGetMembers->bind_result($member["userID"], $member["firstName"], $member["lastName"], $member["status"]);

    $membersList = Array();

    //fetch - returns true if there is another row in the results
    while($stmtGetMembers->fetch()){
        $thisMember = Array(
            "userID" => $member["userID"],
            "firstName" => $member["firstName"],
            "lastName" => $member["lastName"],
            "status" => $member["status"]
        );
        array_push($membersList, $thisMember);
    }
    /*End get memners working on task*/

    /*Get list of all members*/
    $mysqli3 = new mysqli($db_server, $db_user, $db_password, $db_database);
    if($mysqli3->connect_errno){ die("Could not connect to database: " . $mysqli3->connect_errno); }

    $stmtGetAllMembers = $mysqli3->prepare("SELECT userID, firstName, LastName FROM TeamMember");

    $allMemberResult["userID"];
    $allMemberResult["firstName"];   
    $allMemberResult["lastName"];

    $stmtGetAllMembers->execute();

    $stmtGetAllMembers->bind_result($allMemberResult["userID"], $allMemberResult["firstName"], $allMemberResult["lastName"]);

    $allMembers = Array();

    while($stmtGetAllMembers->fetch()){
        $currentMember = Array(
            "userID" => $allMemberResult["userID"],
            "firstName" => $allMemberResult["firstName"],
            "lastName" => $allMemberResult["lastName"]
        );
        array_push($allMembers, $currentMember);
    }
    /*End get list of all members*/

    /*Get prereq tasks*/
    $mysqli4 = new mysqli($db_server, $db_user, $db_password, $db_database);
    if($mysqli4->connect_errno){ die("Could not connect to database: " . $mysqli4->connect_errno); }

    $stmtGetPrereq = $mysqli4->prepare("SELECT Task.taskId, Task.name, Task.completed FROM Task, Prerequisite WHERE Task.taskId = Prerequisite.prereqTask_id AND Prerequisite.primaryTask_id=?");

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

    /*Get all tasks*/
    $mysqli5 = new mysqli($db_server, $db_user, $db_password, $db_database);
    if($mysqli5->connect_errno){ die("Could not connect to database: " . $mysqli5->connect_errno); }

    $stmtGetAllTasks = $mysqli5->prepare("SELECT taskId, name FROM Task");

    $allTaskResult["taskId"];
    $allTaskResult["name"];


    $stmtGetAllTasks->execute();

    $stmtGetAllTasks->bind_result($allTaskResult["taskId"], $allTaskResult["name"]);

    $alltasks = Array();

    while($stmtGetAllTasks->fetch()){
        $currentTask = Array(
            "taskId" => $allTaskResult["taskId"],
            "name" => $allTaskResult["name"]
        );
        array_push($alltasks, $currentTask);
    }
    /*End get all tasks*/

     /*Calculate days left until due date*/
    $now = time();
    $taskDeadline = strtotime($task["deadline"]);
    $datediff = $taskDeadline - $now;
    $daystodeadline = round($datediff / (60 * 60 * 24));
    
 /*Calculate days from original due date after extension*/
    $taskNewDeadline = $task["newDeadline"];
    $taskNewDeadlineCalculation = strtotime($task["newDeadline"]);
    $datediff2 = $taskNewDeadlineCalculation - $taskDeadline;
    $daysExtended = round($datediff2 / (60 * 60 * 24));

 /*Calculate if extension affects overall project deadline*/   
 
 $projectDeadlineDate = strtotime($projectDeadline["deadline"]);
 $affectsProjectDeadlineCalculation = $taskNewDeadlineCalculation - $projectDeadlineDate;
 $daysAffected = round($affectsProjectDeadlineCalculation / (60 * 60 * 24));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Tasks</title>
  <?php include("templates/html_head.php") ?>
  <style>
  form .ui.button[type="submit"] {
    margin-left: 0.75rem;
  }

  .member-actions {
    float: right;
  }
  </style>

  <script>
  $(function() {
    $('.ui.dropdown').dropdown();
  })
  </script>
</head>

<body>
  <div>
    <?php include("templates/navbar.php"); ?>

    <div class="ui two column centered grid">
      <div class="column">
        <h2 class="ui header">
          <i class="file alternate outline icon"></i>
          <div class="content">
            <?php echo($task["name"]) ?>

            <div class="sub header">
              <?php
                if ($daystodeadline > 0) {
                  echo("(Due in " . $daystodeadline . " days)");
                } else {
                  echo("(Overdue, due " . abs($daystodeadline) . " days ago)");
                }
              ?>
            </div>
          </div>
        </h2>

        <a
          class="ui icon button header-action"
          data-tooltip="Edit task"
          href="edittask.php?id=<?php echo($task["id"]); ?>">
          <i class="edit outline icon"></i>
        </a>

        <div class="ui segment">
          <table class="ui definition table">
            <tbody>
              <tr>
                <td class="two wide column">Description</td>
                <td><?php echo($task["description"]) ?></td>
              </tr>
              <tr>
                <td>Start Date</td>
                <td><?php echo($task["startDate"]) ?></td>
              </tr>
              <tr>
                <td>Deadline</td>
                <td><?php echo($task["deadline"]) ?></td>
              </tr>
              <tr>
                <td>Extension</td>
                <td>
                <?php
                  if ($taskNewDeadline == null) {
                    echo("No extensions made");
                  } else {
                    echo(" " . $taskNewDeadline . " (Extended with " . $daysExtended . " \ndays) ");
                  }
                ?>
                </td>
              </tr>
              <tr>
                <td>Current Status</td>
                <td>
                  <div class="ui tiny <?php echo($task["completed"] ? "" : "red") ?> label">
                    <?php echo($task["completed"] ? "Completed" : "Incomplete"); ?>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <?php
            if($daysAffected > 0) {
              echo("<p>Warning: The extension has caused the overall project deadline to be delayed with  <strong>" . $daysAffected . "</strong> days</p>");
            }
          ?>

          <h4 class="ui horizontal divider header">
            <i class="list ul icon"></i>
            Prerequisite Tasks
          </h4>
          <?php
            if(count($preTaskList) == 0){
              echo("<p>This task currently has no prerequisite tasks assigned. </p>");
            } else { ?>
            <table class="ui very basic table">
              <?php foreach($preTaskList as $preTask) { ?>
                <tr>
                  <td>
                    <a href="taskdetails.php?id=<?php echo($preTask["taskId"]) ?>">
                      <?php echo($preTask["name"]) ?>
                    </a>
                    <div class="member-actions">
                      <div class="ui tiny <?php echo($preTask["completed"] ? "" : "red") ?> label"><?php echo($preTask["completed"] ? "Completed" : "Incomplete"); ?></div>&nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="removeprerequisite.php?prereqtaskid=<?php echo($preTask["taskId"]); ?>&primarytaskid=<?php echo($taskID); ?>">
                        <i class="alternate trash icon"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php } ?>
            </table>
          <?php } ?>

          <form action="assigprerequisitetask.php" method="get">
            <input type="hidden" name="primarytaskid" value="<?php echo($taskID); ?>" />

            <div class="ui selection dropdown">
              <input type="hidden" name="prereqtaskid">
              <i class="dropdown icon"></i>
              <div class="default text">Assign a prerequisite task...</div>
              <div class="menu">
              <?php
                foreach($alltasks as $selectedTask) {
                  $isAlreadyAssigned2 = false;

                  foreach($preTaskList as $existingprereq) {
                    if($existingprereq["taskId"] === $selectedTask["taskId"]) {
                      $isAlreadyAssigned2 = true;
                    }

                    if($taskID == $selectedTask["taskId"]) {
                      $isAlreadyAssigned2 = true;
                    }
                  }

                  if($taskID == $selectedTask["taskId"]) {
                    $isAlreadyAssigned2 = true;
                  }

                  if(!$isAlreadyAssigned2) { ?>
                    <div class="item" data-value="<?php echo($selectedTask["taskId"]);?>">
                      <?php echo($selectedTask["name"]); ?>
                    </div> <?php
                  }
                }
              ?>
              </div>
            </div>
            <button type="submit" class="ui button">Add</button>
          </form>

          <h4 class="ui horizontal divider header">
            <i class="users icon"></i>
            Assigned Members
          </h4>
          <?php
            if (count($membersList) == 0) {
               echo("<p>No members currently assigned. </p>");
            } else { ?>
            <table class="ui very basic table">
              <?php foreach ($membersList as $member) { ?>
                <tr>
                  <td>
                    <a href='memberdetails.php?id=<?php echo($member["userID"]) ?>'>
                      <?php echo($member["firstName"] . " " . $member["lastName"]) . " " ?>
                    </a>
                    <div class="member-actions">
                      <div class="ui tiny label"><?php echo($member["status"]); ?></div>&nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="unassignmember.php?userid=<?php echo($member["userID"]); ?>&taskid=<?php echo($taskID); ?>">
                        <i class="alternate trash icon"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php } ?>
            </table>
          <?php } ?>

          <form action="assignmember.php" method="get">
            <input type="hidden" name="taskid" value="<?php echo($taskID); ?>" />

            <div class="ui selection dropdown">
              <input type="hidden" name="userid">
              <i class="dropdown icon"></i>
              <div class="default text">Assign another user...</div>
              <div class="menu">
                <?php
                  foreach ($allMembers as $member) {
                    $isAlreadyAssigned = false;
                    foreach ($membersList as $assignee) {
                      if ($assignee["userID"] === $member["userID"]) {
                        $isAlreadyAssigned = true;
                      }
                    }
                    if (!$isAlreadyAssigned) { ?>
                      <div class="item" data-value="<?php echo($member["userID"]);?>">
                        <?php echo($member["firstName"] . " " . $member["lastName"]); ?>
                      </div> <?php
                    }
                  }
                ?>
              </div>
            </div>
            <button type="submit" class="ui button">Assign</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
