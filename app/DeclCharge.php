<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeclCharge extends Model
{
    //
    public function chargeAttachable(){
    	$this->morphTo();
    }
}
