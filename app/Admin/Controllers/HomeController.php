<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Chart\Bar;
use Encore\Admin\Widgets\Chart\Doughnut;
use Encore\Admin\Widgets\Chart\Line;
use Encore\Admin\Widgets\Chart\Pie;
use Encore\Admin\Widgets\Chart\PolarArea;
use Encore\Admin\Widgets\Chart\Radar;
use Encore\Admin\Widgets\Collapse;
use Encore\Admin\Widgets\InfoBox;
use Encore\Admin\Widgets\Tab;
use Encore\Admin\Widgets\Table;

use App\Product;
use App\Sale;
use App\Exchangerate;

class HomeController extends Controller
{
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('Dashboard');
            $content->description('Description...');

            $content->row(function ($row) {

                $nbproducts = Product::getNumberofProducts()[0]->nb;
                $row->column(4, new InfoBox('Products', 'product-hunt', 'aqua', '/admin/product',$nbproducts));

                

                $nbsales = Sale::getNumberofSales()[0]->nb;
                $row->column(4, new InfoBox('Sale', 'shopping-cart', 'green', '/admin/sale/list', $nbsales));




                $exchangerate = Exchangerate::where('currentrate',1)->first();
                $row->column(4, new InfoBox('Exchange Rate', 'usd', 'yellow', '/admin/exchangerate', $exchangerate->amount));

                $row->column(4, new InfoBox('Stock Reminder', 'inventory', 'red', '/admin/product/stockreminder', ""));
                //$row->column(4, new InfoBox('Inventory', 'inventory', 'Pink', '/admin/product/stockreminder', ""));
                
            });


