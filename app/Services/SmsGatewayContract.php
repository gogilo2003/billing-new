<?php

namespace App\Services;

abstract class SmsGatewayContract
{
    abstract public function send($phone, $message);

    abstract public function status($message_id);

    abstract public function balance();

    abstract public function update($message_id);

    public function sendRequest($endpoint, $data)
    {
        if (env('SMS_TEST')) {
            \Illuminate\Support\Facades\Storage::put('sms.txt', $data);

            return [];
        } else {
            $ch = curl_init($this->base_url.$endpoint);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept:application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);

            return json_decode($result);
        }
    }
}
