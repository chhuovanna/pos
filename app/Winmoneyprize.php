<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Winmoneyprize extends Model
{
	protected $primaryKey = 'wmpid';
    protected $table = 'winmoneyprize';
    
    public function customer()
    {
        return $this->belongsTo('App\Customer','cusid');
    }

    public static function getWinMoneyPrizeWithCustomer($wmpid){
    	$sql = <<<script
select wmp.*, c.name
from winmoneyprize wmp join customers c
	on wmp.cusid = c.cusid
where wmp.wmpid = $wmpid;
script;
		$res = DB::select($sql);
    	return $res[0];
    }
}
