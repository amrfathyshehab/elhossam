<?php

/*
include('include/config.php');
include('include/header.php');

?>
<?php

$question = 'formula of carbon dioxide';
$answer ='CO2';
$center=14;
$stmt=$conn->prepare('INSERT INTO `question` (`question`, `answer`)
                                                  
                                   VALUES(
                                
                                      :question,  :answer 
                                        
                                        )');
             
             
             $stmt->execute(array( 'question'=>$question,'answer'=>$answer));

$stmt=$conn->prepare('Select q_id from question
                        
                        where question=? AND  answer=?
                        ');
  
  $stmt->execute(array($question,$answer));
  
  
   $row=$stmt->fetchColumn();
  $row;
  
  $answer1='FANA';
  $answer2='K';
  $answer3='Mg';
  $stmt=$conn->prepare('INSERT INTO `answer` (`answer1`, `answer2`, `answer3`,`question`)
                                                  
                                   VALUES(
                                
                                     :answer1, :answer2, :answer3 , :question
                                        
                                        )');
             
             
             $stmt->execute(array(  "answer1"=>$answer1 ,"answer2"=>$answer2 ,"answer3"=>$answer3,"question"=>$row ));
  
  ?>


<?php
$stmt=$conn->prepare('SELECT
                             question.question AS q , question.answer AS a,
                             answer.answer1 AS a1 ,answer.answer2 AS a2 ,answer.answer3 AS a3
                       
                    FROM 
                             answer
                    INNER JOIN
                             question on answer.question=question.q_id');

$stmt->execute();
$show=$stmt->fetchAll();



  foreach($show as $sho){
    echo $sho['q'].' ?<br>';
    $one="<span style='color : red' >".$sho['a']."</span>";
      $two="<span  >".$sho['a1']."</span>";
        $three="<span  >".$sho['a2']."</span>";
          $four="<span  >".$sho['a3']."</span>";
    
  $my_array = array($one,$two,$three, $four);

shuffle($my_array);
echo $strs =  implode(" ",$my_array);



 echo '<br><br><br>';
 }
*/?>


















<!--

<!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>javascript timer-countDown</title>
     <style media="screen">
       div {
     /* text-align: center; */
     border: 5px solid #004853;
     display:inline;
     padding: 5px;
     color: #004853;
     font-family: Verdana, sans-serif, Arial;
     font-size: 40px;
     font-weight: bold;
     text-decoration: none;
 }
 
 body {
   padding: 20px;
   text-align: center;
 }
     </style>
   </head>
   <body>
     <div id="ten-countdown"></div>
   </body>
   <script>
     function countdown( elementName, minutes, seconds )
 {
     var element, endTime, hours, mins, msLeft, time;
 
     function twoDigits( n )
     {
         return (n <= 9 ? "0" + n : n);
     }
 
     function updateTimer()
     {
         msLeft = endTime - (+new Date);
         if ( msLeft < 1000 ) {
             element.innerHTML = "Time is up!";
         } else {
             time = new Date( msLeft );
             hours = time.getUTCHours();
             mins = time.getUTCMinutes();
             element.innerHTML = (hours ? hours + ':' + twoDigits( mins ) : mins) + ':' + twoDigits( time.getUTCSeconds() );
             setTimeout( updateTimer, time.getUTCMilliseconds() + 500 );
         }
     }
 
     element = document.getElementById( elementName );
     endTime = (+new Date) + 1000 * (60*minutes + seconds) + 500;
     updateTimer();
 }
 
 countdown( "ten-countdown", 1, 0 );
 
   </script>
 </html>-->

<?php

include('include/config.php');
include('include/header.php');

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
  <tbody>
  <?php
    $center_id=19;
         $stmt=$conn->prepare('SELECT *  from student_result ');
         $stmt->execute();
         $rows=$stmt->fetchAll();
          $stmt=$conn->prepare('SELECT * from exam ');
         $stmt->execute();
         $finds=$stmt->fetchAll();
         
         
        
            
           
          foreach($rows as $row ){
            
            echo  $row['student_id'];
                        
          }
         foreach( $finds as $find){
            
            echo  $find['exam_name'];
                        
            
          }
        
                
                 ?>
        </tbody>
</table>
           
          
          
          
         </div>
         











?>