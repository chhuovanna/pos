<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Sale extends Model
{
	protected $primaryKey = 'saleid';
    //
    public function customer()
    {
        return $this->belongsTo('App\Customer','cusid');
    }


    public static function searchsale($searchkey){

        $prizeproducts = array();
        $prize = true;
		$where = array();
        //uncleared loan 
    	$sql = <<<EOT
Select s.saleid 
	, total
	, ftotal
	, cusid
    , s.sotid
    , l.amount as amount
	  
From  sales s 
    left join loan l
        on (s.saleid = l.saleid and l.state = 0) 

	
EOT;
 
        $sqlprize = <<<END
select pid
    , sum(unitquantity) as unitprize
    , sum(packquantity) as packprize 
    , sum(boxquantity)  as boxprize
from sales s join saleproducts
    using(saleid)
END;

    	if (array_key_exists('saleid', $searchkey) ){
    		$where[] = " `s`.`saleid` = " 
    			. $searchkey['saleid'] . " ";	
    	}

		if (array_key_exists('saledate_start', $searchkey) ){
    		$where[] = " `saledate` between '" 
    			. $searchkey['saledate_start'] . "' and '" 
    			. $searchkey['saledate_end'] 	. "' ";	
    	}
    	if (array_key_exists('discount_start', $searchkey) ){
    		$where[] = " `discount` between " 
    			. $searchkey['discount_start'] . " and " 
    			. $searchkey['discount_end'] . " ";	
    	}

		if (array_key_exists('grandtotalrange', $searchkey) ){
    		$where[] = " `ftotal` between " 
    			. $searchkey['grandtotalrange'] . " ";	
    	}

    	if (array_key_exists('cusid', $searchkey) ){
    		$where[] = " `s`.`cusid` = " 
    			. $searchkey['cusid']  . " ";	
    	}

        $sqlprizewhere = "";

        if (array_key_exists('sotid', $searchkey) ){
            $where[] = " `s`.`sotid` = " 
                . $searchkey['sotid']  . " ";   
            if ($searchkey['sotid'] != 3){ //not prize stockout type or not default case
                $prize = false;
            }

        }else{
            $sqlprizewhere = " `s`.`sotid` = 3 "; //winning product prize
        }
        
        $sqlwhere = "";


    	if (sizeof($where) > 0){

    		for ($i = 0 ; $i < sizeof($where) ; $i++){
    			$sqlwhere .= $where[$i];

    			if ($i < (sizeof($where) -1)){
    				$sqlwhere .= " AND ";
    			}
    		}

            $sql .= ("Where " . $sqlwhere );      
            
            if ($sqlprizewhere){
                $sqlprizewhere .= " AND ";
            }
    	}
    	

    	$sales = DB::select($sql);

    	if ( count($sales) > 0){

   			
			$saleids = "( ";
			foreach ($sales as $sale) {
				$saleids .= $sale->saleid . ",";
			}

			$saleids = substr($saleids, 0, strlen($saleids)-1) . ")";

			$sql = <<<EOT
Select pid
	, sum(unitquantity) as sumunit
	, sum(packquantity) as sumpack 
	, sum(boxquantity) as sumbox  
From saleproducts
Where saleid in $saleids
group by pid;
EOT;

			$saleproducts = DB::select($sql);

			$pids = "( ";
            $temp = array();
			foreach ($saleproducts as $product) {
				$pids .= $product->pid . ",";
                $temp[$product->pid] = $product;
			}

            $saleproducts = $temp;

			$pids = substr($pids, 0, strlen($pids)-1) . ")";

			$sql = <<<EOT
Select i.pid
	, buypriceunit
	, buypricepack
	, buypricebox
From inventories i join (Select max(invid) as invid
						From inventories
						Where pid in $pids
						group by pid) as temp
	on i.invid = temp.invid
Order by i.pid;
EOT;
			$productprices = DB::select($sql);

            unset($temp);
            $temp = array();

            foreach ($productprices as $productprice) {
                $temp[$productprice->pid] = $productprice;
            }

            $productprices = $temp;
            unset($temp);

            if ($prize){
                //data of win product prize

                $sqlprize .= ("Where " . $sqlprizewhere . $sqlwhere ." group by pid");
                $prizeproducts = DB::select($sqlprize);
                $temp = array();

                foreach ($prizeproducts as $prizeproduct) {
                    $temp[$prizeproduct->pid] = $prizeproduct;
                }

                $prizeproducts = $temp;
                unset($temp);
            }

			return array('sales' 		=> $sales 
    				, 'saleproducts' 	=> $saleproducts
    				, 'productprices' 	=> $productprices
                    , 'prizeproducts'   => $prizeproducts
                );

		}

		return false;
    	

    }
/*

    public static function searchsale($searchkey){

        $where = array();
        $sql = <<<EOT
Select saleid 
    , total
    , ftotal
    , cusid
      
From  sales s

    
EOT;
 


        if (array_key_exists('saleid', $searchkey) ){
            $where[] = " `s`.`saleid` = " 
                . $searchkey['saleid'] . " ";   
        }

        if (array_key_exists('saledate_start', $searchkey) ){
            $where[] = " `saledate` between '" 
                . $searchkey['saledate_start'] . "' and '" 
                . $searchkey['saledate_end']    . "' "; 
        }
        if (array_key_exists('discount_start', $searchkey) ){
            $where[] = " `discount` between " 
                . $searchkey['discount_start'] . " and " 
                . $searchkey['discount_end'] . " "; 
        }

        if (array_key_exists('grandtotalrange', $searchkey) ){
            $where[] = " `ftotal` between " 
                . $searchkey['grandtotalrange'] . " ";  
        }

        if (array_key_exists('cusid', $searchkey) ){
            $where[] = " `s`.`cusid` = " 
                . $searchkey['cusid']  . " ";   
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

        

        $sales = DB::select($sql);

        if ( count($sales) > 0){

            
            $saleids = "( ";
            foreach ($sales as $sale) {
                $saleids .= $sale->saleid . ",";
            }

            $saleids = substr($saleids, 0, strlen($saleids)-1) . ")";

            $sql = <<<EOT
Select pid
    , sum(unitquantity) as sumunit
    , sum(packquantity) as sumpack 
    , sum(boxquantity) as sumbox  
From saleproducts
Where saleid in $saleids
group by pid;
EOT;

            $saleproducts = DB::select($sql);

            $pids = "( ";
            $temp = array();
            foreach ($saleproducts as $product) {
                $pids .= $product->pid . ",";
                $temp[$product->pid] = $product;
            }

            $saleproducts = $temp;

            $pids = substr($pids, 0, strlen($pids)-1) . ")";

            $sql = <<<EOT
Select i.pid
    , buypriceunit
    , buypricepack
    , buypricebox
From inventories i join (Select max(invid) as invid
                        From inventories
                        Where pid in $pids
                        group by pid) as temp
    on i.invid = temp.invid
Order by i.pid;
EOT;
            $productprices = DB::select($sql);

            unset($temp);
            $temp = array();

            foreach ($productprices as $productprice) {
                $temp[$productprice->pid] = $productprice;
            }

            $productprices = $temp;
            unset($temp);

            return array('sales'        => $sales 
                    , 'saleproducts'    => $saleproducts
                    , 'productprices'   => $productprices);

        }

        return false;
        

    }

*/

    public static function getSixMonthSale($start_date) {

        
        $sql = <<<EOT
select sum(ftotal) as sftotal
    , year(saledate) as year
    ,month(saledate) as month
from sales
where saledate between "$start_date" and curdate()
group by year(saledate)
    ,month(saledate);
    
EOT;
        $salebymonth = DB::select($sql);


        if ( count($salebymonth) > 0 ){
            $sql = <<<EOT
        
select  year(saledate) as year
    ,month(saledate) as month
    , sum(unitquantity*buypriceunit 
        + packquantity*buypricepack  
        + boxquantity*buypricebox ) as stotal
from (sales s join saleproducts sp on s.saleid = sp.saleid) join inventories i on sp.pid = i.pid 
where saledate between "$start_date" and curdate()
    and i.invid = (Select max(invid) as invid
                        From inventories i1
                        Where i1.pid = sp.pid)
group by year(saledate)
    ,month(saledate);
EOT;
            $expensebymonth = DB::select($sql);

            return ['salebymonth'       => $salebymonth
                    , 'expensebymonth'  => $expensebymonth];

        }else{

            return ['salebymonth'       => array()
                    , 'expensebymonth'  => array()
                ];
        }

    }

    public static function getNumberofSales(){
        $sql = <<<EOT

select count(*) as nb
from sales;
EOT;
        return DB::select($sql);
    }

    public function stockouttype(){
         return $this->belongsTo('App\StockoutType','sotid');
    }
    public function loan(){
         return $this->hasOne('App\Loan','saleid');
    }


    public static function getSaleForReceipt($saleid){
        $sql = <<<EOT
select name
    , s.saleid
    , exchangerate
    , total 
    , cast(total * exchangerate as Decimal(10))as totalr
    , cast(discount * total/100 as Decimal(10,4))  as discountd
    , cast( (discount * total/100)*exchangerate  as Decimal(10))  as discountr
    , ftotal
    , cast(ftotal * exchangerate as Decimal(10)) as ftotalr
    , recievedd
    , recievedr
    , l.amount as loand
    , cast( l.amount * exchangerate as Decimal(10))as loanr
    , 0 as changed 
    , 0 as changer
    , 0 as changertotal 
    , s.created_at
from ( sales s join customers c 
        on  s.cusid = c.cusid) 
        left join loan l
        on s.saleid = l.saleid
where s.saleid = $saleid;
EOT;
        $sale = DB::select($sql);

        $sale = $sale[0];      

        if(!$sale->loand){
            $sale->loand = 0;
            $sale->loanr = 0;
        }

        $recieved = $sale->recievedd + ($sale->recievedr/$sale->exchangerate);
        if ($recieved > $sale->ftotal){
            $sale->changed = intval($recieved - $sale->ftotal);
            $sale->changer = ($recieved - $sale->ftotal - $sale->changed)*$sale->exchangerate;
            $sale->changertotal = ($recieved - $sale->ftotal)*$sale->exchangerate;
        } 
        return $sale;

    }
}
