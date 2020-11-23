<?php
  ob_start();

?>
 
 <style>
   .back1 {

     background-color: #FF5722;
     text-align: center;
   }


/*    
   .video-js .vjs-current-time-display{
    display: block;
}
   
   .video-js .vjs-duration{
    display: block;
}

   .video-js .vjs-control {
    display: block;
} */
   /* .video-js .vjs-time-control {
    display: block;
}

   .video-js .vjs-current-time {
    display: block;
    }

   .video-js .vjs-remaining-time {
    display: none;
    } */

  


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

  if (isset($_SESSION['id'])) {
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

     <center>
       <h1>lessons</h1>
     </center>
     <br><br><br>
     <div class="container">
     <!-- <a class="btn btn-primary" style="margin-bottom: 10px" href="?do=add"> Add new lesson</a><br>
   -->

       <div class='row'>

         <div class="col-lg-4 ">
           <a class="a" href="?do=view&stage=1">
             <div class='back1'>
               <div class='title'> 1 secondary</div>
             
               <h3 style="line-height: 125px;">

                 <?php

                  $stmt = $conn->prepare('select  lesson_id from lesson where stage = 1');
                  $stmt->execute();
                  $row = $stmt->rowCount();

                  echo $row;
                  ?>


               </h3>
             </div>
           </a>
         </div>


         <div class="col-lg-4 ">
           <a class="a" href="?do=view&stage=2">
             <div class='back2'>
               <div class='title'> 2 secondary</div>
               <h3 style="line-height: 125px;">

                 <?php



                  $stmt = $conn->prepare('select  lesson_id from lesson where stage = 2');
                  $stmt->execute();
                  $row = $stmt->rowCount();

                  echo $row;
                  ?>


               </h3>
             </div>
           </a>
         </div>

         <div class="col-lg-4 ">
           <a class="a" href="?do=view&stage=3">
             <div class='back3'>
               <div class='title'> 3 secondary</div>
               <h3 style="line-height: 125px;">

                 <?php

                  $stmt = $conn->prepare('select  lesson_id from lesson where stage = 3');
                  $stmt->execute();
                  $row = $stmt->rowCount();

                  echo $row;
                  ?>


               </h3>
             </div>
           </a>
         </div>

       </div>


     </div>
   <?php
    } elseif ($do == 'add') {

    ?>

     <center>
       <h3>Add new lesson</h3>
     </center><br>
     <div class="container">
       <form method="post" action="?do=insert" enctype="multipart/form-data">


         <div class="form-row">
           <div class="form-group col-md-4">
             <label for="inputEmail4">lesson name</label>

             <input type="text" class="form-control" id="name" name="name" required>
           </div>
           <div class="form-group col-md-8">
             <label for="inputPassword4">lesson description <span>(optional)</span></label>
             <input type="text" class="form-control" name="desc" id="desc">
           </div>

           
           <div class="form-group col-md-4">
             <label for="inputPassword4">choose pdf (optional)</label>
             <input type="file" class="form-control" name="file" id="inputPassword4">
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

           <div class="form-group col-md-8">
             <label for="inputPassword4">Choose video type</label>
             <select id="video_type" class="form-control " name="quiz_video_type" required>
               <option value="" selected disabled> lesson video type </option>
               <option value="1">Normal video</option>
               <option value="2">Interactive video with quiz</option>
            
             </select>
           </div>

           <div class="form-group col-md-4" >
                      
                      <label for="inputPassword4">choose video</label>
                      <input type="file" class="form-control" name="video" id="inputVideo" required>
           </div>

           <!-- <div class="form-group col-md-4" id="targety"> -->

           <!-- <script>
                         
                  var element = document.getElementById('video_type');
                  var videoFile = document.getElementById('inputVideo');
                 
                 
                  var event = new Event('change');
                  videoFile.onchange = function(e) { 
                    alert(videoFile.value);
                    alert(e.target.value);

                    getItems(e.target.value);
  
                                };

                  element.addEventListener('change', function(event) {
                      //getItems(event.target.value);
                  });

                  function getItems(val) {
                    var wrappingDiv = document.getElementById('targety');
                    if(val ==2 && videoFile.value ) {
                      alert("FUCK ME")
                      var htmlContent = `
                      <div >
                      <h1>fuck you </h1>
                      <a class="nav-link" href=".\lessons.php?do=quizVideoPreview"></a>
           </div>


                      `;
                      wrappingDiv.innerHTML = htmlContent;

                    }
                    else if(val ==2) {
                      
                      // var wrappingDiv = document.createElement('div');
                      // var htmlContent = `
                      //   <div>
                      //        <h1>why why</h1>
                      //   </div>


                      // `;
                      // wrappingDiv.innerHTML = htmlContent;

                      
                    }
                  }

                  // //set the value and trigger the event manually
                  // function selectItem(value){
                  //     //select the item without using the dropdown
                  //     element.value = value;
                  //     //trigger the event manually
                  //     element.dispatchEvent(event);
                  // }

                  //using a function with the option you want to choose
                  //selectItem("thredddddddddddddd");
           </script> -->


          
         </div>


         <button type="submit" name="send" style="float:right" class="btn btn-primary" style="width: 100px;"> Add</button>



       </form>
     </div>

   <?php


}  elseif ($do == 'insert') {

  // ahmed
  // HUGE PROBLEM :: I NEED TO MAINTAIN POST DATA FROM PREVIOUS TAB IN THIS TAB AFTER I POST ANY INSTANT QUESTION IN DB AND RETURN BACK HERE

  $files_handle = 0 ; 
  // if(count($_POST) != 0 ){
  // $_SESSION['post_data'] = $_POST ; 
  // }
 

   
  if (! isset($_POST['quiz_video_type'])) {

     $_POST = $_SESSION['post_data'] ; 
     $files_handle = 1 ; 

  }

   

      $videoType = $_POST['quiz_video_type'];

      // interative video type case
      if ($videoType == 2) {
        $name = $_POST['name'];
        $_SESSION['lname'] = $name; // i had to do this 
        $stage = $_POST['stage'];

        if ($files_handle) {
          $instant_number = 1 ; 
          $vname =  $_POST['vname'];
          $tmp =  $_POST['tmp']; 
        }

        else {
        
          $vname = $_FILES['video']['name'];
          $tmp = $_FILES['video']['tmp_name'];
          $pdfname = $_FILES['file']['name'];
         
          $_POST['vname']=  $vname;
          $_POST['tmp']=  $tmp;
          $_POST['pdfname']=  $pdfname;
          $_SESSION['post_data'] = $_POST ; 

          move_uploaded_file($tmp, '../' . $vname . '');
          $instant_number = 1 ; 
        }

   

        ?>
      
                   

     
      <div class="container py-5">
     
    <div class="row">
    <div >
                        <!-- possible expansion -->
                        <!-- <button onclick="AddInstant()" class="btn btn-primary px-4 float-right">Add time instant</button> -->
                        </div>
                        <script>
                        var instant_number = 2 ; 
                        function AddInstant() {
                             var target =  document.getElementById("hope")
                             //alert(target);
                            

                             var htmlPayload =`
            
                            
                        <label for="inputContactNumber">Time instant</label>
                        <input name="${instant_number}" type="number" class="form-control" id="inputContactNumber" placeholder="">
                        <a class="btn btn-primary" style="margin-top:5px" href="?do=instantQuestions&time=${instant_number++}">Add Questions</a><br>
                   
                             `;
                             //alert(instant_number) ;
                              var child = document.createElement('div');
                              child.innerHTML = htmlPayload;
                              child.classList.add("col-sm-3");
                              target.appendChild(child);

                              //target.insertAdjacentHTML('afterend', htmlPayload);
                            }
                        </script>
        <div class="col-md-10 mx-auto">
        <form method="post" action="?do=add_in_video&name=<?php echo $name?>&stage=<?php echo $stage?>" enctype="multipart/form-data">
                
        <h4>Add questions to your desired time instantance: </h4>
                <div id="hope" class="form-group row">
                         
                    <div  class="col-sm-3">
                        <label for="inputContactNumber">Time instant</label>
                        <!-- <input name="<?php echo $instant_number ?>" type="number" class="form-control" id="inputContactNumber" placeholder="in seconds" required> -->
                        <input name="time_instant" type="number" class="form-control" id="inputContactNumber" placeholder="in seconds" required>     
                          <?php // if($files_handle){
                              ?>
                              <!-- <a class="btn btn-success" style="margin-top:5px" href="?do=instantQuestions&time=1">Add Questions</a><br> -->
                              <?php
                         // }
                        //  else {
                            ?>
                            <!-- <a class="btn btn-primary" style="margin-top:5px" href="?do=instantQuestions&time=1">Add Questions</a><br> -->
                            <?php
                          //}
                          ?>
                        

                    </div>
                    
                </div>
                <div class="col-sm-9">
                    <video style="width:600px" controls controlslist="nodownload">

                    <source src="../<?php echo $vname ?>" type="video/mp4">

                    </video>
                    </div>
                 <!-- // new vission 
                 

                 // new vission  -->

                 <div class="form-row">
              <div style="margin-top:1rem" class="form-group col-md-12">
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
            <!-- <button type="submit" class="btn btn-primary">insert</button> -->

                
                <button type="submit" class="btn btn-primary" >Add the question </button>
                <!-- <button  style="float:right" class="btn btn-primary" style="width: 100px;"> </button> -->
                <!-- <a  class="btn btn-primary px-4 float-right"  href="?do=interactive_added"> Add the lesson</a><br> -->
            </form>
        </div>
    </div>


 
         <!-- <div class="form-row"> -->
    
         
        


         
<!-- 
           <div class="form-group col-md-8">
             <label for="inputPassword4">Choose video type</label>
             <select id="video_type" class="form-control " name="quiz_video_type" required>
               <option value="" selected disabled> lesson video type </option>
               <option value="1">Normal video</option>
               <option value="2">Interactive video with quiz</option>
            
             </select>
           </div> -->
    
         <!-- </div> -->


         



       </form>

     
        </div>


<?php 

// i still need to add a record for this lesson even though it has the interactive video property

      }

      else {

        $name = $_POST['name'];
       
        $desc = $_POST['desc'];
        $vname = $_FILES['video']['name'];
        $tmp = $_FILES['video']['tmp_name'];
        $pdfname = $_FILES['file']['name'];
        $pdftmp = $_FILES['file']['tmp_name'];
        $stage = $_POST['stage'];
  
        move_uploaded_file($tmp, '../' . $vname . '');
        move_uploaded_file($pdftmp, '../' . $pdfname . '');
  
        $stmt = $conn->prepare('insert into lesson (`lesson_name`, `lesson_desc`, `video`,`pdf`, `stage`)
                                   VALUES (:lesson_name,:lesson_desc,:video,:pdf, :stage) ');
  
        $stmt->execute(array('lesson_name' => $name, 'lesson_desc' => $desc, 'video' => $vname, 'pdf' => $pdfname, 'stage' => $stage));
  
      ?>
  
       <div class="container" style="margin-top: 100px">
         <div class="alert alert-success alert-dismissible fade show" role="alert">
           <center> New lesson added successfully</center>
           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>
  
       </div>
  
       
  
     <?php
     header("Refresh:3; url=lessons.php");
      }

      

    } 

    elseif ($do == 'instantQuestions') {

      // logic ::insert instant , its qusetions in db 

      $instant_number = $_GET['time'] ; 



      ?>
    
    <br><br>
        <div class="container col-md-6">
          <form method="post" action="?do=insert_instant_questions&time=<?php echo $instant_number?>">
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

        
    elseif ($do == 'insert_instant_questions') {

      // logic : i need q_id	instant_number	question	answer	student_answer	a1	a2	a3	lesson_id
      $instant_number = $_GET['time'] ; 
      $lesson_name       =  $_SESSION['lname']; // i had to do this 
      $question    = $_POST['question'];
      $answer      = $_POST['answer'];
      $a1     = $_POST['a1'];
      $a2     = $_POST['a2'];
      $a3     = $_POST['a3'];
  


      $stmt = $conn->prepare('INSERT INTO `in_video_questions` (`instant_number`,`question`, `answer`,`a1`,`a2`,`a3`,`lesson_name`)
                                            
                             VALUES(
                          
                               :instant_number , :question,  :answer  ,  :a1  ,:a2  ,:a3  ,:lesson_name
                                  
                                  )');


      $stmt->execute(array(
        'instant_number' => $instant_number , 
        'question' => $question,
        'answer' => $answer,
        'a1' => $a1,
        'a2' => $a2,
        'a3' => $a3,
        'lesson_name' => $lesson_name
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
 header("Refresh:3; url=lessons.php?do=insert"); // polish

      
      }

      
    
    elseif ($do == 'add_in_video') {
        // logic :: get inserted time instants values , post them in db ,  
        // i need : lesson_name	stage	 instant_number time_instant	question_id
        //
      $lesson_name =$_GET['name'] ; 
      $stage =$_GET['stage'] ;
      // logic : i need q_id	instant_number	question	answer	student_answer	a1	a2	a3	lesson_id
      //$instant_number = $_GET['time'] ; 
      $time_instant = $_POST['time_instant'] ; 
      // $lesson_name       =  $_SESSION['lname']; // i had to do this 
      $question    = $_POST['question'];
      $answer      = $_POST['answer'];
      $a1     = $_POST['a1'];
      $a2     = $_POST['a2'];
      $a3     = $_POST['a3'];
      // $question_id =  ; 

      
      //old
      // foreach ($_POST   as $key=>$value ) {
      //   $stmt = $conn->prepare('insert into in_video_quiz (`lesson_name`, `stage`,`instant_number`, `time_instant`)
      //   VALUES (:lesson_name,:stage,:instant_number,:time_instant) ');
      //   $stmt->execute(array('lesson_name' => $lesson_name, 'stage' => $stage,'instant_number' => $key , 'time_instant' => $value));
      // }

      //new 

      // $stmt = $conn->prepare('insert into in_video_quiz (`lesson_name`, `stage`,`instant_number`, `time_instant`)
      //   VALUES (:lesson_name,:stage,:instant_number,:time_instant) ');
      //   $stmt->execute(array('lesson_name' => $lesson_name, 'stage' => $stage,'instant_number' => $key , 'time_instant' => $value));

              $stmt = $conn->prepare('INSERT INTO `in_video_questions` (`time_instant`, `question`, `answer`,`a1`,`a2`,`a3`,`lesson_name`,`stage`)
                                                  
              VALUES(
          
                 :time_instant , :question,  :answer  ,  :a1  ,:a2  ,:a3  ,:lesson_name,:stage
                  
                  )');


      $stmt->execute(array(
      // 'instant_number' => $instant_number , 
      'time_instant' => $time_instant , 
      'question' => $question,
      'answer' => $answer,
      'a1' => $a1,
      'a2' => $a2,
      'a3' => $a3,
      'lesson_name' => $lesson_name,
      'stage' => $stage
      ));



        // i still need to add the lesson record "if it doesn't already exist !" even though it has the interactive video preperty set 
        //polish 
        $stmt = $conn->prepare('select lesson_name from lesson where lesson_name = ?');
        $stmt->execute(array($lesson_name));
        if($stmt->rowCount() == 0){
         // insert
        $stmt = $conn->prepare('insert into lesson (`lesson_name`, `lesson_desc`, `video`,`pdf`, `stage`)
        VALUES (:lesson_name,:lesson_desc,:video,:pdf, :stage) ');

        $stmt->execute(array('lesson_name' => $lesson_name, 'lesson_desc' => $_SESSION['post_data']['desc'], 'video' => $_SESSION['post_data']['vname'], 'pdf' => $_SESSION['post_data']['pdfname'], 'stage' => $stage));

        } 
        else {
        
          // do nothing 
        }

        
?>
<div class="container" style="margin-top: 100px">
<div class="alert alert-success alert-dismissible fade show" role="alert">
<center> New Question added successfully</center>
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
</div>
<?php
    header("Refresh:3; url=lessons.php?do=insert");   
    ob_end_flush();  
    
 
    }

    
    elseif ($do == 'view') {
    ?>

     <div class="container">

    
     
       <h1> </h1>
   
       
       <?php
        $stage = $_GET['stage'];

        $stmt = $conn->prepare('select * from lesson where stage =?');
        $stmt->execute(array($stage));
        $rows = $stmt->fetchAll();
        echo '<center><h3>All lessons for ' . $stage . ' secondary </h3></center><br><br>';

        ?>
        <a class="btn btn-primary" style=" width : 12rem ; margin-bottom: 10px" href="?do=add"> Add new lesson</a><br>
        <table class="table table-striped">

          <thead>
            <tr class="thead-dark">

              <!-- <th scope="col">id</th> -->
              <th scope="col">Lesson</th>
              <th scope="col">Control</th>
              
            </tr>
          </thead>
        <?php
        foreach ($rows as $row) {

        ?>

         

         <tbody>
            <tr>
              <!-- <td> <?php echo $row['lesson_id']; ?> </td> -->
              <td>
              <a class="aa" href="?do=watch&id=<?php echo $row['lesson_id'] ?>"> <?php echo $row['lesson_name'] ?> >> </a>
              </td>
              <td>
              <a type="button" class="btn btn-danger btn-sm" href="?do=delete&id=<?php echo $row['lesson_id'] ?>&stage=<?php echo $row['stage'] ?>">Delete</a>
                  <a type="button" class="btn btn-primary btn-sm" href="?do=edit&id=<?php echo $row['lesson_id'] ?>&stage=<?php echo $row['stage'] ?>">Edit</a>
        
              </td>
            </tr>
          </tbody>
        
        
       <?php
        }
        ?>
         </table>
         <?php
          

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
           <br>
           <!-- so the teacher can see what he made too  -->
           <video lesson_name="<?php echo $row['lesson_name']?>" stage="<?php echo $row['stage']?>" id="myPlayer" class="video-js vjs-big-play-centered" >
                        <source src="../<?php echo $row['video'] ?>"type="video/mp4">
                        
            </video>


           <?php
            if (!empty($row['lesson_desc'])) {

              echo '<br><br><h6>Description<h6>' . '<span style="color:gray">' . $row['lesson_desc'] . '</span><br><br><br><br>';
            }

            ?>
         </center>
         <center>
           <div class="container">
             <a class="pdf-download" href=<?php echo "./../" . $row['pdf'] ?> download>

               <?php echo $row['pdf'] ? "Download the pdf :" . $row['pdf'] : ""; ?></a>
           </div>
         </center>
         <div style="height:20px;"> </div>

       </div>

   <?php
      // edit&delete lessons 
      }elseif ($do == "edit") {
       

        ?>

     <center>
       <h3>Edit lesson</h3>
     </center><br>
     <div class="container">
       <form method="post" action="?do=update&id=<?php echo $_GET['id'] ?>" enctype="multipart/form-data">


         <div class="form-row">
           <div class="form-group col-md-4">
             <label for="inputEmail4">lesson name</label>

             <input type="text" class="form-control" id="name" name="name">
           </div>
           <div class="form-group col-md-8">
             <label for="inputPassword4">lesson description <span>(optional)</span></label>
             <input type="text" class="form-control" name="desc" id="desc">
           </div>

           <div class="form-group col-md-4">
             <label for="inputPassword4">choose video</label>
             <input type="file" class="form-control" name="video" id="inputPassword4">
           </div>
           <div class="form-group col-md-4">
             <label for="inputPassword4">choose pdf</label>
             <input type="file" class="form-control" name="file" id="inputPassword4">
           </div>


           <div class="form-group col-md-8">
             <label for="inputPassword4">Stage</label>
             <select class="form-control " name="stage">
               <option value="" selected disabled> student stage </option>
               <option value="1">1</option>
               <option value="2">2</option>
               <option value="3">3</option>

             </select>
           </div>


         </div>


         <button type="submit" style="float:right" class="btn btn-primary" style="width: 100px;"> Edit</button>



       </form>
     </div>

   <?php


      }
      elseif ($do == "update") {
        //$_FILES["video"]["name"]
        // Check if file already exists
        $video = $_FILES["video"]["name"];
        $pdf = $_FILES["file"]["name"];
        if (file_exists($video) || file_exists($pdf)) {  //it's already set to required in html so this condition won't be fulfilled and is here for further decisions
          echo "Sorry, file already exists.";
          
        }
        else {

          function str_replace_last( $search , $replace , $str ) {
            if( ( $pos = strrpos( $str , $search ) ) !== false ) {
                $search_length  =1 ;
                $str    = substr_replace( $str , $replace , $pos , $search_length );
            }
            return $str;
        }



          // polish 
          $queryString = 'UPDATE  lesson SET ' ; 
          //foreach ($_POST   as $key=>$value ) {

          if(isset($_POST['name'])){
            $queryString .= sprintf(' lesson_name= "%s" ,' , $_POST['name']);


          }
          if(isset($_POST['desc']) && $_POST['desc'] != "" ){
            $queryString .= sprintf(' lesson_desc= "%s" , ' , $_POST['desc']); 

          }
          if(isset($_POST['stage'])){
            $queryString .= sprintf(' stage= "%s" , ' , $_POST['stage']); 

          }
          if($video != ""){
            $queryString .= sprintf(' video= "%s" , ' , $video ); 

          }
          if($pdf != ""){
            $queryString .= sprintf(' pdf = "%s" , ' , $pdf ); 

          }
          $queryString .= sprintf(' where lesson_id = %u ' , $_GET['id']); 


          
          $queryString = str_replace_last("," , "", $queryString) ;

          //$stmt = $conn->prepare('UPDATE `lesson` SET lesson_name=?,lesson_desc=?,video=?,pdf=?,stage=? where lesson_id=?');
          $stmt = $conn->prepare($queryString);
          $stmt->execute(); 
         
          //$stmt->execute(array($_POST['name'], $_POST['desc'],$video,$pdf,$_POST['stage'],$_GET['id']));

        }
        
        ?>
        <div class="container" style="margin-top: 50px">
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <center>Lesson edited successfully</center>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
  
        </div>
  
      <?php
  
        // polish header("Refresh:3; url=lessons.php?do=view&stage=".$_POST['stage']);

      }
      elseif ($do == "delete") {
        $id = $_SESSION['id'];


        $stmt = $conn->prepare('SELECT rank FROM `teacher` WHERE `teacher`.`id` = ?');
  
  
        $stmt->execute(array($id));
  
        $rank = $stmt->fetch();
  
  
        if ($rank['rank'] == 1 or $rank['rank'] == 2) {
          $stmt = $conn->prepare('DELETE FROM `lesson` WHERE `lesson`.`lesson_id` = ? AND stage = ?');
  
  
          $stmt->execute(array($_GET['id'] , $_GET['stage']));

      }
      ?>
      <div class="container" style="margin-top: 50px">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <center>Lesson deleted successfully</center>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

      </div>
    <?php
     //polish header("Refresh:3; url=lessons.php?do=view&stage=".$_GET['stage']);
      //header('location:lessons.php?do=view&stage='.$_GET['stage']);
    }
     
      
      else {

        header('location:dashboard.php');
      }





      include('include/footer.php');
    } else {


      header('location:login.php');
    }
    ?>