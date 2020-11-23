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
          <h2>Students</h2><br>
        </center>

        <a class="btn btn-primary" href="?do=add" style=" margin-bottom:  10px"> Add new student</a>

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
            <th scope="col">mobile</th>
            <th scope="col">parent_mobile_#1</th>
            <th scope="col">parent_mobile_#2</th>
            <th scope="col">group</th>
            <th scope="col">is_online</th>
            <th scope="col">Control</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $stmt = $conn->prepare('SELECT * FROM student  ');
          $stmt->execute();
          $rows = $stmt->fetchAll();

          foreach ($rows as $row) {
          ?>
            <tr>

              <td> <?php echo ucWords($row['student_name'], " "); ?> </td>

              <td> <?php echo ucWords($row['username'], " "); ?> </td>
              <td> <?php echo ucWords($row['stage'], " "); ?> </td>
              <td> <?php echo ucWords($row['school'], " "); ?> </td>
              <td> <?php echo ucWords($row['student_mobile'], " "); ?> </td>
              <td> <?php echo ucWords($row['primary_mobile'], " "); ?> </td>
              <td> <?php echo ucWords($row['secondary_mobile'], " "); ?> </td>
              <td> <?php echo ucWords($row['center'], " "); ?> </td>
              <td> <?php echo ucWords($row['is_online'], " "); ?> </td>
              <td><a type="button" class="btn btn-danger btn-sm" href="?do=delete&id=<?php echo $row['student_id'] ?>">Delete</a>
                <a type="button" class="btn btn-primary btn-sm" href="?do=edit&id=<?php echo $row['student_id'] ?>">Edit</a></td>

            </tr>
          <?php



          }

          ?>
        </tbody>
      </table>

    </div>



  <?php

  } elseif ($do == 'add') {

  ?>


    <div class="container">
      <center><br>
        <h2>Add new student</h2><br></cinter>
        <form method="post" action="students.php?do=insert">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="inputEmail4">Name</label>

              <input type="text" class="form-control" name="name" id="inputEmail4" placeholder="Full Name " required>
            </div>
            <div class="form-group col-md-2">
              <label for="inputPassword4">username</label>
              <input type="text" class="form-control" name="username" id="inputPassword4" placeholder="must be unique " required=>
            </div>

            <div class="form-group col-md-4">
              <label for="inputPassword4">password</label>
              <input type="text" class="form-control" name="password" id="inputPassword4" placeholder="" required>
            </div>


          </div>
          <div class="form-row ">

            <div class="form-group col-md-4">
              <label for="inputPassword4">Stage</label>
              <select class="form-control " name="stage" required>
                <option value="" selected disabled>please choose student stage </option>

                <option value="1">1 </option>
                <option value="2"> 2 </option>
                <option value="3"> 3 </option>
              </select>

            </div>
            <div class="form-group col-md-5">
              <label for="inputPassword4">School</label>
              <input type="text" class="form-control" name="school" id="inputSchool" placeholder="student school" required>

            </div>


            <!-- <div class="form-group col-md-3">
              <label for="inputPassword4">Year</label>
              <select class="form-control " name="year" required>
                <option value="" selected disabled> student year </option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>

              </select>
            </div> -->




            <div class="form-group col-md-4">
              <label for="inputPassword4">Student mobile number </label>
              <input type="text" class="form-control" name="snumber" id="inputPassword4" placeholder="required" required>
            </div>
            <div class="form-group col-md-4">
              <label for="inputPassword4">Primary mobile number</label>
              <input type="text" class="form-control" name="pnumber" id="inputPassword4" placeholder="required" required>
            </div>
            <div class="form-group col-md-4">
              <label for="inputPassword4">Secondary mobile number </label>
              <input type="text" class="form-control" name="senumber" id="inputPassword4" placeholder="">
            </div>
            <div class="form-group col-md-4">
              <label for="inputPassword4">group </label>
              <select class="form-control " name="center" required>
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




          <button type="submit" class="btn btn-primary" style="width: 100px;"> Add</button>
        </form>
    </div>

    <?php

  } elseif ($do == 'insert') {

    $name = $_POST['name'];

    $username = $_POST['username'];

    $password = $_POST['password'];

    $stage = $_POST['stage'];
    $school = $_POST['school'];

    // $year = $_POST['year'];

    // PLUS ADD is_online ...
    $stmt = $conn->prepare('SELECT id from center where time=?');
    $stmt->execute(array($_POST['center']));
    $rows = $stmt->fetchAll();
    foreach ($rows as $row) {
      $center = $row['id'];
    }

    $snumber = $_POST['snumber'];
    $pnumber = $_POST['pnumber'];
    $senumber = $_POST['senumber'];

    //if inserted student already exists in the db 
    $stmt = $conn->prepare('select username from student where username=?');
    $stmt->execute(array($username));
    $row = $stmt->rowCount();

    if ($row <= 0) {


      $stmt = $conn->prepare('INSERT INTO `student` (`student_name`,	`username`, 	`password`,	`stage`	,
      `school`,`student_mobile`,	`primary_mobile`,	`secondary_mobile`	,	`profile_pic`,`center`)
                                                  
                                   VALUES(
                                
                    :student_name,:username,:password,:stage,:school,:student_mobile,:primary_mobile,:secondary_mobile,:profile_pic,:center
                    -- ,:is_online
                                        
                                        )');


      $stmt->execute(array(
        'student_name' => $name, 'username' => $username, 'password' => $password, 'stage' => $stage, 'school' => $school,
        'student_mobile' => $snumber, 'primary_mobile' => $pnumber,
        'secondary_mobile' => $senumber, 'profile_pic' => '', 'center' => $center
        // , 'is_online' => $is_online
      ));
    ?>


      <div class="container" style="margin-top: 50px">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <center>New student added successfully</center>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <center> <br>

          <div class="alert alert-primary  alert-dismissible fade show" role="alert">

            <?php $stmt = $conn->prepare("SELECT student_id FROM `student` WHERE `student_name` = ? AND username =? ");
            $stmt->execute(array($name, $username));
            $row = $stmt->fetch();
            echo '<h2> student number is ' . $row['student_id'] . '</h2>';

            ?>
          </div>

        </center>
      </div>

    <?php

    } else {
    ?> <div class="container" style="margin-top: 50px">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <center>This username is already taken by student account</center>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <center> <br>
          <p style="color:gray"> you will be redirected in 3 seconds</p>
        </center>
      </div>
    <?php


      header('Refresh:3; url=students.php');
    }
  } elseif ($do == 'search') {



    $search = $_POST['search'];

    $stmt = $conn->prepare("SELECT * FROM `student` WHERE `student_name` LIKE '%" . $search . "%' OR student_id LIKE  '%" . $search . "%' ");
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row['student_name']) {
      //echo $row['student_name'];
    ?>

      <div style="margin-top: 50px;">
        <div class="container">
          <table class="table table-striped">

            <thead>
              <tr class="thead-dark">

                <th scope="col">name</th>
                <th scope="col">username</th>
                <th scope="col">stage</th>
                <th scope="col">school</th>
                <th scope="col">mobile</th>
                <th scope="col">parent_mobile_#1</th>
                <th scope="col">parent_mobile_#2</th>
                <th scope="col">group</th>
                <th scope="col">is_online</th>
                <th scope="col">Control</th>
              </tr>
            </thead>
            <tbody>

              <tr>

                <td> <?php echo ucWords($row['student_name'], " "); ?> </td>

                <td> <?php echo ucWords($row['username'], " "); ?> </td>
                <td> <?php echo ucWords($row['stage'], " "); ?> </td>
                <td> <?php echo ucWords($row['school'], " "); ?> </td>
                <td> <?php echo ucWords($row['student_mobile'], " "); ?> </td>
                <td> <?php echo ucWords($row['primary_mobile'], " "); ?> </td>
                <td> <?php echo ucWords($row['secondary_mobile'], " "); ?> </td>
                <td> <?php echo ucWords($row['center'], " "); ?> </td>
                <td> <?php echo ucWords($row['is_online'], " "); ?> </td>
                <td><a type="button" class="btn btn-danger btn-sm" href="?do=delete&id=<?php echo $row['student_id'] ?>">Delete</a>
                  <a type="button" class="btn btn-primary btn-sm" href="?do=edit&id=<?php echo $row['student_id'] ?>">Edit</a></td>

              </tr>
            </tbody>
          </table>
        </div>
      </div>

    <?php

    } elseif (!$row['student_name']) {
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


  } elseif ($do == 'update') {


    $id = $_GET['id'];
    $name = $_POST['name'];

    $username = $_POST['username'];

    $password = $_POST['password'];

    $stage = $_POST['stage'];
    $school = $_POST['school'];

    // $year = $_POST['year'];

    $snumber = $_POST['snumber'];
    $pnumber = $_POST['pnumber'];
    $senumber = $_POST['senumber'];

    $stmt = $conn->prepare('UPDATE `student` SET `student_name`=? ,`username`=?
    ,`password`=?
    ,`stage`=?  
    ,`school`=?  
    -- ,`year`=? 
    ,`student_mobile`=? 
    ,`primary_mobile`=? 
    ,`secondary_mobile`=?  WHERE `student_id`=? ');

    $stmt->execute(array($_POST['name'], $_POST['username'], $_POST['password'], $_POST['stage'], $_POST['school'], $_POST['snumber'], $_POST['pnumber'], $_POST['senumber'], $id));


  ?>

    <div class="container" style="margin-top: 50px">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <center> Edited successfully</center>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <center> <br>
        <p style="color:gray"> you will be redirected in 3 seconds</p>
      </center>
    </div>
  <?php

    header('Refresh:3; url=students.php');
  } elseif ($do == 'delete') {


    $stmt = $conn->prepare('delete from student where student_id=?');
    $stmt->execute(array($_GET['id']));
  ?>
    <div class="container" style="margin-top: 50px">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <center> group deleted successfully</center>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <center> <br>
        <p style="color:gray"> you will be redirected in 3 seconds</p>
      </center>
    </div>
<?php

    header('Refresh:3; url=students.php');
  } else {

    header('location:login.php');
  }
}

include('include/footer.php');

?>