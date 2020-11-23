<?php
    include('include/config.php ');

   session_start();

    if(isset($_SESSION['student_name'])){
        // Delete token 
        $result_token = $conn->prepare('DELETE from student_token where student_name = ? ');
   
        $result_token->execute(array($_SESSION['student_name']));
        
       
       // Destroy session
        session_unset();
        session_destroy();
        header('location: login.php');
     }else{
        header('location: login.php');
     }


    
    exit();
