<?php 
  ob_start();
?>

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

            $do = 'custom_class';
        }




        if ($do == 'custom_class') {
            ?>
            
            
                <div class="container">
            
            
                  <div class="form-group ">
                    <center><br>
                      <h2>Custom Classes</h2><br>
                    </center>

                    <center><br>
                      <h4>Available Classes</h4><br>
                    </center>

                    </div>
            
              
            
                    <br>
            
                  
            
            
                  <table class="table table-striped">
            
                    <thead>
                      <tr class="thead-dark">
            
                        <th scope="col">name</th>
                        <th scope="col">stage</th>
                        <th scope="col">issued by teacher</th>
                        <th scope="col">lessons</th>
                        <th scope="col">home work</th>
                        <th scope="col">quiz</th>
                        
                        
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      
                 
                      $row = $stmt->rowCount();

                      //deleting all expired students before showing the available custom classes 
                      
                      $stmt = $conn->prepare('DELETE FROM wl_subscribers WHERE expire < NOW()');

                      $stmt->execute();

                      // logic flow : first select all unfinished work custom_classes and only shows the first of them 
                      // then show all the done ones in the UI

                      $stmt = $conn->prepare('SELECT * FROM whitelist JOIN wl_subscribers ON whitelist.id = wl_subscribers.wl_id  WHERE  wl_subscribers.student_id = ? AND (wl_subscribers.quiz_done = 0 OR wl_subscribers.hw_done =0 ) order by wl_subscribers.id LIMIT 1 ');

                      $stmt->execute(array($_SESSION['student_id']));
            
                    //old
                      // $rows = $stmt->fetchAll();
                      // $records_count = $stmt->rowCount() ; 

            
                      // $index = 0 ; 
                      $row = $stmt->fetch();
                      $records_count = $stmt->rowCount() ; 
                      // foreach ($rows as $row) {
                      //     // logic for automatically unlock the next available custom_class once THIS class's HW is submitted

                           if($records_count > 0 ){
                        
                      //           ?>
                        <tr>
            
                          <td> <?php echo ucWords($row['name'], " "); ?> </td>
                          <td> <?php echo ucWords($row['stage'], " "); ?> </td>             
                          <td> <?php echo ucWords($row['createdBy'], " "); ?> </td>
                          <td>
              <a type="button" class="btn btn-primary btn-sm" href="?do=custom_lesson&id=<?php echo $row['wl_id'] ?>&name=<?php echo $row['name'] ?>">View</a>
              </td>
              <td>

              <?php if($row['hw_done']){

              ?>
              <a type="button" class="btn btn-primary btn-sm" href="?do=HW_RESULT&id=<?php echo $row['wl_id'] ?>&name=<?php echo $row['name'] ?>&hwID=<?php echo $row['HW_id'] ?>">Result</a>
              
              
              <?php
              }
              else{
                ?>
                <a type="button" class="btn btn-primary btn-sm" href="?do=custom_lesson_HW&id=<?php echo $row['wl_id'] ?>&name=<?php echo $row['name'] ?>&hwID=<?php echo $row['HW_id'] ?>">View</a>
                <?php
              } 
              ?>
              
              </td>

              <td>
              <?php if($row['quiz_done']){

                ?>
                <a type="button" class="btn btn-primary btn-sm" href="?do=quiz_result&id=<?php echo $row['wl_id'] ?>&name=<?php echo $row['name'] ?>&hwID=<?php echo $row['HW_id'] ?>">Result</a>
                <?php 

              }
              else {
                ?>
              <a type="button" class="btn btn-primary btn-sm" href="?do=custom_lesson_quiz&id=<?php echo $row['wl_id'] ?>&name=<?php echo $row['name'] ?>&hwID=<?php echo $row['HW_id'] ?>">Take</a>
              <?php 

              }
              ?>
              
              </td>
                        
            
                        </tr>
                      <?php


                              


                            }



                            ?>
                            </tbody>
                          </table>
                    
                        </div>
                    
                    
                        <br>
            
                  
                    <div class="container">

                    <div class="form-group ">
                

                    <center><br>
                      <h4>Finished Classes</h4><br>
                    </center>

                    </div>
            
              
            
                    <br>
            
                      <table class="table table-striped">
                
                        <thead>
                          <tr class="thead-dark">
                
                            <th scope="col">name</th>
                            <th scope="col">stage</th>
                            <th scope="col">issued by teacher</th>
                            <th scope="col">lessons</th>
                            <th scope="col">home work</th>
                            <th scope="col">quiz</th>
                            
                            
                          </tr>
                        </thead>
                        <tbody>

                        
                    
                      <?php



                          // now the done ones

                          $stmt = $conn->prepare('SELECT * FROM whitelist JOIN wl_subscribers ON whitelist.id = wl_subscribers.wl_id WHERE  wl_subscribers.student_id = ? AND (wl_subscribers.quiz_done = 1 AND wl_subscribers.hw_done = 1 )  ');

                          $stmt->execute(array($_SESSION['student_id']));
                          $rows = $stmt->fetchAll();

                            
                            
                            
                            foreach ($rows as $row) {
                                

                             //   if($row['quiz_done'] && $row['hw_done']  ){

                                    ?>
                                    <tr>
                        
                                      <td> <?php echo ucWords($row['name'], " "); ?> </td>
                                      <td> <?php echo ucWords($row['stage'], " "); ?> </td>             
                                      <td> <?php echo ucWords($row['createdBy'], " "); ?> </td>
                                      <td>
              <a type="button" class="btn btn-primary btn-sm" href="?do=custom_lesson&id=<?php echo $row['wl_id'] ?>&name=<?php echo $row['name'] ?>">View</a>
              </td>

                                      <td>
                                      <a type="button" class="btn btn-primary btn-sm" href="?do=HW_RESULT&id=<?php echo $row['wl_id'] ?>&name=<?php echo $row['name'] ?>&hwID=<?php echo $row['HW_id'] ?>">Result</a>
                          </td>
                          <td>
                          <a type="button" class="btn btn-primary btn-sm" href="?do=quiz_result&id=<?php echo $row['wl_id'] ?>&name=<?php echo $row['name'] ?>&hwID=<?php echo $row['HW_id'] ?>">Result</a>
                          </td>
            
                                    
                        
                                    </tr>
                                  <?php



                              //  }
                                // else {
                                //     //simply do nothing , it's not available just yet
                                // }



                            }
                            // $index += 1 ;  

                      ?>
                       </tbody>
                  </table>
            
                </div>



                        <!-- <tr>
            
                          <td> <?php echo ucWords($row['name'], " "); ?> </td>
                          <td> <?php echo ucWords($row['stage'], " "); ?> </td>             
                          <td> <?php echo ucWords($row['createdBy'], " "); ?> </td>
                          <td>
              <a type="button" class="btn btn-primary btn-sm" href="?do=custom_lesson&id=<?php echo $row['id'] ?>&name=<?php echo $row['name'] ?>">View</a>
              </td>
              <td>
              <a type="button" class="btn btn-primary btn-sm" href="?do=custom_lesson_HW&id=<?php echo $row['id'] ?>&name=<?php echo $row['name'] ?>&hwID=<?php echo $row['HW_id'] ?>">View</a>
              <a type="button" class="btn btn-primary btn-sm" href="?do=HW_RESULT&id=<?php echo $row['id'] ?>&name=<?php echo $row['name'] ?>&hwID=<?php echo $row['HW_id'] ?>">Result</a>
              </td>

                        
            
                        </tr> -->
                      <?php
            
            
            
                      //}
            
            
              }



              elseif($do == 'custom_lesson_quiz'){

                ///////////////RADIO 
                $stmt = $conn->prepare('
                SELECT name  FROM `whitelist` 
  
  
                where id=?
                
                
                
                ');
              $id = $_GET['id'];
              $stmt->execute(array($id));
              $show = $stmt->fetch();
              ?>
              <div class="container">
              <center>
              
              <h4 style="color:gray">Quiz of : <?php echo $show['name'] ?></h4>
              <br><br>
              </center>
              <?php 




                // polish // no text or img questions >> show nothing

                $counts = 0 ; 
                $id = $_GET['id'];

                

                  $stmt = $conn->prepare('
                  SELECT  COUNT(*) FROM quiz_images 

                  where quiz_images.wl_id=? 


                ');
                // $id = $_GET['id'];
                $stmt->execute(array($id));
                $show = $stmt->fetch();
                $counts += (int)$show[0] ; 

                $stmt = $conn->prepare('
                SELECT COUNT(*)  FROM `custom_class_quiz` 


                where custom_class_id=? 



                ');
               
                $stmt->execute(array($id));
                $show = $stmt->fetch();
                $counts += (int)$show[0] ; 

                if($counts ==0){


                  ?>
                  
                  <h4>* No quiz assigned to this class yet </h4> 
                  
                  <?php 


                }

                else {
?>
              

                <form method="post" id='quiz_form' action="?do=answer_quiz&id=<?php echo $id ?> ">
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
                      <span id="min"></span> <b>Minutes</b>
                      <span id="remain"></span> <b>Seconds</b></center>
                  </div>
                </div>
                <br>
                <?php
        
                //for texts
                    $stmt = $conn->prepare('
                    SELECT * FROM `custom_class_quiz` 


                    where custom_class_id=? order by rand()
                    
                    
                    
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
SELECT distinct q_id , question FROM quiz_images 

where quiz_images.wl_id=? order by rand()



');
// $id = $_GET['id'];
$stmt->execute(array($id));
$show = $stmt->fetchAll();




foreach ($show as $sho) {

  echo '<h4> ' . $num++ . ' ) ' . $sho['question'] . ' ?</h4><br>';

// echo '<h4 style="display:inline">' . $sho['question'] . ' ? </h4><br><br>';

$stmt = $conn->prepare('
SELECT * FROM quiz_images 

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



?>

<li>


  <input type="radio"  name="<?php echo $sho2['q_id'] ?>" value="<?php echo $sho2['answer'] ?>">
  
  <img data-name="<?php echo $sho2['id'] ?>" src="../uploads/quizs/<?php echo $sho2['file_name']?>" class="img-responsive inline-block" alt="Responsive image" />
</li>




<?php




}

?>

</ul>
</div>
</div>
 


<?php 


}




                // FOR IMAGES 









                ?>

              
 <button style="margin-top:2rem" type="submit" id='quiz_form' class="btn btn-primary">submit your Quiz answer</button>
        
        </form>
        </div>
               
        
            <?php
            echo "</div>";

                /////////////////RADIO 
            

              // will get text-based questions first then image-based ones .... 

              // i have 2 question types : text-based , img-based 
           


              ?>
              <div class="container">
              <center>
              
              
              <br><br>
              </center>
              <?php




}
              
              }

        elseif($do =='answer_quiz'){




          // to hide take button as quiz is already been taken 
          $stmt = $conn->prepare('UPDATE `wl_subscribers` SET `quiz_done`=?  WHERE `wl_id`=? ');
           $stmt->execute(array(
             1 , $_GET['id']
           ));



          $test = $_POST ; 
          $test2 = $_GET ; 

         // quiz grading system 

         $num = 0;
         $right = 0;
     
         foreach ($_POST as $key => $value) {
     
           $num++;
           "{$key} = {$value}\r\n";
     
     
           ///grading text-based questions first ///
           $stmt = $conn->prepare('UPDATE `custom_class_quiz` SET `student_answer`=?  WHERE `q_id`=? ');
           $stmt->execute(array(
             $value, $key
           ));

       
     
     
           /// grading text-based questions
     
     
           $stmt = $conn->prepare('select answer from custom_class_quiz where q_id= ?');
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


         $stmt = $conn->prepare('INSERT INTO `quiz_result` (`percentage`, `outof`,`student_id`, `quiz_id`)
                                VALUES (:percentage, :outof, :student_id , :quiz_id)');
         $stmt->execute(array(
           'percentage' => $percentage, 'outof' => $outof, 'student_id' => $_SESSION['student_id'],
           'quiz_id' => $id
         ));
         // now deleting the exam being examined from the studnet exams list ...
         //$stmt = $conn->prepare('DELETE FROM `exam` WHERE `exam`.`id` = ' . $_GET['id'] ');
     
     
     
         header('location:custom_class.php');
         ob_end_flush();

         // quiz grading system 
        } 
        
        elseif($do =='quiz_result'){

        ?>

           <div class="container">
      <center>
        <h4>Result & model answer</h4>
        <hr><br><br>
      </center>
      <table class="table table-striped">

        <thead>
          <tr class="thead-dark">

            <th scope="col">Quiz on Class </th>
            <th scope="col">Percentage </th>
            <th scope="col">Mark</th>
            <th scope="col">Model answer</th>
          </tr>
        </thead>
        <tbody>

          <?php
          //getting quiz results for THIS class 
          $stmt = $conn->prepare('select * from quiz_result where quiz_id=? ');
          $stmt->execute(array($_GET['id']));
          $rows = $stmt->fetchAll();



          foreach ($rows as $row) { ?>
            <tr>

              <td>
                <?php
                // manually fetching the exam_name from its id through the fetched student exams results
                $stmt = $conn->prepare('select name from whitelist where id=? ');
                $stmt->execute(array($row['quiz_id']));
                $name = $stmt->fetch();
                echo $name['name'];


                ?>
              </td>



              <td> <?php echo $row['percentage'] . ' %'; ?></td>
              <td> <?php echo $row['outof']; ?></td>
              <td>
                <a href="?do=quiz_model&id=<?php echo $row['quiz_id']; ?>" class="btn btn-primary btn-sm"> show model answer</a>

              </td>

            </tr>
          <?php
          } ?>
        </tbody>
      </table>




    </div>

  <?php

        }

        elseif($do =='quiz_model'){

         
    $stmt = $conn->prepare('
    SELECT name  FROM `whitelist` 


    where id=?
    
    
    
    ');
$id = $_GET['id'];
$stmt->execute(array($id));
$show = $stmt->fetch();




?>

<div class="container">
<center>
<h2>Quiz with model answer</h2><br>
<h4 style="color:gray">Quiz of : <?php echo $show['name'] ?></h4>
<br><br>
</center>
<?php

// i have 2 question types : text-based , img-based 



//for texts
$stmt = $conn->prepare('
    SELECT * FROM `custom_class_quiz` 


    where custom_class_id=?
    
    
    
    ');
$id = $_GET['id'];
$stmt->execute(array($id));
$show = $stmt->fetchAll();


foreach ($show as $sho) {

echo '<h4 style="display:inline">' . $sho['question'] . ' ? </h4> <br><br>';
$one = "<span style='color : red' >" . $sho['answer'] . "</span><br>";
$two = "<span  >" . $sho['a1'] . "</span><br>";
$three = "<span  >" . $sho['a2'] . "</span><br>";
$four = "<span  >" . $sho['a3'] . "</span><br>";

$my_array = array($one, $two, $three, $four);
echo $strs =  implode(" ", $my_array);

}


// for images i will select all question_ids first then loop over each to display its respective images 

$stmt = $conn->prepare('
SELECT distinct q_id , question FROM quiz_images 

where quiz_images.wl_id=?



');
// $id = $_GET['id'];
$stmt->execute(array($id));
$show = $stmt->fetchAll();




foreach ($show as $sho) {
echo '<h4 style="display:inline">' . $sho['question'] . ' ? </h4><br><br>';

$stmt = $conn->prepare('
SELECT * FROM quiz_images 

where q_id=?


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


if($sho2['answer']){
?>


<li>

 <div class="containerr">
 <img src="../uploads/quizs/<?php echo $sho2['file_name']?>" class="img-responsive inline-block" alt="Responsive image" />

   <div class="top-left"> <b style="color:green; font-size:1.5rem"> [The Answer] </b><br></div>
 </div>



</li>
<?php 



}

else {


?>

<li>
 <img src="../uploads/quizs/<?php echo $sho2['file_name']?>" class="img-responsive inline-block" alt="Responsive image" />
</li>




<?php

}



}

?>

</ul>
</div>
</div>

<?php 


}





?>



</div>

<?php
        }
        elseif ($do == 'custom_lesson') {


            //can only see if he finished the previous class HW


    ?>

         <div class="container">
             <h1></h1>
             <?php

                $wl_id = $_GET['id'];
                $wl_name = $_GET['name'];

                $stmt = $conn->prepare('SELECT lesson.*, whitelist.*  FROM `wl_lessons`  JOIN lesson ON wl_lessons.lesson_id = lesson.lesson_id   JOIN whitelist ON wl_lessons.wl_id = whitelist.id where wl_lessons.wl_id = ? ');
                $stmt->execute(array($wl_id));
                $row = $stmt->rowCount();
                $rows = $stmt->fetchAll();

         

                if ($row > 0){
                
                echo '<center><h3>All lessons for ' . $wl_name . ' </h3></center><br><br>';
                }
                else {
                    
                    echo '<center><h3>No lessons assigned for this class yet </h3></center><br><br>';
                }

                ?>

                <table class="table table-striped">

                <thead>
                  <tr class="thead-dark">

                   
                    <th scope="col">lesson name </th>
                    
                    
                  </tr>
                </thead>

                    <?php
            
                foreach ($rows as $row) { //compensates if ($row > 0) check 

                ?>

                  <tbody>
                <tr>
                 
                  
          
                  <td> <a type="button" class="btn btn-primary btn-sm" href="?do=watch&id=<?php echo $row['lesson_id'] ?>"> <?php echo $row['lesson_name'] ?>  </a></td>
                </tr>
              </tbody>

                 
             <?php
                }

                echo '</table>';

                echo '</div>';

                
               
            }
            
            
            elseif ($do == 'custom_lesson_HW') {

               

  ?>

  <center>
    <h3>Class <?php echo $_GET['name'] ?> Home Work</h3><br>
  </center>


  <div class="container">



    <form method="post" id='myForm' action="?do=answer_HW&wl_id=<?php echo $_GET['id']?>">
      
      <?php

      $hw_id = $_GET['hwID'];
      $class_id = $_GET['id'];
      // to associate exam_id to current user for quiz examined being removed from exams user list feature 
      //$_SESSION['exam_id'] = $_POST['exam_id'];
      //

      $stmt = $conn->prepare('SELECT * from custom_class_hw  JOIN lesson ON custom_class_hw.lesson_id = lesson.lesson_id
                            
                           
                           where custom_class_hw.custom_class_id=? order by rand() LIMIT 20
                           ');

      $stmt->execute(array($class_id));
      $show = $stmt->fetchAll();

      

      $num = 1;
      foreach ($show as $sho) {
        // echo '<h3> On lesson:"'. $sho['lesson_name'] . '"</h4><br>';
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
      ?>
     
<?php 
       if($stmt->rowCount()==0){


        ?>
        <h4>* No homework assigned to this class yet </h4> 
<?php 
             
              }

              else {
                  ?>

<button type="submit" id='myForm' class="btn btn-primary">submit your Home work</button>
<?php 

              }
              ?>
     

    </form>

  <?php
  echo "</div>";



            }


            elseif($do =='answer_HW'){
                $wl_id = $_GET['wl_id'];

              // to hide take button as quiz is already been taken 
              $stmt = $conn->prepare('UPDATE `wl_subscribers` SET `hw_done`=?  WHERE `wl_id`=? ');
              $stmt->execute(array(
                1 , $wl_id 
              ));



                $num = -1;
                $right = 0;
            
                foreach ($_POST as $key => $value) {
            
                  $num++;
                  "{$key} = {$value}\r\n";
            
            
                  ///nice feature added ///
                  $stmt = $conn->prepare('UPDATE `custom_class_hw` SET `student_answer`=?  WHERE `q_id`=? ');
                  $stmt->execute(array(
                    $value, $key
                  ));

                 
        
            
                  ///
            
            
                  $stmt = $conn->prepare('select answer from custom_class_hw where q_id= ?');
                  $stmt->execute(array($key));
                  $answer = $stmt->fetch();
            
            
                  if ($value == $answer['answer']) {
            
            
                    $right++;
                  } else {

                  }
                }


                ?>
            
            <!-- hw rating is not needed  -->

                  <div class="container">
                <?php
            
                // $result = ($num == 0) ? 0 : $right / $num;
                // echo '<br>';
                // $center = $_POST['center'];
                // echo $percentage = floor($result * 100);
                // echo ' % <br>';
                // echo  $outof = $right . ' out of  ' . $num;
                // echo '</div>';
                // $date = date("d/m/Y");
                $stmt = $conn->prepare('SELECT `lesson_id` FROM  custom_class_hw  WHERE `custom_class_id`=? ');
                $stmt->execute(array(
                  $wl_id
                ));

                $rows = $stmt->fetchAll();
                $whereIn = implode('","', $rows[0]);
            
           
                $stmt = $conn->prepare('UPDATE `custom_class_hw` SET `lesson_HW`=?  WHERE `lesson_id` IN

                                            ("'.$whereIn.'")
                
                
                ');
                $stmt->execute(array(
                  1 
                ));


               
            
                header('location:custom_class.php');

            }

            elseif($do == 'HW_RESULT'){

                $id = $_GET['id'];


                ?>
              
                  <div class="container">
                    <center>
                      <h2>Home Work with model answer</h2><br>
              
                      <br><br>
                    </center>
                    <?php
                    // UNDER TEST
                    // $stmt = $conn->prepare('select * from student_result where student_id=? ');
                    // $stmt->execute(array($_SESSION['student_id']));
                    // $student_results = $stmt->fetchAll();
                    ///
                    $stmt = $conn->prepare('
                                           SELECT * FROM `custom_class_hw` 
                                           where custom_class_id=?
                                           
                                           
                                           
                                           ');
                  
                    $stmt->execute(array($id));
                    $show = $stmt->fetchAll();
              
              
                    # number of question options is hard-coded with 4 options ...
                    foreach ($show as $sho) {
              
                      echo '<h4 style="display:inline">' . $sho['question'] . ' ? </h4><br><br>';
                      $one = "<span style='color : green' >" . $sho['answer'] . "</span><br>";
                      $two = "<span  >" . $sho['a1'] . "</span><br>";
                      $three = "<span  >" . $sho['a2'] . "</span><br>";
                      $four = "<span  >" . $sho['a3'] . "</span><br>";
                      $yourAnswer = "<span style='color : blue' >" . "Your Answer: " . $sho['student_answer'] . "</span><br>";
              
                      $my_array = array($one, $two, $three, $four, $yourAnswer);
              
              
                      echo $strs =  implode(" ", $my_array);
              
                    ?>
              
                    <?php
                      echo '<br><hr><br>';
                    }
                    ?>
                  </div>
              
              <?php
              

            }
            
            elseif ($do == "watch") {

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
                     <br>
                      <!-- unexpected integration problem -->
                     <!-- <video id="myPlayer" class="col-lg-12" controls controlslist="nodownload">

                         <source src="../<?php echo $row['video'] ?>" type="video/mp4">

                     </video> -->

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



<script type="text/javascript">
      window.onload = counter;

      function counter() {
        minutes = 15;
        seconds = 1;
        countDown();
      }
    </script>
    <script type="text/javascript">
      function countDown() {
        document.getElementById("min").innerHTML = minutes;
        document.getElementById("remain").innerHTML = seconds;
        setTimeout("countDown()", 1000);
        if (minutes == 0 && seconds == 0) {

          document.getElementById("quiz_form").submit();
        } else {
          seconds--;
          if (seconds == 0 && minutes > 0) {
            minutes--;
            seconds = 60;
          }
        }
      }
    </script>