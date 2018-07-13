<div id="wrap-close-overlay">
    <div class="col-lg-6 col-xs-12 title-left-close-overlay">
        <div class="DivParent">
           <div class="DivWhichNeedToBeVerticallyAligned">
                Edit Event - Work Order
           </div><div class="DivHelper"></div>
        </div>
    </div>
    <div class="col-lg-6 col-xs-12 title-right-close-overlay">
        <div style="text-align: right;">
            <button onclick="Calendar_Edit.Update_Event(<?php echo !empty($scheduling_id)?$scheduling_id:0; ?>,<?php echo !empty($Event_id)?$Event_id:0; ?>)" type="button" class="btn btn-sm btn-primary">Save Work Order</button>
            <button onclick="Calendar_Edit.Delete_Event(<?php echo !empty($Event_id)?$Event_id:0; ?>)" type="button" class="btn btn-sm btn-primary">Delete Work Order</button>
            <button type="button" class="btn btn-sm btn-primary" onclick="Js_Top.closeNav()"><i class="fa fa-times" aria-hidden="true" style="color:#fff"></i></button>
        </div>
    </div>
</div>
<div id="overlay-content" class="overlay-content">
    <form id="Frm_E_Service" method="POST" accept-charset="utf-8">
        <div id="existing_customers_calendar">
            <div class="space"></div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_zero">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left: 5px;">
                    <strong><?php echo !empty($Billing[0]['customer_name'])?$Billing[0]['customer_name']:''; ?></strong>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="text-align:right">
                    <strong>PO# <?php echo !empty($Serivce[0]['service_PO'])?$Serivce[0]['service_PO']:''; ?></strong>
                </div>
            </div>
            <div class="space"></div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="padding-left: 0px;padding-right: 0px;">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="padding-left: 5px;padding-right: 5px;">
                    <div style="height: 100px;border: 1px solid #000;margin-bottom: 6px;margin-top: 3px;background-color: #fff;" class="Wap_Billing_Address">
                        <div style="padding-left: 5px;"><strong style="font-size: 0.9em;">Billing Address</strong></div>    
                        <div style="padding-left: 5px;font-size: 0.85em;">
                            <?php echo !empty($Billing[0]['billing_name'])?'<p>'.$Billing[0]['billing_name'].'</p>':''; ?>
                            <?php echo !empty($Billing[0]['billing_address_1'])?'<p>'.$Billing[0]['billing_address_1'].'</p>':''; ?>
                            <?php echo !empty($Billing[0]['billing_address_2'])?'<p>'.$Billing[0]['billing_address_2'].'</p>':''; ?>
                            <p>
                                <?php echo !empty($Billing[0]['billing_city'])?$Billing[0]['billing_city'].', ':''; ?>
                                <?php echo !empty($Billing[0]['billing_state'])?$Billing[0]['billing_state'].' ':''; ?>
                                <?php echo !empty($Billing[0]['billing_zip'])?$Billing[0]['billing_zip']:''; ?>
                            </p>
                        </div> 
                        <input name="billing_id" type="hidden" value="<?php echo !empty($Billing[0]['id'])?$Billing[0]['id']:''; ?>">
                    </div> 
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="padding-left: 5px;padding-right: 5px;">
                    <div style="height: 100px;border: 1px solid #000;margin-bottom: 4px;margin-top: 3px;background-color: #fff;" class="Wap_Service_Address">
                        <div style="float:left;padding-left: 5px;"><strong style="font-size: 0.9em;">Service Address</strong></div>
                        <div style="clear:both"></div>
                        <div style="padding-left: 5px;font-size: 0.85em;" class="Wap_Service_Address">
                            <?php echo !empty($Serivce[0]['service_address_name'])?'<p>'.$Serivce[0]['service_address_name'].'</p>':''; ?>
                            <?php echo !empty($Serivce[0]['service_address_1'])?'<p>'.$Serivce[0]['service_address_1'].'</p>':''; ?>
                            <?php echo !empty($Serivce[0]['service_address_2'])?'<p>'.$Serivce[0]['service_address_2'].'</p>':''; ?>
                            <p>
                                <?php echo !empty($Serivce[0]['service_city'])?$Serivce[0]['service_city'].', ':''; ?>
                                <?php echo !empty($Serivce[0]['service_state'])?$Serivce[0]['service_state'].' ':''; ?>
                                <?php echo !empty($Serivce[0]['service_zip'])?$Serivce[0]['service_zip']:''; ?>
                            </p>
                        </div>  
                        <input name="service_id" type="hidden" value="<?php echo !empty($Serivce[0]['service_id'])?$Serivce[0]['service_id']:''; ?>">
                    </div>
                </div>
            </div>
            <div style="clear:both"></div>
            <div class="col-lg-6 col-md-6 col-sm-6" style="padding-left: 5px;padding-right: 5px;margin-bottom:10px">
                <div class="col-lg-3 col-md-3 col-sm-3" style="padding-left: 0px;padding-right: 0px;text-align: left;">
                    <span style="position: relative;top: 0.5em;"><b>Service Type</b></span>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-9" style="padding-right: 0px;padding-left: 0px;">
                    <div class="space"></div>
                    <select name="service_service_type_" class="form-control">
                        <option value="">--</option>                                                 
                        <option <?php if(!empty($Serivce[0]['service_service_type']) && $Serivce[0]['service_service_type'] == '01_Black_Widows'): echo 'selected'; endif; ?> value="01_Black_Widows">01 Black Widows</option>
                        <option <?php if(!empty($Serivce[0]['service_service_type']) && $Serivce[0]['service_service_type'] == '02_I/O_Ants'): echo 'selected'; endif; ?> value="02_I/O_Ants">02 I/O Ants</option>
                    </select>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="main_service_">
                
            </div>
        </div>
    </form>
</div>
