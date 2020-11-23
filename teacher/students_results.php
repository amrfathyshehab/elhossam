<?php
session_start();
include('include/config.php');



if (isset($_SESSION['id'])) {
  include('include/header.php');
  include('include/navbar.php');
  //this line solves Confirm Form Resubmission error 
  header("Cache-Control: no-cache");


  $do = '';


  if (isset($_GET['do'])) {


    $do = $_GET['do'];
  } else {


    $do = 'students';
  }






  if ($do == 'students') {
?>


    <div class="container">


      <div class="form-group ">
        <center><br>
          <h2>Students Results</h2><br>
        </center>

  

        <br>


        <form action="?do=search" method="post">
          <div class="input-group mb-2 mr-sm-2">

            <input type="text" name="search" class="form-control col-form-label-lg" id="inlineFormInputGroupUsername2" placeholder="search by student id or student name">
            <div class="input-group-prepend">
              <button type="submit" class="btn " style="border-bottom-style: dashed;padding-top: 0px;"> <img style="width:60px" src="images/search.jpg"> </button>
            </div>
          </div>
        </form>
      </div>

      <!-- 
      PLUS  -->


      <table class="table table-striped">

        <thead>
          <tr class="thead-dark">

            <th scope="col">name</th>
            <th scope="col">username</th>
            <th scope="col">stage</th>
            <th scope="col">school</th>
            <!-- <th scope="col">exam</th>
            <th scope="col">result</th>
            <th scope="col">result %</th> -->
            <th scope="col">Control</th>
          </tr>
        </thead>
        <tbody>
          <?php
          
          $stmt = $conn->prepare('SELECT DISTINCT student_result.student_id,student_name ,username,stage,school FROM student_result  JOIN student ON student_result.student_id = student.student_id ');
          $stmt->execute();


          $rows = $stmt->fetchAll();

          foreach ($rows as $row) {
          ?>
            <tr>

              <td> <?php echo ucWords($row['student_name'], " "); ?> </td>

              <td> <?php echo ucWords($row['username'], " "); ?> </td>
              <td> <?php echo ucWords($row['stage'], " "); ?> </td>
              <td> <?php echo ucWords($row['school'], " "); ?> </td>
             
              <td><a type="button" class="btn btn-primary btn-sm" href="?do=rview&id=<?php echo $row['student_id'] ?>&sname=<?php echo $row['student_name'] ?>">View Results</a>
              </td>

            </tr>
          <?php



          }

          ?>
        </tbody>
      </table>

    </div>



  <?php

  } 
  
  elseif ($do == 'rview') {
      // here 

      ?>
      <div class="container">


      <div class="form-group ">
        <center><br>
          <h2><?php echo strtoupper($_GET['sname']) ?> Results</h2><br>
        </center>

  

        <br>

      </div>

      <div class="form-group ">
        <center><br>
          <h4> Exams Results</h4><br>
        </center>

  

        <br>

      </div>

      <!-- 
      PLUS  -->

      <!-- first exams results  -->


      <table class="table table-striped">

        <thead>
          <tr class="thead-dark">

            <!-- <th scope="col">name</th>
            <th scope="col">username</th>
            <th scope="col">stage</th>
            <th scope="col">school</th> -->
            <th scope="col">exam</th>
            <th scope="col">result</th>
            <th scope="col">result %</th>
            <th scope="col">Control</th>
          </tr>
        </thead>
        <tbody>
          <?php
          
          $stmt = $conn->prepare('SELECT * FROM student_result  JOIN student ON student_result.student_id = student.student_id  JOIN exam on exam.id = student_result.exam_id ');
          $stmt->execute();

          


          $rows = $stmt->fetchAll();

          foreach ($rows as $row) {
          ?>
            <tr>

              
              <td> <?php echo ucWords($row['exam_name'], " "); ?> </td>
              <td> <?php echo ucWords($row['outof'], " "); ?> </td>
              <td> <?php echo ucWords($row['percentage'], " "); ?> </td>
              <td><a type="button" class="btn btn-danger btn-sm" href="?do=delete&id=<?php echo $row['student_id'] ?>&exam_id=<?php echo $row['exam_id'] ?>&sname=<?php echo $row['student_name'] ?>">Delete</a>
              </td>

            </tr>
          <?php



          }

          ?>
        </tbody>
      </table>

    </div>

    <!-- // second quiz results -->
    <div class="form-group ">
        <center><br>
          <h4> Quiz Results</h4><br>
        </center>

  

        <br>

      </div>


      <div class="container">
      <table class="table table-striped">

        <thead>
          <tr class="thead-dark">

            <!-- <th scope="col">name</th>
            <th scope="col">username</th>
            <th scope="col">stage</th>
            <th scope="col">school</th> -->
            <th scope="col">quiz</th>
            <th scope="col">result</th>
            <th scope="col">result %</th>
            <th scope="col">Control</th>
          </tr>
        </thead>
        <tbody>
         


  <?php



$stmt = $conn->prepare('SELECT * FROM quiz_result  JOIN student ON quiz_result.student_id = student.student_id  JOIN whitelist on whitelist.id = quiz_result.quiz_id ');
$stmt->execute();




$rows = $stmt->fetchAll();

foreach ($rows as $row) {
?>
  <tr>

    
    <td> <?php echo ucWords($row['name'], " "); ?> </td>
    <td> <?php echo ucWords($row['outof'], " "); ?> </td>
    <td> <?php echo ucWords($row['percentage'], " "); ?> </td>
    <td><a type="button" class="btn btn-danger btn-sm" href="?do=delete_quiz_results&id=<?php echo $row['student_id'] ?>&quiz_id=<?php echo $row['quiz_id'] ?>&sname=<?php echo $row['student_name'] ?>">Delete</a>
    </td>

  </tr>
<?php



}

?>
</tbody>
</table>

</div>


<?php 

  }
  
  
  
  
  elseif ($do == 'search') {



    $search = $_POST['search'];

    //exams
    $stmt = $conn->prepare("SELECT * FROM student_result  JOIN student ON student_result.student_id = student.student_id   JOIN exam ON exam.id = student_result.exam_id WHERE student.student_name LIKE '%" . $search . "%' OR student.student_id LIKE  '%" . $search . "%' ");
    $stmt->execute();
    $row = $stmt->fetch();
    $exam_count = $stmt->rowCount() ; 

    // quizs
    $stmt = $conn->prepare('SELECT * FROM quiz_result  JOIN student ON quiz_result.student_id = student.student_id  JOIN whitelist on whitelist.id = quiz_result.quiz_id ');
    $stmt->execute();

    $row2 = $stmt->fetch();
    $quiz_count = $stmt->rowCount() ; 


    if ($exam_count > 0 && $row['student_name']) {
      //echo $row['student_name'];
    ?>

      <div style="margin-top: 50px;">
        <div class="container">

         <h4> Exams Results </h4>
          <table class="table table-striped">

            <thead>
              <tr class="thead-dark">

               
            <th scope="col">name</th>
            <th scope="col">username</th>
            <th scope="col">stage</th>
            <th scope="col">school</th>
            <th scope="col">exam</th>
            <th scope="col">result</th>
            <th scope="col">result %</th>
            <th scope="col">Control</th>
              </tr>
            </thead>
            <tbody>

              <tr>

              <td> <?php echo ucWords($row['student_name'], " "); ?> </td>

              <td> <?php echo ucWords($row['username'], " "); ?> </td>
              <td> <?php echo ucWords($row['stage'], " "); ?> </td>
              <td> <?php echo ucWords($row['school'], " "); ?> </td>
              <td> <?php echo ucWords($row['exam_name'], " "); ?> </td>
              <td> <?php echo ucWords($row['outof'], " "); ?> </td>
              <td> <?php echo ucWords($row['percentage'], " "); ?> </td>
              <td><a type="button" class="btn btn-danger btn-sm" href="?do=delete&id=<?php echo $row['student_id'] ?>&exam_id=<?php echo $row['exam_id'] ?>">Delete</a>
                </td>

              </tr>
            </tbody>
          </table>
        </div>
      </div>

    <?php

    } if ($quiz_count > 0 && $row2['student_name']) {

      ?>

      <div style="margin-top: 50px;">
        <div class="container">

         <h4> Quiz Results </h4>
          <table class="table table-striped">

            <thead>
              <tr class="thead-dark">

               
            <th scope="col">name</th>
            <th scope="col">username</th>
            <th scope="col">stage</th>
            <th scope="col">school</th>
            <th scope="col">quiz</th>
            <th scope="col">result</th>
            <th scope="col">result %</th>
            <th scope="col">Control</th>
              </tr>
            </thead>
            <tbody>

              <tr>

              <td> <?php echo ucWords($row2['student_name'], " "); ?> </td>

              <td> <?php echo ucWords($row2['username'], " "); ?> </td>
              <td> <?php echo ucWords($row2['stage'], " "); ?> </td>
              <td> <?php echo ucWords($row2['school'], " "); ?> </td>
              <td> <?php echo ucWords($row2['quiz_id'], " "); ?> </td>
              <td> <?php echo ucWords($row2['outof'], " "); ?> </td>
              <td> <?php echo ucWords($row2['percentage'], " "); ?> </td>
              <td><a type="button" class="btn btn-danger btn-sm" href="?do=delete_quiz_results&id=<?php echo $row2['student_id'] ?>&quiz_id=<?php echo $row2['quiz_id'] ?>&sname=<?php echo $row2['student_name'] ?>">Delete</a>
                </td>

              </tr>
            </tbody>
          </table>
        </div>
      </div>

    <?php


    }
    
    
    
    elseif ( !$row && !$row2 ) {
      //PLUS
    ?>

      <center>
        <h1> No Match </h1>
      </center>
      <center> <br>
        <p style="color:gray"> you will be redirected in 3 seconds</p>
      </center>
    <?php

      header('Refresh:3; url=students.php');
    } else {
      header('url=students.php');
    }
  } elseif ($do == 'edit') {

    $stmt = $conn->prepare('SELECT * FROM student where student_id = ?');
    $stmt->execute(array($_GET['id']));
    $row = $stmt->fetch();
    ?>


    <div class="container">

      <center><br>
        <h2>Edit <?php echo $row['student_name'] ?> </h2><br>
      </center>

      <form method="post" action="?do=update&id=<?php echo $row['student_id'] ?>">
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputEmail4">Name</label>

            <input type="text" class="form-control" name="name" id="inputEmail4" placeholder="Full Name " required value=" <?php echo $row['student_name'] ?>">
          </div>
          <div class="form-group col-md-2">
            <label for="inputPassword4">username</label>
            <input type="text" class="form-control" name="username" id="inputPassword4" placeholder="must be unique " required value=" <?php echo $row['username'] ?>">
          </div>

          <div class="form-group col-md-4">
            <label for="inputPassword4">password</label>
            <input type="text" class="form-control" name="password" id="inputPassword4" placeholder="" required value=" <?php echo $row['password'] ?>">
          </div>


        </div>
        <div class="form-row ">

          <div class="form-group col-md-4">
            <label for="inputPassword4">Stage</label>
            <select class="form-control " name="stage" required value=" <?php echo $row['stage'] ?>">
              <option value="" selected disabled>please choose student stage </option>

              <option value="1"> 1 </option>
              <option value="2"> 2 </option>
              <option value="3"> 3 </option>
            </select>

          </div>
          <div class="form-group col-md-5">
            <label for="inputPassword4">School</label>
            <input type="text" class="form-control" name="school" id="inputSchool" placeholder="student school" required value=" <?php echo $row['school'] ?>">

          </div>


          <!-- <div class="form-group col-md-3">
            <label for="inputPassword4">Year</label>
            <select class="form-control " name="year" required value=" <?php echo $row['year'] ?>">
              <option value="" selected disabled> student year </option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>

            </select>
          </div> -->




          <div class="form-group col-md-4">
            <label for="inputPassword4">Student mobile number </label>
            <input type="text" class="form-control" name="snumber" id="inputPassword4" placeholder="required" required value=" <?php echo $row['student_mobile'] ?>">
          </div>
          <div class="form-group col-md-4">
            <label for="inputPassword4">Primary mobile number</label>
            <input type="text" class="form-control" name="pnumber" id="inputPassword4" placeholder="required" required value=" <?php echo $row['primary_mobile'] ?>">
          </div>
          <div class="form-group col-md-4">
            <label for="inputPassword4">Secondary mobile number </label>
            <input type="text" class="form-control" name="senumber" id="inputPassword4" placeholder="" value=" <?php echo $row['secondary_mobile'] ?>">
          </div>
          <div class="form-group col-md-4">
            <label for="inputPassword4">group </label>
            <select class="form-control " name="center" required value=" <?php echo $row['center'] ?>">
              <option value="" selected disabled> choose group </option>

              <?php
              $stmt = $conn->prepare('SELECT * FROM center INNER JOIN time ON center.time = time.id  ');
              $stmt->execute();
              $rows = $stmt->fetchAll();



              foreach ($rows as $row) {
              ?>
                <option value="<?php echo $row['id'] ?>"> <?php echo  $row['center_name'] . ' ' . $row['day'] . ' from ' . $row['from'] . ' to ' . $row['to'] ?> </option>

              <?php

              }
              ?>
            </select>
          </div>

        </div>




        <button type="submit" class="btn btn-primary" style="width: 100px;"> Update</button>
      </form>
    </div>
  <?php


  } elseif ($do == 'delete') {


    $stmt = $conn->prepare('delete from student_result where student_id=? AND exam_id=?');
    $stmt->execute(array($_GET['id'],$_GET['exam_id']));
  ?>
    <div class="container" style="margin-top: 50px">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <center> deleted successfully</center>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <center> <br>
        <p style="color:gray"> you will be redirected in 3 seconds</p>
      </center>
    </div>
<?php

    header('Refresh:3; url=students_results.php?do=rview&id='.$_GET['id'].'&sname='.$_GET['sname']);
  } 
  elseif ($do == 'delete_quiz_results') {


    $stmt = $conn->prepare('delete from quiz_result where student_id=? AND quiz_id=?');
    $stmt->execute(array($_GET['id'],$_GET['quiz_id']));
  ?>
    <div class="container" style="margin-top: 50px">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <center> deleted successfully</center>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <center> <br>
        <p style="color:gray"> you will be redirected in 3 seconds</p>
      </center>
    </div>
<?php

    header('Refresh:3; url=students_results.php?do=rview&id='.$_GET['id'].'&sname='.$_GET['sname']);
  }
  
  
  else {

    header('location:login.php');
  }
}

include('include/footer.php');

?>