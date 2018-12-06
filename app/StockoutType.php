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


 	public static function getSelectOption(){
        $rows = StockoutType::orderBy('sotid')->get();
        $result = [null => 'Select Stockout Type'];
        foreach ($rows as $row) {
            $id       = $row->sotid;
            $name     = $row->type;
            $result[$id] = "$id:$name";
        }
        return $result;
    }
}
