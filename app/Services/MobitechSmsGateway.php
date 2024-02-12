<?php

namespace App\Services;

use App\Models\Sms;

class MobitechSmsGateway extends SmsGatewayContract
{
    protected $api_key;
    protected $user_name;
    protected $sender_id;
    protected $base_url;

    public function __construct()
    {
        $this->api_key = env('MOBITECH_API_KEY');
        $this->user_name = env('MOBITECH_USER_NAME');
        $this->sender_id = env('MOBITECH_SENDER_ID');
        $this->base_url = env('MIBITECH_BASE_URL');
    }

    public function send($phone, $message)
    {
        $recepients = explode(',', $phone);

        foreach ($recepients as $recepient) {
            $sms = new Sms();
            $sms->recepient = $recepient;
            $sms->message = $message;
            $sms->save();
        }

        $endpoint = 'api/sendsms';

        $data = json_encode([
            'api_key' => $this->api_key,
            'username' => $this->user_name,
            'sender_id' => $this->sender_id,
            'message' => $message,
            'phone' => $phone,
        ]);

        $results = $this->sendRequest($endpoint, $data);

        foreach ($results as $result) {
            $this->update($result->message_id);
        }

        return $results;
    }

    public function status($message_id)
    {
        if(env('SMS_TEST')){
            return [];
        }
        $endpoint = 'api/sms_delivery_status';

        $data = json_encode([
            'api_key' => $this->api_key,
            'username' => $this->user_name,
            'message_id' => $message_id,
        ]);

        return $this->sendRequest($endpoint, $data);
    }

    public function balance()
    {
        $endpoint = 'api/account_balance';

        $data = json_encode([
            'api_key' => $this->api_key,
            'username' => $this->user_name,
        ]);

        return $this->sendRequest($endpoint, $data);
    }

    public function update($message_id)
    {
        $status = $this->status($message_id);
        $sms = Sms::where('recepient', $status->recepient)->where('message', $status->message)->first() ?? new Sms();
        $sms->message_id = $message_id;
        $sms->message = $status->message;
        $sms->recepient = $status->recepient;
        $sms->send_time = $status->send_time;
        $sms->sender_name = $status->sender_name;
        $sms->status = $status->status;
        $sms->sms_unit = $status->sms_unit;
        $sms->network_name = $status->network_name;
        $sms->save();
    }
}
