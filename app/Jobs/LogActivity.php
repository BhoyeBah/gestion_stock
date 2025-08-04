<?php

namespace App\Jobs;

use App\Models\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userId;
    public $action;
    public $description;

    public function __construct($userId, $action, $description = null)
    {
        $this->userId = $userId;
        $this->action = $action;
        $this->description = $description;
    }

    public function handle(): void
    {
        // Supprimer les activités de plus de 14 jours
        Activity::where('created_at', '<', now()->subDays(14))->delete();

        // Enregistrer la nouvelle activité
        Activity::create([
            'id' => (string) \Str::uuid(),
            'user_id' => $this->userId,
            'action' => $this->action,
            'description' => $this->description,
        ]);
    }
}

