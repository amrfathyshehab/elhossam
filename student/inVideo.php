<?php

session_start();
if (isset($_SESSION['student_id'])) {

  include('include/config.php');

//   include('include/header.php');

//   include('include/navbar.php');


  $do = '';

  if (isset($_GET['do'])) {

    $do = $_GET['do'];
  } else {


    $do = 'test';
  }



  if ($do == 'time_instant') {
    //old
    // $stmt = $conn->prepare('SELECT DISTINCT in_video_questions.* , in_video_quiz.time_instant FROM `in_video_questions` INNER JOIN `in_video_quiz` ON in_video_questions.instant_number = in_video_quiz.instant_number WHERE in_video_quiz.lesson_name = ? AND in_video_quiz.stage = ? ');
    // new 
    $stmt = $conn->prepare('SELECT  *  FROM `in_video_questions` WHERE lesson_name = ? AND stage = ? ');
    $stmt->execute(array($_GET['lesson_name'],$_GET['stage']));
    $show = $stmt->fetchAll();

    $htmlContent = "" ;  
    foreach ($show as $sho) {
      //$htmlContent  .=" ". $sho['time_instant']." ".$sho['answer'] ; 
      $htmlContent  .=" ". $sho['time_instant'] ; 
    }

   
  
    //$htmlContent = $show[0]['time_instant']." ".$show[0]['answer'] ; 
    echo $htmlContent ; 

  }

  else if ($do == 'content') {
    // foreach ($_GET as $value) {
    //     echo $value . " "; 
    // }
    // echo $_GET['lid'] ;
    //   exit ;

    // echo '<center><h1>WORKED!</h1></center><br>';
    // echo  $_POST[0] ; 
    // echo  $_GET[0] ; 
    // exit ; 

    // //** this was for testing
    // $exam = 1 ; #$_POST['exam_id'];
    //     // to associate exam_id to current user for quiz examined being removed from exams user list feature 
    //     //$_SESSION['exam_id'] = $_POST['exam_id'];
    //     //

    //     $stmt = $conn->prepare('SELECT * from question 
                              
                             
    //                          where question.exam_id=? order by rand() LIMIT 20
    //                          ');

    //     $stmt->execute(array($exam));
    //     $show = $stmt->fetchAll();
    //     //**
    
    //old
    // $stmt = $conn->prepare('SELECT DISTINCT in_video_questions.* , in_video_quiz.time_instant FROM `in_video_questions` INNER JOIN `in_video_quiz` ON in_video_questions.instant_number = in_video_quiz.instant_number WHERE in_video_quiz.lesson_name = ? AND in_video_quiz.stage = ? ');
    // new 
    $stmt = $conn->prepare('SELECT  *  FROM `in_video_questions` WHERE lesson_name = ? AND stage = ? AND time_instant = ? ');

    $stmt->execute(array($_GET['lesson_name'],$_GET['stage'],$_GET['time_instant']));
    $show = $stmt->fetchAll();
    // echo $_GET['lesson_name'] ; 
    // echo $_GET['stage'];
    


    // foreach ($show as $row) {
    //   // echo $stmt ;
    //   echo $row['lesson_name'] ; 
    // }




        $num = 1;
        $htmlContent = "" ; 
        // echo $show[0]['time_instant'] ; 
        //   exit ; 
        
        foreach ($show as $sho) {
          

            // building the html string content  
           

            $htmlContent.= '<h4> ' . $num++ . ' ) ' . $sho['question'] . ' ?</h4><br>';


 // '<input type="hidden" name="' . $sho['q_id'] . '" value="no" />
          $one =          
            '<div class="radio">
  <label class="Qans" for="' . $sho['answer'] . '"><input type="radio" name="' . $sho['q_id'] . '" value="' . $sho['answer'] . '">
  ' . str_pad($sho['answer'], 6- strlen($sho['answer']), ".", STR_PAD_RIGHT) . '
  </label>
</div>';
$two =          
'<div class="radio">
<label for="' . $sho['a1'] . '"><input type="radio" name="' . $sho['q_id'] . '" value="' . $sho['a1'] . '">
' .str_pad($sho['a1'], 6- strlen($sho['a1']), ".", STR_PAD_RIGHT) . '
</label>
</div>';

$three =          
'<div class="radio">
<label  for="' . $sho['a2'] . '"><input type="radio" name="' . $sho['q_id'] . '" value="' . $sho['a2'] . '">
' . str_pad($sho['a2'],6- strlen($sho['a2']), ".", STR_PAD_RIGHT) . '
</label>
</div>';




    //         '<input type="radio"  name="' . $sho['q_id'] . '" value="' . $sho['answer'] . '">
    // <lable for="' . $sho['answer'] . '" >' . $sho['answer'] . '</lable><br>';

    // $htmlContent.=$one ; 

    //       $two =
    //         '<input type="radio"  name="' . $sho['q_id'] . '" value="' . $sho['a1'] . '">
    //   <lable for="' . $sho['a1'] . '" >' . $sho['a1'] . '</lable><br>';

    // //   $htmlContent.=$two ; 

    //       $three =
    //         '<input type="radio"  name="' . $sho['q_id'] . '" value="' . $sho['a2'] . '">
    //      <lable for="' . $sho['a2'] . '" >' . $sho['a2'] . '</lable><br>';

        //  $htmlContent.=$three ;

        $four =          
'<div class="radio">
<label  for="' . $sho['a3'] . '"><input type="radio" name="' . $sho['q_id'] . '" value="' . $sho['a3'] . '">
' . str_pad($sho['a3'], 6-strlen($sho['a3']), ".", STR_PAD_RIGHT) . '
</label>
</div>';

// $four = '<a type="button" class="btn btn-primary btn-sm" href="#">View Results</a>' ; 

        //   $four =
        //     '<input type="radio"  name="' . $sho['q_id'] . '" value="' . $sho['a3'] . '">
        //    <lable for="' . $sho['a3'] . '" >' . $sho['a3'] . '</lable><br>';

        //    $htmlContent.=$four ;

        //     echo $htmlContent ; 
        //    exit ; 

          $my_array = array($one, $two, $three, $four);

          shuffle($my_array);

        foreach ($my_array as $value) {
             $htmlContent.= $value ; 
        }
        
        //exit ;

         // echo $strs =  implode(" ", $my_array);

}

$htmlContent .= '
<button id="cont" style="color:white ; position:absolute; right:14% ; bottom:5%" name="submit" type="button" class="btn btn-primary">Continue</button>
<button id="sub" style="color:white ; position:absolute; right:5% ; bottom:5%" name="submit" type="button" class="btn btn-primary">Submit</button>
' ; 


echo $htmlContent ; 

}
else {

    echo "yeah!!";
}

}

?>