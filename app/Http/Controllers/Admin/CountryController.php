<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCountryRequest;
use App\Http\Requests\Admin\UpdateCountryRequest;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CountryController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Country::class);

        $countries = Country::query()->orderBy('name')->paginate(15);

        return view('admin.countries.index', ['countries' => $countries]);
    }

    public function create(): View
    {
        $this->authorize('create', Country::class);

        return view('admin.countries.create');
    }

    public function store(StoreCountryRequest $request): RedirectResponse
    {
        Country::query()->create($request->validated());

        return redirect()->route('admin.countries.index')->with('status', __('Country created.'));
    }

    public function show(Country $country): View
    {
        $this->authorize('view', $country);

        return view('admin.countries.show', ['country' => $country]);
    }

    public function edit(Country $country): View
    {
        $this->authorize('update', $country);

        return view('admin.countries.edit', ['country' => $country]);
    }

    public function update(UpdateCountryRequest $request, Country $country): RedirectResponse
    {
        $country->update($request->validated());

        return redirect()->route('admin.countries.show', $country)->with('status', __('Country updated.'));
    }

    public function destroy(Country $country): RedirectResponse
    {
        $this->authorize('delete', $country);

        $country->delete();

        return redirect()->route('admin.countries.index')->with('status', __('Country deleted.'));
    }
}
