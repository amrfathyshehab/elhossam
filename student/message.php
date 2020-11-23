<?php

session_start();

if (isset($_SESSION['student_id'])) {
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

        <center>
            <h1>Message</h1>
        </center>
        <br><br><br>
        <div class="container">

            <?php

            $num = 1;
            //get all teachers messages directed to THIS student ..   
            $stmt = $conn->prepare(' select teacher_answer.* , ask.* from teacher_answer
                             
                             
                             
                             RIGHT JOIN ask
                            on teacher_answer.ask_id=ask.ask_id
                           
                           where ask.student_id=? ORDER BY `ask`.`ask_id` ASC ');
            $stmt->execute(array($_SESSION['student_id']));

            $rows = $stmt->fetchAll();


            foreach ($rows as $row) {
            ?>

                <form method="post" action="?do=answer">
                    <div class="card">
                        <h5 class="card-header"><?php echo $num++ . ' ) '; ?></h5>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo ucfirst($row['ask']) . '?'; ?></h5>
                            <B>teacher answer : </B>

                            <span> <?php echo $row['answer'] ?  ucfirst($row['answer']) :  "no answer yet"; ?></span><br>
                            <b><?php echo $row['answer'] ? "teacher :" : null;  ?></b>

                            <?php
                            $stmt = $conn->prepare(' select teacher.* from teacher where id=?');
                            $stmt->execute(array($row['teacher_id']));

                            $name = $stmt->fetch(); ?>
                            <span> <?php echo ucfirst($name['name']); ?></span>


                        </div>
                    </div>
                    <br>
                    <br>
                </form>





    <?php
            }
            echo  '</div>';
        } else {

            header('location:dashboard');
        }









        include('include/footer.php');
    } else {


        header('location:login.php');
    }





    ?>