<style>
  .nav-link,
  .navbar-brand {

    color: white;


  }


  .name:hover {

    color: white;


  }

  .dropdown-menu {
    right: 40px;
  }


  a:hover {

    color: white;


  }

  .is_online {
    -webkit-appearance: none;
    width: 15px;
    height: 15px;
    border-radius: 15px;
    /* top: -2px;
    left: -1px; */
    /* position: relative; */
    background-color: #29f229 !important;
    /* content: ''; */
    /* display: inline-block; */
    /* visibility: visible; */
    /* border: 2px solid white; */
  }
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="dashboard.php">El-Hossam</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="exam.php">Exam</a>

      </li>


      <li class="nav-item active">
        <a class="nav-link" href="result.php">Result</a>

      </li>

      <li class="nav-item active">
        <a class="nav-link" href="ask.php">Ask</a>

      </li>

      <li class="nav-item active">
        <a class="nav-link" href="message.php">message

          <?php $stmt = $conn->prepare('SELECT teacher_answer.answer, teacher_answer.seen ,teacher_answer.ask_id ,
                                     ask.ask_id , ask.student_id FROM `ask`
                                     INNER JOIN teacher_answer on teacher_answer.ask_id=ask.ask_id
                                     where teacher_answer.seen= 0
                                     ');
          $stmt->execute();
          $row = $stmt->rowCount();
          if ($row > 0) {


          ?>

            <span style="color:red"><?php echo $row ?></span>
          <?php

          } else {
          }

          ?>
        </a>

      </li>

      <li class="nav-item active">
        <a class="nav-link" href="lessons.php">lesson</a>

      </li>

      <li class="nav-item active">
        <a class="nav-link" href="custom_class.php">custom_class</a>

      </li>

    </ul>




    <div class="nav-item dropdown">

      <p class="nav-link dropdown-toggle" style="margin-right: 55px" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <input class="is_online" type="radio">
        <?php

        $stmt = $conn->prepare('SELECT * from student where student_id=?');
        $stmt->execute(array($_SESSION['student_id']));
        $row = $stmt->fetch();
        echo ucWords($row['username'], ' '); ?>
      </p>
      <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
        <a class="dropdown-item" href="dashboard.php?do=edit&id=<?php echo  $_SESSION['student_id'] ?>" style="color:black">Edit profile</a>
        <hr>
        <a class="dropdown-item" href="logout.php" style="color:red">logout</a>

      </div>
    </div>




  </div>
</nav>