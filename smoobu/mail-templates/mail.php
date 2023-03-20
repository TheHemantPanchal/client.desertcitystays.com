<?php 

   include '/home/customer/www/client.desertcitystays.com/smoobu/db-connect.php';

   include '/home/customer/www/client.desertcitystays.com/smoobu/functions.php';
   
   $booking_details = get_booking_details_for_email($conn);

   send_booking_email_to_landlord($conn, $booking_details);