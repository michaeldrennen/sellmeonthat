<?php

namespace App\Http\Controllers;

use App\Models\Want;
use Illuminate\Http\Request;
use App\Http\Requests\StoreWantRequest;
use App\Http\Requests\UpdateWantRequest;
use Illuminate\Support\Facades\Gate;

class WantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all wants from the database, ordered by the newest first, and paginate them.
        $wants = Want::latest()->paginate(15);

        return view('wants.index', compact('wants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWantRequest $request)
    {
        // Validation is handled by StoreWantRequest

        $want = auth()->user()->wants()->create($request->validated());

        return redirect()->route('wants.show', $want)
                         ->with('success', 'Your want has been successfully posted!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Want $want)
    {
        // Laravel's route-model binding automatically finds the Want by its ID.
        return view('wants.show', compact('want'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Want $want)
    {
        return view('wants.edit', compact('want'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWantRequest $request, Want $want)
    {
        // Authorization is handled by UpdateWantRequest
        $want->update($request->validated());

        return redirect()->route('wants.show', $want)
                         ->with('success', 'Your want has been successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Want $want)
    {
        // Authorize that the logged-in user owns this want
        Gate::authorize('update', $want);

        $want->delete();

        return redirect()->route('wants.index')
                         ->with('success', 'Your want has been successfully deleted.');
    }
}
