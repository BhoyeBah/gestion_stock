<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUuid;


class Tenant extends Model
{
    use HasFactory, HasUuid;
    

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'logo',
        'is_active',

    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // Un tenant a plusieurs utilisateurs
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Un tenant a plusieurs rôles (Spatie modifié)
    public function roles()
    {
        return $this->hasMany(\Spatie\Permission\Models\Role::class);
    }

    // Un tenant peut avoir plusieurs abonnements
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // L’abonnement actif (si on gère plusieurs historiques)
    public function currentSubscription()
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }
}
