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
         line-height: 125px;

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

     .aa:hover {

         color: lightblue;
     }

     .pdf-download:hover {
         color: blue;

     }
 </style>
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

            $do = 'lessons';
        }






        if ($do == 'lessons') {


    ?>

         <div class="container">
             <h1></h1>
             <?php

                $stmt = $conn->prepare('select stage , is_online from student where student_id =?');
                $stmt->execute(array($_SESSION['student_id']));
                $row = $stmt->fetch();

                $stage = $row['stage'];
                echo '<center><h3>All lessons for ' . $stage . ' secondary </h3></center><br><br>';

                // again the RULER variable
                $rows = null;
                if (!(int)$row['is_online']) {


                    $stmt = $conn->prepare('select * from lesson where stage =?');
                    $stmt->execute(array($stage));
                    $rows = $stmt->fetchAll();
                } else { // automatic case when is_online is true
                    $stmt = $conn->prepare('SELECT lessons_taken from lesson where stage =?  ');
                    $stmt->execute(array($stage));
                    $taken_lesson_index = (int)$stmt->fetch()['lessons_taken'];

                    $stmt = $conn->prepare('
                     SELECT * from lesson  where stage= :stage LIMIT :lessonsCount ');


                    $stmt->bindValue(':stage', (int) $stage, PDO::PARAM_INT);
                    $stmt->bindValue(':lessonsCount', $taken_lesson_index, PDO::PARAM_INT);
                    $stmt->execute(); //taken_lesson_index updates via the event..

                    ////algo ends
                    $rows = $stmt->fetchAll();
                }


                ?>

                <table class="table table-striped">

                <thead>
                  <tr class="thead-dark">

                    <!-- <th scope="col">id</th> -->
                    <th scope="col">lesson name </th>
                    
                    
                  </tr>
                </thead>

                    <?php




                foreach ($rows as $row) {

                ?>
                

                    <tbody>
                    <tr>
                    <!-- <td> <?php echo $row['lesson_name']; ?> </td> -->


                    <td> <a type="button" class="btn btn-primary btn-sm" href="?do=watch&id=<?php echo $row['lesson_id'] ?>"> <?php echo $row['lesson_name'] ?>  </a></td>
                    </tr>
                    </tbody>

                    <!-- // old  -->
                 <!-- <a class="aa" href="?do=watch&id=<?php echo $row['lesson_id'] ?>"> <?php echo $row['lesson_name'] ?> >> </a> -->
                 <!-- <hr>
                 <br> -->
             <?php
                }

                echo '</table>';    
                echo '</div>';
            } elseif ($do == "watch") {

                $id = $_GET['id'];
                $stmt = $conn->prepare('select * from lesson where lesson_id =?');
                $stmt->execute(array($id));
                $row = $stmt->fetch();


                ?>
             <div class="container">

                 <?php


                    ?>
                 <center>

                     <h5> you are watching</h5><span style="color:gray"> <?php echo $row['lesson_name'] ?></span><br>

                 </center>
                 <center>
                  <!-- here ahmed -->
                     <br>
                     
                     <video lesson_name="<?php echo $row['lesson_name']?>" stage="<?php echo $row['stage']?>" id="myPlayer" class="video-js vjs-big-play-centered" >
                        <source src="../<?php echo $row['video'] ?>"type="video/mp4">
                        
                        </video>
                     
                  
                     <?php
                        if (!empty($row['lesson_desc'])) {

                            echo '<br><br><h6>Description<h6>' . '<span style="color:gray">' . $row['lesson_desc'] . '</span><br><br><br><br>';
                        } ?>
                 </center>
                 <center>
                     <div class="container">
                         <a class="pdf-download" href=<?php echo "./../" . $row['pdf'] ?> download><?php echo $row['pdf'] ? "Download the pdf :" . $row['pdf'] : ""; ?></a>
                     </div>
                 </center>
                 <div style="height:20px;"> </div>
             </div>

     <?php



            } else {

                header('location:dashboard.php');
            }









            include('include/footer.php');
        } else {


            header('location:login.php');
        }





        ?>