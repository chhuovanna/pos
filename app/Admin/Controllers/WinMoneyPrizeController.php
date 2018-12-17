<?php

namespace App\Admin\Controllers;

use App\Customer;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class WinMoneyPrizeController extends Controller
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
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $customers = Customer::orderBy('cusid')->get();

            $content->header('Win Money Prize');
            $content->description('Add win money prize');
            $content->body(view('winMoneyPrizeAdd', ['customers' => $customers] ));
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
