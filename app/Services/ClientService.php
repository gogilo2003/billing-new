<?php

namespace App\Services;

use App\Models\Client;

class ClientService
{
    public function store(
        String $name,
        String $email,
        String $phone = null,
        String $box_no = null,
        String $post_code = null,
        String $town = null,
        String $address = null
    ) {
        $client = new Client;

        $client->name = $name;
        $client->email = $email;
        $client->phone = $phone;
        $client->box_no = $box_no;
        $client->post_code = $post_code;
        $client->town = $town;
        $client->address = $address;

        $client->save();

        return $client;
    }

    public function update(
        int $id,
        String $name,
        String $email,
        String $phone = null,
        String $box_no = null,
        String $post_code = null,
        String $town = null,
        String $address = null
    ) {
        $client = Client::find($id);
        $client->name = $name;
        $client->email = $email;
        $client->phone = $phone;
        $client->box_no = $box_no;
        $client->post_code = $post_code;
        $client->town = $town;
        $client->address = $address;

        $client->save();

        return $client;
    }
}
