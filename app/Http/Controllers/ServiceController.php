<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index($client_id=null)
    {
        $services = null;
        $client = null;
        if ($client_id) {
            $services = Service::where('client_id',$client_id)->get();
            $client = Client::find($client_id);
        } else {
            $services = Service::all();
        }

        return view('services.index', compact('services','client'));
    }
}
