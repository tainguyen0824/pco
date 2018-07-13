<div id="wrap-close-overlay">
    <div class="col-lg-6 col-xs-6 title-left-close-overlay">
        <div class="DivParent">
           <div class="DivWhichNeedToBeVerticallyAligned">
              Edit Service
           </div><div class="DivHelper"></div>
        </div>
    </div>
    <div class="col-lg-6 col-xs-6 title-right-close-overlay" >
        <div style="text-align: right;">
           <button onclick="Customer_Edit.E_Update_Service(<?php echo $Customers[0]['id'] ?>,<?php echo $Service[0]['service_id'] ?>)" type="button" class="btn btn-sm btn-primary">Save</button>
           <button onclick="Customer_Edit.Delete_Service(<?php echo $Customers[0]['id'] ?>,<?php echo $Service[0]['service_id'] ?>)" type="button" class="btn btn-sm btn-primary">Delete Service</button>
           <button onclick="Customer_Edit.Close_Edit_Service(<?php echo $Customers[0]['id']; ?>)" type="button" class="btn btn-sm btn-primary" ><i class="fa fa-times" aria-hidden="true" style="color:#fff"></i></button>
        </div>
    </div>
</div>
<div id="overlay-content" class="overlay-content">

    <form method="POST" id="Frm_E_Service" accept-charset="utf-8">
        <div class="col-lg-12" style="margin-top: 5px;padding: 0;overflow: hidden;background-color: #cfd1d2;padding-top: 5px;padding-bottom: 5px;">
            <table style="width:100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="width:75%;padding-left: 5px;">Service Name <input name="service_name_" type="text" style="display: inline-block;width: auto;" class="form-control"  value="<?php echo !empty($Service[0]['service_name'])?$Service[0]['service_name']:''; ?>"></td>
                    <td style="width:25%;padding-right: 5px;text-align:right">PO# <?php echo !empty($Service[0]['service_PO'])?$Service[0]['service_PO']:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; ?></td>
                </tr>
            </table>
        </div>
        <div class="clearfix"></div>
        <div>
            <div style="background-color: #fff;overflow: hidden;margin-top:0;margin-bottom: 0;">
                <div class="col-lg-12 service_address_" style="padding-left: 0px;padding-right: 0px;border-left: 5px solid #cfd1d2;border-right: 5px solid #cfd1d2;">
                    <div class="col-lg-8" style="padding-top:10px;padding-bottom:10px;">
                        <div class="col-lg-6 left title_billing_serivce" style="padding-left: 0;"><strong>Service Address</strong></div>
                        <div class="clearfix"></div>
                        <?php /* ?>
                        <div class="col-lg-6 right title_billing_serivce" style="padding-left: 0;">
                            <div class="custom-checkbox">
                                <input onchange="Js_Top.DisableInputSameBilling(this)" <?php if(!empty($Service[0]['service_chk_SA_billing']) && $Service[0]['service_chk_SA_billing'] == 1): ?> checked="checked" <?php endif; ?>  name="same_as_billing_address_" data-id="" type="checkbox" id="same_as_billing"> 
                                <label for="same_as_billing"></label>
                            </div>
                            <span>Same as billing address</span>
                        </div>
                        <?php */ ?>
                        <div class="form-group">
                            <div class="col-lg-2 col-md-2 right_customers padding_zero_left"> <span>Address</span></div>
                            <div class="col-lg-10 col-md-10 padding_zero">
                                <input name="service_address_name_" value="<?php echo !empty($Service[0]['service_address_name'])?$Service[0]['service_address_name']:''; ?>" type="text" class="form-control" placeholder="Blling Name - Leave blank if same as customer name">
                                <input name="service_atn_" value="<?php echo !empty($Service[0]['service_atn'])?$Service[0]['service_atn']:''; ?>" type="text" class="form-control" placeholder="Attn" style="margin-top: 5px;">
                                <input name="service_address_1_" value="<?php echo !empty($Service[0]['service_address_1'])?$Service[0]['service_address_1']:''; ?>" onkeyup="matchRoute(this)" data-type="address_1" type="text" class="form-control" placeholder="Address 1" style="margin-top: 5px;">
                                <input name="service_address_2_" value="<?php echo !empty($Service[0]['service_address_2'])?$Service[0]['service_address_2']:''; ?>" onkeyup="matchRoute(this)" data-type="address_2" type="text" class="form-control" placeholder="Address 2" style="margin-top: 5px;">
                                <div class="form-group" style="margin-top: 5px;margin-bottom: 5px;">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="city_billing_customers">
                                                <input name="service_city_" value="<?php echo !empty($Service[0]['service_city'])?$Service[0]['service_city']:''; ?>" onkeyup="matchRoute(this)" data-type="city" type="text" class="form-control" placeholder="City">
                                            </div>
                                            <div class="space_customers">&nbsp;</div>
                                            <div class="state_billing_customers">
                                                <select name="service_state_"  onchange="matchRoute(this)" data-type="state" class="form-control">
                                                    <?php  if(!empty($this->state)){foreach ($this->state as $key => $value) { ?>
                                                        <option <?php if(!empty($Service[0]['service_state']) && $Service[0]['service_state'] == $value['state_code']): echo 'selected'; endif; ?> value="<?php echo $value['state_code']; ?>"><?php echo $value['state_code']; ?></option>
                                                    <?php }} ?>
                                                </select>
                                            </div>
                                            <div class="space_customers_zip">&nbsp;</div>
                                            <div class="zip_billing_customers">
                                                <input name="service_zip_" value="<?php echo !empty($Service[0]['service_zip'])?$Service[0]['service_zip']:''; ?>" onkeyup="matchRoute(this)" data-type="zip" type="text" class="form-control" placeholder="Zip">
                                            </div>
                                            <div class="space_customers">&nbsp;</div>
                                            <div class="county_billing_customers">
                                                <input name="service_county_" value="<?php echo !empty($Service[0]['service_county'])?$Service[0]['service_county']:''; ?>" type="text" class="form-control" placeholder="County"> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Phone -->
                        <?php  
                            if(!empty($Service[0]['service_phone'])):
                                $phone = json_decode($Service[0]['service_phone'],true);
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
                                <div class="form-group" style="margin-bottom: 5px;">     
                                    <?php $Key_Phone_CHK = 0; ?>            
                                    <?php if(!empty($phone)): ?>
                                        <?php foreach ($phone as $key_phone => $P): ?>
                                            <?php if($P['primary'] == 1): $Key_Phone_CHK = $key_phone; endif; ?>
                                                <div class="row wap_phone_add" <?php if($key_phone != 0): ?>style="margin-top: 5px;"<?php endif; ?>>
                                                    <div class="col-lg-12">
                                                        <div class="phone_billing_customers">
                                                            <input name="service_phone_number_[]" value="<?php echo !empty($P['phone'])?$P['phone']:''; ?>" type="text" class="form-control maskphone">
                                                        </div>
                                                        <div class="space_customers">&nbsp;</div>
                                                        <div class="ext_phone_billing_customers">
                                                            <input name="service_phone_ext_[]" value="<?php echo !empty($P['ext'])?$P['ext']:''; ?>" type="text" class="form-control" placeholder="Ext">
                                                        </div>
                                                        <div class="space_customers_zip">&nbsp;</div>
                                                        <div class="position_phone_billing_customers">
                                                            <select name="service_phone_type_[]" class="form-control">
                                                                <?php foreach ($this->PositionPhone as $key => $value): ?>
                                                                    <option <?php if(!empty($P['type']) && $P['type'] == $key): echo 'selected'; endif; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="space_customers">&nbsp;</div>
                                                        <div class="primary_phone_billing_customers">
                                                            <span class="radio-inline">
                                                                <div class="custom-checkbox-radio">
                                                                    <input onclick="Js_Top.ChangePrimary(this,'service_phone_primary_','index_phone_service_','')" <?php if(!empty($P['primary']) && $P['primary'] == 1): echo 'checked'; endif; ?> name="service_phone_primary_" class="service_phone_primary_" type="radio">
                                                                </div>
                                                                &nbsp;
                                                                Primary 
                                                                <?php if($key_phone == 0): ?>
                                                                    <span onclick="Js_Top.AddPhone('clone_phone_service_','wrap_add_phone_service_','')"  class="fa fa-plus" style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
                                                                <?php else: ?>
                                                                    <span onclick="Js_Top.RemovePhone(this,'index_phone_service_','service_phone_primary_','')" class="fa fa-minus" style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
                                                                <?php endif; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="phone_billing_customers">
                                                    <input name="service_phone_number_[]" type="text" class="form-control maskphone">
                                                </div>
                                                <div class="space_customers">&nbsp;</div>
                                                <div class="ext_phone_billing_customers">
                                                    <input name="service_phone_ext_[]" type="text" class="form-control" placeholder="Ext">
                                                </div>
                                                <div class="space_customers_zip">&nbsp;</div>
                                                <div class="position_phone_billing_customers">
                                                    <select name="service_phone_type_[]" class="form-control">
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
                                        </div>
                                    <?php endif; ?>
                                    <div id="wrap_add_phone_service_"></div>
                                    <div id="wrap_clone_phone_service" style="display:none">
                                        <div id="clone_phone_service_">
                                            <div class="row wap_phone_add" style="margin-top: 5px;">
                                                <div class="col-lg-12">
                                                    <div class="phone_billing_customers">
                                                        <input name="service_phone_number_[]" type="text" class="form-control maskphone">
                                                    </div>
                                                    <div class="space_customers">&nbsp;</div>
                                                    <div class="ext_phone_billing_customers">
                                                        <input name="service_phone_ext_[]" type="text" class="form-control" placeholder="Ext">
                                                    </div>
                                                    <div class="space_customers_zip">&nbsp;</div>
                                                    <div class="position_phone_billing_customers">
                                                        <select name="service_phone_type_[]" class="form-control">
                                                            <?php foreach ($this->PositionPhone as $key => $value): ?>
                                                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="space_customers">&nbsp;</div>
                                                    <div class="primary_phone_billing_customers">
                                                        <span class="radio-inline">
                                                            <div class="custom-checkbox-radio">
                                                                <input onclick="Js_Top.ChangePrimary(this,'service_phone_primary_','index_phone_service_','')" name="service_phone_primary_" class="service_phone_primary_" type="radio">
                                                            </div>
                                                            &nbsp;
                                                            Primary 
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
                        <input type="hidden" name="index_phone_service_" class="index_phone_service_" value="<?php echo $Key_Phone_CHK; ?>">
                        <div class="form-group">
                            <div class="col-lg-2  col-md-2 right_customers padding_zero_left"> <span>Email</span> </div>
                            <div class="col-lg-10 col-md-10 padding_zero">
                                <input name="service_email_" value="<?php echo !empty($Service[0]['service_email'])?$Service[0]['service_email']:''; ?>" type="text" class="form-control"> 
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-2  col-md-2 right_customers padding_zero_left"> <span>Contact Preferences</span> </div>
                            <div class="col-lg-10 col-md-10 padding_zero" style="margin-top: 5px;">
                                <div class="custom-checkbox">
                                    <input name="service_invoice_email_" <?php if(!empty($Service[0]['service_chk_contact']) && $Service[0]['service_chk_contact'] == 1){echo 'checked="checked"';} ?> id="service_invoice_email_" class="dis_same_as_billing" type="checkbox">
                                    <label for="service_invoice_email_"></label>
                                </div>
                                Automatically send invoice e-mails
                                <br>
                                <div class="custom-checkbox">
                                    <input name="service_work_order_email_" <?php if(!empty($Service[0]['service_chk_preferences']) && $Service[0]['service_chk_preferences'] == 1){echo 'checked="checked"';} ?> id="service_orders_email_" class="dis_same_as_billing" type="checkbox">
                                    <label for="service_orders_email_"></label>
                                </div>
                                Automatically send work order e-mails
                            </div>
                        </div>
                        <div class=" form-group">
                            <div class="col-lg-2 col-md-2 right_customers padding_zero_left"> <span>Website</span> </div>
                            <div class="col-lg-10 col-md-10 padding_zero">
                                <input name="service_websites_" value="<?php echo !empty($Service[0]['service_website'])?$Service[0]['service_website']:''; ?>" type="text" class="form-control" style="margin-top: 5px;"> 
                            </div>
                        </div>
                        <div class=" form-group">
                            <div class="col-lg-2 col-md-2 right_customers padding_zero_left"> <span>Notes</span> </div>
                            <div class="col-lg-10 col-md-10 padding_zero">
                                <textarea name="service_notes_" class="form-control" rows="5" style="margin-top: 5px;"><?php echo !empty($Service[0]['service_notes'])?$Service[0]['service_notes']:''; ?></textarea>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class=" form-group">
                            <div class="col-lg-2 col-md-2 right_customers padding_zero_left" style="margin-top: 5px;"> <span>Property Type</span> </div>
                            <div class="col-lg-10 col-md-10 padding_zero">
                                <select name="service_property_type_" class="form-control" style="margin-top: 5px;">
                                    <option value="">------</option>
                                    <?php if(!empty($Property_type)): ?>
                                        <?php foreach ($Property_type as $key => $value): ?>
                                            <option <?php echo (!empty($Service[0]['service_property']) && $Service[0]['service_property'] == $value['id'])?'selected':''; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>     
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
<!--                        Page Edit_service-->
                        <div class=" form-group">
                            <div class="col-lg-2 col-md-2 right_customers padding_zero_left" style="margin-top: 5px;"> <span>Service Type</span> </div>
                            <div class="col-lg-10 col-md-10 padding_zero">
                                <select name="service_service_type_" class="form-control" style="margin-top: 5px;">
                                    <option value="0">------</option>
                                    <?php if(!empty($Service_type)): ?>
                                        <?php foreach ($Service_type as $key => $value): ?>
                                            <option <?php echo (!empty($Service[0]['service_service_type']) && $Service[0]['service_service_type'] == $value['id'])?'selected':''; ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class=" form-group">
                            <div class="col-lg-2 col-md-2 right_customers padding_zero_left" style="margin-top: 5px;"> <span>Route</span> </div>
                            <div class="col-lg-10 col-md-10 padding_zero">
                                <select name="service_route_" class="form-control svRoute" style="margin-top: 5px;">
                                    <option value="0">-------</option>
                                    <?php foreach($routes as $route){
                                        if($Service[0]['service_route'] == $route['route_id']){?>
                                            <option value="<?=$route['route_id']?>" selected><?=$route['route_name']?></option>
                                        <?php } else{?>
                                            <option value="<?=$route['route_id']?>"><?=$route['route_name']?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                        </div>
                        <div class=" form-group" style="margin-bottom: 0;">
                            <div class="col-lg-2 col-md-2 right_customers padding_zero_left"> <span>Salesperson</span> </div>
                            <div class="col-lg-10 col-md-10 padding_zero">
                                <select name="service_salesperson_" class="form-control" style="margin-top: 5px;">
                                    <option value=""><?php echo $this->Technician_No_Name; ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="main_service_">
                
                    </div>
                </div>
            </div> 
        </div>
    </form>

</div>

