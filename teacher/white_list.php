<style>


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


.inv {
    display: none;
}
.vis {
    display: block;
}
</style>

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


    $do = 'whitelists';
  }






  if ($do == 'whitelists') {
?>


    <div class="container">


      <div class="form-group ">
        <center><br>
          <h2>Custom Classes</h2><br>
        </center>

  

        <br>

        <a class="btn btn-primary" style=" width : 12rem ; margin-bottom: 10px" href="?do=add"> Add a new Class</a><br>

       

      <!-- 
      PLUS  -->


      <table class="table table-striped">

        <thead>
          <tr class="thead-dark">

            <th scope="col">name</th>
            <th scope="col">stage</th>
            <th scope="col">created by</th>
            <th scope="col">Content control</th>
            <th scope="col">Class control</th>
          </tr>
        </thead>
        <tbody>
          <?php
          
          $stmt = $conn->prepare('SELECT * FROM whitelist ');
          $stmt->execute();


          $rows = $stmt->fetchAll();

          foreach ($rows as $row) {
          ?>
            <tr>

              <td> <?php echo ucWords($row['name'], " "); ?> </td>
              <td> <?php echo ucWords($row['stage'], " "); ?> </td>

             
              <td> <?php echo ucWords($row['createdBy'], " "); ?> </td>
              <td> 
              <a type="button" class="btn btn-primary btn-sm" href="?do=wAdd&id=<?php echo $row['id'] ?>">Add lessons</a>
              <a type="button" class="btn btn-primary btn-sm" href="?do=wAdd_HW&id=<?php echo $row['id'] ?>">Add Home Work</a>
              <a type="button" class="btn btn-primary btn-sm" href="?do=wAdd_Quiz&id=<?php echo $row['id'] ?>">Add Quiz Questions</a>
              <a type="button" class="btn btn-primary btn-sm" href="?do=view_quiz&id=<?php echo $row['id'] ?>">View Quiz</a>
              
              </td>


              <td>
              <a type="button" class="btn btn-primary btn-sm" href="?do=view&id=<?php echo $row['id'] ?>">View</a>
              <!-- <a type="button" class="btn btn-primary btn-sm" href="?do=viewLessons&id=<?php echo $row['id'] ?>">View lessons</a> -->
              <a type="button" class="btn btn-primary btn-sm" href="?do=edit&id=<?php echo $row['id'] ?>">Edit</a>
             
              <a type="button" class="btn btn-danger btn-sm" href="?do=delete&id=<?php echo $row['id'] ?>">Delete</a>
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

  elseif($do =='wAdd_Quiz'){

    $wl_id = $_GET['id'];
    //$_SESSION['exam_id'] = $id;


?>

<br><br>
<div class="container col-md-6">
<form method="post" action="?do=insert_quiz&wl_id=<?php echo $_GET['id'] ?>" enctype="multipart/form-data">
<div class="form-row">
  <div class="form-group col-md-12">
    <label for="inputEmail4">Question </label>
    <input type="text" class="form-control" name="question" required>
  </div>



</div>


<div class="form-group">
             <label for="inputPassword4">Answer type</label>
             <select id="answer_type" class="form-control " name="ansType" required>
             <option value="" selected disabled> Choose answer type </option>
               <option  value="1"  >text answer</option>
               <option  value="2" >image answer</option>
             

             </select>
</div>

<div id="target" > 

<script>

selectEl = document.getElementById('answer_type')


callback = function () {
    
    'use strict';
    
    var target = document.getElementById("target"); //select
   
    
    if (this.value ==1) {
     
      target.innerHTML = `

<div class="form-group">
<label for="inputAddress">Correct answer</label>
<input type="text" class="form-control" name="answer" placeholder="Correct answer" required>
</div>

<div class="form-group">
<label for="inputAddress">Wrong answer 1</label>
<input type="text" class="form-control" name="a1" placeholder="Wrong answer 1" required>
</div>
<div class="form-group">
<label for="inputAddress">Wrong answer 2</label>
<input type="text" class="form-control" name="a2" placeholder="Wrong answer 2" required>
</div>
<div class="form-group">
<label for="inputAddress">Wrong answer 3</label>
<input type="text" class="form-control" name="a3" placeholder="Wrong answer 3" required>
</div>
<button type="submit" class="btn btn-primary">insert</button>

`;
    }
    else if (this.value ==2) {
     
target.innerHTML = `

<div class="form-group">
<b style="color:green">Correct answer </b> <br>
Select Image Files to Upload:
<input type="file" name="files[]" multiple >

<input name="correct_img" value="files[]" hidden >

</div>


<hr class="mt-3 mb-3"/>
<hr class="mt-3 mb-3"/>



<div class="form-group">
<b style="color:red">Wrong answer 1 </b> <br>
Select Image Files to Upload:
<input type="file" name="files[]" multiple >


</div>


<hr class="my-3"/>


<div class="form-group">
<b style="color:red">Wrong answer 2 </b> <br>
Select Image Files to Upload:
<input type="file" name="files[]" multiple >


</div>

<hr class="my-3"/>



<div class="form-group">
<b style="color:red">Wrong answer 3 </b> <br>
Select Image Files to Upload:
<input type="file" name="files[]" multiple >


</div>
<button type="submit" class="btn btn-primary">insert</button>

`
    }
}


selectEl.addEventListener('change',callback );




</script>



</form>

</div>


<?php

  }

  elseif($do =='view_quiz'){

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
      
      echo '<h4 style="display:inline">' . $sho['question'] . ' ? </h4><a  type="submit"  href="?do=delete_quiz_q&q_id=' . $sho['q_id'] .  '&id='.$id  . '"  class="btn btn-danger btn-sm">delete</a> <br><br>';
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
      echo '<h4 style="display:inline">' . $sho['question'] . ' ? </h4><a  type="submit"  href="?do=delete_quiz_q&q_id=' . $sho['q_id'] .  '&id='.$id  . '"  class="btn btn-danger btn-sm">delete</a> <br><br>';

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

          <!-- <b style="color:green;"> Answer </b><br> -->
          <li>

                  <div class="containerr">
                  <img src="../uploads/quizs/<?php echo $sho2['file_name']?>" class="img-responsive inline-block" alt="Responsive image" />
                 
                    <div class="top-left"> <b style="color:green; font-size:1.5rem"> [The Answer] </b><br></div>
                  </div>

          <!-- <img src="../uploads/<?php echo $sho2['file_name']?>" class="img-responsive inline-block" alt="Responsive image" /> -->


        </li>
        <?php 
          
         // $one = "<b style=". "color:green; >" . "Answer" . "</b><br>"."<img src="."../uploads/" . $sho['file_name'] ." style='width : 30%'". "><br>";
        
          //echo $one ; 


        }

        else {
        

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
                  <img src="../uploads/quizs/<?php echo $sho2['file_name']?>" class="img-responsive inline-block" alt="Responsive image" />
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



      }

      ?>

</ul>
            </div>
            </div>

            <?php 


    }
      


      

?>

<?php
      // echo '<br><hr><br>';
//    }
?>
 <!-- </ul>
            </div>
            </div> -->

</div>

<?php


  }
  
  elseif ($do == 'insert_quiz') {
    $wl_id = $_GET['wl_id'] ; 

    if($_POST['ansType']==1){

     
      $question    = $_POST['question'];
      $answer      = $_POST['answer'];
      $a1     = $_POST['a1'];
      $a2     = $_POST['a2'];
      $a3     = $_POST['a3'];


      $stmt = $conn->prepare('INSERT INTO `custom_class_quiz` (`question`, `answer`,`a1`,`a2`,`a3`,`custom_class_id`)
                                            
                             VALUES(
                          
                                :question,  :answer  ,  :a1  ,:a2  ,:a3  ,:custom_class_id
                                  
                                  )');


      $stmt->execute(array(
        'question' => $question,
        'answer' => $answer,
        'a1' => $a1,
        'a2' => $a2,
        'a3' => $a3,
        'custom_class_id' => $wl_id
      ));



    }

    else {       //INSERTING IMGAE-based question


      
      $question    = $_POST['question'];
      $correct_image_name    = $_POST['correct_img'];
      $targetDir = "../uploads/quizs/"; 
      $allowTypes = array('jpg','png','jpeg','gif'); 
      $insertValuesSQL = '' ;
      //
        // handling the question_id which bugged me much of the time
      //
      $stmt = $conn->prepare('select q_id from quiz_images where wl_id=? ORDER BY q_id DESC  LIMIT 1');
      $stmt->execute(array($wl_id));
      $row = $stmt->fetch();
      $next_q_id = 0 ; 
      if(($stmt->rowCount())>0){
        $next_q_id = $row['q_id'] + 1 ;
      
      }
     
       
     
      $fileNames = array_filter($_FILES['files']['name']); 

      // correctImage is ALWAYS the first one in the array ...
      $correct_index =0 ; 
      if(!empty($fileNames)){ 
          foreach($_FILES['files']['name'] as $key=>$val){ 
              // File upload path 
              $fileName = basename($_FILES['files']['name'][$key]); 
              $targetFilePath = $targetDir . $fileName; 
               
              // Check whether file type is valid 
              $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
              if(in_array($fileType, $allowTypes)){ 
                  // Upload file to server 
                  if(move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)){ 
                      // Image db insert sql 
                      if($key == $correct_index){
                        $insertValuesSQL .= "('".$wl_id. "','".$next_q_id. "','"."1"."','".$question. "','". $fileName."', NOW()),"; 

                      }
                      else {
                        $insertValuesSQL .= "('".$wl_id. "','".$next_q_id. "','"."0"."','".$question. "','". $fileName."', NOW()),"; 

                      }
                      
                  }else{ 
                     // uploaded file isn't moved to the server
                  } 
              }else{ 
                // not supported type
              } 
          } 
           
          if(!empty($insertValuesSQL)){ 
              $insertValuesSQL = trim($insertValuesSQL, ','); 
              // Insert image file name into database 
              
              $insert = $conn->query("INSERT INTO quiz_images (wl_id ,q_id ,answer,question , file_name, uploaded_on) VALUES  $insertValuesSQL"); 
             
          } 
      }else{ 
          // Please select a file to upload.
      }
      
      

    }

    
?>

<div class="container" style="margin-top: 50px">
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <center> New question added successfully</center>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<center> <br>
  <p style="color:gray"> you will be redirected in 3 seconds</p>
</center>
</div>

<?php

      header('Refresh:3; url=white_list.php?do=wAdd_Quiz&id=' . $wl_id . '');

    
    



  }
  

  elseif($do =='delete_quiz_q')
  {


    // if quiz question is image-based ... 
    $stmt = $conn->prepare('delete from quiz_images where q_id=?');
    $stmt->execute(array($_GET['q_id']));

    // if quiz question is text-based ... 
    $stmt = $conn->prepare('delete from custom_class_quiz where q_id=?');
    $stmt->execute(array($_GET['q_id']));

  ?><div class="container">
      <div class="container" style="margin-top: 50px">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <center>Question deleted successfully</center>
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
            header("Refresh:3; url=white_list.php?do=view_quiz&id=" . $_GET['id'] . "");
  }
  elseif ($do == 'view') {

    $wl_id = $_GET['id'];
    $stmt = $conn->prepare('select name from  whitelist where id = ?  ');
    $stmt->execute(array($wl_id));
    $row = $stmt->fetch();


    ?>

     <div class="container">

    
     
       <h1> </h1>
   
       
       <a class="btn btn-primary" style=" width : 16rem ; margin-bottom: 10px" href="?do=addWhite&id=<?php echo $wl_id ?>"> Add student to <?php echo $row['name'] ?> </a><br>
        <table class="table table-striped">

          <thead>
            <tr class="thead-dark">

              <!-- <th scope="col">id</th> -->
              <th scope="col">student name </th>
              <th scope="col">Control</th>
              
            </tr>
          </thead>


       <?php
        

        $wl_id = $_GET['id'];

        $stmt = $conn->prepare('SELECT student.*, whitelist.*  FROM `wl_subscribers`  JOIN student ON wl_subscribers.student_id = student.student_id   JOIN whitelist ON wl_subscribers.wl_id = whitelist.id where wl_subscribers.wl_id = ? ');
        $stmt->execute(array($wl_id));
        $row = $stmt->rowCount();

        if ($row > 0){
         
          $rows = $stmt->fetchAll();
          // echo '<center><h3>All students subscribed in ' .$rows['name'] . '  </h3></center><br><br>';

          foreach ($rows as $row) {

            ?>
    
    
             <tbody>
                <tr>
                  <td> <?php echo $row['student_name']; ?> </td>
                  
          
                  <td> <a type="button" class="btn btn-danger btn-sm" href="?do=deleteWhite&id=<?php echo $row['student_id'] ?>&wl_id=<?php echo $wl_id ?>">Delete</a></td>
                </tr>
              </tbody>
            
            
           <?php
            }
            ?>
             </table>
             <?php
              
    
            echo '</div>';
          }
          
        }



        elseif($do == 'viewLessons'){



        }

   elseif ($do == 'addWhiteLesson'){

    
  ?>
  <form action="?do=lessonSearch" method="post">
           <div class="input-group mb-2 mr-sm-2">
 
             <input type="text" name="lessonSearch" class="form-control col-form-label-lg" id="inlineFormInputGroupUsername2" placeholder="search by name">
             <input type="hidden" name="wl_id" value=<?php echo $_GET['id']?>  /> 
             <div class="input-group-prepend">
               <button type="submit"  class="btn " style="border-bottom-style: dashed;padding-top: 0px;"> <img style="width:60px" src="images/search.jpg"> </button>
             </div>
           </div>
         </form>
       </div>
 
   <?php 


   }

   elseif ($do == 'lessonSearch'){

    
   // logic as follow : add an already existing student 
  $wl_id = $_POST['wl_id'];
  $search = $_POST['lessonSearch'];

    $stmt = $conn->prepare("SELECT * FROM lesson  WHERE lesson.lesson_name LIKE '%" . $search . "%' OR lesson.lesson_id LIKE  '%" . $search . "%' ");
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row['lesson_name']) {
      // echo $row['student_name'];
    ?>

      <div style="margin-top: 50px;">
        <div class="container">
          <table class="table table-striped">

            <thead>
              <tr class="thead-dark">

               
            <th scope="col">name</th>
            <th scope="col">Control</th>
              </tr>
            </thead>
            <tbody>

              <tr>

              <td> <?php echo ucWords($row['lesson_name'], " "); ?> </td>
    
             
              <td><a type="button" class="btn btn-primary btn-sm" href="?do=insertWhiteLesson&id=<?php echo $row['lesson_id'] ?>&wl_id=<?php echo $wl_id ?>">Add</a>
                </td>

              </tr>
            </tbody>
          </table>
        </div>
      </div>

  


    <?php
  
    } elseif (!$row['lesson_name']) {
      //PLUS
    ?>

      <center>
        <h1> No Match </h1>
      </center>
      <center> <br>
        <p style="color:gray"> you will be redirected in 3 seconds</p>
      </center>
    <?php

      header('Refresh:3; url=white_list.php?do=wAdd&id='.$_GET['id']);
    } else {
      header('url=white_list.php');
    }

    
  }
       
  
  elseif($do == 'insertWhiteLesson'){

    //FINALLY insert a student into whitelists

// i need student_id , wl_id 
$lesson_id = $_GET['id'];
$wl_id = $_GET['wl_id'];

$stmt = $conn->prepare('select lesson_id , wl_id from  wl_lessons where lesson_id = ? AND wl_id = ? ');
$stmt->execute(array($lesson_id,$wl_id ));
$row = $stmt->fetch();


if($stmt->rowCount() == 0){
  $stmt = $conn->prepare('insert into wl_lessons (`wl_id`, `lesson_id`)
  VALUES (:wl_id,:lesson_id)');
  
  $stmt->execute(array('wl_id' =>$wl_id , 'lesson_id' => $lesson_id));

  ?>
  <div class="container" style="margin-top: 50px">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <center> added successfully</center>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <center> <br>
        <p style="color:gray"> you will be redirected in 3 seconds</p>
      </center>
    </div>
<?php

}


else if($row && $row['lesson_id'] != $lesson_id && $row['wl_id'] != $wl_id ){

  $stmt = $conn->prepare('insert into wl_lessons (`wl_id`, `lesson_id`)
  VALUES (:wl_id,:lesson_id)');
  
  $stmt->execute(array('wl_id' =>$wl_id , 'lesson_id' => $lesson_id));

  ?>
  <div class="container" style="margin-top: 50px">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <center> added successfully</center>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <center> <br>
        <p style="color:gray"> you will be redirected in 3 seconds</p>
      </center>
    </div>
<?php

   

}


header('Refresh:3; url=white_list.php?do=wAdd&id='.$wl_id);

  }
elseif ($do == 'addWhite') {


  ?>
 <form action="?do=search" method="post">
          <div class="input-group mb-2 mr-sm-2">

            <input type="text" name="search" class="form-control col-form-label-lg" id="inlineFormInputGroupUsername2" placeholder="search by name">
            <input type="hidden" name="wl_id" value=<?php echo $_GET['id']?>  /> 
            <div class="input-group-prepend">
              <button type="submit"  class="btn " style="border-bottom-style: dashed;padding-top: 0px;"> <img style="width:60px" src="images/search.jpg"> </button>
            </div>
          </div>
        </form>
      </div>

  <?php 
  

}

elseif ($do == 'insertWhite') {

  //FINALLY insert a student into whitelists

// i need student_id , wl_id 
$student_id = $_GET['id'];
$wl_id = $_GET['wl_id'];

$stmt = $conn->prepare('select student_id , wl_id from  wl_subscribers where student_id = ? AND wl_id = ? ');
$stmt->execute(array($student_id,$wl_id ));
$row = $stmt->fetch();


if($stmt->rowCount()== 0){

  $stmt = $conn->prepare('insert into wl_subscribers (`wl_id`, `student_id` , `expire`)
  VALUES (:wl_id,:student_id, NOW() + INTERVAL 7*24 HOUR )');
  
  $stmt->execute(array('wl_id' =>$wl_id , 'student_id' => $student_id  ));

  ?>
  <div class="container" style="margin-top: 50px">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <center> added successfully</center>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <center> <br>
        <p style="color:gray"> you will be redirected in 3 seconds</p>
      </center>
    </div>
<?php


}


else if($row && $row['student_id'] != $student_id && $row['wl_id'] != $wl_id ){

  $stmt = $conn->prepare('insert into wl_subscribers (`wl_id`, `student_id` , `expire`)
  VALUES (:wl_id,:student_id, NOW() + INTERVAL 7*24 HOUR )');
  
  $stmt->execute(array('wl_id' =>$wl_id , 'student_id' => $student_id  ));

  ?>
  <div class="container" style="margin-top: 50px">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <center> added successfully</center>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <center> <br>
        <p style="color:gray"> you will be redirected in 3 seconds</p>
      </center>
    </div>
<?php

   

}


header('Refresh:3; url=white_list.php?do=view&id='.$wl_id);



}

elseif($do== 'wAdd_HW'){
  $wl_id = $_GET['id'];
  $stmt = $conn->prepare('select name from  whitelist where id = ?  ');
  $stmt->execute(array($wl_id));
  $row = $stmt->fetch();


  ?>

   <div class="container">

  
   
     <h1> </h1>
 
     
     
      <table class="table table-striped">

        <thead>
          <tr class="thead-dark">

            <!-- <th scope="col">id</th> -->
            <th scope="col">lesson name </th>
            <th scope="col">Home Work</th>
            
          </tr>
        </thead>


     <?php
      

      $wl_id = $_GET['id'];

      $stmt = $conn->prepare('SELECT lesson.*, whitelist.*  FROM `wl_lessons`  JOIN lesson ON wl_lessons.lesson_id = lesson.lesson_id   JOIN whitelist ON wl_lessons.wl_id = whitelist.id where wl_lessons.wl_id = ? ');
      $stmt->execute(array($wl_id));
      $row = $stmt->rowCount();

      if ($row > 0){
       
        $rows = $stmt->fetchAll();
        // echo '<center><h3>All students subscribed in ' .$rows['name'] . '  </h3></center><br><br>';

        foreach ($rows as $row) {

          ?>
  
  
           <tbody>
              <tr>
                <td> <?php echo $row['lesson_name']; ?> </td>
                
        
                <td> <a type="button" class="btn btn-primary btn-sm" href="?do=wAdd_HW_LESSON&id=<?php echo $row['lesson_id'] ?>&wl_id=<?php echo $wl_id ?>">Add</a></td>
              </tr>
            </tbody>
          
          
         <?php
          }
          ?>
           </table>
           <?php
            
  
          echo '</div>';
        }

}

elseif ($do == 'wAdd_HW_LESSON') {

  $lesson_id = $_GET['id'];
  $custom_class_id = $_GET['wl_id'];
  //$_SESSION['exam_id'] = $id;      what 


?>

<br><br>
<div class="container col-md-6">
<form method="post" action="?do=insert_HW&id=<?php echo $lesson_id ?>&wl_id=<?php echo $custom_class_id ?>">
<div class="form-row">
<div class="form-group col-md-12">
  <label for="inputEmail4">Question </label>
  <input type="text" class="form-control" name="question" required>
</div>

</div>

<div class="form-group">
<label for="inputAddress">Correct answer</label>
<input type="text" class="form-control" name="answer" placeholder="Correct answer" required>
</div>

<div class="form-group">
<label for="inputAddress">Wrong answer 1</label>
<input type="text" class="form-control" name="a1" placeholder="Wrong answer 1" required>
</div>
<div class="form-group">
<label for="inputAddress">Wrong answer 2</label>
<input type="text" class="form-control" name="a2" placeholder="Wrong answer 2" required>
</div>
<div class="form-group">
<label for="inputAddress">Wrong answer 3</label>
<input type="text" class="form-control" name="a3" placeholder="Wrong answer 3" required>
</div>
<button type="submit" class="btn btn-primary">insert</button>
</form>

</div>


<?php

}

elseif ($do == 'insert_HW') {

  $lesson_id = $_GET['id'];
  $custom_class_id = $_GET['wl_id'];
  
  $question    = $_POST['question'];
  $answer      = $_POST['answer'];
  $a1     = $_POST['a1'];
  $a2     = $_POST['a2'];
  $a3     = $_POST['a3'];


  $stmt = $conn->prepare('INSERT INTO `custom_class_hw` (`question`, `answer`,`a1`,`a2`,`a3`,`custom_class_id`,`lesson_id`)
                                        
                         VALUES(
                      
                            :question,  :answer  ,  :a1  ,:a2  ,:a3  ,:custom_class_id , :lesson_id
                              
                              )');


  $stmt->execute(array(
    'question' => $question,
    'answer' => $answer,
    'a1' => $a1,
    'a2' => $a2,
    'a3' => $a3,
    'custom_class_id' => $custom_class_id,
    'lesson_id' => $lesson_id

  ));



?>

<div class="container" style="margin-top: 50px">
<div class="alert alert-success alert-dismissible fade show" role="alert">
<center> New question added successfully</center>
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<center> <br>
<p style="color:gray"> you will be redirected in 3 seconds</p>
</center>
</div>

<?php


  header('Refresh:3; url=white_list.php?do=wAdd_HW&id=' . $custom_class_id . '');


}





elseif ($do == 'wAdd') {

    $wl_id = $_GET['id'];
    $stmt = $conn->prepare('select name from  whitelist where id = ?  ');
    $stmt->execute(array($wl_id));
    $row = $stmt->fetch();


    ?>

     <div class="container">

    
     
       <h1> </h1>
   
       
       <a class="btn btn-primary" style=" width : 16rem ; margin-bottom: 10px" href="?do=addWhiteLesson&id=<?php echo $wl_id ?>"> Add lessons to <?php echo $row['name'] ?> </a><br>
        <table class="table table-striped">

          <thead>
            <tr class="thead-dark">

              <!-- <th scope="col">id</th> -->
              <th scope="col">lesson name </th>
              <th scope="col">Control</th>
              
            </tr>
          </thead>


       <?php
        

        $wl_id = $_GET['id'];

        $stmt = $conn->prepare('SELECT lesson.*, whitelist.*  FROM `wl_lessons`  JOIN lesson ON wl_lessons.lesson_id = lesson.lesson_id   JOIN whitelist ON wl_lessons.wl_id = whitelist.id where wl_lessons.wl_id = ? ');
        $stmt->execute(array($wl_id));
        $row = $stmt->rowCount();

        if ($row > 0){
         
          $rows = $stmt->fetchAll();
          // echo '<center><h3>All students subscribed in ' .$rows['name'] . '  </h3></center><br><br>';

          foreach ($rows as $row) {

            ?>
    
    
             <tbody>
                <tr>
                  <td> <?php echo $row['lesson_name']; ?> </td>
                  
          
                  <td> <a type="button" class="btn btn-danger btn-sm" href="?do=deleteWhiteLesson&lid=<?php echo $row['lesson_id'] ?>&wl_id=<?php echo $wl_id ?>">Delete</a></td>
                </tr>
              </tbody>
            
            
           <?php
            }
            ?>
             </table>
             <?php
              
    
            echo '</div>';
          }


}

elseif($do == 'deleteWhiteLesson'){


  $stmt = $conn->prepare('delete from wl_lessons where lesson_id=?');
  $stmt->execute(array($_GET['lid']));
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

  header('Refresh:3; url=white_list.php?do=wAdd&id='.$_GET['wl_id']);

}
  elseif ($do == 'search') {



   // logic as follow : add an already existing student 
  $wl_id = $_POST['wl_id'];
  $search = $_POST['search'];

    $stmt = $conn->prepare("SELECT * FROM student  WHERE student.student_name LIKE '%" . $search . "%' OR student.student_id LIKE  '%" . $search . "%' ");
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row['student_name']) {
      // echo $row['student_name'];
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
            <th scope="col">Control</th>
              </tr>
            </thead>
            <tbody>

              <tr>

              <td> <?php echo ucWords($row['student_name'], " "); ?> </td>

              <td> <?php echo ucWords($row['username'], " "); ?> </td>
              <td> <?php echo ucWords($row['stage'], " "); ?> </td>
              <td> <?php echo ucWords($row['school'], " "); ?> </td>
             
              <td><a type="button" class="btn btn-primary btn-sm" href="?do=insertWhite&id=<?php echo $row['student_id'] ?>&wl_id=<?php echo $wl_id ?>">Add</a>
                </td>

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

      header('Refresh:3; url=white_list.php?do=addWhite&id='.$_GET['id']);
    } else {
      header('url=white_list.php');
    }
  }
  

  elseif ($do == 'add') {

    ?>

     <center>
       <h3>Add new white list</h3>
     </center><br>
     <div class="container">
       <form method="post" action="?do=insert" enctype="multipart/form-data">


         <div class="form-row">
           <div class="form-group col-md-4">
             <label for="inputEmail4">name</label>

             <input type="text" class="form-control" id="name" name="name" required>
           </div>
        

           <div class="form-group col-md-8">
             <label for="inputPassword4">Stage</label>
             <select class="form-control " name="stage" required>
               <option value="" selected disabled> student stage </option>
               <option value="1">1</option>
               <option value="2">2</option>
               <option value="3">3</option>

             </select>
           </div>


         </div>


         <button type="submit" name="send" style="float:right" class="btn btn-primary" style="width: 100px;"> Add</button>



       </form>
     </div>

   <?php

    } elseif ($do == 'insert') {

      $name = $_POST['name'];
      $stage = $_POST['stage'];
      $createdBy = $_SESSION['username'] ; 


      $stmt = $conn->prepare('insert into whitelist (`name`, `stage`, `createdBy`)
                                 VALUES (:name,:stage,:createdBy) ');

      $stmt->execute(array('name' => $name, 'stage' => $stage, 'createdBy' => $createdBy));

    ?>

     <div class="container" style="margin-top: 100px">
       <div class="alert alert-success alert-dismissible fade show" role="alert">
         <center> New white list added successfully</center>
         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button>
       </div>

     </div>



   <?php
   header('Refresh:3; url=white_list.php');

    }
  
  
  
  
  
  
  
  
  
  
  elseif ($do == 'edit') {

    $stmt = $conn->prepare('SELECT * FROM whitelist where id = ?');
    $stmt->execute(array($_GET['id']));
    $row = $stmt->fetch();
    ?>


<center>
       <h3>Edit white list</h3>
     </center><br>
     <div class="container">
       <form method="post" action="?do=update&id=<?php echo $_GET['id'] ?>" enctype="multipart/form-data">


         <div class="form-row">
           <div class="form-group col-md-4">
             <label for="inputEmail4">name</label>

             <input type="text" class="form-control" id="name" name="name" required>
           </div>
        

           <div class="form-group col-md-8">
             <label for="inputPassword4">Stage</label>
             <select class="form-control " name="stage" required>
               <option value="" selected disabled> student stage </option>
               <option value="1">1</option>
               <option value="2">2</option>
               <option value="3">3</option>

             </select>
           </div>


         </div>


         <button type="submit" name="send" style="float:right" class="btn btn-primary" style="width: 100px;"> Edit</button>



       </form>
     </div>

              
  <?php


  }

  elseif ($do == "update") {
 

  
      $stmt = $conn->prepare('UPDATE `whitelist` SET name=?,stage=? where id=?');
     
      $stmt->execute(array($_POST['name'], $_POST['stage'],$_GET['id']));


    
    ?>
    <div class="container" style="margin-top: 50px">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <center>white list edited successfully</center>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    </div>

  <?php

    header("Refresh:3; url=white_list.php");

  }
  
  elseif ($do == 'deleteWhite') {


    $stmt = $conn->prepare('delete from wl_subscribers where student_id=? AND wl_id = ? ');
    $stmt->execute(array($_GET['id'],$_GET['wl_id']));
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

    header('Refresh:3; url=white_list.php?do=view&id='.$_GET['wl_id']);
  }
  
  elseif ($do == 'delete') {


    $stmt = $conn->prepare('delete from whitelist where id=?');
    $stmt->execute(array($_GET['id']));


    // polish deleting all this custom_class quizs 
    $stmt = $conn->prepare('delete from quiz_result where quiz_id=?');
    $stmt->execute(array($_GET['id']));


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

    header('Refresh:3; url=white_list.php');
  } else {

    header('location:login.php');
  }
}

include('include/footer.php');

?>