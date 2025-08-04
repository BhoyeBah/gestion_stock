<?php
namespace App\Traits;

use App\Jobs\LogActivity;

trait LogsActivity
{
    public function saveActivity(string $action, string $description = null)
    {
        $userId = auth()->check() ? auth()->id() : null;
        LogActivity::dispatch($userId, $action, $description);
    }
}
