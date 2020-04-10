<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    public function data()
    {
        return $this->hasMany(ProcessData::class, 'process_id');
    }
}
