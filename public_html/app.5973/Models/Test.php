<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $guarded = [];

    public function questions()
    {
        return $this->hasMany(TestQuestion::class);
    }


    public function results()
    {
        return $this->hasMany(TestResult::class);
    }
}
