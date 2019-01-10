<?php
// TODO

  require("config.php");
  $mysqli = new mysqli($db_server, $db_user, $db_password, $db_database);
  if($mysqli->connect_errno){ die("Could not connect to database: " . $mysqli->connect_errno); }

  $result = $mysqli->query("SELECT * FROM Task ORDER BY startDate");

  $tasks = array();

  while($row = $result->fetch_assoc()) {
    $thisTask = array(
      "id" => $row["taskId"],
      "name" => $row["name"],
      "description" => $row["description"],
      "startDate" => $row["startDate"],
      "deadline" => $row["deadline"],
      "completed" => $row["completed"]
    );
    array_push($tasks, $thisTask);
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Tasks</title>
  <?php include("templates/html_head.php") ?>
  <style>
  .trash.icon {
    position: absolute;
    right: 0;
    top: 1rem;
    display: none;
    cursor: pointer;
  }

  .item:first-child .trash.icon {
    top: 0.2rem;
  }

  .ui.items > .item {
    position: relative;
  }

  .ui.items > .item:hover .trash.icon {
    display: block;
  }

  .ui.items >.item .extra {
    margin-top: 2rem;
  }
  </style>
</head>

<body>
  <div>
    <?php include("templates/navbar.php"); ?>

    <div class="ui two column centered grid">
      <div class="column">
        <h2 class="ui header">
          <i class="list ul icon"></i>
          <div class="content">
            Task List
            <div class="sub header">this page shows all tasks in the project</div>
          </div>
        </h2>

        <a class="ui icon button header-action" data-tooltip="Create new task" href="createtask.php">
          <i class="add icon"></i>
        </a>

        <div class="ui segment">
          <div class="ui divided items">
            <?php
              foreach($tasks as $task) {
            ?>
            <div class="item">
              <div class="content">
                <a class="header"><?php echo($task["name"]) ?></a>

                <div class="description">
                  <p><?php echo($task["description"]) ?></p>
                </div>

                <div class="extra">
                  <a href="taskdetails.php?id=<?php echo($task["id"]) ?>" class="ui right floated primary tiny button">
                    Details
                    <i class="right chevron icon"></i>
                  </a>

                  <div class="ui tiny <?php echo($task["completed"] ? "" : "red") ?> label">
                    <?php echo($task["completed"] ? "Completed" : "Incomplete") ?>
                  </div>
                </div>
              </div>

              <a href="taskdelete.php?id=<?php echo($task["id"]) ?>">
                <i class="alternate trash icon"></i>
              </a>
            </div>
            <?php
              }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
