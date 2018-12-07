<?php

namespace App\Admin\Controllers;

use App\Customer;
use App\Sale;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class LoanController extends Controller
{
    use ModelForm;

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
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('Loan');
            $content->description('Edit Loan');

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
        return Admin::grid(Loan::class, function (Grid $grid) {
            $grid->disableBatchDeletion();
            $grid->disableRowSelector();


            if (!Admin::user()->isRole('Administrator')){
                $grid->disableActions();
            }


            $grid->filter(function ($filter) {
                $customerwithloan = Customer::getCustomerWithLoan();
                $filter->equal('cusid')->select($customerwithloan);
            });


            $grid->saleid('SALEID');
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
