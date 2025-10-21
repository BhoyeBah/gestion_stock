<?php
namespace App\Traits;
use App\Jobs\LogActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    
    public function saveActivity(string $action, string $description = null, array $extra = [])
    {
    
        $meta = array_merge([
            'ip'         => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
        ], $extra);

        LogActivity::dispatch(Auth::id(), $action, $description, $meta)
            ->onQueue('activities'); // optionnel
    }

}
