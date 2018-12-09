<?php

namespace App\Admin\Controllers;

use App\Customer;
use App\Sale;
use App\Loan;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    use ModelForm;
    protected $script_grid = <<<SCRIPT
$(document).ready(function() {
    $("[name='cusid']").select2({ width: '170px' });
    $("[name='cusid']").on('change', function(){
        $('.btn-primary')[0].click();
    });

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

            $content->header('Loan');
            $content->description('List Loan');

            $content->body($this->grid());
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
        return Admin::grid(Loan::class, function (Grid $grid) use($script){
            $grid->disableBatchDeletion();
            $grid->disableRowSelector();


            if (!Admin::user()->isRole('Administrator')){
                $grid->disableActions();
            }
            $sql = <<<END
(
select c.cusid as cusid
     , c.name as name
     , s.saleid as saleid
     , s.ftotal as ftotal
     , s.recievedd as recievedd
     , s.recievedr as recievedr
     , s.exchangerate as exchangerate
     , (s.recievedd + (s.recievedr/s.exchangerate)) as recieved
     , l.amount as amount
     , l.state as state
     , l.created_at as created_at
  from customers c 
  join sales     s on c.cusid = s.cusid
  join loan      l on s.saleid= l.saleid
 where s.sotid = 2
) X
END;
            $grid->model()
                ->selectRaw(' * ')
                ->from(DB::raw($sql))
                ;
            
            
            $grid->filter(function ($filter) {
                $filter->between('created_at', 'Created at')->datetime();
                $customerwithloan = Customer::getCustomerWithLoan();
                $filter->equal('cusid','Customer')->select($customerwithloan);
                
            });
            


            $grid->saleid('SALEID');
            $grid->name('Name');
            $grid->ftotal('GrandTotal');
            $grid->recieved('Recieved');
            $grid->amount('Left');
            $grid->recievedd('Recieved$');
            $grid->recievedr('Recieved៛');
  
            $grid->state('Clear')->display(function ($state) {
                return $state ? 'Cleared' : 'NO';
            }); 
            
            $grid->created_at();
            
            /*$grid->updated_at();*/

          
            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();
                // append an action view sale.
                $actions->append('<a title="View sale" href="sale/list?saleid=' .$actions->getKey(). '"><i class="fa fa-search"></i></a>');
                // append action clear loan
                $actions->append('<a title="Clear loan" href="loan/clear?saleid=' .$actions->getKey(). '"><i class="fa fa-eraser"></i></a>');

            });

            Admin::script($script);
          
        });
    }

    
}
