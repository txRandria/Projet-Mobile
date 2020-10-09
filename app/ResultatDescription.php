<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResultatDescription extends Model
{
    public function descriptionAttachable(){
        $this->morphTo();
    }
}
