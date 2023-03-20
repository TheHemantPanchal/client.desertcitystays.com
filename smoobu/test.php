<?php 

   include '/home/customer/www/client.desertcitystays.com/smoobu/db-connect.php';

   include '/home/customer/www/client.desertcitystays.com/smoobu/functions.php';

   // $booking_details_for_ll =  get_ll_id($conn, 1453501);
   // extract($booking_details_for_ll[0]);

   // $user_name = 'me@thehemantpanchal.com';
   // $password = 'C0nsistency!';

   // $resp = login($conn, $user_name, $password);
   
   //var_dump($booking_details_for_ll);

   //var_dump(get_ll_id($conn, 1454815));

   // Define the variables
   $landlord_id = "13, 14";
   $smb_id = "34996477";
   $smb_reference_id = "HMX5NSZYHK";
   $smb_type = "cancellation";
   $smb_arrival = "2023-02-17";
   $smb_departure = "2023-03-01";
   $smb_created_at = "2023-02-16 20:17";
   $smb_modified_at = "2023-02-17 12:26:04";
   $smb_apartment_id = "1564799";
   $smb_apartment_name = "1402 | 1 BR | O2 Tower | JVC";
   $smb_channel_id = "865116";
   $smb_channel_name = "Airbnb";
   $smb_guest_name = "Ahmad Aziz";
   $smb_firstname = "Ahmad";
   $smb_lastname = "Aziz";
   $smb_email = "N/A";
   $smb_phone = "+46738707549";
   $smb_adults = "2";
   $smb_children = "0";
   $smb_check_in = "N/A";
   $smb_check_out = "N/A";
   $smb_notice = "1";
   $smb_assistant_notice = "N/A";
   $smb_price = "5792";
   $smb_price_paid = "No";
   $smb_commission_included = "868.8";
   $smb_prepayment = "N/A";
   $smb_prepayment_paid = "No";
   $smb_deposit = "N/A";
   $smb_deposit_paid = "N/A";
   $smb_language = "1";
   $smb_guest_app_url = "https://guest.smoobu.com/?t=ual164031bc&b=34996477";
   $smb_is_blocked_booking = "";
   $smb_guest_id = "27288880";

   // Build the SQL query with variables
   $sql = "INSERT INTO `booking_details` (
      `lanlord_id`, 
      `smb_id`, 
      `smb_reference-id`, 
      `smb_type`, 
      `smb_arrival`, 
      `smb_departure`, 
      `smb_created-at`, 
      `smb_modifiedAt`, 
      `smb_apartment-id`, 
      `smb_apartment-name`, 
      `smb_channel-id`, 
      `smb_channel-name`, 
      `smb_guest-name`, 
      `smb_firstname`, 
      `smb_lastname`, 
      `smb_email`, 
      `smb_phone`, 
      `smb_adults`, 
      `smb_children`, 
      `smb_check-in`, 
      `smb_check-out`, 
      `smb_notice`, 
      `smb_assistant-notice`, 
      `smb_price`, 
      `smb_price-paid`, 
      `smb_commission-included`, 
      `smb_prepayment`, 
      `smb_prepayment-paid`, 
      `smb_deposit`, 
      `smb_deposit-paid`, 
      `smb_language`, 
      `smb_guest-app-url`, 
      `smb_is-blocked-booking`, 
      `smb_guestId`) VALUES ('$landlord_id', '$smb_id', '$smb_reference_id', '$smb_type', '$smb_arrival', '$smb_departure', '$smb_created_at', '$smb_modified_at', '$smb_apartment_id', '$smb_apartment_name', '$smb_channel_id', '$smb_channel_name', '$smb_guest_name', '$smb_firstname', '$smb_lastname', '$smb_email', '$smb_phone', '$smb_adults', '$smb_children', '$smb_check_in', '$smb_check_out', '$smb_notice', '$smb_assistant_notice', '$smb_price', '$smb_price_paid', '$smb_commission_included', '$smb_prepayment', '$smb_prepayment_paid', '$smb_deposit', '$smb_deposit_paid', '$smb_language', '$smb_guest_app_url', '$smb_is_blocked_booking', '$smb_guest_id')";

   $sql = mysqli_query($conn, $sql);

   var_dump($sql);

   // Run the SQL query
   // assuming you have already set up a database
