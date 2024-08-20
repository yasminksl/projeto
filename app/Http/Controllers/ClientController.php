<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Inertia\Inertia;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Clients/Index', [
            'clients' => ClientResource::collection(Client::query()
                ->when(FacadesRequest::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(10)->withQueryString()),
            'filters' => FacadesRequest::only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Clients/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required',
            'postal_code' => 'required',
            'street_address' => 'required',
            'address_number' => 'required',
            'city' => 'required',
            'neighborhood' => 'required',
        ]);

        Client::create($attributes);

        sleep(2);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return Inertia::render('Clients/Show', [
            'client' => ClientResource::make($client)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return Inertia::render('Clients/Edit', [
            'client' => ClientResource::make($client)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $attributes = $request->validate([
            'name' => 'required',
            'postal_code' => 'required',
            'street_address' => 'required',
            'address_number' => 'required',
            'city' => 'required',
            'neighborhood' => 'required',
        ]);

        $client->update($attributes);

        sleep(2);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect('/clients');
    }
}
