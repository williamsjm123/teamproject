<?php
    require("config.php");
    $mysqli = new mysqli($db_server, $db_user, $db_password, $db_database);
    if($mysqli->connect_errno){ die("Could not connect to database: " . $mysqli->connect_errno); }

    $userID = $_GET["id"];

    $stmt = $mysqli->prepare("SELECT `userID`, `firstName`, `lastName`, `dateOfBirth`, `gender`, `userName`, `status`, `userPassword`, `taskId` FROM TeamMember WHERE `userID`=?");
    

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
    $member["taskId"];

    //Tells mysql server to execute prepared statement
    $stmt->execute();

    //Variables to store properties in when fetch() is called
    $stmt->bind_result($member["userID"], $member["firstName"], $member["lastName"], $member["dateOfBirth"], $member["gender"], $member["userName"], $member["status"], $member["userPassword"], $member["taskId"]);

    //Store the properties from the query in the variables bound above
    $stmt->fetch();


    /*Get tasks a member is working on*/

    $mysqli2 = new mysqli($db_server, $db_user, $db_password, $db_database);
    if($mysqli2->connect_errno){ die("Could not connect to database: " . $mysqli2->connect_errno); }
    $stmtGetTasks = $mysqli2->prepare("SELECT Task.taskId, Task.name, Task.completed FROM Task LEFT JOIN Assign ON Task.taskId = Assign.taskID WHERE Assign.userID = ? ORDER BY Task.startDate");
    $stmtGetTasks->bind_param("i", $userID);
    $task["taskId"];
    $task["name"];
    $task["completed"];
    $stmtGetTasks->execute();
    $stmtGetTasks->bind_result($task["taskId"], $task["name"], $task["completed"]);
    $taskList = Array();

    //fetch - returns true if there is another row in the results
    while($stmtGetTasks->fetch()){
        $thisTask = Array(
            "taskId" => $task["taskId"],
            "name" => $task["name"],
            "completed" => $task["completed"]
        );
        array_push($taskList, $thisTask);
    }

    /*End get tasks a member is working on*/

     /*Get name of task the team member is currently working on*/

     $mysqli3 = new mysqli($db_server, $db_user, $db_password, $db_database);
     if($mysqli3->connect_errno){ die("Could not connect to database: " . $mysqli3->connect_errno); }
     
     $stmtGetTaskName = $mysqli3->prepare("SELECT Task.name, Task.taskId FROM Task, TeamMember WHERE Task.taskId = TeamMember.taskId AND TeamMember.userID = ?");
     $stmtGetTaskName->bind_param("i", $userID);
     $task["name1"]; 
     $task["taskId1"];
     $stmtGetTaskName->execute();
     $stmtGetTaskName->bind_result($task["name1"], $task["taskId1"]);
     $stmtGetTaskName->fetch();
     /*End get name of task*/

    /*Get percentage completion of tasks based on number of completed tasks*/
    $mysqli01 = new mysqli($db_server, $db_user, $db_password, $db_database);
    if($mysqli01->connect_errno){ die("Could not connect to database: " . $mysqli01->connect_errno); }
   $overAll = $mysqli01->prepare("SELECT count(Task.completed) FROM Task LEFT JOIN Assign ON Task.taskId = Assign.taskID WHERE Assign.userID = ?");
   $overAll->bind_param("i", $userID);
   
   $totalTasks["completed"];
   $overAll->execute();
   $overAll->bind_result($totalTasks["completed"]);
   $overAll->fetch();
   
   $mysqli02 = new mysqli($db_server, $db_user, $db_password, $db_database);
     if($mysqli02->connect_errno){ die("Could not connect to database: " . $mysqli02->connect_errno); }
    $taskscompleted = $mysqli02->prepare("SELECT count(Task.completed) FROM Task LEFT JOIN Assign ON Task.taskId = Assign.taskID WHERE Assign.userID = ? AND Task.completed = 1");
    $taskscompleted->bind_param("i", $userID);
    $totalTasksCompleted["completed"];
    $taskscompleted->execute();
    $taskscompleted->bind_result($totalTasksCompleted["completed"]);
    $taskscompleted->fetch();
  
    $memberCompletePercentage = ($totalTasksCompleted["completed"] / $totalTasks["completed"]) * 100;


