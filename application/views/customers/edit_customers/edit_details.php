<div id="wrap-close-overlay">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 title-left-close-overlay">
        <div class="DivParent">
            <div class="DivWhichNeedToBeVerticallyAligned">Edit Customer Information</div>
            <div class="DivHelper"></div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 title-right-close-overlay">
        <button type="button" class="btn btn-sm btn-primary btn-next" onclick="Customer_Edit.Save_Edit_Details(<?php echo !empty($customer_id)?$customer_id:''; ?>)">Save</button>
        <button type="button" class="btn btn-sm btn-primary" onclick="Customer_Edit.Close_Edit_Details(<?php echo !empty($customer_id)?$customer_id:''; ?>)">X</button>
    </div>
</div>
<div id="overlay-content" class="overlay-content">
    <form id="Edit_Details" class="fieldset_1" method="POST" accept-charset="utf-8">
        <div class="space"></div>
        <div class="oflow">
            <span><strong>Customer Information</strong></span>
            <div class="clearfix"></div>
            <table width="100%">
                <tr>
                        <td>
                         <div class="col-md-7 padding_zero">
                            <input value="<?php echo !empty($Customer[0]['customer_name'])?$Customer[0]['customer_name']:''; ?>" name="customer_name" type="text" onkeyup="Js_Top.CheckCustomerNameEmpty(this)" class="form-control" placeholder="Customer Name" style="margin-top: 5px;">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="col-md-7 padding_zero">
                            <input value="<?php echo !empty($Customer[0]['customer_no'])?$Customer[0]['customer_no']:''; ?>" name="customer_no" onkeyup="Js_Top.CheckExistsCustomerNumber(this)"  type="text" class="form-control customer_number NoSpace" placeholder="Customer No." style="margin-top: 5px;">
                            <input type="hidden" class="CheckExistsCustomerNumber" value="0" />
                        </div>
                    </td>
                </tr>
            </table>
            <div class="clearfix"></div>
            <div class="col-lg-7 col-md-6 col-sm-6 col-xs-12 left padding_zero" style="padding-top: 5px;">
                <div class="form-group" style="margin-bottom:0">
                    <div style="padding-top: 5px;">
                        <label class="radio-inline">
                            <div class="custom-checkbox-radio">
                                <input <?php echo (!empty($Customer[0]['customer_business_type']) && $Customer[0]['customer_business_type'] == 'Individual')?'checked="checked"':'';?> name="customer_business_type" value="Individual" type="radio">
                            </div>
                            &nbsp;
                            Individual
                        </label>
                        <label class="radio-inline">
                            <div class="custom-checkbox-radio">
                                <input <?php echo (!empty($Customer[0]['customer_business_type']) && $Customer[0]['customer_business_type'] == 'Organization')?'checked="checked"':'';?> name="customer_business_type" value="Organization" type="radio">
                            </div>
                            &nbsp;
                            Organization
                        </label>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12 left padding_zero">
                <table style="width: 100%;margin-top:5px">
                    <tr>
                        <td style="width:26%">Customer Type</td>
                        <td>
                            <input value="<?php echo !empty($Customer[0]['customer_type'])?$Customer[0]['customer_type']:''; ?>" name="customer_type" type="text" class="form-control">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="oflow">
            <div class="col-lg-7 padding_zero">
                <div class="col-lg-12 left title_billing_serivce" style="padding-left: 0;"><strong>Billing Address</strong></div>
                <div class="form-group">
                    <div class="col-lg-2 col-md-2 right_customers padding_zero_left"> <span>Address</span></div>
                    <div class="col-lg-10 col-md-10 padding_zero">
                        <input value="<?php echo !empty($Customer[0]['billing_name'])?$Customer[0]['billing_name']:''; ?>" onchange="Customer_Add.OnChangeBilling()" name="billing_name" type="text" class="form-control" placeholder="Blling Name - Leave blank if same as customer name">
                        <input value="<?php echo !empty($Customer[0]['billing_atn'])?$Customer[0]['billing_atn']:''; ?>" onchange="Customer_Add.OnChangeBilling()" name="billing_attention" type="text" class="form-control" placeholder="Attn"  style="margin-top: 5px;">
                        <input value="<?php echo !empty($Customer[0]['billing_address_1'])?$Customer[0]['billing_address_1']:''; ?>" onchange="Customer_Add.OnChangeBilling()" name="billing_address_1" type="text" class="form-control" placeholder="Address 1"  style="margin-top: 5px;">
                        <input value="<?php echo !empty($Customer[0]['billing_address_2'])?$Customer[0]['billing_address_2']:''; ?>" onchange="Customer_Add.OnChangeBilling()" name="billing_address_2" type="text" class="form-control" placeholder="Address 2"  style="margin-top: 5px;">
                        <div class="form-group" style="margin-top: 5px;margin-bottom:0px">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="city_billing_customers">
                                        <input value="<?php echo !empty($Customer[0]['billing_city'])?$Customer[0]['billing_city']:''; ?>" onchange="Customer_Add.OnChangeBilling()" name="billing_city" type="text" class="form-control" placeholder="City"> 
                                    </div>
                                    <div class="space_customers">&nbsp;</div>
                                    <div class="state_billing_customers">
                                        <select onchange="Customer_Add.OnChangeBilling()" name="billing_state" class="form-control">
                                            <?php  if(!empty($this->state)){foreach ($this->state as $key => $value) { ?>
                                                <option <?php echo (!empty($Customer[0]['billing_state']) && $Customer[0]['billing_state'] == $value['state_code'])?'selected="selected"':'';?> value="<?php echo $value['state_code']; ?>"><?php echo $value['state_code']; ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="space_customers_zip">&nbsp;</div>
                                    <div class="zip_billing_customers">
                                        <input value="<?php echo !empty($Customer[0]['billing_zip'])?$Customer[0]['billing_zip']:''; ?>" onchange="Customer_Add.OnChangeBilling()" name="billing_zip" type="text" class="form-control" placeholder="Zip"> 
                                    </div>
                                    <div class="space_customers">&nbsp;</div>
                                    <div class="county_billing_customers">
                                        <input value="<?php echo !empty($Customer[0]['billing_county'])?$Customer[0]['billing_county']:''; ?>" onchange="Customer_Add.OnChangeBilling()" name="billing_county" type="text" class="form-control" placeholder="County"> 
                                    </div>      
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="phone col-lg-2 col-md-2 right_customers padding_zero_left"> 
                        <span>Phone</span> 
                    </div>
                    <div class="col-lg-10 col-md-10 padding_zero">
                        <div class="form-group" style="margin-bottom:5px;">
                            <div class="row">
                                <?php 
                                    $Key_Phone_CHK = 0; 
                                    $ArrPhoneBilling = array();
                                    if(!empty($Customer[0]['billing_phone'])):
                                        $ArrPhoneBilling = json_decode($Customer[0]['billing_phone'],true);
                                    endif;
                                ?>   
                                <?php if(!empty($ArrPhoneBilling)): ?>
                                    <?php foreach ($ArrPhoneBilling as $keyPhone => $ValPhone): ?>
                                        <?php if($ValPhone['primary'] == 1): $Key_Phone_CHK = $keyPhone; endif; ?>
                                        <div class="col-lg-12 oflow wap_phone_add" style="margin-top: 5px;">
                                            <div class="phone_billing_customers">
                                                <input value="<?php echo !empty($ValPhone['phone'])?$ValPhone['phone']:''; ?>" onchange="Customer_Add.OnChangeBilling()" name="billing_phone_number[]" type="text" class="form-control maskphone"/>
                                            </div>
                                            <div class="space_customers">&nbsp;</div>
                                            <div class="ext_phone_billing_customers">
                                                <input value="<?php echo !empty($ValPhone['ext'])?$ValPhone['ext']:''; ?>" onchange="Customer_Add.OnChangeBilling()" name="billing_phone_ext[]" type="text" class="form-control" placeholder="Ext">
                                            </div>
                                            <div class="space_customers_zip">&nbsp;</div>
                                            <div class="position_phone_billing_customers">
                                                <select onchange="Customer_Add.OnChangeBilling()" name="billing_phone_type[]" class="form-control">
                                                    <?php foreach ($this->PositionPhone as $key => $value): ?>
                                                        <option <?php echo (!empty($ValPhone['type']) && $ValPhone['type'] == $key)?'selected="selected"':''; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="space_customers">&nbsp;</div>
                                            <div class="primary_phone_billing_customers">
                                                <span class="radio-inline">
                                                    <div class="custom-checkbox-radio" style="display: inline-block;">
                                                        <input <?php echo (!empty($ValPhone['primary']) && $ValPhone['primary'] == 1)?'checked="checked"':''; ?> onchange="Customer_Add.OnChangeBilling()" onclick="Js_Top.ChangePrimary(this,'billing_phone_primary','billing_index_primary','')" name="billing_phone_primary" class="billing_phone_primary" type="radio"> 
                                                        &nbsp;
                                                        Primary 
                                                    </div>
                                                    <?php if($keyPhone == 0): ?>
                                                        <span onclick="Js_Top.AddPhone('clone_phone_billing','wrap_add_phone_billing','');Customer_Add.OnChangeBilling()" class="fa fa-plus" style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
                                                    <?php else: ?>
                                                        <span onclick="Js_Top.RemovePhone(this,'billing_index_primary','billing_phone_primary','');Customer_Add.OnChangeBilling()" class="fa fa-minus"  style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
                                                    <?php endif; ?>
                                                </span>
                                            </div>     
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="col-lg-12 oflow wap_phone_add" style="margin-top: 5px;">
                                        <div class="phone_billing_customers">
                                            <input onchange="Customer_Add.OnChangeBilling()" name="billing_phone_number[]" type="text" class="form-control maskphone">
                                        </div>
                                        <div class="space_customers">&nbsp;</div>
                                        <div class="ext_phone_billing_customers">
                                            <input onchange="Customer_Add.OnChangeBilling()" name="billing_phone_ext[]" type="text" class="form-control" placeholder="Ext">
                                        </div>
                                        <div class="space_customers_zip">&nbsp;</div>
                                        <div class="position_phone_billing_customers">
                                            <select onchange="Customer_Add.OnChangeBilling()" name="billing_phone_type[]" class="form-control">
                                                <?php foreach ($this->PositionPhone as $key => $value): ?>
                                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="space_customers">&nbsp;</div>
                                        <div class="primary_phone_billing_customers">
                                            <span class="radio-inline">
                                                <div class="custom-checkbox-radio" style="display: inline-block;">
                                                    <input onchange="Customer_Add.OnChangeBilling()" onclick="Js_Top.ChangePrimary(this,'billing_phone_primary','billing_index_primary','')" name="billing_phone_primary" class="billing_phone_primary" checked="checked" type="radio"> 
                                                    &nbsp;
                                                    Primary 
                                                </div>
                                                <span onclick="Js_Top.AddPhone('clone_phone_billing','wrap_add_phone_billing','');Customer_Add.OnChangeBilling()" class="fa fa-plus" style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
                                            </span>
                                        </div>      
                                    </div>
                                <?php endif; ?>
                                <div id="wrap_add_phone_billing"></div>
                                <div id="wrap_clone_phone_billing" style="display:none">
                                    <div id="clone_phone_billing">
                                        <div class="col-lg-12 wap_phone_add" style="margin-top: 5px;overflow: hidden;clear: both;">
                                            <div class="phone_billing_customers">
                                                <input onchange="Customer_Add.OnChangeBilling()" name="billing_phone_number[]" type="text" class="form-control maskphone">
                                            </div>
                                            <div class="space_customers">&nbsp;</div>
                                            <div class="ext_phone_billing_customers">
                                                <input onchange="Customer_Add.OnChangeBilling()" name="billing_phone_ext[]" type="text" class="form-control" placeholder="Ext">
                                            </div>
                                            <div class="space_customers_zip">&nbsp;</div>
                                            <div class="position_phone_billing_customers">
                                                <select onchange="Customer_Add.OnChangeBilling()" name="billing_phone_type[]" class="form-control">
                                                    <?php foreach ($this->PositionPhone as $key => $value): ?>
                                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="space_customers">&nbsp;</div>
                                            <div class="primary_phone_billing_customers">
                                                <span class="radio-inline">
                                                    <div class="custom-checkbox-radio" style="display: inline-block;">
                                                        <input onchange="Customer_Add.OnChangeBilling()" onclick="Js_Top.ChangePrimary(this,'billing_phone_primary','billing_index_primary','')" name="billing_phone_primary" class="billing_phone_primary" type="radio"> 
                                                        &nbsp;
                                                        Primary 
                                                    </div>
                                                    <span onclick="Js_Top.RemovePhone(this,'billing_index_primary','billing_phone_primary','');Customer_Add.OnChangeBilling()" class="fa fa-minus"  style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
                                                </span>
                                            </div>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="billing_index_primary" class="billing_index_primary" value="<?php echo $Key_Phone_CHK; ?>"/>
                <div class="form-group">
                    <div class="col-lg-2  col-md-2 right_customers padding_zero_left"> <span>Email</span> </div>
                    <div class="col-lg-10 col-md-10 padding_zero">
                        <input value="<?php echo !empty($Customer[0]['billing_email'])?$Customer[0]['billing_email']:''; ?>" onchange="Customer_Add.OnChangeBilling()" name="billing_email" type="text" class="form-control"> </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-2  col-md-2 right_customers padding_zero_left"> <span>Contact Preferences</span> </div>
                        <div class="col-lg-10 col-md-10 padding_zero" style="margin-top: 5px;">
                            <div class="custom-checkbox">
                                <input <?php echo (!empty($Customer[0]['billing_chk_contact']) && $Customer[0]['billing_chk_contact'] == 1)?'checked="checked"':'';?> onchange="Customer_Add.OnChangeBilling()" name="billing_invoice_email" type="checkbox" id="billing_invoice_email" value="yes"> 
                                <label for="billing_invoice_email"></label>
                            </div>
                            Automatically send invoice e-mails
                            <br>
                            <div class="custom-checkbox">
                                <input <?php echo (!empty($Customer[0]['billing_chk_preferences']) && $Customer[0]['billing_chk_preferences'] == 1)?'checked="checked"':'';?> onchange="Customer_Add.OnChangeBilling()" name="billing_work_order_email" type="checkbox" id="billing_orders_email" value="yes"> 
                                <label for="billing_orders_email"></label>
                            </div>
                            Automatically send work order e-mails
                        </div>
                    </div>
                    <div class=" form-group">
                        <div class="col-lg-2 col-md-2 right_customers padding_zero_left"> <span>Website</span> </div>
                        <div class="col-lg-10 col-md-10 padding_zero">
                            <input value="<?php echo !empty($Customer[0]['billing_website'])?$Customer[0]['billing_website']:''; ?>" onchange="Customer_Add.OnChangeBilling()" name="billing_websites" type="text" class="form-control" style="margin-top: 5px;"> </div>
                        </div>
                        <div class=" form-group">
                            <div class="col-lg-2 col-md-2 right_customers padding_zero_left"> <span>Notes</span> </div>
                            <div class="col-lg-10 col-md-10 padding_zero">
                                <textarea onchange="Customer_Add.OnChangeBilling()" name="billing_notes" class="form-control" rows="5" style="margin-top: 5px;"><?php echo !empty($Customer[0]['billing_notes'])?$Customer[0]['billing_notes']:''; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- CONTACT -->
                <div class="oflow">
                    <div class="space"></div>
                    <div class="space"></div>
                    <div><strong>Addititional Contacts</strong></div>
                    <div id="wrap_add_contacts" class="oflow"> 
                        <?php if(!empty($Customers_contact)): ?>
                            <?php foreach ($Customers_contact as $key_c_contact => $c_contact): ?>
                                <?php  
                                    $ArrPhoneContact = array();
                                    if(!empty($c_contact['contact_phone'])):
                                        $ArrPhoneContact = json_decode($c_contact['contact_phone'],true);
                                    endif;
                                ?>
                                <div class="col-lg-7 oflow" style="border: 1px solid #545252;padding-top: 10px;padding-bottom: 10px;margin-top: 5px;position: relative;">
                                    <input name="index_contact[]" type="hidden" class="index_contact" value="<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8 left title_billing_serivce" style="padding-left: 0;margin-bottom: 10px;">
                                        <strong>Additional Contact <?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?></strong>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4 right" style="margin-bottom: 10px;">
                                        <button type="button" class="btn btn-sm btn-danger close_contact_customers" onclick="Customer_Add.Remove_Contact(this)">X</button>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <div class="col-lg-2 col-md-2 right_customers"> <span>Address</span></div>
                                        <div class="col-lg-10 col-md-10">
                                            <input value="<?php echo !empty($c_contact['contact_name'])?$c_contact['contact_name']:''; ?>" name="contact_name_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" type="text" class="form-control" placeholder="Contact Name">
                                            <input value="<?php echo !empty($c_contact['contact_atn'])?$c_contact['contact_atn']:''; ?>" name="contact_attn_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" type="text" class="form-control" placeholder="Attn" style="margin-top: 5px;">
                                            <input value="<?php echo !empty($c_contact['contact_address_1'])?$c_contact['contact_address_1']:''; ?>" name="contact_address_1_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" type="text" class="form-control" placeholder="Address 1" style="margin-top: 5px;">
                                            <input value="<?php echo !empty($c_contact['contact_address_2'])?$c_contact['contact_address_2']:''; ?>" name="contact_address_2_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" type="text" class="form-control" placeholder="Address 2" style="margin-top: 5px;">
                                            <div class="form-group" style="margin-top: 5px;margin-bottom: 0;">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="city_billing_customers">
                                                            <input value="<?php echo !empty($c_contact['contact_city'])?$c_contact['contact_city']:''; ?>" name="contact_city_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" type="text" class="form-control" placeholder="City"> 
                                                        </div>
                                                        <div class="space_customers">&nbsp;</div>
                                                        <div class="state_billing_customers">
                                                            <select name="contact_state_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" class="form-control">
                                                                <?php  if(!empty($this->state)){foreach ($this->state as $key => $value) { ?>
                                                                    <option <?php echo (!empty($c_contact['contact_state']) && $c_contact['contact_state'] == $value['state_code'])?'selected="selected"':''; ?> value="<?php echo $value['state_code']; ?>"><?php echo $value['state_code']; ?></option>
                                                                <?php }} ?>
                                                            </select>
                                                        </div>
                                                        <div class="space_customers_zip">&nbsp;</div>
                                                        <div class="zip_billing_customers">
                                                            <input value="<?php echo !empty($c_contact['contact_zip'])?$c_contact['contact_zip']:''; ?>" name="contact_zip_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" type="text" class="form-control" placeholder="Zip"> 
                                                        </div>
                                                        <div class="space_customers">&nbsp;</div>
                                                        <div class="county_billing_customers">
                                                            <input value="<?php echo !empty($c_contact['contact_county'])?$c_contact['contact_county']:''; ?>" name="contact_county_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" type="text" class="form-control" placeholder="County"> 
                                                        </div>      
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" >
                                        <div class="phone col-lg-2 col-md-2 right_customers"> 
                                            <span>Phone</span> 
                                        </div>
                                        <div class="col-lg-10 col-md-10">
                                            <div class="form-group" style="margin-bottom:5px">
                                                <div class="row">
                                                    <?php $Key_Phone_CHK_Contact = 0; ?>   
                                                    <?php if(!empty($ArrPhoneContact)): ?>
                                                        <?php foreach ($ArrPhoneContact as $keyPhoneContact => $PhoneContact): ?>
                                                            <?php if($PhoneContact['primary'] == 1): $Key_Phone_CHK_Contact = $keyPhoneContact; endif; ?>
                                                            <div class="wap_phone_add" style="margin-top: 5px;overflow: hidden;">
                                                                <div class="col-lg-12" style="overflow: hidden;">
                                                                    <div class="phone_billing_customers">
                                                                        <input value="<?php echo !empty($PhoneContact['phone'])?$PhoneContact['phone']:''; ?>" name="contact_phone_number_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>[]" type="text" class="form-control maskphone">
                                                                    </div>
                                                                    <div class="space_customers">&nbsp;</div>
                                                                    <div class="ext_phone_billing_customers">
                                                                        <input value="<?php echo !empty($PhoneContact['ext'])?$PhoneContact['ext']:''; ?>" name="contact_phone_ext_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>[]" type="text" class="form-control" placeholder="Ext">
                                                                    </div>
                                                                    <div class="space_customers_zip">&nbsp;</div>
                                                                    <div class="position_phone_billing_customers">
                                                                        <select name="contact_phone_type_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>[]" class="form-control">
                                                                            <?php foreach ($this->PositionPhone as $key => $value): ?>
                                                                                <option <?php echo (!empty($PhoneContact['type']) && $PhoneContact['type'] == $key)?'selected="selected"':''; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="space_customers">&nbsp;</div>
                                                                    <div class="primary_phone_billing_customers">
                                                                        <span class="radio-inline">
                                                                            <div class="custom-checkbox-radio" style="display: inline-block;">
                                                                                <input <?php echo (!empty($PhoneContact['primary']) && $PhoneContact['primary'] == 1)?'checked="checked"':''; ?> onclick="Js_Top.ChangePrimary(this,'contact_phone_primary_','index_phone_contact_',<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>)" class="contact_phone_primary_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" name="contact_phone_primary_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" type="radio"> 
                                                                                &nbsp;
                                                                                Primary 
                                                                            </div>
                                                                            <?php if($keyPhoneContact == 0): ?>
                                                                                <span class="fa fa-plus" onclick="Js_Top.AddPhone('clone_phone_contact_','wrap_add_phone_contact_',<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>)" style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
                                                                            <?php else: ?>
                                                                                <span onclick="Js_Top.RemovePhone(this,'index_phone_contact_','contact_phone_primary_',<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>)" class="fa fa-minus" style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
                                                                            <?php endif; ?>
                                                                        </span>
                                                                    </div>      
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <div class="wap_phone_add" style="margin-top: 5px;overflow: hidden;">
                                                            <div class="col-lg-12" style="overflow: hidden;">
                                                                <div class="phone_billing_customers">
                                                                    <input name="contact_phone_number_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>[]" type="text" class="form-control maskphone">
                                                                </div>
                                                                <div class="space_customers">&nbsp;</div>
                                                                <div class="ext_phone_billing_customers">
                                                                    <input name="contact_phone_ext_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>[]" type="text" class="form-control" placeholder="Ext">
                                                                </div>
                                                                <div class="space_customers_zip">&nbsp;</div>
                                                                <div class="position_phone_billing_customers">
                                                                    <select name="contact_phone_type_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>[]" class="form-control">
                                                                        <?php foreach ($this->PositionPhone as $key => $value): ?>
                                                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="space_customers">&nbsp;</div>
                                                                <div class="primary_phone_billing_customers">
                                                                    <span class="radio-inline">
                                                                        <div class="custom-checkbox-radio" style="display: inline-block;">
                                                                            <input onclick="Js_Top.ChangePrimary(this,'contact_phone_primary_','index_phone_contact_',<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>)" class="contact_phone_primary_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" name="contact_phone_primary_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" checked="checked" type="radio"> 
                                                                            &nbsp;
                                                                            Primary 
                                                                        </div>
                                                                        <span class="fa fa-plus" onclick="Js_Top.AddPhone('clone_phone_contact_','wrap_add_phone_contact_',<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>)" style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
                                                                    </span>
                                                                </div>      
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div id="wrap_add_phone_contact_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>"></div>
                                                <div id="wrap_clone_phone_contact_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" style="display:none">
                                                    <div id="clone_phone_contact_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>">
                                                        <div class="row wap_phone_add">
                                                            <div class="col-lg-12" style="overflow: hidden;margin-top: 5px;">
                                                                <div class="phone_billing_customers">
                                                                    <input name="contact_phone_number_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>[]" type="text" class="form-control maskphone">
                                                                </div>
                                                                <div class="space_customers">&nbsp;</div>
                                                                <div class="ext_phone_billing_customers">
                                                                    <input name="contact_phone_ext_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>[]" type="text" class="form-control" placeholder="Ext">
                                                                </div>
                                                                <div class="space_customers_zip">&nbsp;</div>
                                                                <div class="position_phone_billing_customers">
                                                                    <select name="contact_phone_type_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>[]" class="form-control">
                                                                        <?php foreach ($this->PositionPhone as $key => $value): ?>
                                                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="space_customers">&nbsp;</div>
                                                                <div class="primary_phone_billing_customers">
                                                                    <span class="radio-inline">
                                                                        <div class="custom-checkbox-radio" style="display: inline-block;">
                                                                            <input onclick="Js_Top.ChangePrimary(this,'contact_phone_primary_','index_phone_contact_',<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>)" class="contact_phone_primary_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" name="contact_phone_primary_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" type="radio"> 
                                                                            &nbsp;
                                                                            Primary 
                                                                        </div>
                                                                        <span onclick="Js_Top.RemovePhone(this,'index_phone_contact_','contact_phone_primary_',<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>)" class="fa fa-minus" style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
                                                                    </span>
                                                                </div>      
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="index_phone_contact_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" class="index_phone_contact_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" value="<?php echo $Key_Phone_CHK_Contact; ?>">
                                    <div class="form-group">
                                        <div class="col-lg-2  col-md-2 right_customers"> <span>Email</span> </div>
                                        <div class="col-lg-10 col-md-10">
                                            <input value="<?php echo !empty($c_contact['contact_email'])?$c_contact['contact_email']:''; ?>" name="contact_email_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" type="text" class="form-control"> </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-2  col-md-2 right_customers"> <span>Contact Preferences</span> </div>
                                        <div class="col-lg-10 col-md-10" style="margin-top: 5px;">
                                            <div class="custom-checkbox">
                                                <input <?php echo (!empty($c_contact['contact_chk_contact']) && $c_contact['contact_chk_contact'] == 1)?'checked="checked"':''; ?> name="contact_invoice_email_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" type="checkbox" id="contact_invoice_email_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" value="1">
                                                <label for="contact_invoice_email_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>"></label>
                                            </div>
                                            Automatically send invoice e-mails
                                            <br>
                                            <div class="custom-checkbox">
                                                <input <?php echo (!empty($c_contact['contact_chk_preferences']) && $c_contact['contact_chk_preferences'] == 1)?'checked="checked"':''; ?> name="contact_work_order_email_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" id="contact_orders_email_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" type="checkbox" value="1"> 
                                                <label for="contact_orders_email_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>"></label>
                                            </div>
                                            Automatically send work order e-mails
                                        </div>
                                    </div>
                                    <div class=" form-group">
                                        <div class="col-lg-2 col-md-2 right_customers"> <span>Website</span> </div>
                                        <div class="col-lg-10 col-md-10">
                                            <input value="<?php echo !empty($c_contact['contact_website'])?$c_contact['contact_website']:''; ?>" name="contact_websites_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" type="text" class="form-control" style="margin-top: 5px;"> </div>
                                    </div>
                                    <div class=" form-group">
                                        <div class="col-lg-2 col-md-2 right_customers"> <span>Notes</span> </div>
                                        <div class="col-lg-10 col-md-10">
                                            <textarea name="contact_notes_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" class="form-control" rows="5" style="margin-top: 5px;"><?php echo !empty($c_contact['contact_notes'])?$c_contact['contact_notes']:''; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="contact_id_<?php echo !empty($c_contact['index_contact'])?$c_contact['index_contact']:1; ?>" value="<?php echo !empty($c_contact['id'])?$c_contact['id']:'add'; ?>" />
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-12 padding_zero">
                    <div class="btn-add-contact">
                        <button class="btn btn-sm btn-primary" type="button" onclick="Customer_Add.AddContacts(this)" style="margin-top: 5px;">
                            <span class="fa fa-plus"></span> 
                        </button>
                        <span style="position: absolute;margin-top: 10px;margin-left: 10px;"> Add contacts</span> 
                    </div>
                </div>
                <!-- END CONTACT -->
            </div>
        </div>
    </form>           
</div>



