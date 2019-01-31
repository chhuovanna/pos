<?php

namespace App;

use App\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Inventory extends Model
{
    //
    protected $primaryKey = 'invid';
    protected $table = 'inventories';

    public function product()
    {
        return $this->belongsTo('App\Product','pid');
    }

  	public function importer()
    {
        return $this->belongsTo('App\Importer','impid');
    }  

    public static function updatestock($pid){


        $sql = <<<END
select  sum(unitinstock) as su
        , sum(packinstock) as sp
        , sum(boxinstock) as sb
from inventories

where pid = ? 
    and finish =0;
END;
        $stock = DB::select($sql,[$pid]);
		$sql = <<<END
update products set
    unitinstock     = ?
    ,packinstock    = ?
    ,boxinstock     = ?
where pid = $pid;
END;

        foreach ($stock as $test) {
			
			if ( is_null($test->su) && is_null($test->sp) && is_null($test->sb) ){
				DB::update($sql,[0,0,0]);
			}else{
				DB::update($sql,[$test->su,$test->sp,$test->sb]);
			}
        }
        
    }


    public static function updateInventory($pid, $stock){
        $inventories = Inventory::whereRaw('pid = ? and finish = 0', array($pid))->orderBy('invid','DESC')->get();
        if ( count($inventories) > 0){
            $inventories[0]->unitinstock = $stock['unitinstock'];
            $inventories[0]->packinstock = $stock['packinstock'];
            $inventories[0]->boxinstock  = $stock['boxinstock'];
    		if ( $stock['unitinstock'] == 0 && $stock['packinstock'] == 0 && $stock['boxinstock'] == 0 ){
    			$inventories[0]->finish	 = 1;
    		}
            $inventories[0]->save();

            for ( $i = 1; $i < sizeOf($inventories); $i ++){
                $inventories[$i]->unitinstock = 0;    
                $inventories[$i]->packinstock = 0;    
                $inventories[$i]->boxinstock  = 0;    
                $inventories[$i]->finish      = 1;    
                $inventories[$i]->save();
            }
        }else{
            
            if ( $stock['unitinstock'] != 0 || $stock['packinstock'] != 0 || $stock['boxinstock'] != 0 ){
                

                $inventories = Inventory::whereRaw('pid = ? and finish = 1', array($pid))->orderBy('invid','DESC')->first();
                
                if (count($inventories) > 0){
                    $inventories->finish  = 0;

                    $inventories->unitinstock = $stock['unitinstock'];
                    $inventories->packinstock = $stock['packinstock'];
                    $inventories->boxinstock  = $stock['boxinstock'];
                    $inventories->save();
                }else{

                /*    $inventories = new Inventory();


                    $inventories->finish  = 0;

                    $inventories->unitinstock = $stock['unitinstock'];
                    $inventories->packinstock = $stock['packinstock'];
                    $inventories->boxinstock  = $stock['boxinstock'];
                    $inventories->unitinstock = $stock['unitinstock'];
                    $inventories->packinstock = $stock['packinstock'];
                    $inventories->boxinstock  = $stock['boxinstock'];

                    $inventories->save();
*/
                }
            }
            
        }

    }

    public static function searchinventory($searchkey){
        $where = array();
       
        $sql = <<<EOT
Select i.pid as PID
    , p.name as Product
    , sum(importunit) as sumunit
    , sum(importpack) as sumpack
    , sum(importbox) as sumbox
    , round(avg(buypriceunit),4 ) as avgup
    , round(avg(buypricepack),4) as avgpp
    , round(avg(buypricebox),4) as avgbp
    , sum(i.amount) as sumstt 
    , sum(i.unitinstock) as sumsu
    , sum(i.packinstock) as sumsp
    , sum(i.boxinstock) as sumsb
    , finish
from inventories i join products p on i.pid = p.pid
    
EOT;




        if (array_key_exists('importdate_start', $searchkey) ){
            $where[] = " i.importdate between '" 
                . $searchkey['importdate_start'] . "' and '" 
                . $searchkey['importdate_end']  . "' "; 
        }
        
        if (array_key_exists('finish', $searchkey) ){
            $where[] = " `finish` = " 
                . $searchkey['finish']  . " ";   
        }

        if (array_key_exists('pid', $searchkey) ){
            $where[] = " i.pid = " 
                . $searchkey['pid']  . " "; 
        }

        if (array_key_exists('impid', $searchkey) ){
            $where[] = " i.impid = " 
                . $searchkey['impid']  . " "; 
        }

        if (array_key_exists('invid', $searchkey) ){
            $where[] = " i.invid = " 
                . $searchkey['invid']  . " "; 
        }

        if (sizeof($where) > 0){
            $sql .= " where ";

            for ($i = 0 ; $i < sizeof($where) ; $i++){
                $sql .= $where[$i];

                if ($i < (sizeof($where) -1)){
                    $sql .= " AND ";
                }
            }
        }

        
        $sql .= " group by i.pid, i.finish order by i.finish asc;";
        return DB::select($sql);
        //return $sql;
    }

    public static function quickAdd($input){
        
        $importedprices = Inventory::where('pid', '=' , $input['pid'])->orderBy('invid','DESC')->first();

        if ($importedprices){
            $myInventory = new Inventory();
            $myInventory->pid = $input['pid'];
            $myInventory->impid = $importedprices->impid;
            $myInventory->importunit = 0;
            $myInventory->importpack = 0;
            $myInventory->importbox = $input['box'];
            $myInventory->buypricebox = $importedprices->buypricebox;
            $myInventory->buypriceunit = $importedprices->buypriceunit;
            $myInventory->buypricepack = $importedprices->buypricepack;
            $myInventory->unitinstock = 0;
            $myInventory->packinstock = 0;
            $myInventory->boxinstock = $input['box'];


            $myInventory->finish = 0;
            $myInventory->amount = $myInventory->importbox * $myInventory->buypricebox;
            if ($myInventory->save()){
                Inventory::updatestock($input['pid']);
                return true;
            }else{
                return false;
            }
                
                
        }else{
            $product = Product::find($input['pid']);
            if (!is_null($product->importpricebox)){
                $myInventory = new Inventory();
                $myInventory->pid = $input['pid'];
                $myInventory->importunit = 0;
                $myInventory->importpack = 0;
                $myInventory->importbox = $input['box'];
                $myInventory->buypricebox = $product->importpricebox;
                $myInventory->buypriceunit = $product->importpriceunit;
                $myInventory->buypricepack = $product->importpricepack;
                $myInventory->unitinstock = 0;
                $myInventory->packinstock = 0;
                $myInventory->boxinstock = $input['box'];


                $myInventory->finish = 0;
                $myInventory->amount = $myInventory->importbox * $myInventory->buypricebox;
                if ($myInventory->save()){
                    Inventory::updatestock($input['pid']);
                    return true;
                }else{
                    return false;
                }


            }else{
                return false;
            }
        }

            
    }

}

