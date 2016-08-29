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
function CurlRequest2($question, $returnJson = true)
{
    global $UniqID, $update;
    if ($returnJson)
        $url = "http://sandbox.api.simsimi.com/request.p?key=935a036f-488e-4ebf-ba9b-705afc65ad2c&lc=ar&ft=1.0&text=" .
            urlencode($question);
    else
        $url = "http://oiu.edu.sd/medicine/misc.php?do=rem&save=" . urlencode($question);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_USERAGENT => 'MTFM Bot'));
    $resp = curl_exec($curl);
    curl_close($curl);
    // $result = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $resp);
    if ($returnJson)
        return json_decode($resp, true);
    else
        return $resp;
    //return $question;
}

$client = new Zelenin\Telegram\Bot\Api('240828389:AAG31vll3NsB4lPI0MvsUTmOkzMKSu7ucG0'); // Set your access token
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
            
        CurlRequest2("[" . $update->message->chat->username . "]: " . $update->
            message->text, false);
            
        $response = CurlRequest2($update->message->text);
        if (empty($response["response"]))
            $response["response"] = "سيبني حالياً, أنا زعلان وعاوز أقعد براي";
        if ($response["result"] == 509 && $response["msg"] ==
            "Daily Request Query Limit Exceeded.")
            $response["response"] = "أنا نعسان أسه, ممكن نتكلم بكرة ؟";
            
            CurlRequest2("[" . $update->message->chat->username . "]: " . $update->
            message->text . "\n [Response]: " . $response["result"], false);

        $client->sendMessage(['chat_id' => $update->message->chat->id, 'text' =>
            $response["response"]]);

    }
}
catch (\Zelenin\Telegram\Bot\NotOkException $e)
{
    $response = $client->sendMessage(['chat_id' => $update->message->chat->id,
        'text' => "معليش ما عاوز أتكلم أسه"]); //$e->getMessage()
    //echo error message ot log it
    //echo $e->getMessage();

}

?>
