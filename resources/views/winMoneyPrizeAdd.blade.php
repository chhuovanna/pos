<!--view for win money prize-->

<style type="text/css" scoped="">

    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
         margin: 0;
    }

    input[type=number] {
        -moz-appearance:textfield;
    }

</style>

<div class="box">
    
        <div class="btn-group pull-right" style="margin-right: 10px" width='100%'>
            <a href=" {{url('/admin/sale/addsale') }}" class="btn btn-sm btn-default"><i class="fa fa-plus-circle"></i>&nbsp;Add Sale</a>
        </div>
 
        <div class="btn-group pull-right" style="margin-right: 10px" width='100%'>
            <a href=" {{url('/admin/winmoneyprize/list') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;List</a>
        </div>
        
        <BR>
    <form method="post" action="{{ url('/admin/winmoneyprize/submit') }}" accept-charset="UTF-8" class="form-horizontal" id="winmoneyprizeform" name="winmoneyprizeform">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">


    
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover" id="winmoneyprizetable">
                <thead>
                    <tr>
                        <th>Product</th> 
                        <th>PayAmount$</th>
                        <th>WinAmount$</th>
                        <th>Unit</th>
                        <th>PaySubTotal$</th>
                        <th>WinSubTotal$</th>
                        <th>Action</th> 
                    </tr>
                </thead>
                <tbody id = "winmoneyprizebody">

                    <input type="hidden" id="row" value='1'>
                    <tr id='row_0' style="display:none">
                        <td>
                            <select class="form-control product" id="product_0" name = 'product[]'>
                                @foreach($products as $key => $value)
                                    <option value="{{  $key }}">{{ $value }}</option>
                                @endforeach                                
                            </select>
                        </td>
                        <td>
                            <input id='payamount_0' class='payamount' value='0' style='width: 100px' name = 'payamount[]'>
                        </td>
                        <td>
                            <input id='winamount_0' class='winamount' value='0' style='width: 100px' name = 'winamount[]'>
                        </td>
                        <td>
                            <input id='unit_0' class='unit' value='0' style='width: 100px' name = 'unit[]'>
                        </td>
                        <td>
                            <input id='paysubtotal_0' type='number'   class='paysubtotal'     value='0'  style='width: 100px' readonly= 'readonly' name = 'paysubtotal[]'>
                        </td>
                        <td>
                            <input id='winsubtotal_0' type='number'   class='winsubtotal'     value='0'  style='width: 100px' readonly= 'readonly' name = 'winsubtotal[]'>
                        </td>
                        <td>
                            <a title="Remove row" href="javascript:void(0);" class="removerow" data-idtr="0">
                                <i class="fa fa-minus-circle"></i>
                            </a>
                        </td>
                    </tr>

                    <tr id = 'row_1'>
                        <td>
                            <select class="form-control product" id="product_1" name = 'product[]'>
                                @foreach($products as $key => $value)
                                    <option value="{{  $key }}">{{ $value }}</option>
                                @endforeach                                
                            </select>
                        </td>
                        <td>
                            <input id='payamount_1' class='payamount' value='0' style='width: 100px' name = 'payamount[]'>
                        </td>
                        <td>
                            <input id='winamount_1' class='winamount' value='0' style='width: 100px' name = 'winamount[]'>
                        </td>
                        <td>
                            <input id='unit_1' class='unit' value='0' style='width: 100px' name = 'unit[]'>
                        </td>
                        <td>
                            <input id='paysubtotal_1' type='number'   class='paysubtotal'     value='0'  style='width: 100px' readonly= 'readonly' name = 'paysubtotal[]'>
                        </td>
                        <td>
                            <input id='winsubtotal_1' type='number'   class='winsubtotal'     value='0'  style='width: 100px' readonly= 'readonly' name = 'winsubtotal[]'>
                        </td>
                        <td>
                            <a title="Add row" href="javascript:void(0);" class="addrow">
                                <i class="fa fa-plus-circle"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    



        <div class="box-body">
            <div class="fields-group">

                
                <div class="form-group 1">
                    <label for="customer" class="col-sm-2 control-label">Customer</label>
                    <div class="col-sm-1">
                        <div class="input-group">
                            <select class="form-control customer" name="customer" id="customer" style="width:150px">
                                @foreach($customers as $customer)
                                    <option value="{{  $customer->cusid }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>


                <div class="form-group 1">
                    <label for="exchangerate" class="col-sm-2 control-label">Exchange Rate</label>
                    <div class="col-sm-4">
                        <div class="input-group" >
                            <span class="input-group-addon">៛</span>
                            <input style="width: 120px" type="number"   id="exchangerate" name="exchangerate" value="{{ $exchangerate }}" class="form-control exchangerate" placeholder="Exchange Rate" readonly="readonly" />
                        </div>
                    </div>
                </div>

                <div class="form-group 1">
                    <label for="wintotal" class="col-sm-2 control-label">Win Total</label>
                    <div class="col-sm-4">
                        <div class="input-group" >
                            <span class="input-group-addon">$</span>
                            <input style="width: 120px" type="number"   id="wintotal" name="wintotal" value="0" class="form-control wintotal" placeholder="Win Total in Dollar" readonly="readonly" />
                  
                            <span class="input-group-addon">៛</span>
                            <input style="width: 120px" type="number"   id="wintotalr" name="wintotalr" value="0" class="form-control wintotalr" placeholder="Win Total in Riel" readonly="readonly" />
                        </div>
                    </div>
                </div>

                <div class="form-group 1">
                    <label for="paytotal" class="col-sm-2 control-label">Pay Total</label>
                    <div class="col-sm-4">
                        <div class="input-group" >
                            <span class="input-group-addon">$</span>
                            <input style="width: 120px" type="number"   id="paytotal" name="paytotal" value="0" class="form-control paytotal" placeholder="Pay Total in Dollar" readonly="readonly" />
                        
                            <span class="input-group-addon">៛</span>
                            <input style="width: 120px" type="number"   id="paytotalr" name="paytotalr" value="0" class="form-control paytotalr" placeholder="Pay Total in Riel" readonly="readonly" />
                        </div>
                    </div>
                </div>


                <div class="form-group 1">
                    <label for="lefttotal" class="col-sm-2 control-label">Left Total</label>
                    <div class="col-sm-4">
                        
                        <div class="input-group" >
                            <span class="input-group-addon">$</span>
                            <input style="width: 120px" type="number"   id="lefttotal" name="lefttotal" value="0" class="form-control lefttotal" placeholder="Left Total in Dollar" readonly="readonly" />
                   
                            <span class="input-group-addon">៛</span>
                            <input style="width: 120px" type="number"   id="lefttotalr" name="lefttotalr" value="0" class="form-control lefttotalr" placeholder="Left Total in Riel" readonly="readonly" />
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="box-footer">
            <div class="col-sm-8">
                <div class="btn-group pull-right">
                    <input type="submit" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Submit" value="submit">
                </div>
            </div>

        </div>

        
    </form>

    <div id='testerror' >
            <!-- <button onclick="getStock()">click me</button> -->
        </div>


