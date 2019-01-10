<?php

namespace App\Admin\Controllers;

use App\Customer;
use App\Product;
use App\Exchangerate;
use App\Winmoneyprize;
use App\winmoneyprizeProduct;

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
    use ModelForm;


    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('Win money prize');
            $content->description('List');
            $content->body($this->grid());
        });
    }


    protected function grid()
    {
           
        return Admin::grid(Winmoneyprize::class, function (Grid $grid){


            $grid->filter(function ($filter) {

                $filter->disableIdFilter();

                $filter->equal('wmpid');
                
                $customers = Customer::getSelectOption();
                $filter->equal('cusid')->select($customers);
            
            });   

            $grid->model()->with('customer');
            $grid->model()->orderBy('wmpid','DESC');
            $grid->paginate(20);
            $grid->disableBatchDeletion();
            $grid->disableRowSelector();
            
           
            $grid->wmpid('ID');
            $grid->customer()->name('Customer');
            $grid->exchangerate();
            $grid->paytotal('Pay amount')->sortable();
            $grid->wintotal('Win amount')->sortable();
            $grid->lefttotal('Left amount')->sortable();


            $grid->actions(function ($actions) {


                $actions->disableEdit();
                
                // append an action.
                $actions->append('<a title="View detail" onclick="window.open(\'' .url('/admin/winmoneyprize/viewdetail?wmpid=').$actions->getKey(). '\' ,\'_blank\', \'height=700,width=700\'); "><i class="fa fa-eye"></i></a>');

            });

            $script = <<<SCRIPT
$("[name='cusid']").select2({ width: '170px' });
$("[name='pid']").select2({ width: '170px' });

SCRIPT;

            Admin::script($script);
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create(Request $request)
    {
        return Admin::content(function (Content $content) use ($request){

            $customers = Customer::orderBy('cusid')->get();
            $products  = Product::getSelectOption();
            $exchangerate = Exchangerate::where('currentrate',1)->first();

            $content->header('Win Money Prize');
            $content->description('Add win money prize');
            $content->body(view('winMoneyPrizeAdd', ['customers' => $customers
                                                , 'products'     => $products
                                                , 'exchangerate' => $exchangerate->amount ] ));


            if ($request){
                $input = $request->all();
                
                
                if (array_key_exists('savestate', $input) && $input['savestate'] === 'success'){

                    
                    $script = <<<SCRIPT
$(document).ready(function(){
    toastr.success('Save Success');
});


SCRIPT;
                Admin::script($script);
                }

            }

        });
    }


    public function save(Request $request){

        $input = $request->all();
        print_r($input);

        DB::transaction(function () use ($input){
            $size = sizeof($input['unit']);
            $winmoneyprize = new Winmoneyprize();
            $winmoneyprize->cusid = $input['customer'];
            $winmoneyprize->paytotal = $input['paytotal'];
            $winmoneyprize->wintotal = $input['wintotal'];
            $winmoneyprize->lefttotal = $input['lefttotal'];
            $winmoneyprize->exchangerate = $input['exchangerate'];
            $winmoneyprize->save();

            for($i = 0 ; $i < $size ; $i++){
                $winmoneyprizeproduct = new WinmoneyprizeProduct();
                $winmoneyprizeproduct->wmpid = $winmoneyprize->wmpid;
                $winmoneyprizeproduct->pid = $input['product'][$i];
                $winmoneyprizeproduct->payamount = $input['payamount'][$i];
                $winmoneyprizeproduct->winamount = $input['winamount'][$i];
                $winmoneyprizeproduct->unit = $input['unit'][$i];
                $winmoneyprizeproduct->paysubtotal = $input['paysubtotal'][$i];
                $winmoneyprizeproduct->winsubtotal = $input['winsubtotal'][$i];
                $winmoneyprizeproduct->leftsubtotal = $input['winsubtotal'][$i] - $input['paysubtotal'][$i];
                $winmoneyprizeproduct->save();
            }


        });

        DB::commit();
        $url = strtok(url()->previous(), '?')."?savestate=success";

        return redirect($url);    

        
    }


    public function destroy($id)
    {

        if (Admin::user()->isRole('Administrator')){
            $winmoneyprize = Winmoneyprize::where('wmpid',$id)->first();
            if ($winmoneyprize) {
                $winmoneyprize->delete();
                return response()->json([
                    'status'  => true,
                    'message' => trans('admin::lang.delete_succeeded'),
                ]);
            }
        }
    }

    public function viewdetail(Request $request){
        $input = $request->all();

        $winmoneyprize = Winmoneyprize::getWinMoneyPrizeWithCustomer($input['wmpid']);
        $winmoneyprizeproducts = WinmoneyprizeProduct::getWinMoneyPrizeWithProduct($input['wmpid']);
        return view('winMoneyPrizeProduct',
                [ 'winmoneyprize' => $winmoneyprize
                , 'winmoneyprizeproducts' => $winmoneyprizeproducts
                ]);
    }


}
