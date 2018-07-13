<div class="space" style="margin-top: 0px;"></div>
<div class="form-group">
    <div class="col-lg-2 col-md-2 right_customers padding_zero_left"> <span>Address</span></div>
    <div class="col-lg-10 col-md-10 padding_zero">
        <input value="<?php echo !empty($Info_Arr_Address['name'])?$Info_Arr_Address['name']:''; ?>" name="billing_name" type="text" class="form-control" placeholder="Blling Name - Leave blank if same as customer name">
        <input value="<?php echo !empty($Info_Arr_Address['atn'])?$Info_Arr_Address['atn']:''; ?>" name="billing_attention" type="text" class="form-control" placeholder="Attn"  style="margin-top: 5px;">
        <input value="<?php echo !empty($Info_Arr_Address['address_1'])?$Info_Arr_Address['address_1']:''; ?>" name="billing_address_1" type="text" class="form-control" placeholder="Address 1"  style="margin-top: 5px;">
        <input value="<?php echo !empty($Info_Arr_Address['address_2'])?$Info_Arr_Address['address_2']:''; ?>" name="billing_address_2" type="text" class="form-control" placeholder="Address 2"  style="margin-top: 5px;">
        <div class="form-group" style="margin-top: 5px;margin-bottom: 5px;">
            <div class="row">
                <div class="col-lg-12">
                    <div class="city_billing_customers">
                        <input  value="<?php echo !empty($Info_Arr_Address['city'])?$Info_Arr_Address['city']:''; ?>" name="billing_city" type="text" class="form-control" placeholder="City"> 
                    </div>
                    <div class="space_customers">&nbsp;</div>
                    <div class="state_billing_customers">
                        <select name="billing_state" class="form-control">
                            <?php  if(!empty($this->state)){foreach ($this->state as $key => $value) { ?>
                            <option <?php if(!empty($Info_Arr_Address['state']) && $Info_Arr_Address['state'] == $value['state_code']): echo 'selected'; endif; ?> value="<?php echo $value['state_code']; ?>"><?php echo $value['state_code']; ?></option>
                            <?php }} ?>
                        </select>
                    </div>
                    <div class="space_customers_zip">&nbsp;</div>
                    <div class="zip_billing_customers">
                        <input  value="<?php echo !empty($Info_Arr_Address['zip'])?$Info_Arr_Address['zip']:''; ?>" name="billing_zip" type="text" class="form-control" placeholder="Zip"> 
                    </div>
                    <div class="space_customers">&nbsp;</div>
                    <div class="county_billing_customers">
                        <input  value="<?php echo !empty($Info_Arr_Address['county'])?$Info_Arr_Address['county']:''; ?>" name="billing_county" type="text" class="form-control" placeholder="County"> 
                    </div>      
                </div>
            </div>
        </div>
    </div>
</div>
<!-- phone -->
<?php  
    if(!empty($Info_Arr_Address['phone'])):
        $phone = json_decode($Info_Arr_Address['phone'],true);
        if(empty($phone)):
            $phone = array();
        endif;
    endif;
