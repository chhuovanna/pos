<?php

namespace App\Admin\Controllers;

use App\Product;
use App\Category;
use App\Manufacturer;
use App\Exchangerate;
use App\StockReminder;
use App\Importer;
use App\Inventory;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    use ModelForm;

    protected $script_grid = <<<SCRIPT
$(document).ready(function() {
    $("[name='catid']").select2({ width: '170px' });
    $("[name='mid']").select2({ width: '170px' });

    $("[placeholder='Keyword(PID,barcode,name,shortcut,description)']").focus();
    $("[name='catid']").on('change', function(){
        $('.btn-primary')[0].click();
    });

    
    $($('table')[0]).addClass('myfirsttable');
    $('table.myfirsttable th:nth-child(4)').css("background-color", "#ffff99");
    $('table.myfirsttable th:nth-child(5)').css("background-color", "#ffff99");
    $('table.myfirsttable th:nth-child(6)').css("background-color", "#ffff99");
    $('table.myfirsttable td:nth-child(4)').css("background-color", "#ffff99");
    $('table.myfirsttable td:nth-child(5)').css("background-color", "#ffff99");
    $('table.myfirsttable td:nth-child(6)').css("background-color", "#ffff99");
    

    $('table.myfirsttable th:nth-child(7)').css("background-color", "#eefc85");
    $('table.myfirsttable th:nth-child(8)').css("background-color", "#eefc85");
    $('table.myfirsttable th:nth-child(9)').css("background-color", "#eefc85");
    $('table.myfirsttable td:nth-child(7)').css("background-color", "#eefc85");
    $('table.myfirsttable td:nth-child(8)').css("background-color", "#eefc85");
    $('table.myfirsttable td:nth-child(9)').css("background-color", "#eefc85");

    $('table.myfirsttable th:nth-child(10)').css("background-color", "#ccffcc");
    $('table.myfirsttable th:nth-child(11)').css("background-color", "#ccffcc");
    $('table.myfirsttable th:nth-child(12)').css("background-color", "#ccffcc");
    $('table.myfirsttable td:nth-child(10)').css("background-color", "#ccffcc");
    $('table.myfirsttable td:nth-child(11)').css("background-color", "#ccffcc");
    $('table.myfirsttable td:nth-child(12)').css("background-color", "#ccffcc");


    $('th:nth-child(13)').css("background-color", "#66ffcc");
    $('th:nth-child(14)').css("background-color", "#66ffcc");
    $('td:nth-child(13)').css("background-color", "#66ffcc");
    $('td:nth-child(14)').css("background-color", "#66ffcc");

    $('.quickadd').off('click');
    $('.quickadd').on('click', function(){
        var numbox = prompt("Please enter number of imported box:", "5");
        if (numbox == null || numbox == "" || isNaN(numbox) || numbox <= 0) {
            toastr.error('Fail');
        } else {

            $.ajax({
                type:"GET",
                url:"inventory/quickadd",
                data:{box: numbox, pid:$(this).data('id')},    
                success: function (data) {
                    if(data == 1){
                        $.pjax.reload('#pjax-container');
                        toastr.success('Added');
                    }else{
                        toastr.error('Fail');
                    }
                    console.log(data);
                },
                error: function(data){
                    console.log(data);
                }
            }); 

            
        }
    });

    
    
});
SCRIPT;

    protected $script_form = <<<SCRIPT

function initiateNumber(id){
    if (!$('#'+id).val() || isNaN($('#'+id).val()) ){
        $('#'+id).val(0);
    }
}

$(document).off('keyup','#salepriceunit');
$(document).off('keyup','#salepricepack');
$(document).off('keyup','#salepricebox');
$(document).off('keyup','#importpriceunit');
$(document).off('keyup','#importpricepack');
$(document).off('keyup','#importpricebox');
$(document).off('keyup','#unitperpack');
$(document).off('keyup','#unitperbox');

$(document).on('keyup','#unitperpack', function(){ initiateNumber('unitperpack');});
$(document).on('keyup','#unitperbox', function(){ initiateNumber('unitperbox');});


$(document).on('keyup','#salepriceunit',function(){
    initiateNumber('salepriceunit');
    var amount = new Decimal( $('#salepriceunit').val() );
    $('#spur').val(amount.mul( $('#exchangerate').val() ));
});


$(document).on('keyup','#salepricepack',function(){
    initiateNumber('salepricepack');
    var amount = new Decimal( $('#salepricepack').val() );
    $('#sppr').val(amount.mul( $('#exchangerate').val() ));
});


