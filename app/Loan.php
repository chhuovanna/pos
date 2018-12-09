<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $primaryKey = 'saleid';
    protected $table = 'loan';

    public function sale()
    {
        return $this->belongsTo('App\Sale','saleid');
    }
}
