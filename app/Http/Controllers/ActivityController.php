<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        // Cas 1 : Peut voir toutes les activités (plateforme)
        if ($user->can('read_all_activities')) {
            $activities = Activity::with('user')->latest()->paginate(20);
        }

        // Cas 2 : Peut voir toutes les activités de son entreprise (tenant)
        elseif ($user->can('read_activities')) {
            $activities = Activity::with('user')->whereHas('user', function ($query) use ($user) {
                $query->where('tenant_id', $user->tenant_id);
            })->latest()->paginate(20);
        }

        // Cas 3 : Ne voit que ses propres activités
        else {
            $activities = Activity::with('user')->where('user_id', $user->id)->latest()->paginate(20);
        }

        return view('back.activities.index', compact('activities'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
