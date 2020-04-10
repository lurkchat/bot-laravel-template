<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcessData extends Model
{
    protected $fillable = [
        'key', 'value', 'process_id'
    ];

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id');
    }
}
