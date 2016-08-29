<?php

/** بسم الله الرحمن الرحيم **/
/*/*====================================================================*\*\
* || #################################################################### ||
* || # Script Coded By  Mustafa Taj Elsir : www.fb.com/kemo2011         # ||
* || # Copyright 2016  All Rights Reserved.                             # ||
* || # This file may not be redistributed in whole or significant part. # ||
* || # ------------------ This IS NOT A FREE SOFTWARE ----------------- # ||
* || # -----------       Mustafa Taj Special Software      -------------- ||
* || #################################################################### ||
* \*======================================================================*/

require 'vendor/autoload.php';
function CurlRequest2($question)
{
    global $UniqID, $update;
    $url = "http://sandbox.api.simsimi.com/request.p?key=c55ce39f-d4db-4ad1-8f73-6c694601b35d&lc=ar&ft=1.0&text=" .
        urlencode($question);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_USERAGENT => 'Sozi Bot'));
    $resp = curl_exec($curl);
    curl_close($curl);
    // $result = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $resp);
    return json_decode($resp, true);
    //return $question;
}

$client = new Zelenin\Telegram\Bot\Api('220891610:AAETuu8cre-NTgEzU5gI9zdw-BX1lc9G_Sk'); // Set your access token
//$url = 'https://oiu-medicine.herokuapp.com/'; // URL RSS feed
$update = json_decode(file_get_contents('php://input'));
$UniqID = $update->message->from->id . "Split" . $update->message->chat->
    username;


//your app
try
{
    if ($update->message->text == '/me')
    {
        $response = $client->sendChatAction(['chat_id' => $update->message->
            chat->id, 'action' => 'typing']);
        $response = $client->sendMessage(['chat_id' => $update->message->chat->
            id, 'text' => json_encode($update)]);

    } else
    {
        $responses = $client->sendChatAction(['chat_id' => $update->message->
            chat->id, 'action' => 'typing']);
        $response = CurlRequest2($update->message->text);
        if (empty($response["response"]))
            $response["response"] = "سيبني حالياً, أنا زعلان وعاوز أقعد براي";
        $client->sendMessage(['chat_id' => $update->message->chat->id, 'text' =>
            $response["response"]]);

    }
}
catch (\Zelenin\Telegram\Bot\NotOkException $e)
{
    $response = $client->sendMessage(['chat_id' => $update->message->chat->id,
        'text' => "خطأ: " . $e->getMessage()]);
    //echo error message ot log it
    //echo $e->getMessage();

}

?>
