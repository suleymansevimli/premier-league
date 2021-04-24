<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PremierLeague extends Model
{
    protected $fillable = [
        'point','won','equalization','lose','weekly_score','week'
    ];
}
