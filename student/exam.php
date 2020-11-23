<style>

ul {
  list-style-type: none;
}


.containerr {
  position: relative;
  text-align: center;
  color: white;
}


/* Top left text */
.top-left {
  position: absolute;
  top: 8px;
  left: 16px;
}
.row li {
  width: 33.3%;
  float: left;
}

img {
  border: 0 none;
  display: inline-block;
  height: auto;
  max-width: 70%;
  vertical-align: middle;
}

</style>

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


    $do = 'exam';
  }





  if ($do == 'exam') {

    echo '<center><h1>Exam</h1></center><br>';



?>



    <?php
    $center_id = $_SESSION['center'];
    //what does v mean ? 

    $stmt = $conn->prepare('SELECT is_online FROM student where student_id=?');
    $stmt->execute(array($_SESSION['student_id']));
    $row = (int) $stmt->fetch()['is_online'];

    ///////////////// THIS GLOBAL VARIABLE IS THE RULER
    $rows = null;
    if ($row) {
      $stmt = $conn->prepare('SELECT exams_taken , stage from student  where student_id =?  ');
      $stmt->execute(array($_SESSION['student_id']));
      $row = $stmt->fetch();

      $taken_exam_index = (int)$row['exams_taken'];
      $stage =  $row['stage'];

      $stmt = $conn->prepare('SET GLOBAL event_scheduler = ON;');
      $stmt->execute();

      // $stmt = $conn->prepare('CREATE EVENT IF NOT EXISTS `add_student_quiz` 
      // ON SCHEDULE EVERY 5 second
      // STARTS CURRENT_TIMESTAMP
      // ON COMPLETION PRESERVE 

      // DO 
      // BEGIN
      // SELECT * FROM exam WHERE id > ? ORDER BY id LIMIT 1;
      // UPDATE `student` SET `exams_taken`=exams_taken+1  WHERE `student_id`=?;
      // UPDATE `lesson` SET `lessons_taken`=lessons_taken+1  WHERE `year`=?;

      // END');
      // $stmt->execute(array($taken_exam_index, $_SESSION['student_id'], $year));


      $stmt = $conn->prepare('SELECT exams_taken from student  where  student_id =?  ');
      $stmt->execute(array($_SESSION['student_id']));
      $taken_exam_index = (int)$stmt->fetch()['exams_taken'];

      $stmt = $conn->prepare('
       SELECT * from exam  where v=0 AND exam.group= :centerID LIMIT :examsCount ');


      $stmt->bindValue(':centerID', (int) $center_id, PDO::PARAM_INT);
      $stmt->bindValue(':examsCount', $taken_exam_index, PDO::PARAM_INT);
      $stmt->execute(); //taken_exam_index updates via the event..

      ////algo ends
      $rows = $stmt->fetchAll();
      $test = 5;
    } else {

       // quiz examined being removed from user exams list feature
      $stmt = $conn->prepare('SELECT exam_id from student_result WHERE  student_result.student_id = ?');
      $stmt->execute(array($_SESSION['student_id']));
      $examined_exams_ids = $stmt->fetchAll();
      $student_results = $stmt->rowCount();


      if ($student_results == 0 ) {
        // // old approach before adding // quiz examined being removed from user exams list feature
      // // teacher chooses freely what to add to the student ...
      //what does v mean ?
      $stmt = $conn->prepare('SELECT * from exam  where v=0 AND  exam.group=?  ');
      $stmt->execute(array($center_id));
      $rows = $stmt->fetchAll();

      }else {


      function mapToids($n)
      {
          return $n['exam_id'] ; 
      }
      
      $examined_exams_ids = array_map('mapToids', $examined_exams_ids);

      $in = join(',', array_fill(0, count($examined_exams_ids), '?'));
      $select = <<<SQL
      SELECT *
      FROM exam
      WHERE  v=0 AND  exam.id NOT IN ($in);
      SQL;
   
      $stmt = $conn->prepare($select);
      $stmt->execute($examined_exams_ids);
      $rows = $stmt->fetchAll();

      }

  
    }




    foreach ($rows as $row) {
      // getting the student result of exams given exam id 

      $stmt = $conn->prepare('SELECT * from student_result  where student_id=? AND  exam_id=?  ');
      $stmt->execute(array($_SESSION['student_id'], $row['id']));
      $find = $stmt->rowCount();


      // exam results undone
      if ($find > 0) {
      } else {
      }
    }

    ?>


    <div class="container">

      <table class="table table-striped">

        <thead>
          <tr class="thead-dark">

            <th scope="col">Exam</th>
            <th scope="col">Duration</th>
            <th scope="col">Best wishes</th>
          </tr>
        </thead>



        <?php foreach ($rows as $exam) : ?>
          <tbody>
            <tr>
              <td> <?php echo ucWords($exam['exam_name'], " "); ?> </td>
              <td>
                <?php echo $exam['exam_time']; ?> Minutes
              </td>
              <td>
                <form method="post" action="?do=take&id=<?php echo $exam['id'] ?>&exam_time=<?php echo $exam['exam_time'] ?>">
                  <input value="<?php echo $exam['id'] ?>" name="exam_id" hidden>
                  <button type="submit" class="btn btn-primary btn-sm">Take exam</button>
                </form>
              </td>
            </tr>
          </tbody>
        <?php endforeach; ?>
      </table>

    </div>




  <?php


  } elseif ($do == 'take') {

    
                ///////////////RADIO 
                $stmt = $conn->prepare('
                SELECT exam_name  FROM `exam` 
  
  
                where id=?
                
                
                
                ');
              $id = $_GET['id'];
              $stmt->execute(array($id));
              $show = $stmt->fetch();
  
  
                ?>
                <div class="container">
                <center>
                
                <h4 style="color:gray">Exam of : <?php echo $show['exam_name'] ?></h4>
                <br><br>
                </center>
              

                <form method="post" id='exam_form' action="?do=answer&id=<?php echo $id ?> ">
                <style>
                  .sticky {
                    position: -webkit-sticky;
                    position: sticky;
                    top: 0;
                    background-color: #cec4c43d;
                  }
                </style>
                <div class="sticky" class="container">
                  <div style="padding:30px">
                    <center> <b>You have:</b>
                      <span vall="<?php echo $_GET['exam_time']?>" id="min"></span> <b>Minutes</b>
                      <span id="remain"></span> <b>Seconds</b></center>
                  </div>
                </div>
                <br>
                <?php
        
                //for texts
                    $stmt = $conn->prepare('
                    SELECT * FROM `question` 


                    where exam_id =? order by rand()
                    
                    
                    
                    ');
                $id = $_GET['id'];
                $stmt->execute(array($id));
                $show = $stmt->fetchAll();
                
        
        
        
                $num = 1;
                foreach ($show as $sho) {
                  echo '<h4> ' . $num++ . ' ) ' . $sho['question'] . ' ?</h4><br>';
        
        
        
                  $one =
                    // '<input type="hidden" name="' . $sho['q_id'] . '" value="no" />
                    '<input type="radio"  name="' . $sho['q_id'] . '" value="' . $sho['answer'] . '">
            <lable for="' . $sho['answer'] . '" >' . $sho['answer'] . '</lable><br>';
        
        
        
                  $two =
                    '<input type="radio"  name="' . $sho['q_id'] . '" value="' . $sho['a1'] . '">
              <lable for="' . $sho['a1'] . '" >' . $sho['a1'] . '</lable><br>';
        
        
        
                  $three =
                    '<input type="radio"  name="' . $sho['q_id'] . '" value="' . $sho['a2'] . '">
                 <lable for="' . $sho['a2'] . '" >' . $sho['a2'] . '</lable><br>';
        
                  $four =
                    '<input type="radio"  name="' . $sho['q_id'] . '" value="' . $sho['a3'] . '">
                   <lable for="' . $sho['a3'] . '" >' . $sho['a3'] . '</lable><br>';
        
        
                  $my_array = array($one, $two, $three, $four);
        
                  shuffle($my_array);
                  echo $strs =  implode(" ", $my_array);
        
        
        
        
                ?>
        
                  <br><br>
                <?php
        
        
                }
                echo '<br><br><br>';

                // FOR IMAGES 


// for images i will select all question_ids first then loop over each to display its respective images 

$stmt = $conn->prepare('
SELECT distinct q_id , question FROM exam_images 

where exam_id =? order by rand()



');
// $id = $_GET['id'];
$stmt->execute(array($id));
$show = $stmt->fetchAll();




foreach ($show as $sho) {

  echo '<h4> ' . $num++ . ' ) ' . $sho['question'] . ' ?</h4><br>';

// echo '<h4 style="display:inline">' . $sho['question'] . ' ? </h4><br><br>';

$stmt = $conn->prepare('
SELECT * FROM exam_images 

where q_id=? order by rand()


');
// $id = $_GET['id'];
$stmt->execute(array($sho['q_id']));
$q_records = $stmt->fetchAll();

?>
<div class="row">
<div id="small-img" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 center">
 <ul>

 <?php 


foreach ($q_records as $sho2) {




// echo '<h4 style="display:inline">' . $sho['question'] . ' ? </h4><a  type="submit"  href="?do=delete_quiz_q&q_id=' . $sho['q_id'] . '"  class="btn btn-danger btn-sm">delete</a> <br><br>';
// $one = "<img src="."../uploads/" . $sho['file_name'] ." style='width : 30%'". "><br>";
// $two = "<img src="."../uploads/" . $sho['file_name'] ." style='width : 30%'". "><br>";
// $three = "<img src="."../uploads/" . $sho['file_name'] ." style='width : 30%'". "><br>";
// $four = "<img src="."../uploads/" . $sho['file_name'] ." style='width : 30%'". "><br>";

//echo $one ; 
?>
<!-- <div class="row">
<div id="small-img" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 center">
<ul> -->
<li>


  <input type="radio"  name="<?php echo $sho2['q_id'] ?>" value="<?php echo $sho2['answer'] ?>">
  
  <img data-name="<?php echo $sho2['id'] ?>" src="../uploads/exams/<?php echo $sho2['file_name']?>" class="img-responsive inline-block" alt="Responsive image" />
</li>
<!-- <li>
 <img src="../uploads/<?php echo $sho2['file_name']?>" class="img-responsive inline-block" alt="Responsive image" />
</li> -->

<!-- adding more choice is front-end dependant -->
<!-- <li>
 <img src="../uploads/<?php echo $sho2['file_name']?>" class="img-responsive inline-block" alt="Responsive image" />
</li> -->



<?php

// $two = "<span  >" . $sho['a1'] . "</span><br>";
// $three = "<span  >" . $sho['a2'] . "</span><br>";
// $four = "<span  >" . $sho['a3'] . "</span><br>";

// $my_array = array($one, $two, $three, $four);
// echo $strs =  implode(" ", $my_array);





}

?>

</ul>
</div>
</div>
 


<?php 


}




                // FOR IMAGES 









                ?>

                <!-- <input name="center" value="<?php echo $exam; ?>" hidden> -->
 <button style="margin-top:2rem" type="submit"  class="btn btn-primary">submit your Exam answer</button>
        
        </form>
        </div>
               
        
            <?php
            echo "</div>";

                /////////////////RADIO 
            

              // will get text-based questions first then image-based ones .... 

              // i have 2 question types : text-based , img-based 
            //   $stmt = $conn->prepare('
            //   SELECT name  FROM `whitelist` 


            //   where id=?
              
              
              
            //   ');
            // $id = $_GET['id'];
            // $stmt->execute(array($id));
            // $show = $stmt->fetch();


              ?>
              <div class="container">
              <center>
              
              <!-- <h4 style="color:gray">Quiz of : <?php echo $show['name'] ?></h4> -->
              <br><br>
              </center>
              <?php

//       //for texts
//     $stmt = $conn->prepare('
//     SELECT * FROM `custom_class_quiz` 


//     where custom_class_id=?
    
    
    
//     ');
// $id = $_GET['id'];
// $stmt->execute(array($id));
// $show = $stmt->fetchAll();


// foreach ($show as $sho) {

// echo '<h4 style="display:inline">' . $sho['question'] . ' ? </h4><br><br>';
// $one = "<span>" . $sho['answer'] . "</span><br>";
// $two = "<span  >" . $sho['a1'] . "</span><br>";
// $three = "<span  >" . $sho['a2'] . "</span><br>";
// $four = "<span  >" . $sho['a3'] . "</span><br>";

// $my_array = array($one, $two, $three, $four);
// echo $strs =  implode(" ", $my_array);

// }





  


    
  } elseif ($do == 'answer') {


    
          // // to hide take button as quiz is already been taken 
          // $stmt = $conn->prepare('UPDATE `whitelist` SET `quiz_done`=?  WHERE `id`=? ');
          //  $stmt->execute(array(
          //    1 , $_GET['id']
          //  ));



          $test = $_POST ; 
          $test2 = $_GET ; 

         // quiz grading system 

         $num = 0;
         $right = 0;
     
         foreach ($_POST as $key => $value) {
     
           $num++;
           "{$key} = {$value}\r\n";
     
     
           ///grading text-based questions first ///
           $stmt = $conn->prepare('UPDATE `question` SET `student_answer`=?  WHERE `q_id`=? ');
           $stmt->execute(array(
             $value, $key
           ));

            // will solve it now isa
          //  $stmt = $conn->prepare('UPDATE `quiz_images` SET `student_answer`=?  WHERE `q_id`=? ');
          //  $stmt->execute(array(
          //    $value, $key
          //  ));
     
     
           /// grading text-based questions
     
     
           $stmt = $conn->prepare('select answer from question where q_id= ?');
           $stmt->execute(array($key));
           $answer = $stmt->fetch();
     
     
           if ($value == $answer['answer']) {
     
     
             $right++;
           } else {
           }

            /// grading img-based questions by : 
            // i only care about if what is chosen is correct or not , i don't care what did he choose , so , next 3 lines are not important , NICE
          //  $stmt = $conn->prepare('select answer from quiz_images where q_id= ?');
          //  $stmt->execute(array($key));
          //  $answer = $stmt->fetchAll();
     
     
           if ($value == 1 ) { 
     
     
             $right++;
           } else {
           }



         }
         ?>
     
           <div class="container">
         <?php
     
          $result = 0 ;
          if(count($_POST) != 0){
            $result =  $right / $num; // polish

          }
         echo '<br>';
         $id = $_GET['id'];
         echo $percentage = floor($result * 100);
         echo ' % <br>';
         echo  $outof = $right . ' out of  ' . $num;
         echo '</div>';
         $date = date("d/m/Y");


         $stmt = $conn->prepare('INSERT INTO `student_result` (`percentage`, `outof`,`student_id`, `exam_id`)
                                VALUES (:percentage, :outof, :student_id , :exam_id)');
         $stmt->execute(array(
           'percentage' => $percentage, 'outof' => $outof, 'student_id' => $_SESSION['student_id'],
           'exam_id' => $id
         ));
         // now deleting the exam being examined from the studnet exams list ...
         //$stmt = $conn->prepare('DELETE FROM `exam` WHERE `exam`.`id` = ' . $_GET['id'] ');



    header('location:result.php');
  } else {


    header('location:dashboard.php');
  }











  include('include/footer.php');
} else {



  header('location:login.php');
}





    ?>








<script type="text/javascript">
      window.onload = counter;
      let spanEl ;
      let exam_time ;
       
      function counter() {
         spanEl = document.getElementById("min");
         exam_time = spanEl.getAttribute('vall');
       
        //console.log(exam_time) ; 

        //minutes = exam_time;
        seconds = 1;
        countDown();
      }
    
      function countDown() {
        document.getElementById("min").innerHTML = exam_time;
        document.getElementById("remain").innerHTML = seconds;
        setTimeout("countDown()", 1000);
        if (exam_time == 0 && seconds == 0) {

           
           document.getElementById("exam_form").submit();
        } else {
          seconds--;
          if (seconds == 0 && exam_time > 0) {
            exam_time--;
            seconds = 60;
          }
        }
      }
    </script>