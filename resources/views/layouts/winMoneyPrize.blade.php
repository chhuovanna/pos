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
    <form method="post" action="{{ url('/admin/winmoneyprize/submit') }}" accept-charset="UTF-8" class="form-horizontal" id="winmoneyprizeform">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">


        <div class="box-header">Win Money Prize</div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover" id="winmoneyprizetable">
                <thead>
                    <tr>
                        <th>Product</th> 
                        <th>PayAmount</th>
                        <th>WinAmount</th>
                        <th>Unit</th>
                        <th>PaySubTotal</th>
                        <th>WinSubTotal</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody id = "winmoneyprizeboday">
                </tbody>
            </table>
        </div>

    



        <div class="box-body">
            <div class="fields-group">

                <input type="hidden" name="products" id="products" value="ignore">
                
                <div class="form-group 1">
                    <label for="customer" class="col-sm-2 control-label">Customer</label>
                    <div class="col-sm-1">
                        <div class="input-group">
                            <select class="form-control customer" name="customer" id="customer" style="width:150px">
                                    <!-- <option value="0">Select Customer</option> -->
                                    @section('customers')
                                        @show
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group 1">
                    <label for="wintotal" class="col-sm-2 control-label">Win Total</label>
                    <div class="col-sm-4">
                        <div class="input-group" >
                            <span class="input-group-addon">៛</span>
                            <input style="width: 120px" type="number"   id="wintotalr" name="wintotalr" value="0" class="form-control wintotalr" placeholder="Win Total in Riel" readonly="readonly" />
                        </div>
                    </div>
                </div>

                <div class="form-group 1">
                    <label for="paytotal" class="col-sm-2 control-label">Pay Total</label>
                    <div class="col-sm-4">
                        <div class="input-group" >
                            <span class="input-group-addon">៛</span>
                            <input style="width: 120px" type="number"   id="paytotalr" name="paytotalr" value="0" class="form-control paytotalr" placeholder="Pay Total in Riel" readonly="readonly" />
                        </div>
                    </div>
                </div>


                <div class="form-group 1">
                    <label for="lefttotal" class="col-sm-2 control-label">Pay Total</label>
                    <div class="col-sm-4">
                        <div class="input-group" >
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
                    <input type="button" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Submit" value="submit">
                </div>
            </div>

        </div>

        
    </form>

    <div id='testerror' >
            <!-- <button onclick="getStock()">click me</button> -->
        </div>


</div>


<script type="text/javascript">


    function initiateNumber(id){
        if (!$('#'+id).val() || isNaN($('#'+id).val()) ){
            $('#'+id).val(0);
        }
    }


    $(document).ready(function() { 
        $('#customer').select2();

    });
   
</script>

