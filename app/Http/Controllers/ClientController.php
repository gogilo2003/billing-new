<?php

namespace App\Http\Controllers;

use App;

use PDF;
use Validator;
use Inertia\Inertia;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Ogilo\ApiResponseHelpers;
use App\Services\ClientService;
use App\Http\Resources\ClientResource;
use App\Http\Requests\V1\ClientViewRequest;
use App\Http\Requests\V1\ClientStoreRequest;
use App\Http\Requests\V1\ClientUpdateRequest;

class ClientController extends Controller
{
    use ApiResponseHelpers;

    public function index()
    {
        $search = request('search', null);
        // Retrieve the data from the database without sorting
        $clients = Client::when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', "%$search%");
        })->get()->toArray();

        // Sort the array by the "balance" accessor
        usort($clients, function ($a, $b) {
            return $a['balance'] - $b['balance'];
        });

        // Paginate the sorted data
        $perPage = 10;

        $page = request('page', 1); // Get the current page from the request
        if ($page > ceil(count($clients) / $perPage)) {
            $page = ceil(count($clients) / $perPage);
        }
        $clientsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            array_slice($clients, ($page - 1) * $perPage, $perPage),
            count($clients),
            $perPage,
            $page
        );

        $clientsPaginated->setPath(request()->url());

        // return view('clients.index', compact('clients'));
        $data = ['clients' => $clientsPaginated];
        if ($search) {
            $data['searchVal'] = $search;
        }
        return Inertia::render('Clients/Index', $data);
    }

    public function store(ClientStoreRequest $request, ClientService $clientService)
    {

        $clientService->store(
            $request->name,
            $request->email,
            $request->phone,
            $request->box_no,
            $request->post_code,
            $request->town,
            $request->address
        );

        // if ($request->wantsJson()) {
        //     return $this->storeSuccess("Client has been created", ['client' => ClientResource::make($client)]);
        // }

        // return redirect()
        //     ->route('clients')
        //     ->with('global-success', 'Client has been created');
        return redirect()
            ->back()
            ->with('success', 'Client has been created');
    }

    public function update(ClientUpdateRequest $request, ClientService $clientService)
    {
        $client = $clientService->update(
            $request->id,
            $request->name,
            $request->email,
            $request->phone,
            $request->box_no,
            $request->post_code,
            $request->town,
            $request->address
        );

        // if ($request->wantsJson()) {
        //     return $this->updateSuccess("Client has been updated", ['client' => ClientResource::make($client)]);
        // }
        return redirect()
            ->back()
            ->with('success', 'Client has been updated');
    }

    public function show(ClientViewRequest $request)
    {
        $client = Client::with(['accounts.transactions', 'invoices.items', 'accounts'])->find($request->id);

        if (request()->wantsJson()) {
            return ClientResource::make($client);
        }

        return view('clients.view', compact('client'));
    }

    public function destroy(Client $client)
    {

        $client->delete();

        return redirect()
            ->back()
            ->with('success', 'Client Deleted');
    }

    public function downloadClient($id)
    {
        $client = Client::with('accounts.transactions')->find($id);
        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadView('clients.download', compact('client'))->setOption('no-outline', true);
        return $pdf->setOption('margin-left', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-top', 48)
            ->setOption('margin-bottom', 13)
            ->setOption('header-html', public_path('pdf/header.html'))
            ->setOption('footer-html', public_path('pdf/footer.html'))
            ->download(Str::slug($client->name) . '.pdf');
    }

    public function downloadClients()
    {
        $clients = Client::with('accounts.transactions')->orderBy('name', 'ASC')->get();
        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->setOrientation('landscape');
        $pdf->loadView('clients.clients-download', compact('clients'));
        return $pdf->setOption('margin-left', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-top', 48)
            ->setOption('margin-bottom', 13)->setOption('header-html', public_path('pdf/header.html'))
            ->setOption('footer-html', public_path('pdf/footer.html'))
            ->download('clients_' . time() . '.pdf');
    }
}
