<?php

use App\Models\User;

function sendNotification($parameters,$user){

    $firebaseToken = User::whereNotNull('device_token')->whereIn('id',$user)->pluck('device_token')->all();

   // $SERVER_API_KEY = env('FCM_SERVER_KEY');
    $SERVER_API_KEY = "AAAAtd5hy4c:APA91bG2xc7bfpb4CaK5NveNjaqNpmBIhc-xkZwvRMlUdtMOuiW8BEVM--AOlXobwUwK2JOp1agKUl99tvyqv5rpkXlxSqLg8ameF-DjQLyTmBch8ZUcxWhYnFn8LX12dG99u-TjZNGo";

    $data = [
        "registration_ids" => $firebaseToken,
        "data" => [
            "title" => $parameters['title'],
            "body" => $parameters['body'],
            "action" => $parameters['action'],
        ]
    ];
    $dataString = json_encode($data);

    $headers = [
        'accept: application/json',
        'Authorization: key=' . $SERVER_API_KEY,
        'Content-Type: application/json',
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

   return $response = curl_exec($ch);

    //return back()->with('success', 'Notification send successfully.');

}

function sendNotificationNew($parameters,$user){

    $firebaseToken = User::whereNotNull('device_token')->whereIn('id',$user)->first()->pluck('device_token');
    //return $firebaseToken;

//    $SERVER_API_KEY = "AAAAtd5hy4c:APA91bG2xc7bfpb4CaK5NveNjaqNpmBIhc-xkZwvRMlUdtMOuiW8BEVM--AOlXobwUwK2JOp1agKUl99tvyqv5rpkXlxSqLg8ameF-DjQLyTmBch8ZUcxWhYnFn8LX12dG99u-TjZNG";
    $SERVER_API_KEY = env('FCM_SERVER_KEY');
    //return $SERVER_API_KEY;

    $data = [
        "registration_ids" => "$firebaseToken",
        "notification" => [
            "title" => $parameters['title'],
            "body" => $parameters['body'],
        ]
    ];

    $post_data = '{
            "to" : "'.$SERVER_API_KEY.'",
            "data" : {
              "body" : "'.$parameters['title'] .'",
              "title" : "' . $parameters['body'] . '"
            },
            "notification" : {
                 "body" : "' . $parameters['title'] . '",
                 "title" : "' . $parameters['body'] . '"
                }
          }';

    //$dataString = json_encode($data);

    $headers = [
        'accept: application/json',
        'Authorization: key=' . $SERVER_API_KEY,
        'Content-Type: application/json',
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    return $response = curl_exec($ch);

    //return back()->with('success', 'Notification send successfully.');

}
