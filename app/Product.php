<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
	protected $primaryKey = 'pid';
    //

	public function category()
    {
        return $this->belongsTo('App\Category','catid');
    }

    public function manufacturer()
    {
        return $this->belongsTo('App\Manufacturer','mid');
    }

    public function getStockReminder($boxNum){
        $sql = <<<END
select pid
    , name
    , unitinstock
    , packinstock
    , boxinstock
    , unitperpack
    , unitperbox
    , (unitinstock + (packinstock*unitperpack) + (boxinstock*unitperbox)) as totalunitinstock
    , (unitinstock + (packinstock*unitperpack) + (boxinstock*unitperbox))/unitperbox as totalboxinstock
from products 
where (unitinstock + (packinstock*unitperpack) + (boxinstock*unitperbox))/unitperbox < $boNum;
END;
        return DB::select($sql);
    }

    public static function listWithCatMan(){

/*        $sql = <<<END
select pid
     , barcode
     , p.name
     , shortcut
     , salepriceunit as up
     , salepricepack as pp
     , salepricebox  as bp
     , unitinstock as su
     , packinstock as sp
     , boxinstock as sb
     , unitperpack as upp
     , unitperbox  as upb
     , isdrugs
     , c.name as catname
     , m.name as mname 
  from products p 
  join categories c 
  join manufacturers m 
    on (p.catid = c.catid and p.mid = m.mid ) 
END;*/
        $sql = <<<END
select pid
     , barcode
     , p.name
     , shortcut
     , salepriceunit as up
     , salepricepack as pp
     , salepricebox  as bp
     , unitinstock as su
     , packinstock as sp
     , boxinstock as sb
     , unitperpack as upp
     , unitperbox  as upb

  from products p ;

END;
        return DB::select($sql);
    }

    public static function searchProduct($searchKey){


        /*$sql = <<<END
select pid
     , barcode
     , p.name
     , shortcut
     , salepriceunit as up
     , salepricepack as pp
     , salepricebox  as bp
     , unitinstock as su
     , packinstock as sp
     , boxinstock as sb
     , unitperpack as upp
     , unitperbox  as upb
     , isdrugs
     , c.name as catname
     , m.name as mname 
  from products p 
  join categories c 
  join manufacturers m 
    on (p.catid = c.catid and p.mid = m.mid ) 
 where pid      like '%$searchKey%' 
    or p.name     like '%$searchKey%' 
    or shortcut like '%$searchKey%'
    or barcode like '%$searchKey%'
END;*/

$sql = <<<END
select pid
     , barcode
     , name
     , shortcut
     , salepriceunit as up
     , salepricepack as pp
     , salepricebox  as bp
     , unitinstock as su
     , packinstock as sp
     , boxinstock as sb
     , unitperpack as upp
     , unitperbox  as upb

from products 
where pid       like '%$searchKey%' 
    or name     like '%$searchKey%' 
    or shortcut like '%$searchKey%'
    or barcode  like '%$searchKey%'
END;

       return DB::select($sql);
    }



    public static function searchProductBarcode($searchKey){


$sql = <<<END
select pid
     , barcode
     , name
     , shortcut
     , salepriceunit as up
     , salepricepack as pp
     , salepricebox  as bp
     , unitinstock as su
     , packinstock as sp
     , boxinstock as sb
     , unitperpack as upp
     , unitperbox  as upb

from products 
where barcode  like '%$searchKey%'
END;

       return DB::select($sql);
    }

    public static function searchProductID($searchKey){


$sql = <<<END
select pid
     , barcode
     , name
     , shortcut
     , salepriceunit as up
     , salepricepack as pp
     , salepricebox  as bp
     , unitinstock as su
     , packinstock as sp
     , boxinstock as sb
     , unitperpack as upp
     , unitperbox  as upb

from products 
where pid  = $searchKey
END;

       return DB::select($sql);
    }


    public static function searchProductCategory($searchKey){


$sql = <<<END
select pid
     , barcode
     , name
     , shortcut
     , salepriceunit as up
     , salepricepack as pp
     , salepricebox  as bp
     , unitinstock as su
     , packinstock as sp
     , boxinstock as sb
     , unitperpack as upp
     , unitperbox  as upb

from products 
where products.catid  = $searchKey
END;

       return DB::select($sql);
    }


/*
    public function importer()
    {
        return $this->belongsToMany('App\Importer','productimportersaleassistants','pid','impid');
    }

    public function saleassistant()
    {
        return $this->belongsToMany('App\saleassistant','productimportersaleassistants','pid','said');
    }

    public function productimportersaleassistant()
    {
        return $this->hasMany('App\productimportersaleassistants','pid');
    }
*/
    public static function getSelectOption() {
        $rows = Product::all();
        $result = [null => 'Select Product'];
        foreach ($rows as $row) {
            $id       = $row->pid;
            $name     = $row->name;
            $shortcut = $row->shortcut;
            $barcode  = $row->barcode;
            $result[$id] = "$id:$name:$shortcut:$barcode";
        }
        return $result;
    }


    public static function updateStock($pid, $stock){
        Product::where('pid', '=', $pid)->update(   array('unitinstock' => $stock['unitinstock'], 'packinstock' => $stock['packinstock'], 'boxinstock' => $stock['boxinstock']) );
    }


    public static function getStock($products){
        if ( sizeof($products) > 0){
            $stringproducts = "(" . implode(',',$products) .")";
            $sql = <<<END
select pid
    ,unitinstock as su
    ,packinstock as sp
    ,boxinstock as sb
from products
where pid in {$stringproducts};
END;
            return DB::select($sql);
        }else{
            return array();
        }

    }


     public static function getNumberofProducts(){
        
        $sql = <<<END
select count(*) as nb
from products;
END;
        return DB::select($sql);

    }

    public static function getStockReminderPrint($input){

        $sql = <<<END
select p.pid
    , p.name
    , (p.unitinstock + (p.packinstock*p.unitperpack) + (p.boxinstock*p.unitperbox))/p.unitperbox as totalboxinstock
    , im.name as importer
    , inv.buypricebox buypricebox
from (select max(invid) latestinvid
    from inventories inv 
    group by pid)
    as temp 
        join inventories inv
        join products p 
        join importers im
            on temp.latestinvid = inv.invid
                and inv.pid = p.pid and inv.impid = im.impid 

END;
        $where = "";
        if (array_key_exists('pid', $input)){
            if ($input['pid'])
                $where .= " (pid = " . $input['pid'] . ") AND";
        }
        if (array_key_exists('keyword', $input)){
            if ($input['keyword'])
                $where .= " (p.name like = '%". $input['keyword']. "%' OR p.description like '%". $input['keyword']. "%' OR p.shortcut like '%". $input['keyword']. "%' OR barcode like '%". $input['keyword']. "%') AND" ;
        }

        if (array_key_exists('minimum', $input)){
            if ($input['minimum'])
                $where .= " ((unitinstock + (packinstock*unitperpack) + (boxinstock*unitperbox))/unitperbox < ". $input['minimum']." ) AND";
        }

        $where = substr($where, 0 , -4);
        if (strlen($where) > 0 )
            $where =  " where " . $where ;
        return $sql . $where . ";";

    }

}
