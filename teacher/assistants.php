<?php


session_start();
include('include/config.php');


if (isset($_SESSION['id'])) {
?>




  <html>

  <head>
    <?php include('include/header.php'); ?>


  </head>

  <body>
    <?php include('include/navbar.php'); ?>


    <?php

    $do = '';


    if (isset($_GET['do'])) {

      $do = $_GET['do'];
    } else {
      $do = 'assistants';
    }

    if ($do == 'assistants') {

    ?>
      <center>
        <h2 style="line-height: 100px">Assistans</h2>
      </center>
      <div class="container">
        <a type="button" style="margin-bottom: 10px" class="btn btn-primary " href="?do=add">Add new assistant</a>
        <table class="table table-striped">

          <thead>
            <tr class="thead-dark">

              <th scope="col">Name</th>
              <th scope="col">Rank</th>
              <th scope="col">Control</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $stmt = $conn->prepare('SELECT * from teacher where id != ? AND id!=1 ORDER BY rank ASC');
            $stmt->execute(array($_SESSION['id']));
            $rows = $stmt->fetchAll();

            foreach ($rows as $row) {
            ?>
              <tr>

                <td> <?php echo ucWords($row['name'], " "); ?> </td>
                <td>
                  <?php if ($row['rank'] == 1) {
                    echo ' Teacher';
                  } elseif ($row['rank'] == 2) {
                    echo 'Team leader';
                  } else {
                    echo 'Assistant';
                  } ?>
                </td>
                <td><a type="button" class="btn btn-danger btn-sm" href="?do=delete&id=<?php echo $row['id'] ?>">Delete</a>
                  <a type="button" class="btn btn-primary btn-sm" href="?do=edit&id=<?php echo $row['id'] ?>">Edit</a></td>

              </tr>
            <?php



            }

            ?>
          </tbody>
        </table>

      </div>
    <?php



    } elseif ($do == "add") {
    ?>
      <div class="container col-md-6">
        <center>
          <h2 style="line-height: 80px;">New assistant</h2><br>
        </center>
        <form method="post" action="?do=insert">
          <div class="form-row">
            <div class="col-md-12 mb-3">
              <label for="validationDefault01">Name</label>
              <input type="text" class="form-control" id="validationDefault01" name="name" required>
            </div>
            <div class="col-md-12 mb-3">
              <label for="validationDefault02">username</label>
              <input type="text" class="form-control" id="validationDefault02" name="username" required>
            </div>
          </div>
          <div class="form-row">
            <div class="col-md-12 mb-3">
              <label for="validationDefault03">password</label>
              <input type="text" class="form-control" name="pass" id="validationDefault03" required>

              <br>
              <label for="validationDefault02">Rank</label>
              <select class="form-control " name="rank" required>
                <option selected disabled>please choose assistant rank</option>
                <option value="2">Team leader</option>
                <option value="3">Assistant</option>

              </select>

            </div>


          </div>
          <br>
          <button class="btn btn-primary btn-" type="submit">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus text-light" style="color:white" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path style="color:white" fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
            </svg> Add </button>
        </form>
      </div>

      <?php
    } elseif ($do == 'insert') {

      $name     =    $_POST['name'];
      $username =    $_POST['username'];
      $password =    $_POST['pass'];

      $rank     =     empty($_POST['rank']) ?  '' :  $_POST['rank'];




      $stmt = $conn->prepare('SELECT * from teacher where username=?');
      $stmt->execute(array($username));
      $count = $stmt->rowCount();

      if ($count == 0) {
        $errs = array();

        if (strlen($password) < 8) {

          $errs[] = 'your password can\'t be less than 8 characters';
        }
        if (empty($rank)) {

          $errs[] = 'you have to choose rank';
        }

        foreach ($errs as $err) {


      ?>
          <div class="container" style="margin-top: 50px">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <center> <?php echo $err ?> </center>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

          </div>

          <?php

        }
        echo "<center>you will be redirected in 5 seconds</center>";
        header("Refresh:5; url=assistants.php?do=add");
        if (empty($errs)) {


          $id = $_SESSION['id'];


          $stmt = $conn->prepare('SELECT rank FROM `teacher` WHERE `teacher`.`id` = ?');


          $stmt->execute(array($id));

          $ranks = $stmt->fetch();


          if ($ranks['rank'] == 1 or $ranks['rank'] == 2) {

            $stmt = $conn->prepare('INSERT INTO `teacher` (`name`,`username`, `password`, `rank`)
                                                  
                                   VALUES(
                                
                                      :name,  :user, :pass, :rank
                                        
                                        )');


            $stmt->execute(array('name' => $name,  'user' => $username, 'pass' => $password, 'rank' => $rank));



          ?>
            <div class="container" style="margin-top: 50px">
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <center><?php


                        echo $_POST['rank'] == 3 ?   " new assistant added successfuly" :   " new leadr added successfuly"; ?></center>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

            </div>
            <center>
              <p style="color:gray;margin-top: 50px">you will be redirected in 5 seconds </p>
            </center>

            <?php



            ?>

          <?php
          } else {



          ?>
            <div class="container" style="margin-top: 50px">
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <center>you have no permission to add new account</center>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

            </div>
        <?php
          }


          header("Refresh:5; url=assistants.php?do=add");
        }
      } else {

        ?>

        <div class="container" style="margin-top: 100px">
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <center> This username is already taken by an account </center>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <center>
              <p style="color:gray">you will be redirected in 5 seconds </p>
            </center>
          </div>



        </div>


      <?php

        header("Refresh:5; url=assistants.php?do=add");
      }
    } elseif ($do == 'delete') {



      $id = $_SESSION['id'];


      $stmt = $conn->prepare('SELECT rank FROM `teacher` WHERE `teacher`.`id` = ?');


      $stmt->execute(array($id));

      $rank = $stmt->fetch();


      if ($rank['rank'] == 1 or $rank['rank'] == 2) {
        $stmt = $conn->prepare('DELETE FROM `teacher` WHERE `teacher`.`id` = ?');


        $stmt->execute(array($_GET['id']));

      ?>
        <div class="container" style="margin-top: 50px">
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <center>Account deleted successfully</center>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

        </div>

      <?php

        header("Refresh:3; url=assistants.php");
      } else {

      ?>


        <div class="container" style="margin-top: 50px">
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <center>you have no permission to delete this account</center>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

        </div>

      <?php
      }
      ?>





    <?php

    } elseif ($do == 'edit') {

    ?>

      <div class="container col-md-6">
        <center>
          <h2 style="line-height: 80px;">Edit Assistant </h2>


          <?php
          $stmt = $conn->prepare('select name from teacher where id=?');
          $stmt->execute(array($_GET['id']));
          $rows = $stmt->fetch();
          echo  '<h5 style="color:gray">' . ucWords($rows['name']) . '</h5>';
          ?>




          <br>
        </center>
        <form method="post" action="?do=update">
          <div class="form-row">
            <div class="col-md-12 mb-3">
              <input value="<?php echo $_GET['id'] ?>" name="id" hidden>
              <br>
              <label for="validationDefault02">Rank</label>
              <select class="form-control " name="rank" required>
                <option selected disabled>please choose assistant rank</option>
                <option value="2">Team leader</option>
                <option value="3">Assistant</option>

              </select>
              <button type=" submit" style="margin-top: 10px" class="btn btn-primary ">save changes</button>
            </div>

            <?php
          } elseif ($do == "update") {


            $rank = empty($_POST['rank']) ?  '' :  $_POST['rank'];
            $id = $_POST['id'];
            if (!empty($rank) and !empty($id)) {

              $stmt = $conn->prepare('UPDATE `teacher` SET rank=? where id=?');
              $stmt->execute(array($rank, $id));
            ?>

              <div class="container" style="margin-top: 50px">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <center>
                    <?php

                    $stmt = $conn->prepare(' select name from teacher where id=?');
                    $stmt->execute(array($id));
                    $row = $stmt->fetch();

                    $ran =  $rank == 3 ? " an assistant "  : " a team leadr";
                    echo  $row['name'] . ' is currently' . $ran;
                    header('Refresh:3 ; url = assistants.php');

                    ?>

                  </center>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <center>you will be redirected after 3 sec</center>

              </div>
            <?php
            } else {

            ?>


              <div class="container" style="margin-top: 50px">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <center>you have to choose rank to complete this process</center>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

              </div>

          <?php
              header('Refresh:3 ; url=assistants.php');
              // header("Refresh:5;  url=assistants.php?do=add");
            }
          } else {

            header('location:dashboard.php');
          }
          ?>


          <?php include('include/footer.php') ?>
  </body>

  </html>


<?php



} else {

  header('location:login.php');
}

?>