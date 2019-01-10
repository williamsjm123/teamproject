<?php
    require("config.php");
    $mysqli = new mysqli($db_server, $db_user, $db_password, $db_database);
    if($mysqli->connect_errno){ die("Could not connect to database: " . $mysqli->connect_errno); }

    $result = $mysqli->query("SELECT name, startDate, deadline, completed FROM Task");

    $taskInfo = array();

    while($row = $result->fetch_assoc()){
            $thisTask = array(
            "nameOfTask" => $row["name"],
            "startDateOfTask" => $row["startDate"],
            "deadlineOfTask" => $row["deadline"],
            "isTaskCompleted" => $row["completed"]
            );
            array_push($taskInfo, $thisTask);
    }

     /*Get percentage completion of project based on number of complted tasks*/
     $mysqli01 = new mysqli($db_server, $db_user, $db_password, $db_database);
     if($mysqli01->connect_errno){ die("Could not connect to database: " . $mysqli01->connect_errno); }
     $overAll = $mysqli01->prepare("SELECT count(completed) FROM Task");
    $totalTasks["completed"];
    $overAll->execute();
    $overAll->bind_result($totalTasks["completed"]);
    $overAll->fetch();

    $mysqli02 = new mysqli($db_server, $db_user, $db_password, $db_database);
     if($mysqli02->connect_errno){ die("Could not connect to database: " . $mysqli02->connect_errno); }
    $taskscompleted = $mysqli02->prepare("SELECT count(completed) FROM Task WHERE completed = 1");
    $totalTasksCompleted["completed"];
    $taskscompleted->execute();
    $taskscompleted->bind_result($totalTasksCompleted["completed"]);
    $taskscompleted->fetch();
  
    $projectCompletePercentage = ($totalTasksCompleted["completed"] / $totalTasks["completed"]) * 100; 
     
    
    
 

    /*Get the latest deadline*/
    $mysqli2 = new mysqli($db_server, $db_user, $db_password, $db_database);
    if($mysqli2->connect_errno){ die("Could not connect to database: " . $mysqli2->connect_errno); }

    $stmt = $mysqli2->prepare("SELECT max(deadline) FROM Task");

    //Variables to put the result of the query into
    $max["deadline"];

    //Tells mysql server to execute prepared statement
    $stmt->execute();

    //Variables to store properties in when fetch() is called
    $stmt->bind_result($max["deadline"]);

    //Store the properties from the query in the variables bound above
    $stmt->fetch();
    /*End get the latest deadline*/

    /*Get the latest newDeadline*/
    $mysqli3 = new mysqli($db_server, $db_user, $db_password, $db_database);
    if($mysqli3->connect_errno){ die("Could not connect to database: " . $mysqli3->connect_errno); }

    $stmt2 = $mysqli3->prepare("SELECT max(newDeadline) FROM Task");

    //Variables to put the result of the query into
    $max["newDeadline"];

    //Tells mysql server to execute prepared statement
    $stmt2->execute();

    //Variables to store properties in when fetch() is called
    $stmt2->bind_result($max["newDeadline"]);

    //Store the properties from the query in the variables bound above
    $stmt2->fetch();
    /*End get the latest newDeadline*/


    $finalDeadline = $max["deadline"];
    if($max["newDeadline"] > $max["deadline"]){
      $finalDeadline = $max["newDeadline"];
    }


    /*Get project info*/
    $mysqli4 = new mysqli($db_server, $db_user, $db_password, $db_database);
    if($mysqli4->connect_errno){ die("Could not connect to database: " . $mysqli4->connect_errno); }

    $stmt3 = $mysqli4->prepare("SELECT projectId, deadline, projectName, requirements, completed FROM Project WHERE projectId = 1");

    //Variables to put the result of the query into
    $project["projectId"];
    $project["deadline"];
    $project["projectName"];
    $project["requirements"];
    $project["completed"];

    //Tells mysql server to execute prepared statement
    $stmt3->execute();

    //Variables to store properties in when fetch() is called
    $stmt3->bind_result($project["projectId"], $project["deadline"], $project["projectName"], $project["requirements"], $project["completed"]);

    //Store the properties from the query in the variables bound above
    $stmt3->fetch();
    /*End get project info*/


    /*Get total number of members*/
    $mysqli5 = new mysqli($db_server, $db_user, $db_password, $db_database);
    if($mysqli5->connect_errno){ die("Could not connect to database: " . $mysqli5->connect_errno); }

    $stmt4 = $mysqli5->prepare("SELECT count(*) FROM TeamMember;");

    //Variables to put the result of the query into
    $numberOfMembers["total"];

    //Tells mysql server to execute prepared statement
    $stmt4->execute();

    //Variables to store properties in when fetch() is called
    $stmt4->bind_result($numberOfMembers["total"]);

    //Store the properties from the query in the variables bound above
    $stmt4->fetch();
    /*End get total number of members*/

    /*Get tasks a member is working on*/

    $mysqli6 = new mysqli($db_server, $db_user, $db_password, $db_database);
    if($mysqli6->connect_errno){ die("Could not connect to database: " . $mysqli6->connect_errno); }
    
    $stmtGetCurrentTasks = $mysqli6->prepare("SELECT distinct TeamMember.firstName, TeamMember.lastName, TeamMember.userID, TeamMember.taskId, Task.completed, Task.name FROM TeamMember, Task WHERE TeamMember.taskId = Task.taskId");

    $cmember["firstName"];
    $cmember["lastName"];
    $cmember["userID"];
    $cmember["taskId"];
    $cmember["completed"];
    $cmember["taskName"];

    $stmtGetCurrentTasks->execute();

    $stmtGetCurrentTasks->bind_result($cmember["firstName"], $cmember["lastName"], $cmember["userID"], $cmember["taskId"], $cmember["completed"], $cmember["taskName"]);

    $workingOnList = Array();

    //fetch - returns true if there is another row in the results
    while($stmtGetCurrentTasks->fetch()){
        $currentTask = Array(
            "firstName" => $cmember["firstName"],
            "lastName" => $cmember["lastName"],
            "userID" => $cmember["userID"],
            "taskId" => $cmember["taskId"],
            "completed" => $cmember["completed"],
            "taskName" => $cmember["taskName"]
        );
        array_push($workingOnList, $currentTask);
    }

    /*End get tasks a member is working on*/


  
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Projects</title>
  <?php include("templates/html_head.php"); ?>
  <style>
  .ui.attached.segment {
    min-height: 120px;
  }
  </style>
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

      var arrayOfData = JSON.parse( '<?php echo json_encode($taskInfo) ?>' );

      var arrayLength = arrayOfData.length;

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
          <i class="clipboard outline icon"></i>
          <div class="content">
            Project Management System
            <div class="sub header">Project Name: <?php echo($project["projectName"]); ?></div>
          </div>
        </h2>

        <a class="ui icon button header-action" data-tooltip="Edit project" href="editproject.php">
          <i class="edit outline icon"></i>
        </a>

        <h3 class="ui top attached header">
          Description
        </h3>
        <div class="ui attached segment">
          <p><?php echo($project["requirements"]);  ?></p>
        </div>

        <h3 class="ui top attached header">
          Overview
        </h3>
        <div class="ui attached segment">
          <div id="chart_div"></div>
        </div>

        <h3 class="ui top attached header">
          Project Statistics
        </h3>
        <div class="ui attached segment">
          <table class="ui fixed definition table">
            <tr>
              <td>Deadline</td>
              <td><?php echo($project["deadline"]); ?></td>
            </tr>
            <tr>
              <td>Status</td>
              <td>
              <?php
              if ($finalDeadline > $project["deadline"]) {
                echo("Overdue. Estimated completion date is currently: " . $finalDeadline);
              } else {
                echo("Ontime");
              }
              ?>
              </td>
            </tr>
            <tr>
              <td>Total Members</td>
              <td><?php echo($numberOfMembers["total"]); ?></td>
            </tr>
            <tr>
              <td>Percentage completed</td>
              <td><?php echo($projectCompletePercentage . "%"); ?></td>
            </tr>
          </table>
        </div>

        <h3 class="ui top attached header">
          Current Tasks
        </h3>
        <div class="ui attached segment">
        <?php
          if (count($workingOnList) == 0) {
            echo("<p>No tasks currently assigned. </p>");
          } else { ?>
            <table class="ui very basic table">
            <?php foreach ($workingOnList as $currentTasks) {
              if ($currentTasks["completed"] == 0) { ?>
                <tr>
                  <td>
                    <a href="memberdetails.php?id=<?php echo($currentTasks["userID"]) ?>">
                      <strong><?php echo($currentTasks["firstName"] . " " . $currentTasks["lastName"]) ?></strong>
                    </a>
                  </td>
                  <td>
                    <a href="taskdetails.php?id=<?php echo($currentTasks["taskId"]) ?>">
                      <?php echo($currentTasks["taskName"]) ?>
                    </a>
                  </td>
                </tr> <?php
              } else { ?>
                <tr>
                  <td>
                    <a href="memberdetails.php?id=<?php echo($currentTasks["userID"]) ?>">
                      <strong><?php echo($currentTasks["firstName"] . " " . $currentTasks["lastName"]) ?></strong>
                    </a>
                  </td>
                  <td>
                    <a href="taskdetails.php?id=<?php echo($currentTasks["completed"]) ?>">
                      <?php echo($currentTasks["taskName"]) ?>&nbsp;&nbsp;&nbsp;
                      <div class="ui tiny label">Completed</div>
                    </a>
                  </td>
                </tr> <?php
              }
            }
          }
        ?>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
