<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\SelectClientResource;

class ClientController extends Controller
{
    public function accounts()
    {
        $clients = Client::with(['accounts' => function ($query) {
            return $query->orderBy('name', 'ASC');
        }])->orderBy('name', 'ASC')->get();

        // return response()->json($clients);

        return SelectClientResource::collection($clients);
    }

    public function index()
    {
        $clients = Client::with('accounts', 'invoices')->get();
        return ClientResource::collection($clients);
    }

    public function minList()
    {
        $clients = Client::orderBy('name', 'ASC')->get()->map(function ($item) {
            return new class($item->id, $item->name)
            {
                public function __construct($id, $name)
                {
                    $this->id = $id;
                    $this->name = strtoupper($name);
                }
            };
        });

        return response()->json(['data' => $clients->toArray()]);
    }

    public function createClient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'email|unique:clients',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('message');
        }
    }

    public function updateNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:clients',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $account = Client::find($request->id);
        $account->notification = $account->notification ? 0 : 1;
        $account->save();

        return response()->json([
            'success' => true,
            'notification' => $account->notification,
            'message' => 'Notification changed successfuly',
        ]);
    }

    public function showNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:clients',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $account = Client::find($request->id);

        return response()->json([
            'success' => true,
            'notification' => $account->notification,
            'message' => 'Current nottification status',
        ]);
    }
}
