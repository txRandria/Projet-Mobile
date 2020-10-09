<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResultatAnalyse extends Model
{
    public function resultatAttachable(){
        $this->morphTo();
    }
}
