<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Tenant;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function index()
    {
        // Ici on liste toutes les notifications envoyées
        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        return view('back.admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $companies = Tenant::all();
        $users = User::all();
        return view('back.admin.notifications.create', compact('companies', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'url' => 'nullable|url',
            'target_type' => 'required|string', // global, tenant, user
            'target_id' => 'nullable|integer'
        ]);

        if ($request->target_type === 'global') {
            foreach (User::all() as $user) {
                $user->notify(new SystemNotification($request->title, $request->message, $request->url));
            }
        } elseif ($request->target_type === 'tenant') {
            $users = User::where('company_id', $request->target_id)->get();
            foreach ($users as $user) {
                $user->notify(new SystemNotification($request->title, $request->message, $request->url));
            }
        } elseif ($request->target_type === 'user') {
            $user = User::findOrFail($request->target_id);
            $user->notify(new SystemNotification($request->title, $request->message, $request->url));
        }

        return redirect()->route('admin.notifications.index')->with('success', 'Notification envoyée avec succès.');
    }
}