</div>


<script type="text/javascript">





    $(document).off('click','.addrow');    
    $(document).on('click','.addrow',function() {

        var row = $('#row').val();
        row ++;
        $('#row').val(row);


        var newrow = $('#row_0').clone().attr('id', 'row_'+ row);
        var inputchildren = newrow.find('input');
        
        newrow.find('select').attr('id','product_' + row);
        newrow.find('a').attr('data-idtr', row );


        $(inputchildren[0]).attr('id', 'payamount_' + row);
        $(inputchildren[1]).attr('id', 'winamount_' + row);
        $(inputchildren[2]).attr('id', 'unit_' + row);
        $(inputchildren[3]).attr('id', 'paysubtotal_' + row);
        $(inputchildren[4]).attr('id', 'winsubtotal_' + row);
        newrow.show();
        $("#winmoneyprizebody").append(newrow);

        $('#product_' + row ).select2({ width: '250px' });
        

    });



    $(document).off('click','.removerow');    
    $(document).on('click','.removerow',function() {

        var idtr = $(this).attr('data-idtr');
        $('#unit_' + idtr).val(0);
        $('#winamount_' + idtr).val(0);
        $('#payamount_' + idtr).val(0);
        $('#unit_' + idtr).keyup();

        $(this).parents("tr").remove();

    });

    $(document).off('keyup','.payamount');
    $(document).off('keyup','.winamount');
    $(document).off('keyup','.unit');

    $(document).on('keyup','.payamount', getTotal);
    $(document).on('keyup','.winamount', getTotal);
    $(document).on('keyup','.unit', getTotal);


    $(document).off('click', '#submit');
    $(document).on('click', '#submit', function(e){



        var selectlist = $('#winmoneyprizebody').find('select');
        var i;
        var j;
        var selectid;
        var id
        var ignore;

        for ( i=0; i < selectlist.length ; i++){
            ignore = false;
            selectid =  $(selectlist[i]).attr('id')
            id = selectid.substring(selectid.indexOf('_') + 1);

           

            if ( $(selectlist[i]).val() ){
                if ($('#unit_' + id).val() <= 0){
                    ignore = true;

                }
            }else{
                ignore = true;
            }


            if (ignore){
                $('#unit_' + id).val(0);
                $('#unit_' + id).keyup();
                $('#product_' + id).attr('disabled', 'disabled');
                $('#payamount_' + id).attr('disabled', 'disabled');
                $('#winamount_' + id).attr('disabled', 'disabled');
                $('#unit_' + id).attr('disabled', 'disabled');
                $('#paysubtotal_' + id).attr('disabled', 'disabled');
                $('#winsubtotal_' + id).attr('disabled', 'disabled');
            }else{
                //remove the same product
                for (j = 0 ; j< selectlist.length ;j++){
                    if ( i != j &&  (  $(selectlist[i]).val() == $(selectlist[j]).val() ) 
                        && ($(selectlist[j]).attr('disabled') != 'disabled') 
                        ){
                        $('#unit_' + id).val(0);
                        $('#unit_' + id).keyup();
                        $('#product_' + id).attr('disabled', 'disabled');
                        $('#payamount_' + id).attr('disabled', 'disabled');
                        $('#winamount_' + id).attr('disabled', 'disabled');
                        $('#unit_' + id).attr('disabled', 'disabled');
                        $('#paysubtotal_' + id).attr('disabled', 'disabled');
                        $('#winsubtotal_' + id).attr('disabled', 'disabled');
                        break;
                    } 
                }
            }
        }
    
    });






    function getTotal(event){
        var inputid = event.target.id;
        var rowid = inputid.substring(inputid.indexOf('_') + 1);
        initiateNumber(inputid);
        var payamount = new Decimal($('#payamount_' + rowid).val());
        var winamount = new Decimal($('#winamount_' + rowid).val());
        var unit = $('#unit_' + rowid).val();
        var paytotal = new Decimal(0);
        var wintotal = new Decimal(0);
        var exchangerate = $('#exchangerate').val();

        $('#paysubtotal_' + rowid).val(payamount.mul(unit));
        $('#winsubtotal_' + rowid).val(winamount.mul(unit));



        $( '.paysubtotal' ).each(function( ) {
            paytotal = paytotal.add($(this).val());
        });

        $( '.winsubtotal' ).each(function( ) {
            wintotal = wintotal.add($(this).val());
        });

        $('#wintotal').val(wintotal);
        $('#paytotal').val(paytotal);
        $('#lefttotal').val(wintotal.sub(paytotal));


        $('#wintotalr').val(wintotal.mul(exchangerate));
        $('#paytotalr').val(paytotal.mul(exchangerate));
        $('#lefttotalr').val(wintotal.sub(paytotal).mul(exchangerate));

   
    }

    function initiateNumber(id){
        if (!$('#'+id).val() || isNaN($('#'+id).val()) ){
            $('#'+id).val(0);
        }
    }
   



    $(document).ready(function() { 
        $('#customer').select2();
        $('#product_1').select2({ width: '250px' });
        //$('#product').select2({ width: '250px' });


    });



</script>


