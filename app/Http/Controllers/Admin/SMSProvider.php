<?php

namespace App\Http\Controllers\Admin;

use GuzzleHttp\Client;

class SMSProvider
{

    public static function sendSMS($message, $phones)
    {
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',

        );
        $username = 'ubsobyi6';
        $password = 'clpJVBTFIN';
        $senderId = 'baby mumz';
        $message = mb_convert_encoding($message, 'UCS-2', 'utf8');
        $message = strtoupper(bin2hex($message));
        $query = 'username=' . $username . '&password=' . $password . '&language=3'
            . '&sender=' . $senderId . '&mobile=' . $phones . '&message=' . $message;
//        $data = [
//            'username' => $username,
//            'password' => $password,
//            'language' => 1,
//            'sender' => $senderId,
//            'mobile' => '01005107138',
//            'message' => $message
//        ];
        $url = 'https://smsmisr.com/api/webapi/?' . $query;
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_HEADER, false);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
//        $result = curl_exec($ch);
//        curl_close($ch);

        $client = new Client();
        $res = $client->request('POST', $url, []);
        $result = $res->getBody();
        $result = json_decode((string)$result, true);

        if (isset($result['code']) && intval($result['code']) === 1901) {
            return true;
        } else {
            return false;
        }
    }

}