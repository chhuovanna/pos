<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $primaryKey = 'saleid';
    protected $table = 'Loan';

    public function sale()
    {
        return $this->belongsTo('App\Sale','saleid');
    }
}
