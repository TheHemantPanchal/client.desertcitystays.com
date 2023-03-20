<?php 

   include '/home/customer/www/client.desertcitystays.com/smoobu/db-connect.php';

   include '/home/customer/www/client.desertcitystays.com/smoobu/functions.php';

   $lanlord_id = 2; 
   $response = get_ll_details($conn, $lanlord_id);

   //var_dump($response);
   print_array($response);