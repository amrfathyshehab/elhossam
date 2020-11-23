<?php


$stmt=$conn->prepare('SELECT rank from teacher where id=?');
        $stmt->execute(array($_SESSION['id']));
        $rank=$stmt->fetch();



?>
<style>
  .nav-link,.navbar-brand{
    
    color: white;
    
    
  }
  
  
  .name:hover{
    
     color: white;
    
    
  }
  
  .dropdown-menu{
    
    left:25px;
    
  }
  
  
  a:hover{
    
     color: white;
    
    
  }
</style>

<nav class="navbar navbar-expand-lg "  style="background-color: #5564bf">
  <a href="dashboard.php"><h4 class="navbar-brand" href="#">El-Hossam</h4></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <img class="navbar-toggler-icon" src="toggler.png">
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item ">
        <a class="nav-link" href=".\assistants.php" <?php if($rank['rank']==3){echo 'hidden';} ?> >Assistants </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href=".\students.php">Students</a>
      </li>
       <li class="nav-item">
        <a class="nav-link" href=".\center.php">Center</a>
      </li>
       
       <li class="nav-item">
        <a class="nav-link" href=".\exam.php">Exam</a>
      </li>
      
   
 <li class="nav-item active">
       <a class="nav-link" href="message.php">message
       
    <?php
                           
                           $stmt=$conn->prepare('select * from ask where ask.read = 0 ');
                           $stmt->execute();
                           $row=$stmt->rowCount();
                           
                          
                        
                               if($row>0){
       
      
       ?>
       
       <span style="    padding: 2px;
    background-color: #ff1201c9;
    border-radius: 32%;"><?php echo $row?></span>
       <?php
       
       }else{}
       
       ?>    
       </a>
 </li>
 
 
 
 <li class="nav-item">
        <a class="nav-link" href=".\lessons.php">Lessons</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href=".\students_results.php">Students_Results</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href=".\white_list.php">Cutom_classes</a>
      </li>

      <!-- <li class="nav-item">
        <a class="nav-link" href=".\in_video.php">In-Video_Quiz</a>
      </li> -->
 
 
    </ul>
   
   <div class="nav-item dropdown">
        <p class="nav-link dropdown-toggle"  style="margin-right: 25px"  id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?php
        
        $stmt=$conn->prepare('SELECT name from teacher where id=?');
        $stmt->execute(array($_SESSION['id']));
        $row=$stmt->fetch();
        echo ucWords($row['name'],' '); ?>
        </p>
        <div class="dropdown-menu"   aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="dashboard.php?do=edit&id=<?php echo  $_SESSION['id']?>" style="color:black">Edit profile</a>
          <hr>
          <a class="dropdown-item" href="logout.php" style="color:red">logout</a>
          
        </div>
      </div>
   
  

    
  </div>
</nav>