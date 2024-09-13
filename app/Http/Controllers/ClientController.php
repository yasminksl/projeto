<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Http\Resources\OrderResource;
use App\Models\Client;
use Carbon\Carbon;
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
                ->when(FacadesRequest::input('postal_code'), function ($query, $postal_code) {
                    $query->where('postal_code', $postal_code);
                })
                ->when(FacadesRequest::input('street_address'), function ($query, $street_address) {
                    $query->where('street_address', $street_address);
                })
                ->when(FacadesRequest::input('neighborhood'), function ($query, $neighborhood) {
                    $query->where('neighborhood', $neighborhood);
                })
                ->when(FacadesRequest::input('city'), function ($query, $city) {
                    $query->where('city', $city);
                })
                ->when(FacadesRequest::input('start_date') && FacadesRequest::input('end_date'), function ($query) {
                    $startDate = Carbon::parse(FacadesRequest::input('start_date'))->startOfDay();
                    $endDate = Carbon::parse(FacadesRequest::input('end_date'))->endOfDay();
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->paginate(10)->withQueryString()),
            'filters' => FacadesRequest::only(['search', 'postal_code', 'street_address', 'neighborhood', 'city', 'start_date', 'end_date']),
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

        $client = Client::create($attributes);

        return redirect('/clients/' . $client->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $status = request()->input('status');

        $orders = $client->orders()
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Clients/Show', [
            'client' => ClientResource::make($client),
            'orders' => OrderResource::collection($orders),
            'filters' => ['status' => $status],
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

        return redirect('/clients/' . $client->id);
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
