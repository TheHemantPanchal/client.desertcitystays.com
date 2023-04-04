<?php

    include 'db-connect.php';
    include 'functions.php';

    date_default_timezone_set('Asia/Dubai');
    $today_date = date('Y-m-d');
    $tomorrow_date = date("Y-m-d", strtotime('tomorrow'));
    
    // $today_date = "2023-01-26";
    // $tomorrow_date = "2023-01-27";
    
    $url = "https://login.smoobu.com/api/reservations?created_from=2023-02-01&created_to=2023-03-31&from=2023-02-01&to=2023-04-01&modifiedFrom=2023-02-01&modifiedTo=2023-03-31&showCancellation=1&pageSize=100&includePriceElements=1&excludeBlocked=1&page=1";

    $ch = curl_init($url);

    $customHeaders = array(
        'Api-Key: vTunsSKV4XUhZSaAn6CdeyfV1D2cJPaPq02Qu1fzUQ',
        'Content-Type: application/json'
    );
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $customHeaders);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $_response = curl_exec($ch);
    
    $response = json_decode($_response, true);

    $booking_data = $response['bookings'];
    
    echo "Date: $today_date".PHP_EOL;
    echo "Total Bookings Found: ".count($response['bookings']).PHP_EOL;

    //booking object
    $booking_object = array();

    $i = 0;
    foreach($booking_data as $booking){
        
        $booking_details = array();

        //print_array($booking);

        extract($booking);

        $booking_details['smb_id'] = $id;
        $booking_details['smb_reference_id'] = $booking['reference-id'];
        $booking_details['smb_type'] = $type;
        $booking_details['smb_arrival'] = $arrival;
        $booking_details['smb_departure'] = $departure;
        $booking_details['smb_created_at'] = $booking['created-at'];
        $booking_details['smb_modifiedAt'] = $modifiedAt;
        $booking_details['smb_apartment_id'] = $apartment['id'];
        $booking_details['smb_apartment_name'] = $apartment['name'];
        $booking_details['smb_channel_id'] = $channel['id'];
        $booking_details['smb_channel_name'] = $channel['name'];
        $booking_details['smb_guest_name'] = $booking['guest-name'];
        $booking_details['smb_firstname'] = $firstname;
        $booking_details['smb_lastname'] = $lastname;
        $booking_details['smb_email'] = check_empty($email);
        $booking_details['smb_phone'] = $phone;
        $booking_details['smb_adults'] = $adults;
        $booking_details['smb_children'] = $children;
        $booking_details['smb_check_in'] = check_empty($booking['check-in']);
        $booking_details['smb_check_out'] = check_empty($booking['check-out']);
        $booking_details['smb_notice'] = check_empty($notice);
        $booking_details['smb_assistant_notice'] = check_empty($booking['assistant-notice']);
        $booking_details['smb_price'] = $price;
        $booking_details['smb_price_paid'] = $booking['price-paid'];
        $booking_details['smb_commission_included'] = $booking['commission-included'];
        $booking_details['smb_prepayment'] = check_empty($prepayment);
        $booking_details['smb_prepayment_paid'] = $booking['prepayment-paid'];
        $booking_details['smb_deposit'] = check_empty($deposit);
        $booking_details['smb_deposit_paid'] = $booking['deposit-paid'];
        $booking_details['smb_language'] = check_empty($language);
        $booking_details['smb_guest_app_url'] = $booking['guest-app-url'];
        $booking_details['smb_is_blocked_booking'] = $booking['is-blocked-booking'];
        $booking_details['smb_guestId'] = $guestId;

        $booking_object[$i] = $booking_details;
        $i++;
    }

    save_booking_data($conn, $booking_object);

    //$booking_details_for_email = get_booking_details_for_email($conn);

    //send_booking_email_to_landlord($conn, $booking_details_for_email);