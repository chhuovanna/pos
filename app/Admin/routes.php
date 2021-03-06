<?php

use Illuminate\Routing\Router;

Admin::registerHelpersRoutes();

Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => Admin::controllerNamespace(),
    'middleware'    => ['web', 'admin'],
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    
    
    //$router->get('product/createwithimp/save','ProductController@saveformwithimp');
    //$router->get("product/createwithimp", "ProductController@createwithimp");
    //$router->get('product/createwithimp', 'ProductController@createwithimp');
    

    
    

    
    

    //$router->resource('category', CategoryController::class);
    $router->resource('manufacturer', ManufacturerController::class);
    $router->resource('customer', CustomerController::class);
    $router->resource('exchangerate', ExchangerateController::class);
    $router->resource('importer', ImporterController::class);
    $router->resource('saleassistant', SaleassistantController::class);
    

    //$router->get('inventory/search','InventoryController@searchInventory');
    $router->get('inventory/create/{product}', 'InventoryController@productInventory');
    /////

    $router->get('inventory/quickadd',"InventoryController@quickAdd");
    /////

    $router->resource('inventory', InventoryController::class);

    

    $router->get('sale/addsale','SaleController@addSale');
    $router->get('sale/addsalebarcode','SaleController@addSaleBarcode');
    $router->get('sale/addsalebarcodeunit','SaleController@addSaleBarcodeUnit');
    
    $router->get('sale/searchproduct','SaleController@searchProduct');
    $router->get('sale/searchproductbarcode','SaleController@searchProductBarcode');
    $router->get('sale/refreshsearchproduct','SaleController@refreshSearchProduct');
    $router->get('sale/getStock','SaleController@getStock');
    $router->get('sale/searchsale','SaleController@searchsale');
    // for rtpos 
    $router->get('sale/searchproductid','SaleController@searchProductID');
    $router->get('sale/searchproductcategory','SaleController@searchProductCategory');
    //


    //$router->post('sale/checkout','SaleController@checkout');
    $router->post('sale/checkout', 'SaleController@checkout');


    /*$router->get('sale/list','SaleController@listSale');
    $router->get('sale/list/{sale}/edit','SaleController@edit');
    $router->put('sale/list/{sale}','SaleController@update');*/
    $router->resource('sale/list',SaleController::class);
    $router->resource('orderline',SaleProductController::class);
    $router->get("searchsaleproduct","SaleProductController@searchsaleproduct");
    $router->get("searchinventory","InventoryController@searchinventory");

    //for rtpos
    $router->get("loan","LoanController@index");

    $router->post("loan/clear/{saleid}","LoanController@clearLoan");
    $router->get('sale/printreceipt', 'SaleController@printReceipt');
    $router->get('sale/viewreceipt', 'SaleController@viewReceipt');
    $router->get('product/stockreminder', 'ProductController@stockreminder');
    $router->get('product/stockreminder/print', 'ProductController@printStockReminder');
    ////

    $router->resource('product', ProductController::class);
    

    //for win money prize

    $router->get('winmoneyprize/list','WinMoneyPrizeController@index');
    $router->get('winmoneyprize/list/create','WinMoneyPrizeController@create');
    $router->post('winmoneyprize/submit', 'WinMoneyPrizeController@save');
    $router->delete('winmoneyprize/list/{wmpid}','WinMoneyPrizeController@destroy');
    $router->get('winmoneyprize/viewdetail', 'WinMoneyPrizeController@viewdetail');
    ///

    ///for packname, boxname base on category

    $router->get('category/setunitname','CategoryController@setunitname');
    $router->post('category/setunitname/save','CategoryController@setunitnamesave');
    $router->resource('category', CategoryController::class);


    



//    $router->resource('pisassistant', ProdImpSaleassistantController::class);


});

//$router->resource('product', ProductController::class);