?>
<div class="form-group">
    <div class="phone col-lg-2 col-md-2 right_customers padding_zero_left"> 
        <span>Phone</span> 
    </div>
    <div class="col-lg-10 col-md-10 padding_zero">
        <?php $Key_Phone_CHK = 0; ?>            
        <?php if(!empty($phone)): ?>
            <?php foreach ($phone as $key_phone => $P): ?>
                <?php if($P['primary'] == 1): $Key_Phone_CHK = $key_phone; endif; ?>
                    <div class="row wap_phone_add" <?php if($key_phone != 0): ?>style="margin-top: 5px;margin-bottom: 5px;" <?php else: ?> style="margin-bottom: 5px;" <?php endif; ?>>
                        <div class="col-lg-12">
                            <div class="phone_billing_customers">
                                <input name="billing_phone_number[]" value="<?php echo !empty($P['phone'])?$P['phone']:''; ?>" type="text" class="form-control maskphone">
                            </div>
                            <div class="space_customers">&nbsp;</div>
                            <div class="ext_phone_billing_customers">
                                <input name="billing_phone_ext[]" value="<?php echo !empty($P['ext'])?$P['ext']:''; ?>" type="text" class="form-control" placeholder="Ext">
                            </div>
                            <div class="space_customers_zip">&nbsp;</div>
                            <div class="position_phone_billing_customers">
                                <select name="billing_phone_type[]" class="form-control">
                                    <?php foreach ($this->PositionPhone as $key => $value): ?>
                                        <option <?php if(!empty($P['type']) && $P['type'] == $key): echo 'selected'; endif; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="space_customers">&nbsp;</div>
                            <div class="primary_phone_billing_customers">
                                <span class="radio-inline">
                                    <div class="custom-checkbox-radio">
                                        <input onclick="Js_Top.ChangePrimary(this,'service_phone_primary','index_phone_service','')" <?php if(!empty($P['primary']) && $P['primary'] == 1): echo 'checked'; endif; ?> name="service_phone_primary" class="service_phone_primary" type="radio">
                                    </div>
                                    &nbsp;
                                    Primary 
                                    <?php if($key_phone == 0): ?>
                                        <span onclick="Js_Top.AddPhone('clone_phone_service','wrap_add_phone_service','')"  class="fa fa-plus" style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
                                    <?php else: ?>
                                        <span onclick="Js_Top.RemovePhone(this,'index_phone_service','service_phone_primary','')" class="fa fa-minus" style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="row wap_phone_add" style="margin-bottom:5px;">
                <div class="col-lg-12">
                    <div class="phone_billing_customers">
                        <input name="billing_phone_number[]" type="text" class="form-control maskphone">
                    </div>
                    <div class="space_customers">&nbsp;</div>
                    <div class="ext_phone_billing_customers">
                        <input name="billing_phone_ext[]" type="text" class="form-control" placeholder="Ext">
                    </div>
                    <div class="space_customers_zip">&nbsp;</div>
                    <div class="position_phone_billing_customers">
                        <select name="billing_phone_type[]" class="form-control">
                            <?php foreach ($this->PositionPhone as $key => $value): ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="space_customers">&nbsp;</div>
                    <div class="primary_phone_billing_customers">
                        <span class="radio-inline">
                            <div class="custom-checkbox-radio" style="display: inline-block;">
                                <input onclick="Js_Top.ChangePrimary(this,'service_phone_primary','index_phone_service','')" name="service_phone_primary" class="service_phone_primary" checked="checked" type="radio"> 
                                &nbsp;
                                Primary 
                            </div>
                            <span onclick="Js_Top.AddPhone('clone_phone_service','wrap_add_phone_service','')" class="fa fa-plus" style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
                        </span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div id="wrap_add_phone_service"></div>
        <div id="wrap_clone_phone_service" style="display:none">
            <div id="clone_phone_service">
                <div class="row wap_phone_add" style="margin-top: 5px;margin-bottom: 5px;">
                    <div class="col-lg-12">
                        <div class="phone_billing_customers">
                            <input name="billing_phone_number[]" type="text" class="form-control maskphone">
                        </div>
                        <div class="space_customers">&nbsp;</div>
                        <div class="ext_phone_billing_customers">
                            <input name="billing_phone_ext[]" type="text" class="form-control" placeholder="Ext">
                        </div>
                        <div class="space_customers_zip">&nbsp;</div>
                        <div class="position_phone_billing_customers">
                            <select name="billing_phone_type[]" class="form-control">
                                <?php foreach ($this->PositionPhone as $key => $value): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="space_customers">&nbsp;</div>
                        <div class="primary_phone_billing_customers">
                            <span class="radio-inline">
                                <div class="custom-checkbox-radio">
                                    <input onclick="Js_Top.ChangePrimary(this,'service_phone_primary','index_phone_service','')" name="service_phone_primary" class="service_phone_primary" type="radio">
                                </div>
                                &nbsp;
                                Primary 
                                <span onclick="Js_Top.RemovePhone(this,'index_phone_service','service_phone_primary','')" class="fa fa-minus" style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="index_phone_service" class="index_phone_service" value="<?php echo $Key_Phone_CHK; ?>">
<div class="form-group">
    <div class="col-lg-2  col-md-2 right_customers padding_zero_left"> <span>Email</span> </div>
    <div class="col-lg-10 col-md-10 padding_zero">
        <input value="<?php echo !empty($Info_Arr_Address['email'])?$Info_Arr_Address['email']:''; ?>" name="billing_email" type="text" class="form-control"> 
    </div>
</div>
<div class=" form-group">
    <div class="col-lg-2 col-md-2 right_customers padding_zero_left"> <span>Website</span> </div>
    <div class="col-lg-10 col-md-10 padding_zero">
        <input value="<?php echo !empty($Info_Arr_Address['website'])?$Info_Arr_Address['website']:''; ?>" name="billing_websites" type="text" class="form-control" style="margin-top: 5px;"> 
    </div>
</div>
<div class=" form-group">
    <div class="col-lg-2 col-md-2 right_customers padding_zero_left"> <span>Notes</span> </div>
    <div class="col-lg-10 col-md-10 padding_zero">
        <textarea name="billing_notes" class="form-control" rows="5" style="margin-top: 5px;"><?php echo !empty($Info_Arr_Address['notes'])?$Info_Arr_Address['notes']:''; ?></textarea>
    </div>
</div>