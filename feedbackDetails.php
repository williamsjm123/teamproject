<?php
    require("config.php");
    $mysqli = new mysqli($db_server, $db_user, $db_password, $db_database);
    if($mysqli->connect_errno){ die("Could not connect to database: " . $mysqli->connect_errno); }

    $taskID = $_GET["id"];

    $stmt = $mysqli->prepare("SELECT Feedback.idFeedback, Feedback.feedback, Feedback.taskId, Task.name FROM Feedback, Task WHERE Task.taskId=Feedback.taskId AND Task.taskId=" . $taskID);

    $feedback = array();

    while($row = $stmt->fetch_assoc()){
        $thisFeedback = array(
            "idFeedback" => $row["idFeedback"],
            "feedback" => $row["feedback"],
            "taskId" => $row["taskId"],
            "name" => $row["taskName"]
        );

        array_push($feedback, $thisFeedback);

    }

    ?>

    <!DOCTYPE html>
<html>
    <head>
        <title>Feedback</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>

        <?php include("templates/navbar.php"); ?>

        <div id="wrapper">
            
            <h1>Test</h1>

            <?php
                foreach($feedback as $comment){

                    ?>

                    <div class="task">
                        <h3><?php echo($comment["taskName"]); ?></h3>


                    </div>


                    <?php
                
                }


            ?>


            <hr>