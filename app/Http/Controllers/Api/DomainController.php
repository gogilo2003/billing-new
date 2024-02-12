<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DomainCollection;
use App\Models\Client;
use App\Models\Domain;
use DateInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ogilo\ApiResponseHelpers;

class DomainController extends Controller
{
    use ApiResponseHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status = null)
    {
        if ($status == 'active') {
            $active = Domain::where('expires_on', '>=', now())
                ->orderBy('expires_on', 'ASC')
                ->paginate(5);

            return new DomainCollection($active);
        }
        if ($status == 'expired') {
            $expired = Domain::where('expires_on', '<', now())
                ->orderBy('expires_on', 'DESC')->paginate(5);

            return new DomainCollection($expired);
        }
        $domains = Domain::where('expires_on', '>=', now())
            ->orderBy('expires_on', 'ASC')
            ->paginate(5);

        return new DomainCollection($domains);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain' => 'required|unique:domains,domain',
            'registered_on' => 'required|date',
            'expires_on' => 'required|date',
            'client_id' => 'required|exists:clients,id',
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator);
        }
        $domain = new Domain();
        $domain->domain = $request->domain;
        $domain->registered_on = $request->registered_on;
        $domain->expires_on = $request->expires_on;
        if ($request->remarks) {
            $domain->remarks = $request->remarks;
        }
        $domain->client_id = $request->client_id;
        $domain->status = $request->status;
        $domain->save();

        return response()->json([
            'success' => true,
            'message' => 'Domain Created',
            'domain' => $domain,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:domains',
            'domain' => 'required|unique:domains,domain,' . $request->id,
            'registered_on' => 'required|date',
            'expires_on' => 'required|date',
            'client_id' => 'required|exists:clients,id',
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $domain = Domain::find($request->id);
        $domain->domain = $request->domain;
        $domain->registered_on = $request->registered_on;
        $domain->expires_on = $request->expires_on;
        if ($request->remarks) {
            $domain->remarks = $request->remarks;
        }
        $domain->client_id = $request->client_id;
        $domain->status = $request->status;
        $domain->save();

        return response()->json([
            'success' => true,
            'message' => 'Domain Updated',
            'domain' => $domain,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    public function status()
    {
        return response()->json(['active', 'expired']);
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $csv = \League\Csv\Reader::createFromPath($request->csv_file->getRealPath(), 'r');
        $csv->setHeaderOffset(0);
        $records = \League\Csv\Statement::create()->process($csv);

        foreach ($records->getRecords() as $key => $record) {
            $domain = new Domain();
            $domain->domain = $record['domain'];
            $domain->registered_on = $record['registered_on'];
            $domain->expires_on = $record['expires_on'];
            $domain->status = date_create($domain->expires_on) > now() ? 'active' : 'expired';
            if ($record['client_id']) {
                $domain->client_id = $record['client_id'];
            } elseif ($record['name'] && $record['email'] && $record['phone']) {
                $client = new Client();
                $client->name = $record['name'];
                $client->email = $record['email'];
                $client->phone = clean_isdn($record['phone']);
                $client->save();
                $domain->client_id = $client->id;
            }
            $domain->save();
        }

        return response()->json([
            'success' => true,
            'message' => count($records) . ' Domains Imported',
            'domains' => Domain::all(),
        ]);
    }

    /**
     * Disable or enable notification for a domain.
     */
    public function notify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:domains',
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $domain = Domain::find($request->id);
        $domain->notify = $domain->notify ? 0 : 1;
        $domain->save();

        return response()->json([
            'success' => true,
            'message' => 'Notification ' . ($domain->notify ? 'enabled' : 'disabled') . ' for (' . $domain->domain . ')',
            'domain' => $domain,
        ]);
    }

    public function renew(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:domains,id'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }
        $domain = Domain::find($request->id);
        $domain->expires_on = date_create($domain->expires_on)->add(DateInterval::createFromDateString('1 year'));
        $domain->save();

        return $this->respondWithSuccess('Domain Renewed ' . $domain->domain . ' to ' . $domain->expires_on->format('j-M-Y'));
    }
}
