<?php

    // ini_set('display_errors', '1');
    // ini_set('display_startup_errors', '1');
    // error_reporting(E_ALL);

    function print_array($array){
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }

    function check_empty($value){
        $response = $value;
        if(empty($value)){            
            $response = "N/A";
        }

        if($value == true){
            $response = '1';
        }

        return $response;
    }

    function save_booking_data($conn, $booking_details){

        foreach($booking_details as $booking_detail){
            //print_array($booking_detail);

            extract($booking_detail);
            
            //Get lanlord id
            $lanlord_id = get_ll_id($conn, $smb_apartment_id);
            
            $query = 'INSERT INTO `booking_details`(`lanlord_id`,
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
                                                    `smb_deposit`, `smb_deposit-paid`, 
                                                    `smb_language`, 
                                                    `smb_guest-app-url`, 
                                                    `smb_is-blocked-booking`, 
                                                    `smb_guestId`) VALUES ("'.$lanlord_id.'",
                                                                           "'.$smb_id.'",
                                                                           "'.$smb_reference_id.'",
                                                                           "'.$smb_type.'",
                                                                           "'.$smb_arrival.'",
                                                                           "'.$smb_departure.'",
                                                                           "'.$smb_created_at.'",
                                                                           "'.$smb_modifiedAt.'",
                                                                           "'.$smb_apartment_id.'",
                                                                           "'.$smb_apartment_name.'",
                                                                           "'.$smb_channel_id.'",
                                                                           "'.$smb_channel_name.'",
                                                                           "'.$smb_guest_name.'",
                                                                           "'.$smb_firstname.'",
                                                                           "'.$smb_lastname.'",
                                                                           "'.$smb_email.'",
                                                                           "'.$smb_phone.'",
                                                                           "'.$smb_adults.'",
                                                                           "'.$smb_children.'",
                                                                           "'.$smb_check_in.'",
                                                                           "'.$smb_check_out.'",
                                                                           "'.$smb_notice.'",
                                                                           "'.$smb_assistant_notice.'",
                                                                           "'.$smb_price.'",
                                                                           "'.$smb_price_paid.'",
                                                                           "'.$smb_commission_included.'",
                                                                           "'.$smb_prepayment.'",
                                                                           "'.$smb_prepayment_paid.'",
                                                                           "'.$smb_deposit.'",
                                                                           "'.$smb_deposit_paid.'",
                                                                           "'.$smb_language.'",
                                                                           "'.$smb_guest_app_url.'",
                                                                           "'.$smb_is_blocked_booking.'",
                                                                           "'.$smb_guestId.'")';
            
            //echo .PHP_EOL$query.PHP_EOL;
                                                                                          
            $response = check_existing_booking($conn, $smb_id, $smb_type, $booking_detail);
            
            if(!$response){                   
                $sql = mysqli_query($conn, $query);
                if($sql) {
                    echo 'success'.PHP_EOL;
                }
                else {
                    echo 'failed'.PHP_EOL;
                }
            }else{
                //echo 'Booking details updated'.PHP_EOL;
            }    
        }
    }


    function check_existing_booking($conn, $smb_id, $smb_type, $booking_detail){
        $response = false;
        
        $query = 'SELECT * FROM `booking_details` WHERE `smb_id` = "'.$smb_id.'" AND `status` IN (0, 2) AND `deleted` = 0'; 
        
        $result = mysqli_query($conn, $query);
        
        $db_booking_details = $result -> fetch_all(MYSQLI_ASSOC);
        $db_smb_type = $db_booking_details[0]['smb_type'];
        $db_modified_date = $db_booking_details[0]['smb_modifiedAt'];
        
        $current_smb_type = $booking_detail['smb_type'];
        $current_modified_date = $booking_detail['smb_modifiedAt'];

        //response = true meaning existing booking found else new booking
        if(mysqli_num_rows ($result) > 0){
            $response = true;
            
            if($db_smb_type == $current_smb_type){
                //No need to update

                if($db_modified_date == $current_modified_date){
                    /*
                        No need to update
                        For cancelled bookings for some reason the modified date and time is not saving same as per the API response, 
                        so these are keep on updating every minute and sending an email!
                        That's why I am writing a if condition not update the db if it's reservation is cancelled.
                    */    

                }else{
                    
                    echo 'DB Modified Date'.$db_modified_date.PHP_EOL;
                    echo 'Current Modified Date'.$current_modified_date.PHP_EOL;

                    if($db_smb_type != "cancellation"){
                        //Update the booking and send email
                        update_booking_details($conn, $smb_id, $smb_type, $booking_detail);
                    }    
                }
            }else{
                //Update the booking and send email
                update_booking_details($conn, $smb_id, $smb_type, $booking_detail);
                echo '2'.PHP_EOL;
            }
        }

        return $response;
    }

    function update_booking_details($conn, $smb_id, $smb_type, $booking_detail){
        extract($booking_detail);
        $response = false;
        $dbhost = "127.0.0.1";
        $dbuser = "umlbf54ywusmm";
        $dbpass = "^)j(%x4n5c2j";
        $dbname = "dbreefim3bbssz";
        $mail_status = 0;

        switch ($smb_type) {
            case 'cancellation':
                // Updated ...
                $response = update_cancellation_booking($conn, $smb_id);
                break;

            case 'modification of booking':
                // code...
                $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
                
                $lanlord_id = get_ll_id($conn, $smb_apartment_id);
                
                $query = 'UPDATE `booking_details` 
                            SET `lanlord_id` = "'.$lanlord_id.'",
                              `smb_id` = "'.$smb_id.'",
                              `smb_type`= "'.$smb_type.'",
                              `smb_arrival`= "'.$smb_arrival.'",
                              `smb_departure`= "'.$smb_departure.'",
                              `smb_modifiedAt`= "'.$smb_modifiedAt.'",
                              `smb_apartment-id`= "'.$smb_apartment_id.'",
                              `smb_apartment-name`= "'.$smb_apartment_name.'",
                              `smb_guest-name`= "'.$smb_guest_name.'",
                              `smb_firstname`= "'.$smb_firstname.'",
                              `smb_lastname`= "'.$smb_lastname.'",
                              `smb_email`= "'.$smb_email.'",
                              `smb_phone`= "'.$smb_phone.'",
                              `smb_adults`= "'.$smb_adults.'",
                              `smb_children`= "'.$smb_children.'",
                              `smb_check-in`= "'.$smb_check_in.'",
                              `smb_check-out`= "'.$smb_check_out.'",
                              `smb_notice`= "'.$smb_notice.'",
                              `smb_assistant-notice`= "'.$smb_assistant_notice.'",
                              `smb_price`= "'.$smb_price.'",
                              `smb_price-paid`= "'.$smb_price_paid.'",
                              `smb_commission-included`= "'.$smb_commission_included.'",
                              `smb_prepayment`= "'.$smb_prepayment.'",
                              `smb_prepayment-paid`= "'.$smb_prepayment_paid.'",
                              `smb_deposit`= "'.$smb_deposit.'",
                              `smb_deposit-paid`= "'.$smb_deposit_paid.'",
                              `smb_language`= "'.$smb_language.'",
                              `smb_guest-app-url`= "'.$smb_guest_app_url.'",
                              `smb_is-blocked-booking`= "'.$smb_is_blocked_booking.'",
                              `smb_guestId`= "'.$smb_guestId.'",
                              `mail_status`= "'.$mail_status.'"
                              WHERE `smb_id` = "'.$smb_id.'" ';
            
                if ($conn->query($query) === TRUE) {
                        $response = true;
                    }   
                break;

            //reservation
            default:
                // code...
                break;
        
            return $response;    
        }

    }
    function send_email($message, $to, $subject, $i){

        $response = false;
        //Disabled the emails to Landloards by overwriting it
        $to = "me@thehemantpanchal.com";
        
        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        
        //Adding 1 copy for DCS only, in the case of multiple landlords it was sending more then 1 copy, because of this it will send only 1
        if($i == 0){
            $headers .= 'From: Desert City Stays<no-reply@desertcitystays.com>' . "\r\n";
            $headers .= "BCC: booking@desertcitystays.com, me@thehemantpanchal.com";
        }
        if(mail($to, $subject, $message, $headers)){
           $response = true;
        }
        
        return $response;
    }

    function get_booking_details_for_email($conn){
        $response = false;
        
        $query = "SELECT 
                 `landlord_details`.`first_name`,
                 `landlord_details`.`last_name`,
                 `landlord_details`.`email_id`,
                 `booking_details`.`id`,
                 `booking_details`.`lanlord_id`, 
                 `booking_details`.`smb_type`, 
                 `booking_details`.`smb_arrival`, 
                 `booking_details`.`smb_departure`, 
                 `booking_details`.`smb_apartment-name`, 
                 `booking_details`.`smb_adults`, 
                 `booking_details`.`smb_children`, 
                 `booking_details`.`smb_guest-name`, 
                 `booking_details`.`smb_channel-name`,
                 `booking_details`.`smb_price` 
                 FROM 
                 `booking_details`,
                 `landlord_details`
                             WHERE 
                             `booking_details`.`lanlord_id` = `landlord_details`.`id` 
                             AND `booking_details`.`smb_channel-name` NOT IN ('Blocked channel auto', 'Blocked channel')
                             AND `booking_details`.`mail_status` = 0 
                             AND `booking_details`.`status` IN (0, 1, 2)
                             AND `booking_details`.`deleted` = 0";
     
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows ($result) > 0){
           $response = $result -> fetch_all(MYSQLI_ASSOC);
        }
        //print_array($response);

        return $response;
    } 

    function update_email_flag($conn, $b_id){

        $response = false;
        $email_flag = '1';
  
        $dbhost = "127.0.0.1";
        $dbuser = "umlbf54ywusmm";
        $dbpass = "^)j(%x4n5c2j";
        $dbname = "dbreefim3bbssz";
  
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
  
        $query = 'UPDATE `booking_details` SET `mail_status`= "'.$email_flag.'" WHERE `id` = '.$b_id.'';
        
        if ($conn->query($query) === TRUE) {
           $response = true;
         }     
            
         return $response;
    }  
     
    function get_booking_details_for_ll($conn, $id){
         $response = false;
         $like_partial = "";
         
         //0. Get the apartment ids with the logged in user id
         $apartment_ids_array = get_apartment_ids_for_ll($conn, $id);
         
         $apartment_ids_str = "";
            foreach($apartment_ids_array as $apartment_id){
                    $apartment_ids_str.= $apartment_id['smoobu_property_id'].", ";
            }
            
         $apartment_ids = substr($apartment_ids_str, 0, -2);
         //1. Create apartment ids string for the query

         //2. 

         $query = 'SELECT * FROM `booking_details` 
                        WHERE `smb_apartment-id` IN ('.$apartment_ids.')
                        AND `smb_channel-name` NOT IN ("Blocked channel auto", "Blocked channel") 
                        AND `smb_type` NOT IN ("cancellation")
                        AND `status` = 0
                        AND `deleted` = 0
                        ORDER BY `booking_details`.`id` DESC';

        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows ($result) > 0){
            $response = $result -> fetch_all(MYSQLI_ASSOC);
         }
        
        return $response;

    }

    function get_apartment_ids_for_ll($conn, $lid){

        $query = 'SELECT `smoobu_property_id` FROM `property_details` 
                  WHERE `lanlord_id` = "'.$lid.'" 
                  AND `deleted` = 0 
                  AND `status` = 0';

        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows ($result) > 0){
            $response = $result -> fetch_all(MYSQLI_ASSOC);
        }

        return $response;
    }

    function calculate_booking_amount_for_ll($smb_channel, $smb_amount){
        $amount_to_minus = $smb_amount;

        switch ($smb_channel) {
            case 'Airbnb':
                //15% needs to minus
                $amount_to_minus = cal_percentage($smb_amount, 15);
                break;

            case 'Booking.com':
                //7.5% needs to minus
                $amount_to_minus = cal_percentage($smb_amount, 7.5);
                break;

            default:
                // code...
                $amount_to_minus = number_format($amount_to_minus, 2);
                break;
        }
        return $amount_to_minus;

    }

     // defining function
    function cal_percentage($myNumber, $percentToGet) {
        
        //Convert our percentage value into a decimal.
        $percentInDecimal = $percentToGet / 100;
        
        //Get the result.
        $percent = $percentInDecimal * $myNumber;

        $updated_amount = $myNumber - $percent;
        
        //Print it out - Result is 232.
        return number_format($updated_amount, 2);
    }

    function login($conn, $user_name, $password){
        $response = false;
        
        $e_password = base64_encode($password);

        $query = 'SELECT * FROM `landlord_details` 
                  WHERE `email_id` = "'.$user_name.'" 
                  AND `password` = "'.$e_password.'" 
                  AND `status` = 0 
                  AND `deleted` = 0';

        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows ($result) > 0){
            $response = $result -> fetch_all(MYSQLI_ASSOC);
        }else{
            $response = false;
        }
        
        return $response;
    }

    function get_ll_details($conn, $lanlord_id){
        $response = false;
        
        $query = 'SELECT * FROM `landlord_details` WHERE `id` = "'.$lanlord_id.'"';

        $result = mysqli_query($conn, $query);
        

        if(mysqli_num_rows ($result) > 0){
            $response = $result -> fetch_all(MYSQLI_ASSOC);
        }else{
            var_dump(mysqli_error());
        }
        
        return $response;
    }

    function get_ll_id($conn, $sid){
        $response = false;
        
        $query = 'SELECT `lanlord_id` 
                  FROM `property_details` 
                  WHERE `smoobu_property_id` = "'.$sid.'"';

        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows ($result) > 0){
            $lids = $result -> fetch_all(MYSQLI_ASSOC);
        }
        

        $lid_str = '';
        foreach($lids as $lid){
            $landlord_id = $lid['lanlord_id'];
            
            $lid_str.= $landlord_id.', ';            
        }
        $response = substr($lid_str, 0, -2);
        
        return $response;      
    }

    function send_booking_email_to_landlord($conn, $booking_details){
        
        foreach($booking_details as $booking_detail){
           extract($booking_detail);
            //print_array($booking_detail);
            //exit;
           $message = "";
           
           include_once 'mail-templates/mail-header.php';
           
           //Starts: Iterate through LIDs
           /* 
              We will not be using the Landlord details(First Name, Last Name and Email ids) from the $booking_details because code should be able to send emails
              more then one landlord/agent
            */
            $lanlord_id_array = explode(",",$lanlord_id);
            
            $i = 0;
            foreach($lanlord_id_array as $lid){
                
                $_lid = trim($lid);
                
                $ll_details = get_ll_details($conn, $_lid);
                extract($ll_details[0]);
                //print_array($ll_details);
                //exit;

                $lanloard_name = trim($first_name).' '.trim($last_name);
                $lanloard_email = trim($email_id);
                
                $property_name = $booking_detail['smb_apartment-name'];
                $smb_channel_name = $booking_detail['smb_channel-name'];
                $u_booking_amount = calculate_booking_amount_for_ll($smb_channel_name, $smb_price);
                
                $subject = "";
                $line_one = "";
                $line_two = "";
                $line_three = "";

                switch ($smb_type) {
                    case 'cancellation':
                        // code...
                        $subject = "Your Property $property_name has a canceled booking";
                        $line_one = "Sorry the guest has canceled booking.";
                        $line_two = "Details are listed below and it will update on landlord portal.";      
                        $line_three = "";                  
                        break;
                    case 'modification of booking':
                        // code...
                        $subject = "Your Property $property_name has an updated booking";
                        $line_one = "The guest has updated there booking new dates and price are below.";
                        $line_two = "Details will update on landlord portal.";
                        $line_three = "";
                        break;                    
                    default:
                        // code...
                        $subject = "Your Property $property_name is booked";
                        $line_one = "Congratulations your property has been booked.";
                        $line_two = "Thankyou for your continued business and on the landlord portal you will see details as well.";
                        $line_three = "Please find the bookig details below:";
                        break;
                }                
                //$guest_name = $booking_detail['smb_guest-name'];
                
                include_once 'mail-templates/mail-body-booking-details.php';
                
                include_once 'mail-templates/mail-footer.php';
            
                $message = $mail_header.$mail_body.$mail_footer; 

                $to = $lanloard_email;
                //echo $subject;

                $res = send_email($message, $to, $subject, $i);

                $i++;
            }
           //Ends: Iterate through LIDs
    
           if( count($res > 0) ){
              update_email_flag($conn, $booking_detail['id']);
           }
        }
    }

    //Cancellation, Starts
    function get_cancellation_bookings($conn){
        $response = false;
    
        $query = 'SELECT `smb_id`, `smb_apartment-id`, `smb_arrival`,`smb_departure` 
                  FROM `booking_details` 
                  WHERE `smb_type` = "cancellation" 
                  AND `status` = 0';
    
       $result = mysqli_query($conn, $query);
    
       if(mysqli_num_rows ($result) > 0){
           $response = $result -> fetch_all(MYSQLI_ASSOC);
        }
       
       return $response;
    
    }
    
    function update_cancellation_booking($conn, $b_id){
        $response = false;
        $mail_status = 0;
        $flag = '2';
        $smb_type = "cancellation";
        
        $dbhost = "127.0.0.1";
        $dbuser = "umlbf54ywusmm";
        $dbpass = "^)j(%x4n5c2j";
        $dbname = "dbreefim3bbssz";
    
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    
        $query = 'UPDATE `booking_details` 
                      SET 
                      `status`= "'.$flag.'",
                      `smb_type`= "'.$smb_type.'",
                      `mail_status`= "'.$mail_status.'" 
                      WHERE 
                      `smb_id` = '.$b_id.'';
            
            if ($conn->query($query) === TRUE) {
               $response = true;
             }     

    }

    function update_all_cancelled_booking_only($conn){
        $response = false;
        
        $flag = '2';
        $smb_type = 'cancellation';
    
        $dbhost = "127.0.0.1";
        $dbuser = "umlbf54ywusmm";
        $dbpass = "^)j(%x4n5c2j";
        $dbname = "dbreefim3bbssz";
    
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    
        $query = 'UPDATE `booking_details` SET `status`= "'.$flag.'" WHERE `smb_type` = "'.$smb_type.'"';
            
            if ($conn->query($query) === TRUE) {
               $response = true;
             }     
    
    }
    function check_existing_booking_for_cancellation($conn, $cancellation_booking){
        $response = false;
    
        extract($cancellation_booking);
        $smb_apartment_id = $cancellation_booking['smb_apartment-id'];
        
    
        $query = 'SELECT `id` FROM `booking_details` 
                  WHERE `smb_channel-name` NOT IN ("Blocked channel auto", "Blocked channel") 
                  AND `smb_type` NOT IN ("cancelled")
                  AND `smb_apartment-id` = "'.$smb_apartment_id.'"
                  AND `smb_arrival` = "'.$smb_arrival.'"
                  AND `smb_departure` = "'.$smb_departure.'"
                  AND `status` = 0';
      
         $result = mysqli_query($conn, $query);
    
         if(mysqli_num_rows ($result) > 0){
            $response = $result -> fetch_all(MYSQLI_ASSOC);
         }
         //print_array($response);
    
         return $response;
    } 

