<div id="new_customers_calendar">
    <div class="col-lg-12 col-xs-12" style="padding-left: 5px;padding-right: 5px;">
        <div style="width: 100%;background-color: #fff;overflow: hidden;">
            <div class="col-xs-12 left" style="padding-top:5px;padding-bottom:5px;">
                <strong>Customer Information</strong>
                <table width="100%">
                    <tr>
                        <td>
                             <div class="col-md-7 padding_zero">
                                <input name="customer_name" type="text" onkeyup="Js_Top.CheckCustomerNameEmpty(this)" class="form-control" placeholder="Customer Name" style="margin-top: 5px;">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="col-md-7 padding_zero">
                                <input name="customer_no" onkeyup="Js_Top.CheckExistsCustomerNumber(this)"  type="text" class="form-control customer_number" placeholder="Customer Number"  style="margin-top: 5px;">
                                <input type="hidden" class="CheckExistsCustomerNumber" value="0" />
                            </div>
                        </td>
                    </tr>
                </table>
                <div class="col-lg-7 col-md-6 col-sm-6 col-xs-12 left padding_zero">
                    <label class="checkbox-inline" style="padding-left: 0">
                        <div class="custom-checkbox">
                            <input name="auto_customer_no" onchange="Js_Top.ChangeCustomerNo(this)" type="checkbox" id="auto-asssign"  value="yes">
                            <label for="auto-asssign"></label>
                        </div>
                        Auto-asssign customer number
                    </label>
                    <div>
                        <label class="radio-inline">
                            <div class="custom-checkbox-radio">
                                <input name="customer_business_type" value="Individual" type="radio" checked="checked">
                            </div>
                            &nbsp;
                            Individual
                        </label>
                        <label class="radio-inline">
                            <div class="custom-checkbox-radio">
                                <input name="customer_business_type" value="Organization" type="radio">
                            </div>
                            &nbsp;
                            Organization
                        </label>
                    </div>
                </div> 
                <div class="clearfix"></div>
                <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12 left padding_zero">
                    <table style="width: 100%;margin-top:5px">
                        <tr>
                            <td style="width:26%">Customer Type</td>
                            <td>
                                <input name="customer_type" type="text" class="form-control">
                            </td>
                        </tr>
                    </table>
                </div>       
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6" style="padding-left: 5px;padding-right: 5px;overflow: hidden;">
        <table style="width: 100%;margin-top:5px">
            <tr>
                <td style="font-weight: bold;width:26%">Service Type</td>
                <td>
                    <select name="service_service_type_" class="form-control">
                        <option value="">------</option>     
                        <?php if(!empty($Service_type)): ?>
                            <?php foreach ($Service_type as $key => $value): ?>
                                <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>     
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
            </tr>
        </table>
        <table style="width: 100%;margin-top:5px">
            <tr>
                <td style="font-weight: bold;width:26%">Service Name</td>
                <td>
                    <input name="service_name_" type="text" class="form-control" value="Service 1">
                </td>
            </tr>
        </table>
    </div>

    <div style="clear:both"></div>
    <div class="col-lg-6" style="padding-left: 5px;padding-right: 5px;">
        <div class="wrap_billing_address">
            <div class="col-lg-12 left title_billing_serivce" style="padding-left: 0;"><strong>Billing Address</strong></div>
            <div class="form-group">
                <div class="col-lg-2 col-md-2 padding_zero_left"> <span>Address</span></div>
                <div class="col-lg-10 col-md-10 padding_zero">
                    <input onkeyup="New_Work_Order.OnChangeBilling()" name="billing_name" type="text" class="form-control" placeholder="Blling Name - Leave blank if same as customer name">
                    <input onkeyup="New_Work_Order.OnChangeBilling()" name="billing_attention" type="text" class="form-control" placeholder="Attn"  style="margin-top: 5px;">
                    <input onkeyup="New_Work_Order.OnChangeBilling()" name="billing_address_1" type="text" class="form-control" placeholder="Address 1"  style="margin-top: 5px;">
                    <input onkeyup="New_Work_Order.OnChangeBilling()" name="billing_address_2" type="text" class="form-control" placeholder="Address 2"  style="margin-top: 5px;">
                    <div class="form-group" style="margin-top: 5px;">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="city_billing_customers">
                                    <input onkeyup="New_Work_Order.OnChangeBilling()" name="billing_city" type="text" class="form-control" placeholder="City"> 
                                </div>
                                <div class="space_customers">&nbsp;</div>
                                <div class="state_billing_customers">
                                    <select onchange="New_Work_Order.OnChangeBilling()" name="billing_state" class="form-control">
                                        <?php  if(!empty($this->state)){foreach ($this->state as $key => $value) { ?>
                                        <option value="<?php echo $value['state_code']; ?>"><?php echo $value['state_code']; ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                                <div class="space_customers_zip">&nbsp;</div>
                                <div class="zip_billing_customers">
                                    <input onkeyup="New_Work_Order.OnChangeBilling()" name="billing_zip" type="text" class="form-control" placeholder="Zip"> 
                                </div>
                                <div class="space_customers">&nbsp;</div>
                                <div class="county_billing_customers">
                                    <input onkeyup="New_Work_Order.OnChangeBilling()" name="billing_county" type="text" class="form-control" placeholder="County"> 
                                </div>      
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="phone col-lg-2 col-md-2 padding_zero_left"> 
                    <input type="hidden" name="billing_index_primary" class="billing_index_primary" value="0"/>
                    <span>Phone</span> 
                </div>
                <div class="col-lg-10 col-md-10 padding_zero">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-12 oflow">
                                <div class="phone_billing_customers">
                                    <input onchange="New_Work_Order.OnChangeBilling()" name="billing_phone_number[]" type="text" class="form-control maskphone">
                                </div>
                                <div class="space_customers">&nbsp;</div>
                                <div class="ext_phone_billing_customers">
                                    <input onkeyup="New_Work_Order.OnChangeBilling()" name="billing_phone_ext[]" type="text" class="form-control" placeholder="Ext">
                                </div>
                                <div class="space_customers_zip">&nbsp;</div>
                                <div class="position_phone_billing_customers">
                                    <select onchange="New_Work_Order.OnChangeBilling()" name="billing_phone_type[]" class="form-control">
                                        <?php foreach ($this->PositionPhone as $key => $value): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="space_customers">&nbsp;</div>
                                <div class="primary_phone_billing_customers">
                                    <span class="radio-inline">
                                        <div class="custom-checkbox-radio" style="display: inline-block;">
                                            <input onchange="New_Work_Order.OnChangeBilling()" onclick="Js_Top.ChangePrimary(this,'billing_phone_primary','billing_index_primary','')" name="billing_phone_primary" class="billing_phone_primary" checked="checked" type="radio"> 
                                            &nbsp;
                                            Primary 
                                        </div>
                                        <span onclick="Js_Top.AddPhone('clone_phone_billing','wrap_add_phone_billing','');New_Work_Order.OnChangeBilling()" class="fa fa-plus" style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
                                    </span>
                                </div>      
                            </div>
                            <div id="wrap_add_phone_billing"></div>
                            <div id="wrap_clone_phone_billing" style="display:none">
                                <div id="clone_phone_billing">
                                    <div class="col-lg-12 wap_phone_add" style="margin-top: 5px;overflow: hidden;clear: both;">
                                        <div class="phone_billing_customers">
                                            <input onchange="New_Work_Order.OnChangeBilling()" name="billing_phone_number[]" type="text" class="form-control maskphone">
                                        </div>
                                        <div class="space_customers">&nbsp;</div>
                                        <div class="ext_phone_billing_customers">
                                            <input onkeyup="New_Work_Order.OnChangeBilling()" name="billing_phone_ext[]" type="text" class="form-control" placeholder="Ext">
                                        </div>
                                        <div class="space_customers_zip">&nbsp;</div>
                                        <div class="position_phone_billing_customers">
                                            <select onchange="New_Work_Order.OnChangeBilling()" name="billing_phone_type[]" class="form-control">
                                                <?php foreach ($this->PositionPhone as $key => $value): ?>
                                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="space_customers">&nbsp;</div>
                                        <div class="primary_phone_billing_customers">
                                            <span class="radio-inline">
                                                <div class="custom-checkbox-radio" style="display: inline-block;">
                                                    <input onchange="New_Work_Order.OnChangeBilling()" onclick="Js_Top.ChangePrimary(this,'billing_phone_primary','billing_index_primary','')" name="billing_phone_primary" class="billing_phone_primary" type="radio"> 
                                                    &nbsp;
                                                    Primary 
                                                </div>
                                                <span onclick="Js_Top.RemovePhone(this,'billing_index_primary','billing_phone_primary','');New_Work_Order.OnChangeBilling()" class="fa fa-minus"  style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
                                            </span>
                                        </div>     
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-2  col-md-2 padding_zero_left"> <span>Email</span> </div>
                <div class="col-lg-10 col-md-10 padding_zero">
                    <input onkeyup="New_Work_Order.OnChangeBilling()" name="billing_email" type="text" class="form-control"> 
                </div>
            </div>
            <div class=" form-group">
                <div class="col-lg-2 col-md-2 padding_zero_left"> <span>Website</span> </div>
                <div class="col-lg-10 col-md-10 padding_zero">
                    <input onkeyup="New_Work_Order.OnChangeBilling()" name="billing_websites" type="text" class="form-control" style="margin-top: 5px;"> 
                </div>
            </div>
            <div class=" form-group">
                <div class="col-lg-2 col-md-2 padding_zero_left"> <span>Notes</span> </div>
                <div class="col-lg-10 col-md-10 padding_zero">
                    <textarea onkeyup="New_Work_Order.OnChangeBilling()" name="billing_notes" class="form-control" rows="5" style="margin-top: 5px;"></textarea>
                </div>
            </div>
            <div class=" form-group">
                <div class="col-lg-2 col-md-2 padding_zero_left">&nbsp;</div>
                <div class="col-lg-10 col-md-10 padding_zero">&nbsp;</div>
            </div>
            <div class=" form-group">
                <div class="col-lg-2 col-md-2 padding_zero_left">&nbsp;</div>
                <div class="col-lg-10 col-md-10 padding_zero">&nbsp;</div>
            </div>
        </div>
    </div>
    <div class="col-lg-6" style="padding-left: 5px;padding-right: 5px;">
        <div class="wrap_service_address service_address_">
            <div class="col-lg-12 left title_billing_serivce" style="padding-left: 0;">
                <strong>Service Address</strong>
                <span class="pull-right">
                    <div class="custom-checkbox">
                        <input onchange="New_Work_Order.CheckboxSameBilling(this)" data-id="" type="checkbox" name="same_as_billing_address_" id="SA_billing_address_"> 
                        <label for="SA_billing_address_"></label>
                    </div>
                    Same as billing address
                </span>
            </div>
            <div class="form-group">
                <div class="col-lg-2 col-md-2 padding_zero_left"> <span>Address</span></div>
                <div class="col-lg-10 col-md-10 padding_zero">
                    <input name="service_address_name_" type="text" class="form-control" placeholder="Service Name">
                    <input name="service_atn_" type="text" class="form-control" placeholder="Attn"  style="margin-top: 5px;">
                    <input name="service_address_1_" type="text" class="form-control" placeholder="Address 1"  style="margin-top: 5px;">
                    <input name="service_address_2_" type="text" class="form-control" placeholder="Address 2"  style="margin-top: 5px;">
                    <div class="form-group" style="margin-top: 5px;">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="city_billing_customers">
                                    <input name="service_city_" type="text" class="form-control" placeholder="City"> 
                                </div>
                                <div class="space_customers">&nbsp;</div>
                                <div class="state_billing_customers">
                                    <select name="service_state_" class="form-control same_as_billing_dis">
                                        <?php  if(!empty($this->state)){foreach ($this->state as $key => $value) { ?>
                                        <option value="<?php echo $value['state_code']; ?>"><?php echo $value['state_code']; ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                                <div class="space_customers_zip">&nbsp;</div>
                                <div class="zip_billing_customers">
                                    <input name="service_zip_" type="text" class="form-control" placeholder="Zip"> 
                                </div>
                                <div class="space_customers">&nbsp;</div>
                                <div class="county_billing_customers">
                                    <input name="service_county_" type="text" class="form-control" placeholder="County"> 
                                </div>      
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="phone col-lg-2 col-md-2 padding_zero_left"> 
                    <input type="hidden" name="index_phone_service_" class="index_phone_service_" value="0"/>
                    <span>Phone</span> 
                </div>
                <div class="col-lg-10 col-md-10 padding_zero">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-12 oflow">
                                <div class="phone_billing_customers">
                                    <input name="service_phone_number_[]" type="text" class="form-control maskphone">
                                </div>
                                <div class="space_customers">&nbsp;</div>
                                <div class="ext_phone_billing_customers">
                                    <input name="service_phone_ext_[]" type="text" class="form-control" placeholder="Ext">
                                </div>
                                <div class="space_customers_zip">&nbsp;</div>
                                <div class="position_phone_billing_customers">
                                    <select name="service_phone_type_[]" class="form-control same_as_billing_dis">
                                        <?php foreach ($this->PositionPhone as $key => $value): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="space_customers">&nbsp;</div>
                                <div class="primary_phone_billing_customers">
                                    <span class="radio-inline">
                                        <div class="custom-checkbox-radio" style="display: inline-block;">
                                            <input onclick="Js_Top.ChangePrimary(this,'service_phone_primary_','index_phone_service_','')" name="service_phone_primary_" class="service_phone_primary_" checked="checked" type="radio"> 
                                            &nbsp;
                                            Primary 
                                        </div>
                                        <span onclick="Js_Top.AddPhone('clone_phone_service_','wrap_add_phone_service_','')" class="fa fa-plus" style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
                                    </span>
                                </div>      
                            </div>
                            <div id="wrap_add_phone_service_"></div>
                            <div id="wrap_clone_phone_service_" style="display:none">
                                <div id="clone_phone_service_">
                                    <div class="wap_phone_add" >
                                        <div class="col-lg-12 oflow" style="margin-top: 5px;">
                                            <div class="phone_billing_customers">
                                                <input name="service_phone_number_[]" type="text" class="form-control maskphone">
                                            </div>
                                            <div class="space_customers">&nbsp;</div>
                                            <div class="ext_phone_billing_customers">
                                                <input name="service_phone_ext_[]" type="text" class="form-control" placeholder="Ext">
                                            </div>
                                            <div class="space_customers_zip">&nbsp;</div>
                                            <div class="position_phone_billing_customers">
                                                <select name="service_phone_type_[]" class="form-control same_as_billing_dis">
                                                    <?php foreach ($this->PositionPhone as $key => $value): ?>
                                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="space_customers">&nbsp;</div>
                                            <div class="primary_phone_billing_customers">
                                                <span class="radio-inline">
                                                    <div class="custom-checkbox-radio" style="display: inline-block;">
                                                        <input onclick="Js_Top.ChangePrimary(this,'service_phone_primary_','index_phone_service_','')" name="service_phone_primary_" class="service_phone_primary_" type="radio"> 
                                                        &nbsp;
                                                        Primary 
                                                    </div>
                                                    <span onclick="Js_Top.RemovePhone(this,'index_phone_service_','service_phone_primary_','')" class="fa fa-minus" style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-2  col-md-2 padding_zero_left"> <span>Email</span> </div>
                <div class="col-lg-10 col-md-10 padding_zero">
                    <input name="service_email_" type="text" class="form-control"> 
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-2 col-md-2 padding_zero_left"> <span>Website</span> </div>
                <div class="col-lg-10 col-md-10 padding_zero">
                    <input name="service_websites_" type="text" class="form-control" style="margin-top: 5px;"> 
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-2 col-md-2 padding_zero_left"> <span>Notes</span> </div>
                <div class="col-lg-10 col-md-10 padding_zero">
                    <textarea name="service_notes_" class="form-control" rows="5" style="margin-top: 5px;"></textarea>
                </div>
            </div>
            <div class=" form-group">
                <div class="col-lg-2 col-md-2 padding_zero_left"> <span>Property Type</span> </div>
                <div class="col-lg-10 col-md-10 padding_zero">
                    <select name="service_property_type_" class="form-control" style="margin-top: 5px;">
                        <option value="">------</option>     
                        <?php if(!empty($Property_type)): ?>
                            <?php foreach ($Property_type as $key => $value): ?>
                                <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>     
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </div>
    </div> 
    
    <div class="main_service_">
            
    </div>
</div>