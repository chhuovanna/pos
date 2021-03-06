<?php

namespace App\Admin\Controllers;

use App\Product;
use App\Category;
use App\Manufacturer;
use App\Exchangerate;
use App\Customer;
use App\Sale;
use App\SaleProduct;
use App\Inventory;
use App\StockoutType;
use App\Loan;

use Encore\Admin\Form;

use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;


class SaleController extends Controller
{
    use ModelForm;

    protected $saleid = 0;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()    
    {
       return Admin::content(function (Content $content) {

            $content->header('Sale');
            $content->description('List Sale');
            $content->body(view('saleReport'));
            $content->body($this->grid());
            

            //$res = $this->getReport( $grid->model()->eloquent()->get() );

            //$content->body(view('saleReport' ,  [ 'rows' => $res ]));

        });
    }


    public function addSale(Request $request){
        return Admin::content(function (Content $content) use ($request) {

            $content->header('Sale');
            $content->description('Order');
            $content->body($this->formAddSale());


            if ($request){
                $input = $request->all();
                
                
                if (array_key_exists('checkoutstate', $input) && $input['checkoutstate'] === 'success'){

                    
                $script = <<<SCRIPT
$(document).ready(function(){
    toastr.success('Checkout Success');



SCRIPT;
                    if (array_key_exists('saleid', $input)  && $input['saleid'] > 0 ){
                        $url = url('/admin/sale/printreceipt?saleid='. $input['saleid']);


                        $script .= <<<SCRIPT
var printWindow = window.open('{$url}' ,'_blank', "height=700,width=700");
SCRIPT;
                    }

                $script .= "});";
                    Admin::script($script);
                }
                
            }

        
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

            $content->header('Sale');
            $content->description('Edit');
            if (Admin::user()->isRole('Administrator')){
                $content->body($this->form()->edit($id));
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

            $content->header('Sale');
            $content->description('Create New Sale');
            if (Admin::user()->isRole('Administrator')){
                $content->body($this->form());
            }else{
                $content->body("No Permission");
            }
        });
    }

  

    protected function grid()
    {
        return Admin::grid(Sale::class, function (Grid $grid) {




            $grid->disableBatchDeletion();
            $grid->disableRowSelector();

            $grid->disableCreation();
            //$grid->paginate(500);


            $grid->model()->with('customer'); 
            $grid->model()->orderby('saledate','desc');




            $grid->filter(function ($filter) {

                $customers = Customer::getSelectOption();
                $stockouttypes = StockoutType::getSelectOption();

                $filter->between('saledate','Sale Period')->datetime();
                
                $filter->between('discount', 'Discount%');

                $filter->where(function ($query) {

                        $query->whereRaw("`total` between {$this->input} OR `ftotal` between {$this->input}");

                    }, 'Total or GTD(ex: 1 and 100)');

                $filter->equal('cusid')->select($customers);
                $filter->equal('sotid')->select($stockouttypes);



            });


            $grid->actions(function ($actions) {


                if (!Admin::user()->isRole('Administrator')){

                    $actions->disableEdit();
                    $actions->disableDelete();
                }

                // append an action.
                $actions->append('<a title="View Orderline" href="' .url('/admin/orderline?saleid=').$actions->getKey(). '"><i class="fa fa-search"></i></a>');

                //append view receipt
                //$actions->append('<a title="View Receipt" target="_blank" href="' .url('/admin/sale/viewreceipt?saleid=').$actions->getKey(). '"><i class="fa fa-eye"></i></a>');
                $actions->append('<a title="View Receipt" onclick="window.open(\'' .url('/admin/sale/viewreceipt?saleid=').$actions->getKey(). '\' ,\'_blank\', \'height=700,width=700\'); "><i class="fa fa-print"></i></a>');

            });



            $grid->saleid('ID');
            $grid->saledate('Date');
            //$grid->customer()->cusid('CID');
            $grid->customer()->name('Customer');           
            $grid->stockouttype()->type('Type');
            $grid->total('Total');            
            $grid->discount('Dis%');
            $grid->ftotal('GrandTotal$');
            $grid->recievedd('RecUSD');
            $grid->recievedr('RecRiel');
            $grid->exchangerate('ExRate');


            $script = <<<SCRIPT
$("[name='cusid']").select2({ width: '170px' });
$("[placeholder='Total or GTD(ex 1 and 100)']").attr('pattern','([-]?[0-9]+ and [-]?[0-9]+)?');


var ths = document.getElementsByTagName("th");
ths[7].style.backgroundColor = "#f4f442";
ths[9].style.backgroundColor = "#b8f441";

$('.table-hover td:nth-child(5)').css("background-color", "#f4f442");
$('.table-hover td:nth-child(7)').css("background-color", "#b8f441");

SCRIPT;
            Admin::script($script);



            
        });
    }







    public function listSale(){

        return Admin::content(function (Content $content) {

            $content->header('Sale');
            $content->description('List Sale');

            $content->body($this->grid());
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Sale::class, function (Form $form) {

            $form->display('saleid', 'Sale ID');
            $form->display('saledate','Date Time');
            
            $customers = Customer::pluck('name','cusid');      
            $form->select('cusid','Customer')->options($customers);
            $stockouttypes = StockoutType::pluck('type','sotid');      
            $form->select('sotid','Stock out type')->options($stockouttypes);

            $form->currency('total', 'Total');
            $form->number('discount','Discount');
            $form->currency('ftotal','Grand Total in USD');
            $form->currency('exchangerate','Exchange Rate');
            $form->currency('recievedd','Recieved in USD');
            $form->currency('recievedr','Recieved in Riel');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
    protected function formAddSale()
    {
        $exchangerate = Exchangerate::where('currentrate',1)->first();
        /*$products = Product::listWithCatMan();*/
        $customers = Customer::orderBy('cusid')->get();
        $stockouttypes = StockoutType::orderBy('sotid')->get();
        $selectProducts = Product::getSelectOption();
        $selectCategories = Category::getSelectOption();

        return view('productSaleListSearch',[
            'exchangerate'      => $exchangerate->amount,
            /*'products'        => $products,*/
            'customers'         => $customers,
            'stockouttypes'     => $stockouttypes,
            'selectProducts'   => $selectProducts,
            'selectCategories'  => $selectCategories
        ] );
        /*$productOption = Product::getSelectOption();
        return view('productSale',[
            'exchangerate' => $exchangerate->amount,
            'products'     => $products,
            'productOption'=> $productOption,
        ] );*/
    }

    public function searchProduct(Request $request){

        $searchKey = $request->input('searchKey');

        if(isset($searchKey)){
            return Product::searchProduct($searchKey);
        }else{
            return null;
        }
    }

    public function searchProductBarcode(Request $request){

        $searchKey = $request->input('searchKey');

        if(isset($searchKey)){
            return Product::searchProductBarcode($searchKey);
        }else{
            return null;
        }
    }

    public function searchProductID(Request $request){

        $searchKey = $request->input('id');

        if(isset($searchKey)){
            return Product::searchProductID($searchKey);
        }else{
            return null;
        }
    }

    public function searchProductCategory(Request $request){

        $searchKey = $request->input('category');

        if(isset($searchKey)){
            return Product::searchProductCategory($searchKey);
        }else{
            return null;
        }
    }



    public function refreshSearchProduct(Request $request){
        return Product::listWithCatMan();
    }

    public function checkout(Request $request){

        
        

        DB::transaction(function () use ($request){

            $input = $request->all();
            $products = explode(',', $input['products']);
            $size = sizeof($products);
        
            //print_r($input);


            $sale = new Sale;
            if ($input['customer'] != 0){
                $sale->cusid = $input['customer'];
            }
            $sale->total        = $input['totald'];
            $sale->discount     = $input['discount'];
            $sale->ftotal       = $input['ftotald'];
            $sale->recievedd    = $input['recievedd'];
            $sale->recievedr    = $input['recievedr'];
            $sale->exchangerate = $input['exchangerate'];
            $sale->sotid        = $input['stockouttype'];

            $sale->save();

            
            
            for ($i = 1; $i < $size ; $i++ ){
                $saleproduct = new SaleProduct;
                $saleproduct->saleid        = $sale->saleid;
                $saleproduct->pid           = $products[$i];
                $saleproduct->unitquantity  = $input[ $products[$i]. "qu"   ];
                $saleproduct->packquantity  = $input[ $products[$i]. "qp"   ];
                $saleproduct->boxquantity   = $input[ $products[$i]. "qb"   ];
                $saleproduct->salepriceunit = $input[ $products[$i]. "up"   ];
                $saleproduct->salepricepack = $input[ $products[$i]. "pp"   ];
                $saleproduct->salepricebox  = $input[ $products[$i]. "bp"   ];
                $saleproduct->subtotal      = $input[ $products[$i]. "stt"  ];
                $saleproduct->stock         = $input[ $products[$i]."tstock"];
                

                $temp  = explode( ',', $input[   $products[$i]. "tstock"  ] );
                $stock = array( 'unitinstock'   => $temp[0]
                                ,'packinstock'  => $temp[1]
                                ,'boxinstock'   => $temp[2] );

                Product::updateStock( $products[$i], $stock );
                $savedInventory = Inventory::updateInventory( $products[$i], $stock );
                if ($savedInventory)
                    $saleproduct->avgbuypriceunit = $savedInventory->avgbuypriceunit;

                $saleproduct->save();
            }
            //insert loan 
            if ($sale->sotid == 2){
                $loan = new Loan();
                $loan->saleid = $sale->saleid;
                $loan->amount = $sale->ftotal - ($sale->recievedd + ($sale->recievedr/$sale->exchangerate));
                $loan->state = 0;
                $loan->save();
            }

            $this->saleid = $sale->saleid;

        });
        DB::commit();

        if ($request->input('print') == 1){
            $url = strtok(url()->previous(), '?')."?checkoutstate=success&saleid=". $this->saleid;
        }else{
            $url = strtok(url()->previous(), '?')."?checkoutstate=success";
        }

        return redirect($url);        


    }




    public function addSaleBarcode(Request $request){
        return Admin::content(function (Content $content) use ($request) {

			

            $exchangerate = Exchangerate::where('currentrate',1)->first();
            $customers = Customer::orderBy('cusid')->get();
            $stockouttypes = StockoutType::orderBy('sotid')->get();

            $content->header('Add Sale');
            $content->description('By Barcode');
            $content->body( view('productSaleBarcode',[
            'exchangerate' => $exchangerate->amount,
            'customers'    => $customers,
            'stockouttypes'=> $stockouttypes
            
                ] ) 
            ) ;

			if ($request){
				$input = $request->all();
				
				
				if (array_key_exists('checkoutstate', $input) && $input['checkoutstate'] === 'success'){
				$script = <<<SCRIPT
$(document).ready(function(){
	 toastr.success('Checkout Success');

SCRIPT;

                    if (array_key_exists('saleid', $input)  && $input['saleid'] > 0 ){
                        $url = url('/admin/sale/printreceipt?saleid='. $input['saleid']);


                        $script .= <<<SCRIPT
var printWindow = window.open('{$url}' ,'_blank', "height=700,width=700");
SCRIPT;
                    }

                $script .= "});";
					Admin::script($script);
				}
				
			}

        });
    }


    public function addSaleBarcodeUnit(Request $request){
        return Admin::content(function (Content $content) use ($request) {

            

            $exchangerate = Exchangerate::where('currentrate',1)->first();
            $customers = Customer::orderBy('cusid')->get();
            $stockouttypes = StockoutType::orderBy('sotid')->get();

            $content->header('Add Sale');
            $content->description('By Barcode');
            $content->body( view('productSaleBarcodeUnit',[
            'exchangerate' => $exchangerate->amount,
            'customers'    => $customers,
            'stockouttypes'=> $stockouttypes
            
                ] ) 
            ) ;

            if ($request){
                $input = $request->all();
                
                
                if (array_key_exists('checkoutstate', $input) && $input['checkoutstate'] === 'success'){
                $script = <<<SCRIPT
$(document).ready(function(){
     toastr.success('Checkout Success');
SCRIPT;

                    if (array_key_exists('saleid', $input)  && $input['saleid'] > 0 ){
                        $url = url('/admin/sale/printreceipt?saleid='. $input['saleid']);


                        $script .= <<<SCRIPT
var printWindow = window.open('{$url}' ,'_blank', "height=700,width=700");
SCRIPT;
                    }

                $script .= "});";

                    Admin::script($script);
                }
                
            }

        });
    }


    public function getStock(Request $request){

        $products = $request->input('products');

        return Product::getStock($products);
    }



    public function searchsale(Request $request){
        
        $searchKey = $request->all();

        $res = Sale::searchsale($searchKey);

        $report = $this->getReport($res);    
        


        return $report;
    }


/*maynot use*/
    protected function getReport($res)
    {
        $ordinary  = array( 'stotal' => 0, 'sftotal' => 0 );
        $loan      = array( 'stotal' => 0, 'sftotal' => 0 , 'samount' => 0 );
        $prize     = array( 'stotal' => 0, 'sftotal' => 0 , 'prizeexpense' => 0);
        $return    = array( 'stotal' => 0, 'sftotal' => 0 );
        $expired   = array( 'stotal' => 0, 'sftotal' => 0 );
        $lost      = array( 'stotal' => 0, 'sftotal' => 0 );
        $used      = array( 'stotal' => 0, 'sftotal' => 0 );
        
       
        $expense = 0;
        $profit  = 0;
        $income  = 0;
        $prizeexpense = 0;

        if ($res){
            foreach ($res['sales'] as $row) {
                if ( $row->sotid && $row->sotid > 1 ) {
                    switch ($row->sotid) {
                        case "2":
                            $loan['stotal']  += $row->total;
                            $loan['sftotal'] += $row->ftotal;
                            $loan['samount']  += $row->amount;
                            break;
                        case "3":
                            $prize['stotal']  += $row->total;
                            $prize['sftotal'] += $row->ftotal;
                            break;
                        case "5":
                            $return['stotal']  += $row->total;
                            $return['sftotal'] += $row->ftotal;
                            break;
                        case "6":
                            $expired['stotal']  += $row->total;
                            $expired['sftotal'] += $row->ftotal;
                            break;
                        case "7":
                            $lost['stotal']  += $row->total;
                            $lost['sftotal'] += $row->ftotal;
                            break;
                        case "8":
                            $used['stotal']  += $row->total;
                            $used['sftotal'] += $row->ftotal;
                            break;
                        
                    }
                }else{
                    $ordinary['stotal']  += $row->total;
                    $ordinary['sftotal'] += $row->ftotal;
                }

            }

            
/*
            foreach ($res['saleproducts'] as $key => $value) {
                $expense += $value->sumunit*$res['productprices'][$key]->buypriceunit
                            + $value->sumpack*$res['productprices'][$key]->buypricepack
                            + $value->sumbox*$res['productprices'][$key]->buypricebox;
            }

            //prize win product import total cost
            foreach ($res['prizeproducts'] as $key => $value) {
                $prizeexpense += $value->unitprize*$res['productprices'][$key]->buypriceunit
                            + $value->packprize*$res['productprices'][$key]->buypricepack
                            + $value->boxprize*$res['productprices'][$key]->buypricebox;
            }
*/
            $prize['prizeexpense'] = $res['prizepurchaseexpense'];

            $expense = $res['purchaseexpense'];

            $income = $ordinary['sftotal'] 
                + $loan['sftotal'] 
                + $prize['sftotal'] 
                + $return['sftotal']
                + $expired['sftotal']
                + $lost['sftotal']
                + $used['sftotal']
                ;
            $profit = $income - $expense; 
        }

        return array( 'Ordinary' => $ordinary
                    , 'Loan'     => $loan
                    , 'Prize'    => $prize
                    , 'Return'   => $return
                    , 'Expired'  => $expired
                    , 'Lost'     => $lost
                    , 'Used'     => $used
                    , 'Income'   => $income
                    , 'Expense'  => $expense
                    , 'Profit'   => $profit
                    //, 'prizeproducts' => $res['prizeproducts']
                );
    }
   

    protected function printReceipt(Request $request){

        //if (array_key_exists('saleid', $request)){
            //print_r($request['saleid']);
            $sale = Sale::getSaleForReceipt($request['saleid']);
            $orderlines = SaleProduct::getOrderlineForReceipt($request['saleid']);

            return view('printReceipt'
                    , [
                        'sale'         => $sale
                        ,'orderlines'   => $orderlines
                        ,'user'         => Admin::user()->name
                        ,'unitnames'    => Category::getAssociateArray()
                    ]);
        //}

    }


    protected function viewReceipt(Request $request){

        //if (array_key_exists('saleid', $request)){

            $sale = Sale::getSaleForReceipt($request['saleid']);
            $orderlines = SaleProduct::getOrderlineForReceipt($request['saleid']);

            return view('viewReceipt'
                    , [
                    'sale'         => $sale
                    ,'orderlines'   => $orderlines
                    ,'user'         => Admin::user()->name
                    ,'unitnames'    => Category::getAssociateArray()
                ]);

        //}

    }



}
