<?php

namespace App\Jobs;

use App\Models\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class LogActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userId;
    public $action;
    public $description;
    public $meta; // <- pour stocker ip, user-agent, données supplémentaires

    /**
     * @param string $userId       UUID de l'utilisateur
     * @param string $action       Nom de l'action effectuée
     * @param string|null $description Description de l'action
     * @param array $meta          Données supplémentaires (IP, User-Agent, etc.)
     */
    public function __construct($userId, $action, $description = null, array $meta = [])
    {
        $this->userId = $userId;
        $this->action = $action;
        $this->description = $description;
        $this->meta = $meta;
    }

    public function handle(): void
    {
        
        // Supprimer les activités de plus de 14 jours
        Activity::where('created_at', '<', now()->subDays(14))->delete();
        

        // Enregistrer la nouvelle activité
        Activity::create([
            'id'          => (string) Str::uuid(),
            'user_id'     => $this->userId,
            'action'      => $this->action,
            'description' => $this->description,
            'meta'        => json_encode($this->meta), // <- si tu as une colonne meta dans ta table
        ]);
    }
}
