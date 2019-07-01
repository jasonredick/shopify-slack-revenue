<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use Notifiable;

    protected $fillable = [
        'name', 'web_hook'
    ];

    protected $slack_url = null;

    public function stores() {
        return $this->hasMany('App\Store');
    }

    public function routeNotificationForSlack() {
        return $this->slack_url;
    }

    public function setSlackChannel($url) {
        $this->slack_url = $url;
        return $this;
    }
}
