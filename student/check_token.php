<?php
include('include/config.php ');


if (isset($_SESSION['student_name'])) {
    $result_token = $conn->prepare('SELECT token FROM student_token where student_name=?');
   
        
    $row = $result_token->rowCount();
    if ($row > 0) {
 
    $result_token->execute(array($_SESSION['student_name']));
    $row = $result_token->fetch();
    $token = $row['token']; 

    if($_SESSION['token'] != $token){
      session_destroy();
      header('Location: login.php');
    }
  }
}