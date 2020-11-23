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

</style>

<?php

session_start();
if (isset($_SESSION['student_id'])) {

  include('include/config.php');
  include('include/navbar.php');
  include('include/header.php');

  $do = '';


  if (isset($_GET['do'])) {

    $do = $_GET['do'];
  } else {


    $do = 'result';
  }


  if ($do == 'result') {

?>






    <div class="container">
      <center>
        <h4>Result & model answer</h4>
        <hr><br><br>
      </center>
      <table class="table table-striped">

        <thead>
          <tr class="thead-dark">

            <th scope="col">Exam </th>
            <th scope="col">Percentage </th>
            <th scope="col">Mark</th>
            <th scope="col">Model answer</th>
          </tr>
        </thead>
        <tbody>

          <?php
          //getting all exams results for THIS student
          $stmt = $conn->prepare('select * from student_result where student_id=? ');
          $stmt->execute(array($_SESSION['student_id']));
          $rows = $stmt->fetchAll();



          foreach ($rows as $row) { ?>
            <tr>

              <td>
                <?php
                // manually fetching the exam_name from its id through the fetched student exams results
                $stmt = $conn->prepare('select exam_name from exam where id=? ');
                $stmt->execute(array($row['exam_id']));
                $name = $stmt->fetch();
                echo $name['exam_name'];


                ?>
              </td>



              <td> <?php echo $row['percentage'] . ' %'; ?></td>
              <td> <?php echo $row['outof']; ?></td>
              <td>
                <a href="?do=show&id=<?php echo $row['exam_id']; ?>" class="btn btn-primary btn-sm"> show model answer</a>

              </td>

            </tr>
          <?php
          } ?>
        </tbody>
      </table>




    </div>

  <?php






  } elseif ($do == 'show') {




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
<h2>Exam with model answer</h2><br>
<h4 style="color:gray">Exam : <?php echo $show['exam_name'] ?></h4>
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
SELECT distinct q_id , question FROM exam_images 

where exam_id=?



');
// $id = $_GET['id'];
$stmt->execute(array($id));
$show = $stmt->fetchAll();




foreach ($show as $sho) {
echo '<h4 style="display:inline">' . $sho['question'] . ' ? </h4><br><br>';

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




  } else {

    header('location:result.php');
  }





  include('include/footer.php');
} else {


  header('location:login.php');
}




?>