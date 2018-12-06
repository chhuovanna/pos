<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockoutType extends Model
{
    protected $primaryKey = 'sotid';
    protected $table = 'stockoutType';

    public function sale()
    {
        return $this->hasMany('App\Sale','saleid');
    }

}
