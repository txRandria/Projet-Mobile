<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Numerotatiom extends Model
{
    public function mouvements(){
        return $this->morphMany(Mouvement::class,'mouvements');
    }
}
