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

    public static function getAssociateArray(){
        $sql = <<<EOT
select c.catid
    , name
    , COALESCE(packname,"") as packname
    , COALESCE(boxname,"") as boxname
from categories c 
    left join categoryunitname cun
    on c.catid = cun.catid;

EOT;

        $res = DB::select($sql);
        $assores = array();
        foreach ($res as $category) {
            $catid = $category->catid;
            $assores[$catid] = array($category->name, $category->packname, $category->boxname);
        }
        return $assores;
    }

   public static function saveunitname($input){
        
        DB::transaction(function () use ($input){
            $catids = Category::select('catid')->get();
           // print_r($input);
            foreach ($catids as $catid) {

                $id = $catid->catid;
                $packnamekey = $id.'_packname';
                $boxnamekey = $id.'_boxname';
                $sql = 'insert into categoryunitname values('.$id. ',"'.$input[$packnamekey].'","'.$input[$boxnamekey].'") ON DUPLICATE KEY UPDATE  packname = "'.$input[$packnamekey].'", boxname = "'.$input[$boxnamekey].'"  ';

                DB::statement($sql);
            }
        });

        DB::commit();
 
    }
}