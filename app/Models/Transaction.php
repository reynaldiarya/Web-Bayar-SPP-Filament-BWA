<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    use HasFactory, HasUuids;

    protected $primaryKey = 'uuid';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid');
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_uuid');
    }
}
