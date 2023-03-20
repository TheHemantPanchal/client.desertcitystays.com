<?php 
   // Start the session
   session_start();   
   include '/home/customer/www/client.desertcitystays.com/smoobu/db-connect.php';

   include '/home/customer/www/client.desertcitystays.com/smoobu/functions.php';
   
    
   if(isset($_POST['do_login'])){
      extract($_POST);
      
      if( isset($_POST['email']) && isset($_POST['password'])){
         $resp = login($conn, $email, $password);
         extract($resp[0]);

         if(!empty($resp)){
            $_SESSION["id"] = $id;
            $_SESSION["first_name"] = $first_name;
            $_SESSION["last_name"] = $last_name;
            $_SESSION["email_id"] = $email_id;
            //print_array($_SESSION);
            echo 'success';
         }else{
            echo 'failure';
         }
      }
   }