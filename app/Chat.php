<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    public function author(){
        return self::belongsTo('App\User','author_id');
    }
}
