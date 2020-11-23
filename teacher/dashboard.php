<?php

     session_start();
   
    if(isset($_SESSION['id'])){
        
       
    include ('include/config.php');
   
  
    ?>
   
    
    
    <html>
        <head>
          <?php   include ('include/header.php'); ?>
            
 <style>
 a .edit:hover{
   
   
   color: red;
   
   
   }  
  .back{
   text-align: center;
   background-color: #343a40;
   border: 1px solid #000000bd;
   color: white;
   margin-top: 20px;
   padding-bottom: 30px;
   
  }
  
  a {
   
   text-decoration:  none !important;
  
   
  }
  
  
  p{
   
   
  padding-top: 10px;
  padding-bottom :10px;
  }
  
  .trig:nth-child(even){
   
   background-color: #454d55;
   
  }
 </style>          
            
        </head>
        <body>
             <?php   include ('include/navbar.php'); ?>
             
             <?php
             
        
        $do='';
        
        
        if(isset($_GET['do'])){
         
         $do= $_GET['do'];
         
        }else{
        $do='dashboard';
        
        }
        
        
        
        
        
        if($do=='edit'){
         
         $stmt=$conn->prepare('SELECT * FROM teacher where id=?');
         $stmt->execute(array($_SESSION['id']));
         $row=$stmt->fetch();
        ?>
         <div class="container col-md-6">
       <center>   <h2 style="line-height: 80px;">Edit profile</h2><br></center>
         <form method="post" action="?do=update">
  <div class="form-row">
    <div class="col-md-12 mb-3">
      <label for="validationDefault01">Name</label>
      <input type="text" class="form-control" id="validationDefault01" name="name" value="<?php echo $row['name']?>" required>
    </div>
    <div class="col-md-12 mb-3">
      <label for="validationDefault02">username</label>
      <input type="text" class="form-control" id="validationDefault02" name="username" value="<?php echo $row['username']?>" required>
    </div>
  </div>
  <div class="form-row">
    <div class="col-md-12 mb-3">
      <label for="validationDefault03">password</label>
      <input type="text" class="form-control"  placeholder="keep it blank if you don't want to change it " name="new_pass"
             id="validationDefault03" >
       <input type="text" class="form-control" value="<?php echo $row['password']?>"  name="old_pass" id="validationDefault03" hidden>
    </div>
    
    
  </div>
  
  <button class="btn btn-primary" type="submit"> update</button>
</form>
</div>
<?php
        }elseif($do=='update'){
         
         $id        =      $_SESSION['id'];
         $name      =      $_POST['name'];
         $username  =      $_POST['username'];
         $old_pass  =      $_POST['old_pass'];
         $new_pass  =      $_POST['new_pass'];
         $password;
         
          if(empty($new_pass)){ $password  =  $old_pass; }
           else{$password  =  $new_pass; }
    
    
    if(strlen($password) <= 8 ){
     
    ?>
    
    <div class="container" style="margin-top: 100px">
     <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <center>  password must  be more than <strong>8 characters . </strong></center>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
    <center> <p style="color: gray"> you will be redirected to edit page after 5 seconds </p></center>
     </div>
   
     <?php header("Refresh:5; url=?do=edit");
    }else{
        
        $stmt=$conn->prepare('UPDATE `teacher` SET name=? , username=? , password=? WHERE `teacher`.`id` = ?;');
        $stmt->execute(array($name,$username,$password,$id));
        
        
        ?>
        
        <div class="container" style="margin-top: 100px">
     <div class="alert alert-success alert-dismissible fade show" role="alert">
      <center>  successfully process</center>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
    <center> <p style="color: gray"> you will be redirected to dashboard page after 3 seconds </p></center>
     </div>
        
        
        
   <?php
   header("Refresh:5; url=dashboard.php");
    }
         
         
         
         }else{
             ?>
            <center><h2 style="line-height: 100px">Dashboard</h2></center>
            
            
            <div class="container">
      <div class="row">
      
        
        <div class="col-lg-4"
             
              <?php   
            
         $stmt=$conn->prepare(' select rank from teacher where id=?');
                    $stmt->execute(array($_SESSION['id']));
                    $row=$stmt->fetch();
                    
                   if($row['rank']>2){
                    
                    echo 'hidden';
                    
                   };
                    ?> ><a href='assistants.php'>
                  
               <div class="back " h>
                 <p style='background-color:#2056af' >Total Team leader and Assistants  </p>
                 <h1>
                  <?php
                    $stmt=$conn->prepare(' select id from teacher where rank!=1');
                    $stmt->execute();
                    echo$row=$stmt->rowCount();
                  
                  
                  ?>
                  
                 </h1>
               </div>
            </a>   
         </div>
        
        
        <div class="col-lg-4">
                 <a href='students.php'>
               <div class="back ">
                 <p style='background-color:#2056af' >Total Students</p>
                <h1><?php
                    $stmt=$conn->prepare(' select student_id from student ');
                    $stmt->execute();
                    echo$row=$stmt->rowCount();
                  
                  
                  ?></h1>
               </div>
            </a>   
         </div>
                  
        
        
        <div class="col-lg-4">
                 <a href='center.php'>
               <div class="back ">
                 <p style='background-color:#2056af' >Total Time</p>
                  <h1><?php
                    $stmt=$conn->prepare(' select id from center ');
                    $stmt->execute();
                    echo$row=$stmt->rowCount();
                  
                  
                  ?></h1>
               </div>
            </a>   
         </div>
           <div class="col-lg-12">
                 <a href='message.php'>
               <div class="back ">
                 <p style='background-color:#2056af' >Total message</p>
                  <h1><?php
                    $stmt=$conn->prepare(' select ask_id from ask where ask.read= 0 ');
                    $stmt->execute();
                    echo$row=$stmt->rowCount();
                  
                  
                  ?></h1>
               </div>
            </a>   
         </div>        
              
          
             <?php  }include ('include/footer.php'); ?>
        </body>
    </html>
    <?php
        
        
    
    
  }  else{
        
        header('location:login.php');
        
        
    }
    
    