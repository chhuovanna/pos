<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    protected $primaryKey = 'cusid';

    public function sale()
    {
        return $this->hasMany('App\Sale','cusid');
    }

    

    public static function getSelectOption(){
        $rows = Customer::orderBy('cusid')->get();
        $result = [null => 'Select Customer'];
        foreach ($rows as $row) {
            $id       = $row->cusid;
            $name     = $row->name;
            $result[$id] = "$id:$name";
        }
        return $result;
    }
    
    public static function getCustomerWithLoan(){
        $sql= <<<END
select c.cusid, c.name
from customers c join sales s using(cusid)
where sotid = 2;
END;
        $rows = DB::select($sql);
        $result = [null => 'Select Customer'];
        foreach ($rows as $row) {
            $id       = $row->cusid;
            $name     = $row->name;
            $result[$id] = "$id:$name";
        }
        return $result;
    }
}
