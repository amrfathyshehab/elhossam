<?php



session_start();
if (isset($_SESSION['student_id'])) {

    include('include/config.php');

    include('include/header.php');

    include('include/navbar.php');


    $do = '';


    if (isset($_GET['do'])) {

        $do = $_GET['do'];
    } else {


        $do = 'ask';
    }




    if ($do == 'ask') {

?>

        <div class="container">

            <center>

                <h2>Ask your teacher</h2>
                <h6 style="color: gray">Don't be afraid to ask</h6><br><br><br><br>
                <form action="?do=submit" method="post">
                    <div class="col-md-8">
                        <textarea class="col-md-8" style=" height: 100px" name="ask"></textarea><br><br>
                        <button type='submit' class="btn btn-primary col-md-8">submit</button>
                    </div>
                </form>
            </center>






        </div>


    <?php
    } elseif ($do = "submit") {

        $ask = $_POST['ask'];
        $id = $_SESSION['student_id'];

        $stmt = $conn->prepare(" INSERT INTO `ask` ( `ask`, `student_id`)
                                        VALUES (:ask, :student_id) ");


        $stmt->execute(array("ask" => $ask, "student_id" => $id));

    ?>

        <div class="container">
            <div class="container" style="margin-top: 50px">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <center>we will answer your question as soon as possible</center>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <center>
                <p style="color:gray">you will be redirected in 3 seconds </p>
            </center>
        </div>
<?php

        header('Refresh:3;url=dashboard.php');
    } else {
        header('location:dashboard.php');
    }








    include('include/footer.php');
} else {



    header('location:login.php');
}

?>