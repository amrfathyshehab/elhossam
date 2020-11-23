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


  .a:hover {
    color: gray;
    text-decoration: none;
  }
</style><?php

        session_start();

        if (isset($_SESSION['id'])) {



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
        ?>

    <center>
      <h2 style="line-height: 100px">Exam</h2>
    </center>
    <div class="container">


      <form action="?do=create" method="post">

        <?php

            $stmt = $conn->prepare('select rank from teacher where id=?');
            $stmt->execute(array($_SESSION['id']));
            $rank = $stmt->fetch();

            if ($rank['rank'] < 3) {
              echo  '<a type="submit"  href="?do=create"  class="btn btn-primary">create new exam</a>';
            }
        ?>
      </form>


      <table class="table table-striped">

        <thead>
          <tr class="thead-dark">

            <th scope="col">Exam</th>
            <th scope="col">Duration</th>
            <th scope="col">Stage</th>
            <th scope="col">Group</th>

            <th scope="col">Visibility</th>

            <th scope="col">Control</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $stmt = $conn->prepare('SELECT exam.*, exam.id as exam_id , center.center_name as center_name  ,center.time, center.stageName , center.stage FROM `exam` INNER JOIN center ON exam.group = center.id');
            $stmt->execute();
            $rows = $stmt->fetchAll();


            foreach ($rows as $row) {

              $stmt = $conn->prepare('SELECT *  FROM `time` where id =?');
              $stmt->execute(array($row['time']));
              $time = $stmt->fetch();

          ?>
            <tr>

              <td> <?php echo ucWords($row['exam_name'], " "); ?> </td>
              <td> <?php echo ucWords($row['exam_time'], " "); ?> min</td>



              <td> <?php echo $row['stage'] . ' secondary'; ?> </td>

              <td> <?php echo ucWords($row['center_name'], " ") . ' : ' . $time['day'] . ' from ' . $time['from'] . ' to ' . $time['to']; ?> </td>




              <td> <a class="a" href="?do=v&exam_id=<?php echo $row['id'] ?>"><?php if ($row['v'] == 0) {
                                                                                echo 'hidden';
                                                                              } else {
                                                                                echo 'visible';
                                                                              } ?> </a> </td>

              <td>
                <?php


                $stmt = $conn->prepare('select rank from teacher where id=?');
                $stmt->execute(array($_SESSION['id']));
                $rank = $stmt->fetch();

                if ($rank['rank'] < 3) {
                  echo  '<a type="button" class="btn btn-danger btn-sm" href="?do=delete&id=' . $row['id'] . '" > Delete</a>';
                } ?>

                <a type="button" class="btn btn-success btn-sm" href="?do=view&id=<?php echo $row['id'] ?>&exam_name=<?php echo $row['exam_name'] ?>"> view</a>


                <?php
                $stmt = $conn->prepare('select rank from teacher where id=?');
                $stmt->execute(array($_SESSION['id']));
                $rank = $stmt->fetch();
                if ($rank['rank'] < 3) {
                  echo '<a type="button" class="btn btn-primary btn-sm" href="?do=add&id=' . $row['id'] . '" > add</a>';
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



    </div>


  <?php
          } elseif ($do == 'create') {

  ?>
    <center><br>
      <h1>Create new exam</h1>
    </center>
    <div class="container">
      <center>
        <br>
        <p style="color:gray">please choose your group to create the exam</p>
      </center>
      <form action="?do=creatExam" method="post">


        <div class="form-row">

          <div class="form-group col-md-6">
            <label for="inputPassword4">Exam name</label>
            <input type="text" class="form-control" name="exam_name" id="inputEmail4" required>
          </div>
          <div class="form-group col-md-6">
            <label for="inputPassword4">Duration</label>
            <input type="text" class="form-control" name="duration" id="inputEmail4" required>
          </div>

          <div class="form-group col-md-12">
            <label for="inputPassword4">group</label>
            <select class="form-control " name="group" required>
              <option value="" selected disabled> </option>
              <?php

              $stmt = $conn->prepare('SELECT center.* ,center.id as cid , time.* FROM `center` INNER JOIN time ON center.time = time.id');
              $stmt->execute();

              $rows = $stmt->fetchAll();


              foreach ($rows as $row) {


                echo
                  '<option  value="' .  $row['cid'] . '"> ' . $row['center_name'] . ' ' . $row['stage'] . ' ' . $row['stageName'] . ' on '
                    . $row['day'] . ' from ' . $row['from'] . ' to ' . $row['to'] . '</option>';
              }


              ?>
            </select>
          </div>


          <button type="submit" class="btn btn-primary" style="width: 100px;">next</button>
      </form>



    </div>
  <?php

          } elseif ($do == 'creatExam') {

            $exam_name = $_POST['exam_name'];

            $group = $_POST['group'];
            $duration = $_POST['duration'];

            $date = date('d/m/Y');


            $stmt = $conn->prepare('insert into exam (`exam_name`, `exam_time`, `group`, `date`)
                      VALUES (:exam_name, :duration , :group, :date )');
            $stmt->execute(array('exam_name' => $exam_name, 'duration' => $duration, 'group' => $group, 'date' => $date));

  ?>
    <div class="container" style="margin-top: 50px">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <center>New exam added successfully</center>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <center> <br>
        <p style="color:gray"> you will be redirected in 3 seconds</p>
      </center>
    </div>


  <?php

            header('Refresh:3; url=exam.php');
          } elseif ($do == 'add') {

            // adding support for images 
            $id = $_GET['id'];
            $_SESSION['exam_id'] = $id;


  ?>

    <br><br>
    <div class="container col-md-6">
      <form method="post" action="?do=insert&exam_id=<?php echo $id ?>" enctype="multipart/form-data">
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
<input type="file" name="files[]" multiple required>

<input name="correct_img" value="files[]" hidden >

</div>


<hr class="mt-3 mb-3"/>
<hr class="mt-3 mb-3"/>



<div class="form-group">
<b style="color:red">Wrong answer 1 </b> <br>
Select Image Files to Upload:
<input type="file" name="files[]" multiple required>


</div>


<hr class="my-3"/>


<div class="form-group">
<b style="color:red">Wrong answer 2 </b> <br>
Select Image Files to Upload:
<input type="file" name="files[]" multiple required>


</div>

<hr class="my-3"/>



<div class="form-group">
<b style="color:red">Wrong answer 3 </b> <br>
Select Image Files to Upload:
<input type="file" name="files[]" multiple  required>


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

          } elseif ($do == 'insert') {


            if($_POST['ansType']==1){

            $exam_id       = $_SESSION['exam_id'];
            $question    = $_POST['question'];
            $answer      = $_POST['answer'];
            $a1     = $_POST['a1'];
            $a2     = $_POST['a2'];
            $a3     = $_POST['a3'];


            $stmt = $conn->prepare('INSERT INTO `question` (`question`, `answer`,`a1`,`a2`,`a3`,`exam_id`)
                                                  
                                   VALUES(
                                
                                      :question,  :answer  ,  :a1  ,:a2  ,:a3  ,:exam_id
                                        
                                        )');


            $stmt->execute(array(
              'question' => $question,
              'answer' => $answer,
              'a1' => $a1,
              'a2' => $a2,
              'a3' => $a3,
              'exam_id' => $exam_id
            ));

          }
          else { //INSERTING IMGAE-based question

            
      $exam_id   = $_SESSION['exam_id'];
      $question    = $_POST['question'];
      $correct_image_name    = $_POST['correct_img'];
      $targetDir = "../uploads/exams/"; 
      $allowTypes = array('jpg','png','jpeg','gif'); 
      $insertValuesSQL = '' ;
      //
        // handling the question_id which bugged me much of the time
      //
      $stmt = $conn->prepare('select q_id from exam_images where exam_id=? ORDER BY q_id DESC  LIMIT 1');
      $stmt->execute(array($exam_id));
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
                        $insertValuesSQL .= "('".$exam_id. "','".$next_q_id. "','"."1"."','".$question. "','". $fileName."', NOW()),"; 

                      }
                      else {
                        $insertValuesSQL .= "('".$exam_id. "','".$next_q_id. "','"."0"."','".$question. "','". $fileName."', NOW()),"; 

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
              
              $insert = $conn->query("INSERT INTO exam_images (exam_id ,q_id ,answer,question , file_name, uploaded_on) VALUES  $insertValuesSQL"); 
             
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

            header('Refresh:3; url=exam.php?do=add&id=' . $_SESSION['exam_id'] . '');
          } elseif ($do == 'delete') {


            $stmt = $conn->prepare('DELETE FROM `exam` WHERE `exam`.`id` = ' . $_GET['id'] . '');
            $stmt->execute();

            // polish : and deleting its results 

            $stmt = $conn->prepare('DELETE FROM `student_result` WHERE `exam_id` = ' . $_GET['id'] . '');
            $stmt->execute();

  ?>

    <div class="container" style="margin-top: 50px">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <center> Exam deleted successfully</center>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <center> <br>
        <p style="color:gray"> you will be redirected in 3 seconds</p>
      </center>
    </div>

  <?php

            header('Refresh:3; url=exam.php');


          } elseif ($do == 'view') {

            if (isset($_GET['exam_name'])) {
              $_SESSION['exam_name'] = $_GET['exam_name'];
            }

  ?>

    <div class="container">
      <center>
        <h2>Exam with model answer</h2><br>
        <h4 style="color:gray">Exam : <?php echo $_SESSION['exam_name'] ?></h4>
        <br><br>
      </center>
      <?php
          
  // i have 2 question types : text-based , img-based 



      //for texts
    $stmt = $conn->prepare('
    SELECT * FROM `question` 


    where exam_id=?
    
    
    
    ');
$id = $_GET['id'];
$stmt->execute(array($id));
$show = $stmt->fetchAll();


foreach ($show as $sho) {

echo '<h4 style="display:inline">' . $sho['question'] . ' ? </h4><a  type="submit"  href="?do=delete_q&q_id=' . $sho['q_id'] .  '&id='.$id  . '"  class="btn btn-danger btn-sm">delete</a> <br><br>';
$one = "<span style='color : red' >" . $sho['answer'] . "</span><br>";
$two = "<span  >" . $sho['a1'] . "</span><br>";
$three = "<span  >" . $sho['a2'] . "</span><br>";
$four = "<span  >" . $sho['a3'] . "</span><br>";

$my_array = array($one, $two, $three, $four);
echo $strs =  implode(" ", $my_array);

}


// for images i will select all question_ids first then loop over each to display its respective images 

$stmt = $conn->prepare('
SELECT distinct q_id , question FROM exam_images 

where exam_id =?



');
// $id = $_GET['id'];
$stmt->execute(array($id));
$show = $stmt->fetchAll();




foreach ($show as $sho) {
echo '<h4 style="display:inline">' . $sho['question'] . ' ? </h4><a  type="submit"  href="?do=delete_q&q_id=' . $sho['q_id'] .  '&id='.$id  . '"  class="btn btn-danger btn-sm">delete</a> <br><br>';

$stmt = $conn->prepare('
SELECT * FROM exam_images 

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
 <img src="../uploads/exams/<?php echo $sho2['file_name']?>" class="img-responsive inline-block" alt="Responsive image" />

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
 <img src="../uploads/exams/<?php echo $sho2['file_name']?>" class="img-responsive inline-block" alt="Responsive image" />
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
          } elseif ($do == 'delete_q') {




    // if quiz question is image-based ... 
    $stmt = $conn->prepare('delete from exam_images where q_id=?');
    $stmt->execute(array($_GET['q_id']));

    // if quiz question is text-based ... 
 
    $stmt = $conn->prepare('delete from question where q_id=?');
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
            header("Refresh:3; url=exam.php?do=view&id=" . $_SESSION['exam_id'] . "");
          } elseif ($do == 'v') {


            $exam_id = $_GET['exam_id'];

            $stmt = $conn->prepare('select exam.v from exam where id=?');
            $stmt->execute(array($exam_id));
            $row = $stmt->fetch();
            if ($row['v'] == 0) {
              $stmt = $conn->prepare('UPDATE `exam` SET `v` = 1 WHERE `exam`.`id` = ?');
              $stmt->execute(array($exam_id));
            } else {
              $stmt = $conn->prepare('UPDATE `exam` SET `v` = 0 WHERE `exam`.`id` = ?');
              $stmt->execute(array($exam_id));
            }
            header('location:exam.php');
          }













          include('include/footer.php');
        } else {


          header('location:login.php');
        }
?>