            $content->row(function (Row $row) {

                $row->column(8, function (Column $column) {

                   

                    $collapse = new Collapse();

                    $currentyear = date('Y');
                    $currentmonth = date('m');
                    $salebymonth = array();
                    $expensebymonth = array();
                    $profitbymonth = array();
                    
                    $months = array();
                    $monthname = "";

                    for ( $i = 5 ; $i >= 0; $i--){
                        $monthname = date('F', strtotime("-".$i." months") );
                        $salebymonth[$monthname]=0;
                        $expensebymonth[$monthname] = 0;
                        $profitbymonth[$monthname] = 0;
                        array_push( $months, $monthname );
                    }



                    $start_date = date('Y-m', strtotime("-5 months"))."-01";

                    $data = Sale::getSixMonthSale( $start_date ); 

                    


                    $size = sizeof( $data['salebymonth'] );
                    $monthname = "";


                    for ($i=0;  $i < $size ; $i++){

                        $monthname = date('F', mktime(0,0,0,$data['salebymonth'][$i]->month));
                        $salebymonth[$monthname]    = $data['salebymonth'][$i]->sftotal;
                        $expensebymonth[$monthname] = $data['expensebymonth'][$i]->stotal;
                        $profitbymonth[$monthname]  = number_format($data['salebymonth'][$i]->sftotal  - $data['expensebymonth'][$i]->stotal,4,'.','' );
                    }
                   
                        
                    

                    /*$bar = new Bar(
                        $months,
                        [
                            ['First', $salebymonth],
                            ['Second', $expensebymonth],
                            ['Third', $profitbymonth],
                            
                        ]
                    );*/

                    $bar = new Bar(
                        $months,
                        [
                            ['Netincomes', $salebymonth],
                            ['E.Netexpense', $expensebymonth],
                            ['E.Profit', $profitbymonth],
                            
                        ]
                    );
                    $collapse->add('Sale', $bar);
                    
                    $column->append($collapse);

                    
                });

                $row->column(4, function (Column $column) {
                    $headers = ['1', '2', '3'];
                    $rows = [
                                ['Net Income(Red)','Estemated Net Expense(Green)', 'Estemated Profit(Yellow)'],

                            ];

                    $column->append((new Box('Table', new Table($headers, $rows)))->style('info')->solid());

                });

               

            });

            
        });
    }


     public function index1()
    {
        return Admin::content(function (Content $content) {

            $content->header('Dashboard');
            $content->description('Description...');

            $content->row(function ($row) {
                $row->column(3, new InfoBox('New Users', 'users', 'aqua', '/admin/users', '1024'));
                $row->column(3, new InfoBox('New Orders', 'shopping-cart', 'green', '/admin/orders', '150%'));
                $row->column(3, new InfoBox('Articles', 'book', 'yellow', '/admin/articles', '2786'));
                $row->column(3, new InfoBox('Documents', 'file', 'red', '/admin/files', '698726'));
            });

            $content->row(function (Row $row) {

                $row->column(6, function (Column $column) {

                    $tab = new Tab();

                    $pie = new Pie([
                        ['Stracke Ltd', 450], ['Halvorson PLC', 650], ['Dicki-Braun', 250], ['Russel-Blanda', 300],
                        ['Emmerich-O\'Keefe', 400], ['Bauch Inc', 200], ['Leannon and Sons', 250], ['Gibson LLC', 250],
                    ]);

                    $tab->add('Pie', $pie);
                    $tab->add('Table', new Table());
                    $tab->add('Text', 'blablablabla....');

                    $tab->dropDown([['Orders', '/admin/orders'], ['administrators', '/admin/administrators']]);
                    $tab->title('Tabs');

                    $column->append($tab);

                    $collapse = new Collapse();

                    $bar = new Bar(
                        ["January", "February", "March", "April", "May", "June", "July"],
                        [
                            ['First', [40,56,67,23,10,45,78]],
                            ['Second', [93,23,12,23,75,21,88]],
                            ['Third', [33,82,34,56,87,12,56]],
                            ['Forth', [34,25,67,12,48,91,16]],
                        ]
                    );
                    $collapse->add('Bar', $bar);
                    $collapse->add('Orders', new Table());
                    $column->append($collapse);

                    $doughnut = new Doughnut([
                        ['Chrome', 700],
                        ['IE', 500],
                        ['FireFox', 400],
                        ['Safari', 600],
                        ['Opera', 300],
                        ['Navigator', 100],
                    ]);
                    $column->append((new Box('Doughnut', $doughnut))->removable()->collapsable()->style('info'));
                });

                $row->column(6, function (Column $column) {

                    $column->append(new Box('Radar', new Radar()));

                    $polarArea = new PolarArea([
                        ['Red', 300],
                        ['Blue', 450],
                        ['Green', 700],
                        ['Yellow', 280],
                        ['Black', 425],
                        ['Gray', 1000],
                    ]);
                    $column->append((new Box('Polar Area', $polarArea))->removable()->collapsable());

                    $column->append((new Box('Line', new Line()))->removable()->collapsable()->style('danger'));
                });

            });

            $headers = ['Id', 'Email', 'Name', 'Company', 'Last Login', 'Status'];
            $rows = [
                [1, 'labore21@yahoo.com', 'Ms. Clotilde Gibson', 'Goodwin-Watsica', '1997-08-13 13:59:21', 'open'],
                [2, 'omnis.in@hotmail.com', 'Allie Kuhic', 'Murphy, Koepp and Morar', '1988-07-19 03:19:08', 'blocked'],
                [3, 'quia65@hotmail.com', 'Prof. Drew Heller', 'Kihn LLC', '1978-06-19 11:12:57', 'blocked'],
                [4, 'xet@yahoo.com', 'William Koss', 'Becker-Raynor', '1988-09-07 23:57:45', 'open'],
                [5, 'ipsa.aut@gmail.com', 'Ms. Antonietta Kozey Jr.', 'Braun Ltd', '2013-10-16 10:00:01', 'open'],
            ];

            $content->row((new Box('Table', new Table($headers, $rows)))->style('info')->solid());
        });
    }
}
