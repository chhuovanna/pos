<?php

namespace App\Admin\Controllers;

use App\Customer;
use App\Product;
use App\Exchangerate;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;


class WinMoneyPrizeController extends Controller
{
//    use ModelForm;


    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $customers = Customer::orderBy('cusid')->get();
            $products  = Product::getSelectOption();
            $exchangerate = Exchangerate::where('currentrate',1)->first();

            $content->header('Win Money Prize');
            $content->description('Add win money prize');
            $content->body(view('winMoneyPrizeAdd', ['customers' => $customers
                                                , 'products'     => $products
                                                , 'exchangerate' => $exchangerate->amount ] ));
        });
    }


    public function save(Request $request){

        $input = $request->all();

        print_r($input);
        /*DB::transaction(function () use ($request){

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
                $saleproduct->save();

                $temp  = explode( ',', $input[   $products[$i]. "tstock"  ] );
                $stock = array( 'unitinstock'   => $temp[0]
                                ,'packinstock'  => $temp[1]
                                ,'boxinstock'   => $temp[2] );

                Product::updateStock( $products[$i], $stock );
                Inventory::updateInventory( $products[$i], $stock );
            }
            //insert loan 
            if ($sale->sotid == 2){
                $loan = new Loan();
                $loan->saleid = $sale->saleid;
                $loan->amount = $sale->ftotal - ($sale->recievedd + ($sale->recievedr/$sale->exchangerate));
                $loan->state = 0;
                $loan->save();
            }

        });
        DB::commit();
        $url = strtok(url()->previous(), '?');

        return redirect($url);    */
    }


    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('Customer');
            $content->description('List Customers');

            $content->body($this->grid());
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

            $content->header('Customer');
            $content->description('Edit Customer');

            $content->body($this->form()->edit($id));
        });
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Customer::class, function (Grid $grid) {

            if (!Admin::user()->isRole('Administrator')){
                $grid->disableBatchDeletion();
                $grid->disableRowSelector();
                $grid->disableActions();
            }


            $grid->filter(function ($filter) {

                $filter->where(function ($query) {

                        $query->whereRaw("`name` like '%{$this->input}%' OR `tel` like '%{$this->input}%' or `email` like '%{$this->input}%'");

                    }, 'Name or tel or mail');

            });


            $grid->cusid('ID')->sortable();
            $grid->name('Name')->sortable();
            $grid->address('Address');
            $grid->email('Email');
            $grid->tel('Tel');
            $grid->tel1('Tel1');
            $grid->tel2('Tel2');

            $grid->created_at();
            /*$grid->updated_at();*/
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Customer::class, function (Form $form) {

            $form->display('cusid', 'ID');
            $form->text('name', 'Customer Name')->rules('required');
            $form->textarea('address', 'Address');
            $form->mobile('tel', 'Phone Number');
            $form->mobile('tel1', 'Phone Number');
            $form->mobile('tel2', 'Phone Number');
            $form->email('email','Email')->rules('nullable');
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
