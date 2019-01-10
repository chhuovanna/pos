<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class WinmoneyprizeProduct extends Model
{
	protected $table = 'winmoneyprizeproduct';

	public static function getWinMoneyPrizeWithProduct($wmpid){
		$sql = <<<script
select wmpp.*, p.name
from winmoneyprizeproduct wmpp join products p
	on wmpp.pid = p.pid
where wmpp.wmpid = $wmpid;
script;
		return DB::select($sql);
	}

}
