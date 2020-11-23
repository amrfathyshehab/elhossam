<?php

session_start();
        include ('include/config.php');
         $_SESSION['error']='';
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
       $username = $_POST['username'];
       $password = $_POST['password'];
       
       
       $stmt=$conn->prepare('SELECT
                               id, name ,username , password 
                           FROM
                               `teacher`
                           WHERE
                               username= ?
                           AND
                               password = ? ');
       $stmt->execute(array($username,$password));
       $row=$stmt->fetch();
       $count=$stmt->rowCount();
       
      if($count > 0){
        
      $_SESSION['id']=$row['id'];
      $_SESSION['username']=$username;
       $_SESSION['name']=$row['name'];
        header('Location:dashboard.php');
      }else{
         
         
         $_SESSION['error']="invalid username or password";
                 
      }
      
      
        
      }
        ?>
       
        
        
   <!DOCTYPE HTML>
    <html>
                        
         <head>
                     <?php
                     include ('include/header.php');
                     
                     ?>
                  
                  <title>El Hossam login</title>
         </head>
                        
                        
        <body>
              
<nav class="navbar" style="background-color: #5564bf">
  <h5 style="color:white">EL Hossam</h5>
</nav>
               
            <div style="margin-top: 100px">
             <center>
               <div class="container ">
                  <h2 >Login</h2><br>
                   <form  method="post">
                        
                     <div class="row col-md-6 mb-3">
                       <input type="text" class="form-control" id="validationCustom01" name="username" placeholder="username" required>
                     </div>
                        
                         
                     <div class="row col-md-6 mb-3">
                         <input type="password" class="form-control" id="validationCustom01" name="password" placeholder="password" required>
                     </div>
                     
                     <div class="row col-md-6 mb-3">                      
                        <input class="btn  btn-block btn-primary" type="submit"  style="color: white" value="Submit">  
                     </div>
                       
                     <span style="color:red;margin-left: auto;margin-right: auto;"><?php echo $_SESSION['error']?></span>
                        
                   </form>             
                                
                         </div>           
                   </center>
            </div>
                     <?php
                     include ('include/footer.php');
                     
                     ?>
                                             
        </body>
    </html>