$(document).on('keyup','#salepricebox',function(){
    initiateNumber('salepricebox');
    var amount = new Decimal( $('#salepricebox').val() );
    $('#spbr').val(amount.mul( $('#exchangerate').val() ));
});

$(document).on('keyup','#importpriceunit',function(){
    initiateNumber('importpriceunit');
    var amount = new Decimal( $('#importpriceunit').val() );
    $('#ipur').val(amount.mul( $('#exchangerate').val() ));
});

$(document).on('keyup','#importpricepack',function(){
    initiateNumber('importpricepack');
    var amount = new Decimal( $('#importpricepack').val() );
    $('#ippr').val(amount.mul( $('#exchangerate').val() ));
});

$(document).on('keyup','#importpricebox',function(){
    initiateNumber('importpricebox');
    
    var amount = new Decimal( $('#importpricebox').val() );
    $('#ipbr').val(amount.mul( $('#exchangerate').val() ));
    
    if ( ($('#unitperpack').val() > 0)
        && ($('#unitperbox').val() > 0)
      /*  && ($('#importpriceunit').val() == 0) 
        && ($('#importpricepack').val() == 0) */){

        var importpriceunit = new Decimal( amount.div( $('#unitperbox').val() ) );
        $('#importpriceunit').val(importpriceunit); 
        $('#importpriceunit').keyup();

        $('#importpricepack').val( importpriceunit.mul( $('#unitperpack').val() ) );
        $('#importpricepack').keyup();
    }
});
SCRIPT;
    
    


    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('Product');
            $content->description('List');
            $content->body($this->grid());
            //$content->body($this->stockReminderGrid());
        });
    }

    public function stockreminder()
    {
        return Admin::content(function (Content $content) {

            $content->header('Product');
            $content->description('Stock reminder');
            $content->body($this->stockReminderGrid());
        });
    }


    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('Product');
            $content->description('Edit');

            $content->body($this->formEdit()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('Product');
            $content->description('Create New Product');

            $content->body($this->form());
        });
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $script = $this->script_grid;   
        return Admin::grid(Product::class, function (Grid $grid)  use ($script){


            $grid->filter(function ($filter) {

                $filter->disableIdFilter();

                $filter->equal('pid');
                $filter->where(function ($query) {

                    $query->whereRaw("`pid` like '%{$this->input}%' OR `barcode` like '%{$this->input}%' OR `name` like '%{$this->input}%' OR `shortcut` like '%{$this->input}%' OR `description` like '%{$this->input}%'");

                    }, 'Keyword');

                
                $categories = Category::getSelectOption();
                $filter->equal('catid')->select($categories);

                $manufacturers = Manufacturer::getSelectOption();
                $filter->equal('mid')->select($manufacturers);
            
            });   

            $grid->model()->with('category');
            $grid->model()->with('manufacturer');
            $grid->model()->orderBy('pid','DESC');
            //$grid->paginate(5);
            $grid->disableBatchDeletion();
            $grid->disableRowSelector();
            
           
            $grid->pid('ID');
            $grid->barcode('Barcode')->sortable();
            $grid->name('Name')->sortable();           

            /*$grid->shortcut('Shortcut');
            $grid->description('Desc')->limit(20)->ucfirst();
            */
            $grid->salepriceunit('UnitPri')->sortable();
            $grid->salepricepack('PackPri')->sortable();
            $grid->salepricebox('BoxPri')->sortable();

            $grid->importpriceunit('ImpoUnitPri')->sortable();
            $grid->importpricepack('ImpoPackPri')->sortable();
            $grid->importpricebox('ImpoBoxPri')->sortable();
            //$grid->photopath('Photo');
            
            $grid->unitinstock('UnitSto')->sortable();
            $grid->packinstock('PackSto')->sortable();
            $grid->boxinstock('BoxSto')->sortable();
            $grid->unitperpack('Unit/Pack');
            $grid->unitperbox('Unit/Box');
            
/*            $grid->isdrugs('Drug?')->display(function ($isdrugs) {
                return $isdrugs ? 'YES' : 'NO';
            }); */      
            
            $grid->category()->name('Category');
            $grid->manufacturer()->name('Manuf');

            $grid->actions(function ($actions) {

                // append an action.
                $actions->append('<a title="Add Inventory" href="inventory/create/' .$actions->getKey(). '"><i class="fa fa-plus"></i></a>');
                $actions->append('<a title="Inventory Quick Add" data-id="'.$actions->getKey().'" class="quickadd"><i class="fa fa-plus-circle"></i></a>');


            });

            Admin::script($script);
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function stockReminderGrid()
    {
        return Admin::grid(StockReminder::class, function (Grid $grid) {

            $grid->filter(function ($filter) {


                $importers = Importer::getSelectOption();
                $filter->equal('impid')->select($importers);
                $categories = Category::getSelectOption();
                $filter->equal('catid')->select($categories);

                $filter->where(function ($query) {

                    $query->whereRaw("`pid` like '%{$this->input}%' OR `barcode` like '%{$this->input}%' OR `name` like '%{$this->input}%' OR `shortcut` like '%{$this->input}%' OR `description` like '%{$this->input}%'");

                    }, 'Keyword');
                $filter->where(function ($query) {

                    $query->whereRaw("(unitinstock+(packinstock*unitperpack)+(boxinstock*unitperbox))/unitperbox <= {$this->input}");

                    }, 'Minimum box in stock');


            });   

          
            $grid->disableBatchDeletion();
            $grid->disableRowSelector();
            $grid->disableActions();
            $grid->disableCreation();
            $grid->disableExport();

           
            $grid->pid('ID');
            $grid->name('Name');           
            $grid->unitinstock('UnitSto')->sortable();
            $grid->packinstock('PackSto')->sortable();
            $grid->boxinstock('BoxSto')->sortable();
            $grid->unitperpack('Unit/Pack');
            $grid->unitperbox('Unit/Box');
            $grid->totalunitinstock('TotalUnitSto');
            $grid->totalboxinstock('TotalBoxSto');

            $script = <<<script
$("[placeholder='Minimum box in stock']").keyup(function(){
    if (!$(this).val() || isNaN($(this).val()) ){
            $(this).val(0);
        } 
    });
$(document).ready(function(){

    $("[name='catid']").select2({ width: '170px' });
    $("[name='impid']").select2({ width: '170px' });
    
    $('.form-inline').parent().append('<a  href="javascript:void(0);" id="print" class="btn btn-sm btn-twitter" ><i class="fa fa-print"></i>&nbsp;&nbsp;Print</a>');
    $('.form-inline').parent().append('&nbsp;&nbsp;&nbsp;&nbsp;');
    $('#print').click(function(){
        var pid = $('[name="pid"]').val();
        var keyword = $('[placeholder="Keyword"]').val();
        var minimum = $('[placeholder="Minimum box in stock"]').val();
        var catid = $('[name="catid"]').val();
        var impid = $('[name="impid"]').val();
        var url = '/admin/product/stockreminder/print?pid=' + pid + '&keyword=' + keyword + '&minimum=' + minimum  + '&catid=' + catid + '&impid=' + impid;

        var printwindow = window.open(  url,'_blank', 'height=700,width=700');
        
    
    });

});

script;
            Admin::script($script);

            /*var ele = '<div class="btn-group pull-right" style="margin-right: 10px"><a href="javascript:void(0);" class="btn btn-sm btn-twitter" id="print"><i class="fa fa-print"></i>&nbsp;&nbsp;Print';
    ele = ele + '</a></div>';*/

       
        });

    }
   
    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $script = $this->script_form;
        return Admin::form(Product::class, function (Form $form) use ($script){

            $exchangerate = Exchangerate::where('currentrate',1)->first();

            $form->display('pid', 'Product ID');
            
            
            //$form->text('barcode','Barcode Number')->rules('unique:products,barcode')->attribute('pattern','[0-9]+');
            $form->text('barcode','Barcode Number')->attribute('pattern','[0-9]+');
            
            
            $form->text('name','Product Name')->rules( 'required|unique:products,name');
            $form->text('shortcut','Shortcut Name');
            $form->textarea('description', 'Description');

            $attribute = array('pattern'=>'[0-9]+', "autocomplete"=>"off", "style"=>"width: 200px");            
            $attribute1 = array('pattern'=>'[0-9]+', "autocomplete"=>"off", "style"=>"width: 200px;background-color:#f2cff7");            
            $style = ["style"=>"width:115px"];
            $style1 = ["style"=>"width:115px; background-color:#def9fc"];
            $style2 = ["style"=>"width:115px; background-color:#b6d0f9"];

            
            $form->text('exchangerate','Exchange Rate')->readOnly()->attribute($style)->value($exchangerate->amount);
            
            $form->text('unitperbox','Number of Units Per Box')->rules('required')->attribute($attribute)->value(0);
            $form->text('unitperpack','Number of Units Per Pack')->rules('required')->attribute($attribute)->value(0);
            

            $form->text('importpricebox','Import Pice Per Box')->rules('required')->attribute($style1)->value(0);
            $form->text('ipbr','In Riel')->readOnly()->attribute($style);
            $form->text('importpricepack','Import Pice Per Pack')->rules('required')->attribute($style1)->value(0);
            $form->text('ippr','In Riel')->readOnly()->attribute($style);
            $form->text('importpriceunit','Import Pice Per Unit')->rules('required')->attribute($style1)->value(0);
            $form->text('ipur','In Riel')->readOnly()->attribute($style);

            $form->text('boxinstock','Box In Stock (Optional)')->attribute($attribute1);

            


            $form->text('salepricebox','Sale Pice Per Box')->rules('required')->attribute($style2)->value(0);
            $form->text('spbr','In Riel')->readOnly()->attribute($style);
            $form->text('salepricepack','Sale Pice Per Pack')->rules('required')->attribute($style2)->value(0);
            $form->text('sppr','In Riel')->readOnly()->attribute($style);
            $form->text('salepriceunit','Sale Pice Per Unit')->rules('required')->attribute($style2)->value(0);
            $form->text('spur','In Riel')->readOnly()->attribute($style);
            


            $categories = Category::pluck('name','catid');
            $form->select('catid', 'Product Category')->options($categories)->value(-1);
            $manufacturers = Manufacturer::pluck('name','mid');
            $form->select('mid', 'Product Manufacturer')->options($manufacturers)->value(-1);

            //$form->ignore('importbox');


            Admin::script($script);

            $form->saved(function (Form $form) {
                if($form->model()->boxinstock > 0){
                    $input =  ['pid'=>$form->model()->pid, 'box' =>$form->model()->boxinstock];
                    Inventory::quickAdd($input);
                }

            });

        });
    }


    protected function formEdit()
    {
        $script = $this->script_form;
        return Admin::form(Product::class, function (Form $form) use ($script){

            $exchangerate = Exchangerate::where('currentrate',1)->first();

            $form->display('pid', 'Product ID');
            
            
            $form->text('barcode','Barcode Number')->attribute('pattern','[0-9]+');

            $form->text('name','Product Name')->rules('required');
            $form->text('shortcut','Shortcut Name');
            $form->textarea('description', 'Description');

            $attribute = array('pattern'=>'[0-9]+', "autocomplete"=>"off", "style"=>"width: 200px");
            $style = ["style"=>"width:115px"];
            $style1 = ["style"=>"width:115px; background-color:#def9fc"];
            $style2 = ["style"=>"width:115px; background-color:#b6d0f9"];

 
            $html_exchangerate = <<<SCRIPT
<label for="exchangerate" class="col-sm-4 control-label">Exchange rate :</label>
<div class="col-sm-8">
    <div class="input-group">
        <input disabled="1" style="width:115px" type="text" id="exchangerate" name="exchangerate" value="{$exchangerate->amount}" class="form-control spur"  />        
    </div>        
</div>

SCRIPT;
            $form->html($html_exchangerate);
            $form->text('unitperpack','Number of Units Per Pack')->rules('required')->attribute($attribute)->value(0);
            $form->text('unitperbox','Number of Units Per Box')->rules('required')->attribute($attribute)->value(0);

            $form->text('importpricebox','Import Pice Per Box')->rules('required')->attribute($style1);
            $form->text('ipbr','In Riel')->readOnly()->attribute($style);
            $form->text('importpricepack','Import Pice Per Pack')->rules('required')->attribute($style1);
            $form->text('ippr','In Riel')->readOnly()->attribute($style);
            $form->text('importpriceunit','Import Pice Per Unit')->rules('required')->attribute($style1);
            $form->text('ipur','In Riel')->readOnly()->attribute($style);
            


            $form->text('salepricebox','Sale Pice Per Box')->rules('required')->attribute($style2);
            $form->text('spbr','In Riel')->readOnly()->attribute($style);
            $form->text('salepricepack','Sale Pice Per Pack')->rules('required')->attribute($style2);
            $form->text('sppr','In Riel')->readOnly()->attribute($style);
            $form->text('salepriceunit','Sale Pice Per Unit')->rules('required')->attribute($style2);
            $form->text('spur','In Riel')->readOnly()->attribute($style);
            

            $categories = Category::pluck('name','catid');
            $form->select('catid', 'Product Category')->options($categories)->value(-1);
            $manufacturers = Manufacturer::pluck('name','mid');
            $form->select('mid', 'Product Manufacturer')->options($manufacturers)->value(-1);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
            Admin::script($script);
        });
    }



public function update($id)
    {
        return $this->formEdit()->update($id);
    }


public function store()
    {

        
        return $this->form()->store();
    }

public function printStockReminder(Request $request){
    $input = $request->all();

    $result = Product::getStockReminderPrint($input);
    date_default_timezone_set("Asia/Bangkok");
    return view('printStockReminder', ['products'=> $result
        , 'date'=> date('d-m-Y H:m:s')

    ]);

}

/*
        public function createwithimp()
    {
        return Admin::content(function (Content $content) {

            $content->header('Product');
            $content->description('Create New Product With Imported Price');

            $content->body($this->formwithimp());
        });
    }
    protected function formwithimp()
    {
        return Admin::form(Product::class, function (Form $form) {

            $exchangerate = Exchangerate::where('currentrate',1)->first();

            $form->display('pid', 'Product ID');
            
            
            $form->text('barcode','Barcode Number')->rules('required|unique:products,barcode')->attribute('pattern','[0-9]+');
            //$form->text('barcode','Barcode Number')->rules('required|regex:[0-9]+|unique:products,barcode');
            
            $form->text('name','Product Name')->rules('required');
            $form->text('shortcut','Shortcut Name');
            $form->textarea('description', 'Description');

            $attribute = array('pattern'=>'[0-9]+', "autocomplete"=>"off", "style"=>"width: 200px");

            $style = ["style"=>"width:115px"];
            
            $form->text('exchangerate','Exchange Rate')->readOnly()->attribute($style);
            $form->currency('salepriceunit','Sale Pice Per Unit')->rules('required');
            $form->text('spur','In Riel')->readOnly()->attribute($style);
            $form->currency('salepricepack','Sale Pice Per Pack')->rules('required');
            $form->text('sppr','In Riel')->readOnly()->attribute($style);
            $form->currency('salepricebox','Sale Pice Per Box')->rules('required');
            $form->text('spbr','In Riel')->readOnly()->attribute($style);


            $form->currency('importpriceunit','Import Pice Per Unit')->rules('required');
            $form->text('ipur','In Riel')->readOnly()->attribute($style);
            $form->currency('importpricepack','Import Pice Per Pack')->rules('required');
            $form->text('ippr','In Riel')->readOnly()->attribute($style);
            $form->currency('importpricebox','Import Pice Per Box')->rules('required');
            $form->text('ipbr','In Riel')->readOnly()->attribute($style);



            $form->text('unitperpack','Number of Units Per Pack')->rules('required')->attribute($attribute);
            $form->text('unitperbox','Number of Units Per Box')->rules('required')->attribute($attribute);
            
            $form->switch('isdrugs', 'Is Drug?');

            $categories = Category::pluck('name','catid');
            $form->select('catid', 'Product Category')->options($categories)->value(-1);
            $manufacturers = Manufacturer::pluck('name','mid');
            $form->select('mid', 'Product Manufacturer')->options($manufacturers)->value(-1);
            

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

            $form->setAction('createwithimp/save');

            $script = <<<SCRIPT
$('#exchangerate').val("{$exchangerate->amount}");
$(document).off('keyup','#salepriceunit');
$(document).on('keyup','#salepriceunit',function(){
    var amount = new Decimal( $('#salepriceunit').val() );
    $('#spur').val(amount.mul( $('#exchangerate').val() ));
});

$(document).off('keyup','#salepricepack');
$(document).on('keyup','#salepricepack',function(){
    var amount = new Decimal( $('#salepricepack').val() );
    $('#sppr').val(amount.mul( $('#exchangerate').val() ));
});

$(document).off('keyup','#salepricebox');
$(document).on('keyup','#salepricebox',function(){
    var amount = new Decimal( $('#salepricebox').val() );
    $('#spbr').val(amount.mul( $('#exchangerate').val() ));
});

$(document).off('keyup','#importpricebox');
$(document).on('keyup','#importpricebox',function(){
    var amount = new Decimal( $('#importpricebox').val() );
    $('#ipbr').val(amount.mul( $('#exchangerate').val() ));
});

$(document).off('keyup','#importpriceunit');
$(document).on('keyup','#importpriceunit',function(){
    var amount = new Decimal( $('#importpriceunit').val() );
    $('#ipur').val(amount.mul( $('#exchangerate').val() ));
});

$(document).off('keyup','#importpricepack');
$(document).on('keyup','#importpricepack',function(){
    var amount = new Decimal( $('#importpricepack').val() );
    $('#ippr').val(amount.mul( $('#exchangerate').val() ));
});

SCRIPT;
            Admin::script($script);







        });
    }


protected function saveformwithimp(Request $request){
    DB::transaction(function () use ($request){

            $input = $request->all();
            return $this->formwithimp()->save();
    });
}
*/


}
