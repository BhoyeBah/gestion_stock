<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;


class Activity extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ["user_id", "action", "description"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
