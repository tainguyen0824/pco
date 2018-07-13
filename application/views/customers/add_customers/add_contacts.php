<div class="col-lg-7 oflow" style="border: 1px solid #545252;padding-top: 10px;padding-bottom: 10px;margin-top: 5px;position: relative;">
    <input name="index_contact[]" type="hidden" class="index_contact" value="<?php echo !empty($index_contact)?$index_contact:1; ?>">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8 left title_billing_serivce" style="padding-left: 0;margin-bottom: 10px;">
        <strong>Additional Contact <?php echo !empty($index_contact)?$index_contact:1; ?></strong>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4 right" style="margin-bottom: 10px;">
        <button type="button" class="btn btn-sm btn-danger close_contact_customers" onclick="Customer_Add.Remove_Contact(this)">X</button>
    </div>
    <div class="clearfix"></div>
    <div class="form-group">
        <div class="col-lg-2 col-md-2 right_customers"> <span>Address</span></div>
        <div class="col-lg-10 col-md-10">
            <input name="contact_name_<?php echo !empty($index_contact)?$index_contact:1; ?>" type="text" class="form-control" placeholder="Contact Name">
            <input name="contact_attn_<?php echo !empty($index_contact)?$index_contact:1; ?>" type="text" class="form-control" placeholder="Attn" style="margin-top: 5px;">
            <input name="contact_address_1_<?php echo !empty($index_contact)?$index_contact:1; ?>" type="text" class="form-control" placeholder="Address 1" style="margin-top: 5px;">
            <input name="contact_address_2_<?php echo !empty($index_contact)?$index_contact:1; ?>" type="text" class="form-control" placeholder="Address 2" style="margin-top: 5px;">
            <div class="form-group" style="margin-top: 5px;">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="city_billing_customers">
                            <input name="contact_city_<?php echo !empty($index_contact)?$index_contact:1; ?>" type="text" class="form-control" placeholder="City"> 
                        </div>
                        <div class="space_customers">&nbsp;</div>
                        <div class="state_billing_customers">
                            <select name="contact_state_<?php echo !empty($index_contact)?$index_contact:1; ?>" class="form-control">
                                <?php  if(!empty($this->state)){foreach ($this->state as $key => $value) { ?>
                                    <option value="<?php echo $value['state_code']; ?>"><?php echo $value['state_code']; ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                        <div class="space_customers_zip">&nbsp;</div>
                        <div class="zip_billing_customers">
                            <input name="contact_zip_<?php echo !empty($index_contact)?$index_contact:1; ?>" type="text" class="form-control" placeholder="Zip"> 
                        </div>
                        <div class="space_customers">&nbsp;</div>
                        <div class="county_billing_customers">
                            <input name="contact_county_<?php echo !empty($index_contact)?$index_contact:1; ?>" type="text" class="form-control" placeholder="County"> 
                        </div>      
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="phone col-lg-2 col-md-2 right_customers"> 
            <input type="hidden" name="index_phone_contact_<?php echo !empty($index_contact)?$index_contact:1; ?>" class="index_phone_contact_<?php echo !empty($index_contact)?$index_contact:1; ?>" value="0">
            <span>Phone</span> 
        </div>
        <div class="col-lg-10 col-md-10">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12" style="overflow: hidden;">
                        <div class="phone_billing_customers">
                            <input name="contact_phone_number_<?php echo !empty($index_contact)?$index_contact:1; ?>[]" type="text" class="form-control maskphone">
                        </div>
                        <div class="space_customers">&nbsp;</div>
                        <div class="ext_phone_billing_customers">
                            <input name="contact_phone_ext_<?php echo !empty($index_contact)?$index_contact:1; ?>[]" type="text" class="form-control" placeholder="Ext">
                        </div>
                        <div class="space_customers_zip">&nbsp;</div>
                        <div class="position_phone_billing_customers">
                            <select name="contact_phone_type_<?php echo !empty($index_contact)?$index_contact:1; ?>[]" class="form-control">
                                <?php foreach ($this->PositionPhone as $key => $value): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="space_customers">&nbsp;</div>
                        <div class="primary_phone_billing_customers">
                            <span class="radio-inline">
                                <div class="custom-checkbox-radio" style="display: inline-block;">
                                    <input onclick="Js_Top.ChangePrimary(this,'contact_phone_primary_','index_phone_contact_',<?php echo !empty($index_contact)?$index_contact:1; ?>)" class="contact_phone_primary_<?php echo !empty($index_contact)?$index_contact:1; ?>" name="contact_phone_primary_<?php echo !empty($index_contact)?$index_contact:1; ?>"  checked="checked" type="radio"> 
                                    &nbsp;
                                    Primary 
                                </div>
                                <span class="fa fa-plus" onclick="Js_Top.AddPhone('clone_phone_contact_','wrap_add_phone_contact_',<?php echo $index_contact; ?>)" style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
                            </span>
                        </div>      
                    </div>
                </div>
                <div id="wrap_add_phone_contact_<?php echo !empty($index_contact)?$index_contact:1; ?>"></div>
                <div id="wrap_clone_phone_contact_<?php echo !empty($index_contact)?$index_contact:1; ?>" style="display:none">
                    <div id="clone_phone_contact_<?php echo !empty($index_contact)?$index_contact:1; ?>">
                        <div class="row wap_phone_add">
                            <div class="col-lg-12" style="overflow: hidden;margin-top: 5px;">
                                <div class="phone_billing_customers">
                                    <input name="contact_phone_number_<?php echo !empty($index_contact)?$index_contact:1; ?>[]" type="text" class="form-control maskphone">
                                </div>
                                <div class="space_customers">&nbsp;</div>
                                <div class="ext_phone_billing_customers">
                                    <input name="contact_phone_ext_<?php echo !empty($index_contact)?$index_contact:1; ?>[]" type="text" class="form-control" placeholder="Ext">
                                </div>
                                <div class="space_customers_zip">&nbsp;</div>
                                <div class="position_phone_billing_customers">
                                    <select name="contact_phone_type_<?php echo !empty($index_contact)?$index_contact:1; ?>[]" class="form-control">
                                        <?php foreach ($this->PositionPhone as $key => $value): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="space_customers">&nbsp;</div>
                                <div class="primary_phone_billing_customers">
                                    <span class="radio-inline">
                                        <div class="custom-checkbox-radio" style="display: inline-block;">
                                            <input onclick="Js_Top.ChangePrimary(this,'contact_phone_primary_','index_phone_contact_',<?php echo !empty($index_contact)?$index_contact:1; ?>)" class="contact_phone_primary_<?php echo !empty($index_contact)?$index_contact:1; ?>" name="contact_phone_primary_<?php echo !empty($index_contact)?$index_contact:1; ?>" type="radio"> 
                                            &nbsp;
                                            Primary 
                                        </div>
                                        <span onclick="Js_Top.RemovePhone(this,'index_phone_contact_','contact_phone_primary_',<?php echo !empty($index_contact)?$index_contact:1; ?>)" class="fa fa-minus" style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
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
        <div class="col-lg-2  col-md-2 right_customers"> <span>Email</span> </div>
        <div class="col-lg-10 col-md-10">
            <input name="contact_email_<?php echo !empty($index_contact)?$index_contact:1; ?>" type="text" class="form-control"> </div>
    </div>
    <div class="form-group">
        <div class="col-lg-2  col-md-2 right_customers"> <span>Contact Preferences</span> </div>
        <div class="col-lg-10 col-md-10" style="margin-top: 5px;">
            <div class="custom-checkbox">
                <input name="contact_invoice_email_<?php echo !empty($index_contact)?$index_contact:1; ?>" type="checkbox" id="contact_invoice_email_<?php echo !empty($index_contact)?$index_contact:1; ?>" checked="checked" value="">
                <label for="contact_invoice_email_<?php echo !empty($index_contact)?$index_contact:1; ?>"></label>
            </div>
            Automatically send invoice e-mails
            <br>
            <div class="custom-checkbox">
                <input name="contact_work_order_email_<?php echo !empty($index_contact)?$index_contact:1; ?>" id="contact_orders_email_<?php echo !empty($index_contact)?$index_contact:1; ?>" type="checkbox" value=""> 
                <label for="contact_orders_email_<?php echo !empty($index_contact)?$index_contact:1; ?>"></label>
            </div>
            Automatically send work order e-mails
        </div>
    </div>
    <div class=" form-group">
        <div class="col-lg-2 col-md-2 right_customers"> <span>Website</span> </div>
        <div class="col-lg-10 col-md-10">
            <input name="contact_websites_<?php echo !empty($index_contact)?$index_contact:1; ?>" type="text" class="form-control" style="margin-top: 5px;"> </div>
    </div>
    <div class=" form-group">
        <div class="col-lg-2 col-md-2 right_customers"> <span>Notes</span> </div>
        <div class="col-lg-10 col-md-10">
            <textarea name="contact_notes_<?php echo !empty($index_contact)?$index_contact:1; ?>" class="form-control" rows="5" style="margin-top: 5px;"></textarea>
        </div>
    </div>
</div>
<input type="hidden" name="contact_id_<?php echo !empty($index_contact)?$index_contact:1; ?>" value="add" />