<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MX extends Model
{
    public function resultatsAnalyse(){
        return $this->morphMany(ResultatAnalyse::class,'resultatAttachable');
    }
    public function resultatsDescription(){
        return $this->morphMany(ResultatDescription::class,'descriptionAttachable');
    }
}
