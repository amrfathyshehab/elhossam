<?php
   
   
   $err= 'err1';
   $err1='';    
   $err2='err2';
   $err3='err3';
   
  
 $errors=  array($err,$err1,$err2 ,$err3);
      
      
      foreach($errors as $error){
       
       if(empty($error)){
        
       }else{
       echo $error.'<br>';
       }
      }