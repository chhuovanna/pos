<?php

namespace App\Admin\Controllers;

use App\Inventory;
use App\Product;
use App\Importer;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;


class InventoryController extends Controller
{
    use ModelForm;

    public $script_form_create = <<<SCRIPT

$(document).off('keyup','#importunit'   );
$(document).off('keyup','#importpack'   );
$(document).off('keyup','#importbox'    );
$(document).off('keyup','#buypriceunit' );
$(document).off('keyup','#buypricepack' );
$(document).off('keyup','#buypricebox'  );
$(document).off('keyup','#unitinstock'  );
$(document).off('keyup','#packinstock'  );
$(document).off('keyup','#boxinstock'   );

$(document).on('keyup','#importunit'    ,getTotal);
$(document).on('keyup','#importpack'    ,getTotal);
$(document).on('keyup','#importbox'     ,getTotal);
$(document).on('keyup','#buypriceunit'  ,getTotal);
$(document).on('keyup','#buypricepack'  ,getTotal);
$(document).on('keyup','#buypricebox'   ,getTotal);
$(document).on('keyup','#unitinstock'  ,alertUnmatched);
$(document).on('keyup','#packinstock'  ,alertUnmatched);
$(document).on('keyup','#boxinstock'   ,alertUnmatched);


function getTotal(){
    var total   = new Decimal(0);
    var impup   ;
    var imppp   ;
    var impbp   ;


    if ( !$('#importunit').val() || isNaN( $('#importunit').val() )){
        $('#importunit').val(0);
    }

    if ( !$('#importpack').val() || isNaN( $('#importpack').val() )){
        $('#importpack').val(0);
    }

    if ( !$('#importbox').val() || isNaN( $('#importbox').val() )){
        $('#importbox').val(0);
    }

    if ( !$('#buypricebox').val() || isNaN( $('#buypricebox').val() ) ){
        $('#buypricebox').val(0);
    }
    impbp   = new Decimal($('#buypricebox').val());


    if ( !$('#buypriceunit').val() || isNaN( $('#buypriceunit').val() ) ){
        $('#buypriceunit').val(0);
    }
    impup   = new Decimal($('#buypriceunit').val());

    if ( !$('#buypricepack').val() || isNaN( $('#buypricepack').val() ) ){
        $('#buypricepack').val(0);
    }
    imppp   = new Decimal($('#buypricepack').val());

   

    total       = total.add(    impup.mul(  $('#importunit').val()  )   );
    total       = total.add(    imppp.mul(  $('#importpack').val()  )   );
    total       = total.add(    impbp.mul(  $('#importbox').val()  )   );
    $('#amount').val(total);
    $('#unitinstock').val(  $('#importunit').val()  );
    $('#packinstock').val(  $('#importpack').val()  );
    $('#boxinstock').val(   $('#importbox').val()   );
}

function alertUnmatched(event){
    var source = event.target;
    if ( !$(source).val() || isNaN( $(source).val() ) ){
        $(source).val(0);
    }
    

    switch (source.id){
        case 'unitinstock':
            if ( $(source).val() != $('#importunit').val() ){
                alert("The imported unit instock and imported unit in this purcash are unmatched");
            }
            break;
        case 'packinstock':
            if ( $(source).val() != $('#importpack').val() ){
                alert("The imported pack instock and imported pack in this purcash are unmatched");
            }
            break;
        case 'boxinstock':
            if ( $(source).val() != $('#importbox').val() ){
                alert("The imported box instock and imported box in this purcash are unmatched");
            }
            break;
    }
    
}

SCRIPT;
    public $script_form_create_sp = <<<SCRIPT

$(document).off('keyup','#importunit'   );
$(document).off('keyup','#importpack'   );
$(document).off('keyup','#importbox'    );
$(document).off('keyup','#buypriceunit' );
$(document).off('keyup','#buypricepack' );
$(document).off('keyup','#buypricebox'  );
$(document).off('keyup','#unitinstock'  );
$(document).off('keyup','#packinstock'  );
$(document).off('keyup','#boxinstock'   );

$(document).on('keyup','#importunit'    ,getTotal);
$(document).on('keyup','#importpack'    ,getTotal);
$(document).on('keyup','#importbox'     ,getTotal);
$(document).on('keyup','#buypriceunit'  ,getTotal);
$(document).on('keyup','#buypricepack'  ,getTotal);
$(document).on('keyup','#buypricebox'   ,getTotal);
$(document).on('keyup','#unitinstock'  ,alertUnmatched);
$(document).on('keyup','#packinstock'  ,alertUnmatched);
$(document).on('keyup','#boxinstock'   ,alertUnmatched);


function getTotal(){
    var total   = new Decimal(0);
    var impup   ;
    var imppp   ;
    var impbp   ;


    if ( !$('#importunit').val() || isNaN( $('#importunit').val() )){
        $('#importunit').val(0);
    }

    if ( !$('#importpack').val() || isNaN( $('#importpack').val() )){
        $('#importpack').val(0);
    }

    if ( !$('#importbox').val() || isNaN( $('#importbox').val() )){
        $('#importbox').val(0);
    }

   

    if ( !$('#buypricebox').val() || isNaN( $('#buypricebox').val() ) ){
        $('#buypricebox').val(0);
        impbp   = new Decimal($('#buypricebox').val());
    }else{
        impbp   = new Decimal($('#buypricebox').val());
        $('#buypriceunit').val(impbp.div($('#unitperbox').val()));
        var temp = new Decimal($('#buypriceunit').val());
        $('#buypricepack').val(temp.mul($('#unitperpack').val()));
    }

   
    if ( !$('#buypriceunit').val() || isNaN( $('#buypriceunit').val() ) ){
        $('#buypriceunit').val(0);
    }
    impup   = new Decimal($('#buypriceunit').val());

    if ( !$('#buypricepack').val() || isNaN( $('#buypricepack').val() ) ){
        $('#buypricepack').val(0);
    }
    imppp   = new Decimal($('#buypricepack').val());


    total       = total.add(    impup.mul(  $('#importunit').val()  )   );
    total       = total.add(    imppp.mul(  $('#importpack').val()  )   );
    total       = total.add(    impbp.mul(  $('#importbox').val()  )   );
    $('#amount').val(total);
    $('#unitinstock').val(  $('#importunit').val()  );
    $('#packinstock').val(  $('#importpack').val()  );
    $('#boxinstock').val(   $('#importbox').val()   );
}

function alertUnmatched(event){
    var source = event.target;
    if ( !$(source).val() || isNaN( $(source).val() ) ){
        $(source).val(0);
    }
    

    switch (source.id){
        case 'unitinstock':
            if ( $(source).val() != $('#importunit').val() ){
                alert("The imported unit instock and imported unit in this purcash are unmatched");
            }
            break;
        case 'packinstock':
            if ( $(source).val() != $('#importpack').val() ){
                alert("The imported pack instock and imported pack in this purcash are unmatched");
            }
            break;
        case 'boxinstock':
            if ( $(source).val() != $('#importbox').val() ){
                alert("The imported box instock and imported box in this purcash are unmatched");
            }
            break;
    }
    
}

SCRIPT;
    
