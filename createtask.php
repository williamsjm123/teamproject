<!DOCTYPE html>
<html lang="en">
<head>
  <title>Create Task</title>
  <?php include("templates/html_head.php") ?>
</head>

<body>
  <div>
    <?php include("templates/navbar.php"); ?>

    <div class="ui two column centered grid">
      <div class="column">
        <h2 class="ui header">
          <i class="user plus icon"></i>
          <div class="content">
            Create a New Task
            <div class="sub header">
              Please fill in the following information to create a new task.
            </div>
          </div>
        </h2>

        <div class="ui center aligned segment">
          <form method="POST" action="createtask_result.php" class="ui form">
            <h4 class="ui dividing header">Create Task</h4>
            <div class="inline field">
              <label>Task Name</label>
              <input type="text" name="name" placeholder="Type Task Name"/>
            </div>

            <div class="inline field">
              <label>Task Description</label>
              <input type="text" name="description" placeholder="Type Task Description"/>
            </div>

            <div class="inline field">
              <label>Start Date</label>
              <input type="date" name="startDate" />
            </div>

            <div class="inline field">
              <label>Deadline</label>
              <input type="date" name="deadline" />
            </div>

            <button class="ui button" type="submit">Submit Task</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
