<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Category extends Model
{
    //
    protected $primaryKey = 'catid';
    protected $table = 'categories';

    public function products()
    {
        return $this->hasMany('App\Product','catid');
    }

    public static function getSelectOption(){
    	$rows = Category::all();
        $result = [null => 'Select Category'];
        foreach ($rows as $row) {
            $id       = $row->catid;
            $name     = $row->name;
            $result[$id] = "$id:$name";
        }
        return $result;
    }

    public static function getList(){
        $sql = <<<EOT
select c.catid
    , name
    , COALESCE(packname,"") as packname
    , COALESCE(boxname,"") as boxname
from categories c 
    left join categoryunitname cun
    on c.catid = cun.catid;

EOT;

        
        
        return DB::select($sql);
    }
}

