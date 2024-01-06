<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'team_id',
        'first_name',
        'last_name',
        'city',
        'state',
        'country',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
}
