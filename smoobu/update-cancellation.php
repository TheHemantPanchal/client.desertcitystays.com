<?php
    include 'db-connect.php';
    include 'functions.php';

    $flag = false;
/* 
    0. Getting the Cancellation Booking Records
*/
    $cancellation_bookings = get_cancellation_bookings($conn);

/*  
    1. Iterate through each found record(s) and check if the details(apartment_id, arrival_date and departure_date) are matching or not
*/
    print_array($cancellation_bookings);

    foreach($cancellation_bookings as $cancellation_booking){
        $flag = true;
       /*
            Sample Data:
            Array
                (
                    [smb_id] => 30607675
                    [smb_apartment-id] => 1454197
                    [smb_arrival] => 2022-12-14
                    [smb_departure] => 2023-01-14
                )
       */
       $existing_bookings_for_cancellation = check_existing_booking_for_cancellation($conn, $cancellation_booking);
       
       /*
          2. If the details are same then update the status flag with value "2"
       */
       
       //print_array($existing_bookings_for_cancellation);
       
       foreach($existing_bookings_for_cancellation as $existing_booking_for_cancellation){
        extract($existing_booking_for_cancellation); 
        
        $resp = update_cancellation_bookings($conn, $id);
        
       }
    }


    if($flag === true){
        update_all_cancelled_booking_only($conn);
    }
