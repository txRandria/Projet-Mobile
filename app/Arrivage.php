<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Arrivage extends Model
{
    public function pertes(){
    	return $this->morphMany(DeclPerte::class,'perteAttachable');
    }
    public function charges(){
    	return $this->morphMany(DeclCharge::class,'chargeAttachable');
    }
}
