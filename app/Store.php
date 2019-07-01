<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'name', 'handle', 'api_key', 'api_password', 'channel_id'
    ];

    public function channel() {
        return $this->belongsTo('App\Channel');
    }
}
