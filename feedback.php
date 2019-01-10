<?php
    require("config.php");
    $mysqli = new mysqli($db_server, $db_user, $db_password, $db_database);
    if($mysqli->connect_errno){ die("Could not connect to database: " . $mysqli->connect_errno); }

    $result = $mysqli->query("SELECT * FROM Task ORDER BY startDate");

    $tasks = array();

    while($row = $result->fetch_assoc()){
        $thisTask = array(
            "id" => $row["taskId"],
            "name" => $row["name"],
            "description" => $row["description"],
            "startDate" => $row["startDate"],
            "deadline" => $row["deadline"],
            "completed" => $row["completed"],
            "feedback" => $row["feedback"]
        );

        array_push($tasks, $thisTask);

    }

?>


<!DOCTYPE html>
<html>
    <head>
        <title>Tasks Awaiting Feedback</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>

        <?php include("templates/navbar.php"); ?>

        <div id="wrapper">
            
            <h1>Feedback</h1>
            <p>This page shows all tasks which can be given feedback</p>

            <?php
                foreach($tasks as $task){
                    if($task["completed"] == 1){
                    ?>

                    <div class="task">
                        <h3><?php echo($task["name"]); ?> <?php echo( ($task["completed"] == 1 ? "(Completed)" : "(Incomplete)") ); ?></h3>
                        <p><?php echo($task["description"]); ?></p>
                        <a href="addfeedback.php?id=<?php echo($task["id"]); ?>">Give Feedback</a>


                    </div>


                    <?php
                }
                }


            ?>



        </div>


    </body>
</html>
