<form  class="add_new_customer save_existing_new" type="POST">
    <div class="space"></div>
    <div class="col-lg-6 col-md-6 col-sm-6" style="padding-left: 5px;padding-right: 5px;">
        <div class="Change_Work_Order_Radio" style="position: relative;top: 5px;">
            <div class="custom-checkbox-radio" style="float: left;margin-right: 20px;">
                <input onchange="Js_Top.Change_Existing_New(this,'<?php echo !empty($type_controller)?$type_controller:''; ?>')" style="margin-left: 0;" name="check_customers_calendar" value="exitsing" checked="checked" type="radio"> 
                <span style="font-size: 0.9em;position: relative;bottom: 4px;">Existing customer</span>
            </div>
            <div class="custom-checkbox-radio" style="float: left;">
                <input onchange="Js_Top.Change_Existing_New(this,'<?php echo !empty($type_controller)?$type_controller:''; ?>')" style="margin-left: 0;" name="check_customers_calendar" value="new" type="radio"> 
                <span style="font-size: 0.9em;position: relative;bottom: 4px;">New customer</span>
            </div>
        </div>
    </div>    
    <div class="col-lg-6 col-md-6 col-sm-6" style="padding-left: 5px;padding-right: 5px;">
        <div style="float: right;width: 100%;">
            <input name="service_po_" type="text" class="form-control" placeholder="PO# (Auto-assigned if empty)" style="position: relative;bottom: 3px;">
        </div>
    </div>
    <div id="wrap_chk_customers">
        <div id="Tpl_chk_customers">

        </div>
    </div>
</form>