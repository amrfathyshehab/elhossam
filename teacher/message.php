<?php

session_start();

if (isset($_SESSION['id'])) {
    include('include/header.php');
    include('include/config.php');
    include('include/navbar.php');




    $do = '';


    if (isset($_GET['do'])) {

        $do = $_GET['do'];
    } else {

        $do = 'message';
    }






    if ($do == 'message') {

?>
        <style>
            .back1 {

                background-color: #FF5722;
                text-align: center;
            }

            .back2 {
                background-color: #FF9800;
                text-align: center;
            }

            .back3 {
                background-color: #FFC107;
                text-align: center;
            }

            h3 {

                line-height: 150px;

            }

            .title {

                background-color: black;
                color: white;
                text-align: center;
                padding-top: 10px;
                padding-bottom: 10px;

            }


            .a {
                text-decoration: none;
                color: black;
            }

            .a:hover {
                text-decoration: none;
                color: black;
            }
        </style>


        <center>
            <h1>Message</h1>
        </center>
        <br><br><br>
        <div class="container">
            <div class='row'>

                <div class="col-lg-4 ">
                    <a class="a" href="?do=view&stage=1">
                        <div class='back1'>
                            <div class='title'> 1 secondary</div>
                            <h3>

                                <?php

                                $stmt = $conn->prepare('select student.* , ask.* from student INNER JOIN ask
                                                           on student.student_id=ask.student_id
                                                           where ask.read = 0 AND student.stage = 1');
                                $stmt->execute();
                                $row = $stmt->rowCount();

                                echo $row;
                                ?>


                            </h3>
                        </div>
                    </a>
                </div>


                <div class="col-lg-4 ">
                    <a class="a" href="?do=view&stage=2">
                        <div class='back2'>
                            <div class='title'> 2 secondary</div>
                            <h3>

                                <?php

                                $stmt = $conn->prepare('select student.* , ask.* from student INNER JOIN ask
                                                           on student.student_id=ask.student_id
                                                           where ask.read = 0 AND student.stage = 2');
                                $stmt->execute();
                                $row = $stmt->rowCount();

                                echo $row;
                                ?>


                            </h3>
                        </div>
                    </a>
                </div>

                <div class="col-lg-4 ">
                    <a class="a" href="?do=view&stage=3">
                        <div class='back3'>
                            <div class='title'> 3 secondary</div>
                            <h3>

                                <?php

                                $stmt = $conn->prepare('select student.* , ask.* from student INNER JOIN ask
                                                           on student.student_id=ask.student_id
                                                           where ask.read = 0 AND student.stage = 3');
                                $stmt->execute();
                                $row = $stmt->rowCount();

                                echo $row;
                                ?>


                            </h3>
                        </div>
                    </a>
                </div>

            </div>

        </div>
    <?php
    }



    if ($do == 'view') {
    ?>

        <div class="container">
            <br><br>
            <?php
            $stage = $_GET['stage'];
            // get all student and teacher info from db who are messageing to each other in this session
            $stmt = $conn->prepare(' select student.* , ask.* from student INNER JOIN ask
                            on student.student_id=ask.student_id
                            where ask.read = 0 AND student.stage = ? ');
            $stmt->execute(array($stage));

            $rows = $stmt->fetchAll();


            foreach ($rows as $row) {

            ?>


                <form method="post" action="?do=answer">
                    <div class="card">
                        <h5 class="card-header"><?php echo ucwords($row['student_name']); ?></h5>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo ucfirst($row['ask']) . '?'; ?></h5>
                            <p>Answer : </p><textarea name="answer" class="col-lg-12"></textarea>
                            <input name='ask_id' value="<?php echo $row['ask_id'] ?>" hidden>
                            <br><br><button type="submit" style="float: right" class="btn btn-primary">submit</button>
                        </div>
                    </div>
                    <br>
                    <br>
                </form>
            <?php
            }
            echo '</div>';
        } elseif ($do == 'answer') {

            $answer = $_POST['answer'];
            $teacher_id = $_SESSION['id'];
            $ask_id = $_POST['ask_id'];
            $seen = 1;

            $stmt = $conn->prepare("INSERT INTO `teacher_answer` ( `answer`, `teacher_id`, `ask_id`, `seen`)
                             VALUES ( :answer, :teacher_id, :ask_id, :seen )");
            $stmt->execute(array("answer" => $answer, "teacher_id" => $teacher_id, "ask_id" => $ask_id, "seen" => $seen));

            $stmt = $conn->prepare("UPDATE `ask` SET `read` = '1' WHERE `ask`.`ask_id` = ?;");
            $stmt->execute(array($ask_id));
            ?>

            <div class="container" style="margin-top: 100px">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <center> message sent</center>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <center>
                    <p style="color: gray"> you will be redirected to message page after 3 seconds </p>
                </center>
            </div>



    <?php
            header("Refresh:5; url=message.php");
        } else {

            // header('location:dashboard');
        }









        include('include/footer.php');
    } else {


        header('location:login.php');
    }





    ?>