/*Schedule*/

    $mysqli4 = new mysqli($db_server, $db_user, $db_password, $db_database);
    if($mysqli4->connect_errno){ die("Could not connect to database: " . $mysqli4->connect_errno); }
    
    $stmtGetTasksInSchedule = $mysqli4->prepare("SELECT Task.taskId, Task.name, Task.startDate, Task.deadline, Task.completed FROM Task LEFT JOIN Assign ON Task.taskId = Assign.taskID WHERE Assign.userID = ? ORDER BY Task.startDate");

    $stmtGetTasksInSchedule->bind_param("i", $userID);

    $taskInSchedule["taskId"];
    $taskInSchedule["name"];
    $taskInSchedule["startDate"];
    $taskInSchedule["deadline"];
    $taskInSchedule["completed"];

    $stmtGetTasksInSchedule->execute();

    $stmtGetTasksInSchedule->bind_result($taskInSchedule["taskId"], $taskInSchedule["name"], $taskInSchedule["startDate"], $taskInSchedule["deadline"], $taskInSchedule["completed"]);

    $taskInScheduleList = Array();

    //fetch - returns true if there is another row in the results
    while($stmtGetTasksInSchedule->fetch()){
        $thisTaskInSchedule = Array(
            "nameOfTask" => $taskInSchedule["name"],
            "startDateOfTask" => $taskInSchedule["startDate"],
            "deadlineOfTask" => $taskInSchedule["deadline"],
            "isTaskCompleted" => $taskInSchedule["completed"]
        );
        array_push($taskInScheduleList, $thisTaskInSchedule);
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Tasks</title>
  <?php include("templates/html_head.php") ?>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load('current', {'packages':['gantt']});
    google.charts.setOnLoadCallback(drawChart);

    function daysToMilliseconds(days) {
      return days * 24 * 60 * 60 * 1000;
    }

    function drawChart() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Task ID');
      data.addColumn('string', 'Task Name');
      data.addColumn('date', 'Start Date');
      data.addColumn('date', 'End Date');
      data.addColumn('number', 'Duration');
      data.addColumn('number', 'Percent Complete');
      data.addColumn('string', 'Dependencies');

      var arrayOfData = JSON.parse( '<?php echo json_encode($taskInScheduleList) ?>' );

      var arrayLength = arrayOfData.length;

      if(arrayLength<2){
        var err = document.getElementById("error");
        err.innerHTML ="<p>No schedule Generated as less than 2 tasks assigned!</p>"
      }

      for (var i = 0; i < arrayLength; i++) {
        var name = arrayOfData[i].nameOfTask;

        var start = arrayOfData[i].startDateOfTask;

        var startYear = start.substr(0, 4);
        var startMonth = start.substr(5, 2);
        var startDay = start.substr(8, 2);

        var end = arrayOfData[i].deadlineOfTask;

        var endYear = end.substr(0, 4);
        var endMonth = end.substr(5, 2);
        var endDay = end.substr(8, 2);

        var isComplete = arrayOfData[i].isTaskCompleted;
        var completedNumber;
        if(isComplete == 1){
          completedNumber = 100;
        }
        else{
          completedNumber = 0;
        }

        data.addRows([
        [name, name,
         new Date(startYear, startMonth, startDay), new Date(endYear, endMonth, endDay), null, completedNumber,  null]
      ]);

      }
      /*console.log(arrayOfData[0].nameOfTask);
      console.log(arrayOfData[0].startDateOfTask);
      console.log(arrayOfData[0].deadlineOfTask);*/

      var options = {
        height: (42 + (42 * arrayLength))
      };

      var chart = new google.visualization.Gantt(document.getElementById('chart_div'));

      chart.draw(data, options);
    }
  </script>
</head>

<body>
  <div>
    <?php include("templates/navbar.php"); ?>

    <div class="ui two column centered grid">
      <div class="column">
        <h2 class="ui header">
          <i class="user icon"></i>
          <div class="content">
            <?php echo($member["firstName"] . " " . $member["lastName"]); ?>
            <div class="sub header"><?php echo($member["userName"]); ?></div>
          </div>
        </h2>

        <a class="ui icon button header-action" data-tooltip="Edit member" href="editmember.php?id=<?php echo($member["userID"]); ?>">
          <i class="edit outline icon"></i>
        </a>

        <div class="ui segment">
          <table class="ui definition table">
            <tbody>
              <tr><td>Role</td><td><?php echo($member["status"]); ?></td><tr>
              <tr><td>DOB</td><td><?php echo($member["dateOfBirth"]); ?></td></tr>
              <tr><td>Progress</td><td><?php echo($memberCompletePercentage . "%"); ?></td></tr>
            </tbody>
          </table>

          <h4 class="ui horizontal divider header">
            Currently working on
          </h4>
          <?php
            if ($task["name1"] == null) {
              echo("<p>Not currently working on any task. </p>");
            } else { ?>
              <div style="overflow: hidden">
                <a href="taskdetails.php?id=<?php echo($task["name1"]) ?>">
                  <?php echo($task["name1"]);?>
                </a>
                <div class="ui tiny <?php echo($task["completed"] ? "" : "red") ?> label" style="float: right">
                  <?php echo($task["completed"] ? "Completed" : "Incomplete"); ?>
                </div>
              </div> <?php
            } ?>

          <h4 class="ui horizontal divider header">
            Incomplete
          </h4>
          <?php
          if (count($taskList) == 0) {
            echo("<p>No tasks currently assigned. </p>");
          } else { ?>
          <table class="ui very basic table">
            <?php foreach($taskList as $taskAssigned) {
              if ($taskAssigned["completed"] == 0) { ?>
              <tr>
                <td>
                  <a href="taskdetails.php?id=<?php echo($taskAssigned["taskId"]) ?>">
                    <?php echo($taskAssigned["name"]);?>
                  </a>
                  <div style="float: right">
                    <div class="ui tiny red label">Incompleted</div>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href='setcurrenttask.php?userid=<?php echo($userID); ?>&taskid=<?php echo($taskAssigned["taskId"]); ?>' data-tooltip="Set as current task">
                      <i class="arrow up icon"></i>
</a>
                    </a>
                  </div>
                </td>
              </tr>
              <?php } ?>
            <?php } ?>
          </table>
          <?php } ?>

          <h4 class="ui horizontal divider header">
            Completed
          </h4>
          <?php
          if (count($taskList) == 0) {
            echo("<p>No tasks currently assigned. </p>");
          } else { ?>
          <table class="ui very basic table">
            <?php foreach($taskList as $taskAssigned) {
              if ($taskAssigned["completed"] == 1) { ?>
              <tr>
                <td>
                  <a href="taskdetails.php?id=<?php echo($taskAssigned["taskId"]) ?>">
                    <?php echo($taskAssigned["name"]);?>
                  </a>
                  <div style="float: right">
                    <div class="ui tiny label">Completed</div>&nbsp;&nbsp;&nbsp;&nbsp;
                  </div>
                </td>
              </tr>
              <?php } ?>
            <?php } ?>
          </table>
          <?php } ?>
        </div>

        <div id="chart_div"></div>
        <div id="error"></div>
      </div>
    </div>
  </div>
</body>
</html>
