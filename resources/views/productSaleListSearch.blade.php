<!--view for orderline called by saleController-->
@extends('layouts.productSale')


@section('header', 'Search Product')



@section('search')

    <div class="pull-right">
        <div class="form-inline pull-right">
      
            <fieldset>

                <div class="input-group input-group-sm">
                    <span class="input-group-addon">
                        <strong>Search Key</strong>
                    </span>
                    <input type="text" style="width: 70px" class="form-control" placeholder="Search Key" id="searchKey" value="">
                </div>

                <div class="input-group input-group-sm">
                    <span class="input-group-addon">
                        <strong>Select Products</strong>
                    </span>
                    <select class="selectProducts" name="selectProducts" id="selectProducts" >
                        @foreach($selectProducts as $key=>$value)
                            <option value="{{  $key }}">{{ $value }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="input-group input-group-sm">
                    <span class="input-group-addon">
                        <strong>Select Category</strong>
                    </span>
                    <select class="selectCategories" name="selectCategories" id="selectCategories">
                        @foreach($selectCategories as $key => $value)
                            <option value="{{  $key }}">{{ $value }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="btn-group btn-group-sm">
                    <a href="javascript:void(0);" class="btn btn-warning" id="searchproduct"><i class="fa fa-search"></i></a>
                </div>

             </fieldset>
         
        </div>
    </div>

    <span>
        <a class="btn btn-sm btn-primary grid-refresh" id="refresh"><i class="fa fa-refresh"></i> Refresh</a>
    </span>
     <span>
        <a class="btn btn-sm btn-primary goto-orderline" href="#orderline">Order Line</a>
    </span>
    
@endsection


@section('customers')
    @foreach($customers as $customer)
        <option value="{{  $customer->cusid }}">{{ $customer->name }}</option>
        @endforeach

@endsection

@section('stockouttype')
    @foreach($stockouttypes as $stockouttype)
        <option value="{{  $stockouttype->sotid }}">{{ $stockouttype->type }}</option>
        @endforeach

@endsection


@section('exchangerate')
    <input style="width: 70px" type="number" min="0" id="exchangerate" name="exchangerate" value="{{$exchangerate}}" class="form-control amount" readonly="readonly" />
@endsection


<script type="text/javascript">

    


    $(document).off('keyup','#searchKey');
    $(document).on('keyup','#searchKey',function(e){
        if (e.keyCode == 13) {
            $('#searchproduct').click();
        }
    });



    $(document).off('click','#searchproduct');
    $(document).on('click','#searchproduct',function(){
       
        if ($('#searchKey').val() ){

            $.ajax({
                type:"GET",
                url:"searchproduct",
                data:{searchKey:$('#searchKey').val()},    // multiple data sent using ajax
                success: function (data) {
                    //console.log(data);
                    var html="";
                    data.forEach(function (product){
                        html = html + "<tr>"  
                        + "<td>" + product.pid         + "</td>"
                        + "<td>" + product.barcode     + "</td>"
                        + "<td>" + product.name        + "</td>"
                        + "<td>" + product.shortcut    + "</td>"
                        + "<td>" + product.up          + "</td>"
                        + "<td>" + product.pp          + "</td>"
                        + "<td>" + product.bp          + "</td>"
                        + "<td>" + product.su          + "</td>"
                        + "<td>" + product.sp          + "</td>"
                        + "<td>" + product.sb          + "</td>"
                        + "<td>" + product.upp         + "</td>"
                        + "<td>" + product.upb         + "</td>";

                      /*  if (product.isdrugs){
                            html = html + "<td>YES</td>";
                        }else{
                            html = html + "<td>NO</td>";
                        }*/

                        html = html 
                        /*+ "<td>" + product.catname          + "</td>"
                        + "<td>" + product.mname            + "</td>"*/
                        + "<td><a href='javascript:void(0);' "
                            + "  data-pid='"    + product.pid
                            + "' data-barcode='"+ product.barcode
                            + "' data-name='"   + product.name
                            + "' data-up='"     + product.up
                            + "' data-pp='"     + product.pp
                            + "' data-bp='"     + product.bp
                            + "' data-su='"     + product.su
                            + "' data-sp='"     + product.sp
                            + "' data-sb='"     + product.sb
                            + "' data-upp='"    + product.upp
                            + "' data-upb='"    + product.upb
                            + "' class='grid-row-add-sale'>"
                            + "<i class='fa fa-cart-plus' ></i></a></td>"
                        + "</tr>";
                    });
                    $("#productbody").html(html);

                },
                error: function(data){
                    console.log(data);
                }
            });        
        }else if ($('#selectProducts').val() ){

            $.ajax({
                type:"GET",
                url:"searchproductid",
                data:{id:$('#selectProducts').val()},    // multiple data sent using ajax
                success: function (data) {
                    //console.log(data);
                    if (data.length == 1){
                        var pid = data[0].pid;
                        var row = "<tr id='tr"+pid+"' name='tr"+pid+"' >";
                        var products;
                        var html = "";
       
                        if (!$('#tr'+pid).length){ 

                            products = $('#products').val() + "," + pid ;
                            $('#products').val(products);


                        
    row = row + "<td><input id='" + pid + "pid' name='"
        + pid + "pid' type='text' value='" + data[0].pid 
        + "' style='width: 60px' readonly= 'readonly'>";
    row = row + "<input id='" + pid + "barcode' name='"
        + pid + "barcode' type='hidden' class='barcode' value='" + data[0].barcode + "'></td>";

    row = row + "<td><input id='" + pid +"name' name='"
        + pid + "name' type='text' value='" + data[0].name 
        + "' readonly= 'readonly'></td>";
    //first scan unit quantity = 1
    row = row + "<input id='" +pid+ "su' name='" +pid+ "su' type='hidden' value='" + data[0].su + "'>" 
        + "<td><input id='" +pid+ "qu' name='" +pid+ "qu' class='quantity' value='0' style='width: 60px; background-color:#def9fc' pattern='[-]?[0-9]+' autocomplete='off' ></td>" 
        + "<td><input id='" +pid+ "up' name='" +pid+ "up' class='price' value='" + data[0].up + "' style='width: 60px' ></td>";
    
    row = row + "<input id='" +pid+ "sp' name='" +pid+ "sp' type='hidden' value='" + data[0].sp + "'>"
        + "<input id='" +pid+ "upp' type='hidden' value='" +data[0].upp + "'>"
        + "<td><input id='" +pid+ "qp' name='" +pid+ "qp' class='quantity' value='0' style='width: 60px; background-color:#def9fc' pattern='[-]?[0-9]+' autocomplete='off' ></td>"
        + "<td><input id='" +pid+ "pp' name='" +pid+ "pp' class='price' value='" +data[0].pp + "' style='width: 60px' ></td>"
        ;


    row = row + "<input id='" +pid+ "sb' name='" +pid+  "sb' type='hidden' value='" + data[0].sb + "'>"
        + "<input id='" +pid+ "upb' type='hidden' value='" +data[0].upb + "'>"
        + "<td><input id='" +pid+ "qb' name='" +pid+ "qb' class='quantity' value='0' style='width: 60px; background-color:#def9fc' pattern='[-]?[0-9]+' autocomplete='off' ></td>"
        + "<td><input id='" +pid+ "bp' name='" +pid+ "bp' class='price' value='" +data[0].bp + "' style='width: 60px' ></td>"
        ;
    
    row = row + "<td><input id = '" +pid+   "stt' name='" + pid 
        + "stt' type='number' class='stt' value='0' style='width: 100px' readonly= 'readonly'>"
        + "<input type='hidden' id='" +pid+   "tstock' name='" +pid+   "tstock' value=''></td>";


    row = row + '<td><a title="Remove Orderline" href="javascript:void(0);" class="removeorderline" data-idtr="' + pid +'"><i class="fa fa-minus-circle"></i></a></td></tr>';

    /*row = row + "<input type='hidden' id='" +pid+   "tstock' name='" 
        + pid + "tstock' value=''>";
    */                        
                            $("#orderlinebody").append(row);

                            html = html + "<tr>"  
                            + "<td>" + data[0].pid         + "</td>"
                            + "<td>" + data[0].barcode     + "</td>"
                            + "<td>" + data[0].name        + "</td>"
                            + "<td>" + data[0].shortcut    + "</td>"
                            + "<td>" + data[0].up          + "</td>"
                            + "<td>" + data[0].pp          + "</td>"
                            + "<td>" + data[0].bp          + "</td>"
                            + "<td>" + data[0].su          + "</td>"
                            + "<td>" + data[0].sp          + "</td>"
                            + "<td>" + data[0].sb          + "</td>"
                            + "<td>" + data[0].upp         + "</td>"
                            + "<td>" + data[0].upb         + "</td>";

                            /*if (data[0].isdrugs){
                                html = html + "<td>YES</td>";
                            }else{
                                html = html + "<td>NO</td>";
                            }*/

                            html = html 
                            /*+ "<td>" + data[0].catname          + "</td>"
                            + "<td>" + data[0].mname            + "</td>"*/
                            + "</tr>";
                
                            $("#productbody").html(html);

                            $('#barcode').val("");
                            $('#barcode').focus();

                            $('#'+ pid+ 'qu').keyup();
                            
                        }else{
                            //alert(e.target.id);
                            toastr.error('Product has already been in the list. Please Change the quantity instead');
                        }
                    }else if( data.length > 1) {
                        alert("More than one product returned.");
                    }else {
                        alert("No product match");
                    }

                },
                error: function(data){
                    console.log(data);
                }
            });        
        }else if ($('#selectCategories').val() ){

            $.ajax({
                type:"GET",
                url:"searchproductcategory",
                data:{category:$('#selectCategories').val()},    // multiple data sent using ajax
                success: function (data) {
                    //console.log(data);
                    var html="";
                    data.forEach(function (product){
                        html = html + "<tr>"  
                        + "<td>" + product.pid         + "</td>"
                        + "<td>" + product.barcode     + "</td>"
                        + "<td>" + product.name        + "</td>"
                        + "<td>" + product.shortcut    + "</td>"
                        + "<td>" + product.up          + "</td>"
                        + "<td>" + product.pp          + "</td>"
                        + "<td>" + product.bp          + "</td>"
                        + "<td>" + product.su          + "</td>"
                        + "<td>" + product.sp          + "</td>"
                        + "<td>" + product.sb          + "</td>"
                        + "<td>" + product.upp         + "</td>"
                        + "<td>" + product.upb         + "</td>";

                      /*  if (product.isdrugs){
                            html = html + "<td>YES</td>";
                        }else{
                            html = html + "<td>NO</td>";
                        }*/

                        html = html 
                        /*+ "<td>" + product.catname          + "</td>"
                        + "<td>" + product.mname            + "</td>"*/
                        + "<td><a href='javascript:void(0);' "
                            + "  data-pid='"    + product.pid
                            + "' data-barcode='"+ product.barcode
                            + "' data-name='"   + product.name
                            + "' data-up='"     + product.up
                            + "' data-pp='"     + product.pp
                            + "' data-bp='"     + product.bp
                            + "' data-su='"     + product.su
                            + "' data-sp='"     + product.sp
                            + "' data-sb='"     + product.sb
                            + "' data-upp='"    + product.upp
                            + "' data-upb='"    + product.upb
                            + "' class='grid-row-add-sale'>"
                            + "<i class='fa fa-cart-plus' ></i></a></td>"
                        + "</tr>";
                    });
                    $("#productbody").html(html);

                },
                error: function(data){
                    console.log(data);
                }
            });        
        }else{
            var html = "";
            $("#productbody").html(html);
        }
        
        
    });

    $(document).off('click','#refresh');
    $(document).on('click','#refresh', function(){
        $.ajax({
                type:"GET",
                url:"refreshsearchproduct",
                success: function (data) {
                    //console.log(data);
                    var html="";
                    data.forEach(function (product){
                        html = html + "<tr>"  
                        + "<td>" + product.pid         + "</td>"
                        + "<td>" + product.barcode     + "</td>"
                        + "<td>" + product.name        + "</td>"
                        + "<td>" + product.shortcut    + "</td>"
                        + "<td>" + product.up          + "</td>"
                        + "<td>" + product.pp          + "</td>"
                        + "<td>" + product.bp          + "</td>"
                        + "<td>" + product.su          + "</td>"
                        + "<td>" + product.sp          + "</td>"
                        + "<td>" + product.sb          + "</td>"
                        + "<td>" + product.upp         + "</td>"
                        + "<td>" + product.upb         + "</td>";

                        /*if (product.isdrugs){
                            html = html + "<td>YES</td>";
                        }else{
                            html = html + "<td>NO</td>";
                        }*/

                        html = html 
                       /* + "<td>" + product.catname          + "</td>"
                        + "<td>" + product.mname            + "</td>"*/
                        + "<td><a href='javascript:void(0);' "
                            + "  data-pid='"    + product.pid
                            + "'  data-barcode='"+ product.barcode
                            + "' data-name='"   + product.name
                            + "' data-up='"     + product.up
                            + "' data-pp='"     + product.pp
                            + "' data-bp='"     + product.bp
                            + "' data-su='"     + product.su
                            + "' data-sp='"     + product.sp
                            + "' data-sb='"     + product.sb
                            + "' data-upp='"    + product.upp
                            + "' data-upb='"    + product.upb
                            + "' class='grid-row-add-sale'>"
                            + "<i class='fa fa-cart-plus' ></i></a></td>"
                        + "</tr>";
                    });
                    $("#productbody").html(html);
                    $("#searchKey").val("");

                },
                error: function(data){
                    console.log(data);
                }
            });
    });
    
    $(document).off('click','.grid-row-add-sale');    
    $(document).on('click','.grid-row-add-sale',function() {
        var prodatt = ["pid","name"];
        var i ;
        var pid = $(this).data('pid');
        var row = "<tr id='tr"+pid+"' name='tr"+pid+"' >";
        var products;
       
        if (!$('#tr'+pid).length){ 

            products = $('#products').val() + "," + pid ;
            $('#products').val(products);

/*            row = row + "<td><input id='" + pid + "name' name='" 
                                          + pid + "name' type='text' value='" 
                                          + $(this).data('name')
                                          +   "' style='width: 200px' readonly= 'readonly'></td>";
*/
            for (i = 0 ;i< 2 ; i++){
                row = row + "<td><input id='"   +   pid+    prodatt[i]
                                +   "' name='"  +   pid+    prodatt[i]  
                    +   "' type='text' value='" +   $(this).data(prodatt[i]);
                if (i==0){
                    row = row + "' style='width: 60px";
                }
                row = row + "' readonly= 'readonly'></td>";

            }

            
            row = row + "<td><input id='" +pid+ "barcode' name='" +pid
                    +"barcode' type='hidden' value='" +$(this).data('barcode')+ "' class='barcode'>"
                    +"<input type='hidden' id='" +pid+   "su' name='"   +pid+   "su' value='"    +$(this).data('su')+   "'>"
                    +"<input id='" +pid+   "qu' name='"    +pid+   "qu' class='quantity' value='0' style='width: 60px; background-color:#def9fc' pattern='[-]?[0-9]+' autocomplete='off' >"
                    +"</td>"
                    +"<td>"
                    +"<input id='" +pid+   "up' name='"    +pid+   "up' class='price' value='"    +$(this).data('up')+   "' style='width: 60px'>"
                    +"</td>";
            row = row + "<td>"
                    +"<input type='hidden' id='" +pid+   "sp' name='"   +pid+   "sp' value='"    +$(this).data('sp')+   "'>"
                    +"<input type='hidden' id='" +pid+   "upp' value='"    +$(this).data('upp')+   "'>"
                    +"<input id='" +pid+   "qp' name='"    +pid+   "qp'    class='quantity' value='0' style='width: 60px; background-color:#def9fc' pattern='[-]?[0-9]+' autocomplete='off' >"
                    +"</td>"
                    +"<td>"
                    +"<input id='" +pid+   "pp' name='"    +pid+   "pp' class='price' value='"   +$(this).data('pp')+   "' style='width: 60px'>"
                    +"</td>";
            row = row + "<td>"
                    +"<input type='hidden' id='" +pid+   "sb' name='"   +pid+   "sb' value='"    +$(this).data('sb')+   "'>"
                    +"<input type='hidden' id='" +pid+   "upb' value='"    +$(this).data('upb')+   "'>"
                    +"<input id='" +pid+   "qb' name='"    +pid+   "qb'  class='quantity' value='0' style='width: 60px; background-color:#def9fc' pattern='[-]?[0-9]+' autocomplete='off' >"
                    +"</td>"
                    +"<td>"                
                    +"<input id='" +pid+   "bp' name='"    +pid+   "bp' class='price' value='"   +$(this).data('bp')+   "' style='width: 60px'>"
                    +"</td>";
            row = row + "<td>"
                    +"<input id = '" +pid+   "stt' name='"   +pid+   "stt' type='number'   class='stt'     value='0'  style='width: 100px' readonly= 'readonly'>"
                    +"<input type='hidden' id='" +pid+   "tstock' name='" +pid+   "tstock' value=''></td>";

            row = row + '<td><a title="Remove Orderline" href="javascript:void(0);" class="removeorderline" data-idtr="' + pid +'"><i class="fa fa-minus-circle"></i></a></td></tr>';


//            row = row + "";

            
            $("#orderlinebody").append(row);
            
            
            
        }else{
            toastr.error('Product has already been in the list. Please Change the quantity instead');
        }

    });




    $(document).ready(function() {
        $('#selectProducts').select2({ width: '300px' });
        $('#selectCategories').select2({ width: '250px' });

        $("#selectProducts").off('change');
        $("#selectProducts").on('change', function(){
           //alert('here');
            $('#searchKey').val('');
            $('#selectCategories').val(0);
            $('#searchproduct').click();
        });

        $("#selectCategories").on('change');
        $("#selectCategories").on('change', function(){
            //alert('here2');
            $('#searchKey').val('');
            $('#selectProducts').val(0);
            $('#searchproduct').click();
        });

    })


    
   
</script>