    protected $script_form_edit = <<<SCRIPT

$(document).off('keyup','#importunit'   );
$(document).off('keyup','#importpack'   );
$(document).off('keyup','#importbox'    );
$(document).off('keyup','#buypriceunit' );
$(document).off('keyup','#buypricepack' );
$(document).off('keyup','#buypricebox'  );
$(document).off('keyup','#unitinstock'  );
$(document).off('keyup','#packinstock'  );
$(document).off('keyup','#boxinstock'   );



$(document).on('keyup','#importunit'    ,getTotal);
$(document).on('keyup','#importpack'    ,getTotal);
$(document).on('keyup','#importbox'     ,getTotal);
$(document).on('keyup','#buypriceunit'  ,getTotal);
$(document).on('keyup','#buypricepack'  ,getTotal);
$(document).on('keyup','#buypricebox'   ,getTotal);
$(document).on('keyup','#unitinstock'  ,alertUnmatched);
$(document).on('keyup','#packinstock'  ,alertUnmatched);
$(document).on('keyup','#boxinstock'   ,alertUnmatched);



function getTotal(){
    var total   = new Decimal(0);
    var impup   ;
    var imppp   ;
    var impbp   ;


    if ( !$('#importunit').val() || isNaN( $('#importunit').val() )){
        $('#importunit').val(0);
    }

    if ( !$('#importpack').val() || isNaN( $('#importpack').val() )){
        $('#importpack').val(0);
    }

    if ( !$('#importbox').val() || isNaN( $('#importbox').val() )){
        $('#importbox').val(0);
    }
   
    
    if ( !$('#buypricebox').val() || isNaN( $('#buypricebox').val() ) ){
        $('#buypricebox').val(0);
    }
    impbp   = new Decimal($('#buypricebox').val());


    if ( !$('#buypriceunit').val() || isNaN( $('#buypriceunit').val() ) ){
        $('#buypriceunit').val(0);
    }
    impup   = new Decimal($('#buypriceunit').val());

    if ( !$('#buypricepack').val() || isNaN( $('#buypricepack').val() ) ){
        $('#buypricepack').val(0);
    }
    imppp   = new Decimal($('#buypricepack').val());

    total       = total.add(    impup.mul(  $('#importunit').val()  )   );
    total       = total.add(    imppp.mul(  $('#importpack').val()  )   );
    total       = total.add(    impbp.mul(  $('#importbox').val()  )   );
    $('#amount').val(total);
    
}

function alertUnmatched(event){
    var source = event.target;
    if ( !$(source).val() || isNaN( $(source).val() ) ){
        $(source).val(0);
    }

    

    switch (source.id){
        case 'unitinstock':
            if ( $(source).val() != $('#importunit').val() ){
                $('#unitinstock').css("background-color", "#f9b3b3");
            }else{
                $('#unitinstock').css("background-color", "white");
            }
            break;
        case 'packinstock':
            if ( $(source).val() != $('#importpack').val() ){
                $('#packinstock').css("background-color", "#f9b3b3");
            }else{
                $('#packinstock').css("background-color", "white");
            }
            break;
        case 'boxinstock':
            if ( parseInt($(source).val()) != $('#importbox').val() ){
                $('#boxinstock').css("background-color", "#f9b3b3");
            }else{
                $('#boxinstock').css("background-color", "white");
            }
            break;
    }
    
}



SCRIPT;


    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {

        return Admin::content(function (Content $content) {

            $content->header('Inventory');
            $content->description('List Inventory');

            $content->body($this->grid());

            $content->body(view('inventoryReport'));

            
            
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

            $content->header('Inventory');
            $content->description('Edit Inventory');
            if (Admin::user()->isRole('Administrator')){
                $content->body($this->formEdit()->edit($id));
            }else{
                $content->body("No Permission");
            }
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

            $content->header('Inventory');
            $content->description('Create New Inventory');

            $content->body($this->formCreate());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Inventory::class, function (Grid $grid) {    

            if (!Admin::user()->isRole('Administrator')){
                $grid->disableActions();
            }

            $grid->disableBatchDeletion();
            $grid->disableRowSelector();

            
            $grid->filter(function ($filter) {

                $products = Product::getSelectOption();
                $importers = Importer::getSelectOption();

                $filter->equal('pid')->select($products);
                $filter->between('amount', 'Amount');
                $filter->equal('impid')->select($importers);
                $filter->equal('finish')->select([ '1' => 'Finish' , '0' => 'Not finish']);
                $filter->between('importdate', 'Period')->datetime();

            });


            $grid->model()->with('product');
            $grid->model()->with('importer');
            //$grid->model()->orderby('invid','desc');

            $grid->invid('Inv.ID')->sortable();
            //$grid->product()->pid('Prod.ID');
            $grid->pid('Prod.ID')->sortable();
            $grid->product()->name('Prod.Name');
            //$grid->importer()->impid('Impo.ID');
            $grid->importer()->name('Impo.Name');
                        
            $grid->importunit('ImpUnit');
            $grid->importpack('ImpPack');
            $grid->importbox('ImpBox');
            $grid->buypriceunit('ImpUnitPri');
            $grid->buypricepack('ImpPackPri');
            $grid->buypricebox('ImpBoxPri');
            $grid->amount('Total Amount');  
            
            $grid->unitinstock('UnitSto');
            $grid->packinstock('PackSto');
            $grid->boxinstock('BoxSto');
            $grid->finish('Finish?')->display(function ($finish) {
                return $finish ? 'YES' : 'NO';
            }); 
            
            $grid->importdate('Impo.Date');
            //$grid->mfg('MFG');
            $grid->exp('EXP');
            $grid->shelf('Shelf');
            
            $script = <<<SCRIPT
$("[name='pid']").select2({ width: '200px' });
$("[name='impid']").select2({ width: '170px' });



var ths = document.getElementsByTagName("th");
ths[4].style.backgroundColor = "#f4f442";
ths[5].style.backgroundColor = "#f4f442";
ths[6].style.backgroundColor = "#f4f442";
ths[7].style.backgroundColor = "#b8f441";
ths[8].style.backgroundColor = "#b8f441";
ths[9].style.backgroundColor = "#b8f441";
ths[10].style.backgroundColor = "#41f4f1";
ths[11].style.backgroundColor = "#f4f442";
ths[12].style.backgroundColor = "#f4f442";
ths[13].style.backgroundColor = "#f4f442";
SCRIPT;
            Admin::script($script);
            //$grid->created_at();
            //$grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function formCreate()
    {
        $script_form_create = $this->script_form_create;
        return Admin::form(Inventory::class, function (Form $form) use ($script_form_create){

            $form->display('invid', 'ID');

            $products = Product::getSelectOption();
            $form->select('pid', 'Product')->options($products)->rules('required');

            $importers = Importer::pluck('name','impid');
            $form->select('impid', 'Importer')->options($importers)->rules('required')->value(-1);


            $attribute = array("style"=>"width: 100px; color: #0762f2", "autocomplete"=>"off");
            $style = ["style"=>"width:115px; background-color:#def9fc", "autocomplete"=>"off"];
            $form->text('importbox','Number of Imported Box')->attribute($attribute)->value(0);
            $form->text('importpack','Number of Imported Pack')->attribute($attribute)->value(0);
            $form->text('importunit','Number of Imported Unit')->attribute($attribute)->value(0);
            
            
            $form->text('buypricebox','Imported Box Price')->rules('required')->attribute($style);
            $form->text('buypricepack','Imported Pack Price')->rules('required')->attribute($style);
            $form->text('buypriceunit','Imported Unit Price')->rules('required')->attribute($style);
            
            
            $form->text('amount', 'Total')->attribute($style);
            //$form->currency('amount', 'Total')->rules('required');

            $form->text('boxinstock','Number of Box in Stock')->attribute($attribute)->value(0);
            $form->text('packinstock','Number of Pack in Stock')->attribute($attribute)->value(0);
            $form->text('unitinstock','Number of Unit in Stock')->attribute($attribute)->value(0);
            
            
            $form->switch('finish', 'Finished?')->value(0);
            
            
            
            $form->date('importdate','Imported Date');
            $form->date('mfg','Manufactured Date');
            $form->date('exp','Expired Date');
            $form->text('shelf', 'Shelf');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

            Admin::script($script_form_create);


            $form->saved(function (Form $form) {
                Inventory::updatestock($form->model()->pid);
                Inventory::updateavgbuypricebox($form->model()->pid);
            });
        });
    }


    protected function formEdit()
    {

        $script_form_edit = $this->script_form_edit;        
        return Admin::form(Inventory::class, function (Form $form) use ($script_form_edit){

            $form->display('invid', 'ID');

            $products = Product::getSelectOption();
            $form->select('pid', 'Product')->options($products)->rules('required');

            $importers = Importer::pluck('name','impid');
            $form->select('impid', 'Importer')->options($importers)->rules('required');


            $attribute = array("style"=>"width: 100px; color: #0762f2", "autocomplete"=>"off");
            $style = ["style"=>"width:115px; background-color:#def9fc", "autocomplete"=>"off"];
            $form->text('importbox','Number of Imported Box')->attribute($attribute)->value(0);
            $form->text('importpack','Number of Imported Pack')->attribute($attribute)->value(0);
            $form->text('importunit','Number of Imported Unit')->attribute($attribute)->value(0);
            
            $form->text('buypricebox','Imported Box Price')->rules('required')->attribute($style);
            $form->text('buypricepack','Imported Pack Price')->rules('required')->attribute($style);
            $form->text('buypriceunit','Imported Unit Price')->rules('required')->attribute($style);
            
            
            $form->text('amount', 'Total')->value(0)->attribute($style);
            //$form->currency('amount', 'Total')->rules('required')->value(0);

            $form->text('boxinstock','Number of Box in Stock')->attribute($attribute)->value(0);
            $form->text('packinstock','Number of Pack in Stock')->attribute($attribute)->value(0);
            $form->text('unitinstock','Number of Unit in Stock')->attribute($attribute)->value(0);
            
            
            $form->switch('finish', 'Finished?');
            
            
            
            $form->date('importdate','Imported Date');
            $form->date('mfg','Manufactured Date');
            $form->date('exp','Expired Date');
            $form->text('shelf', 'Shelf');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');


            Admin::script($script_form_edit);


            $form->saved(function (Form $form) {
                Inventory::updatestock($form->model()->pid);
                Inventory::updateavgbuypricebox($form->model()->pid);
            });
        });
        
    }

    protected function productInventoryForm($product)
    {
        $script_form_create = $this->script_form_create_sp;
        return Admin::form(Inventory::class, function (Form $form) use ($product,$script_form_create) {

            $form->display('invid', 'ID');


            $sp = Product::find($product);
            

            $form->select('pid', 'Product')->options([$sp->pid=>$sp->name])->value($product);

            $importedprices = Inventory::where('pid', '=' , $product)->orderBy('invid','desc')->first();
            //$importedprices = Inventory::where('pid', '=' , $product)->orderBy('invid')->first();

            $importers = Importer::pluck('name','impid');

            if ($importedprices){
                $form->select('impid', 'Importer')->options($importers)->rules('required')->value($importedprices->impid);
            }else{
                $form->select('impid', 'Importer')->options($importers)->rules('required')->value(-1);
            }


            $attribute = array("style"=>"width: 100px; color: #0762f2", "autocomplete"=>"off");
            $style = ["style"=>"width:115px; background-color:#def9fc", "autocomplete"=>"off"];
            $form->text('importbox','Number of Imported Box')->attribute($attribute)->value(0);
            $form->text('importpack','Number of Imported Pack')->attribute($attribute)->value(0);
            $form->text('importunit','Number of Imported Unit')->attribute($attribute)->value(0);
            
            
            

            
            if ($importedprices){
                $form->text('buypricebox','Imported Box Price')->value($importedprices->buypricebox)->rules('required')->attribute($style);
                $form->text('buypricepack','Imported Pack Price')->value($importedprices->buypricepack)->rules('required')->attribute($style);
                $form->text('buypriceunit','Imported Unit Price')->value($importedprices->buypriceunit)->rules('required')->attribute($style);
                
                
            }elseif (!is_null($sp->importpricebox)){
                $form->text('buypricebox','Imported Box Price')->rules('required')->value($sp->importpricebox)->attribute($style);
                $form->text('buypricepack','Imported Pack Price')->rules('required')->value($sp->importpricepack)->attribute($style);
                $form->text('buypriceunit','Imported Unit Price')->rules('required')->value($sp->importpriceunit)->attribute($style);
            }else{
                $form->text('buypricebox','Imported Box Price')->rules('required')->attribute($style);
            $form->text('buypricepack','Imported Pack Price')->rules('required')->attribute($style);
            $form->text('buypriceunit','Imported Unit Price')->rules('required')->attribute($style);
            }



            $form->text('amount', 'Total')->value(0)->attribute($style);
            //$form->currency('amount', 'Total')->rules('required')->value(0);
            $form->text('boxinstock','Number of Box in Stock')->attribute($attribute)->value(0);
            $form->text('packinstock','Number of Pack in Stock')->attribute($attribute)->value(0);
            $form->text('unitinstock','Number of Unit in Stock')->attribute($attribute)->value(0);
            
            
            $form->switch('finish', 'Finished?')->value(0);
            
            
            
            $form->date('importdate','Imported Date');
            $form->date('mfg','Manufactured Date');
            $form->date('exp','Expired Date');
            $form->text('shelf', 'Shelf');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
            $form->html('<input type="hidden" name="unitperpack" id="unitperpack" value="'. $sp->unitperpack.'" disabled="disabled">');
            $form->html('<input type="hidden" name="unitperbox" id="unitperbox" value="'. $sp->unitperbox.'" disabled="disabled">');


            Admin::script($script_form_create);


            $form->saved(function (Form $form) {
                Inventory::updatestock($form->model()->pid);
                Inventory::updateavgbuypricebox($form->model()->pid);
            });
        });
    }

    protected function productInventory($product){

        return Admin::content(function (Content $content) use ($product) {

            $content->header('Product');
            $content->description('Add Inventory');           
    
            $content->body($this->productInventoryForm($product));
        });
    }
    public function update($id)
    {
        return $this->formEdit()->update($id);
    }

    public function store()
    {

        
        return $this->formCreate()->store();
    }

    public function destroy($id)
    {

        if (Admin::user()->isRole('Administrator')){
            $inventory = Inventory::where('invid',$id)->first();
            if ($this->formEdit()->destroy($id)) {
                Inventory::updatestock($inventory->pid);
                return response()->json([
                    'status'  => true,
                    'message' => trans('admin::lang.delete_succeeded'),
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => trans('admin::lang.delete_failed'),
                ]);
            }
        }
    }

/*

use for inventory report
*/

    public function searchinventory(Request $request){

        $searchKey = $request->all();

        return Inventory::searchinventory($searchKey);
    }

    public function quickAdd(Request $request){
        $input = $request->all();
        $res = "0";
        if (Inventory::quickAdd($input)){
            $res = "1";
        }
        return $res;
    }

}
