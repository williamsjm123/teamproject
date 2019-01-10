<!DOCTYPE html>
<html lang="en">
<head>
  <title>Create Team Member</title>
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
            Create a New Team Member
            <div class="sub header">
              Please fill in the following information to create a new team member.
            </div>
          </div>
        </h2>

        <div class="ui center aligned segment">
          <form method="POST" action="createmember_result.php" class="ui form">
            <h4 class="ui dividing header">Create Team Member</h4>
            <div class="inline field">
              <label>First name</label>
              <input type="text" name="firstName" placeholder="Type first Name"/>
            </div>

            <div class="inline field">
              <label>Last name</label>
              <input type="text" name="lastName" placeholder="Type last Name"/>
            </div>

            <div class="inline field">
              <label>Gender</label>
              <input type="text" name="gender" placeholder="Type gender"/>
            </div>

            <div class="inline field">
              <label>Date of birth</label>
              <input type="date" name="dateOfBirth" placeholder="Type date of birth"/>
            </div>

            <div class="inline field">
              <label>Status</label>
              <input type="text" name="status" placeholder="Type status"/>
            </div>

            <div class="inline field">
              <label>User name</label>
              <input type="text" name="userName" placeholder="Type username"/>
            </div>

            <div class="inline field">
              <label>Password</label>
              <input type="password" name="userPassword" placeholder="Type password"/>
            </div>

            <button class="ui button" type="submit">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
