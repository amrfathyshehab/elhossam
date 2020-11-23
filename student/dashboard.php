<?php

session_start();


if (isset($_SESSION['student_id'])) {


  include('include/config.php');


?>



  <html>

  <head>
    <?php include('include/header.php'); ?>
    <link rel="icon" type="image/png" href="images/icons/favicon.ico" />


  </head>

  <body>
    <?php include('include/navbar.php');



    $do = '';


    if (isset($_GET['do'])) {

      $do = $_GET['do'];
    } else {
      $do = 'dashboard';
    }





    if ($do == 'edit') {

      $stmt = $conn->prepare('SELECT * FROM student where student_id=?');
      $stmt->execute(array($_SESSION['student_id']));
      $row = $stmt->fetch();
    ?>
      <div class="container col-md-6">
        <center>
          <h2 style="line-height: 80px;">Edit profile</h2><br>
        </center>
        <form method="post" action="?do=update">

          <div class="form-row">
            <div class="col-md-12 mb-3">
              <label for="validationDefault03">password</label>
              <input type="text" class="form-control" placeholder="keep it blank if you don't want to change it " name="new_pass" id="validationDefault03">
              <input type="text" class="form-control" value="<?php echo $row['password'] ?>" name="old_pass" id="validationDefault03" hidden>
            </div>


          </div>

          <button class="btn btn-primary" type="submit"> update</button>
        </form>
      </div>
      <?php
    } elseif ($do == 'update') {

      $id        =      $_SESSION['student_id'];

      $old_pass  =      $_POST['old_pass'];
      $new_pass  =      $_POST['new_pass'];
      $password;

      if (empty($new_pass)) {
        $password  =  $old_pass;
      } else {
        $password  =  $new_pass;
      }


      if (strlen($password) < 8) {

      ?>

        <div class="container" style="margin-top: 100px">
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <center> password must be more than <strong>8 characters . </strong></center>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <center>
            <p style="color: gray"> you will be redirected to edit page after 5 seconds </p>
          </center>
        </div>

      <?php header("Refresh:5; url=?do=edit");
      } else {

        $stmt = $conn->prepare('UPDATE `student` SET  password=? WHERE `student_id` = ?;');
        $stmt->execute(array($password, $id));


      ?>

        <div class="container" style="margin-top: 100px">
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <center> successfully process</center>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <center>
            <p style="color: gray"> you will be redirected to dashboard page after 3 seconds </p>
          </center>
        </div>



      <?php
        header("Refresh:5; url=dashboard.php");
      }
    } else {
      // RegisterDeamonDB_periodic_event @ first exam/quiz db insert::

      $stmt = $conn->prepare('SELECT * from exam where exam.group =?  ');
      $stmt->execute(array($_SESSION['center']));

      $rows = $stmt->rowCount();

      if ($_SESSION['is_online'] && $rows > 0) {
        ////
        $stmt = $conn->prepare('SELECT exams_taken , stage from student  where student_id =?  ');
        $stmt->execute(array($_SESSION['student_id']));
        $row = $stmt->fetch();

        $taken_exam_index = (int)$row['exams_taken'];
        $stage =  $row['stage'];

        $stmt = $conn->prepare('SET GLOBAL event_scheduler = ON;');
        $stmt->execute();

        $stmt = $conn->prepare('CREATE EVENT IF NOT EXISTS `add_student_quiz` 
    ON SCHEDULE EVERY 5 second
    STARTS CURRENT_TIMESTAMP
    ON COMPLETION PRESERVE 
    
    DO 
    BEGIN
    SELECT * FROM exam WHERE id > ? ORDER BY id LIMIT 1;
    UPDATE `student` SET `exams_taken`=exams_taken+1  WHERE `student_id`=?;
    UPDATE `lesson` SET `lessons_taken`=lessons_taken+1  WHERE `stage`=?;
    
    END');
        $stmt->execute(array($taken_exam_index, $_SESSION['student_id'], $stage));
      }

      ?>

      <center>
        <h2 style="line-height: 100px">Dashboard</h2>
      </center>



    <?php  }
    include('include/footer.php'); ?>
  </body>

  </html>
<?php




} else {

  header('location:login.php');
}
