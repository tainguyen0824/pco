<div class="tab-pane" id="service_<?php echo $index_service; ?>">
    <input name="index_service[]" type="hidden" style="display: inline-block;width: auto;" class="form-control index_service"  value="<?php echo $index_service; ?>">
    <div class="col-lg-12" style="margin-top: 5px;padding: 0;overflow: hidden;">
        <div class="col-lg-6 col-xs-12 col-md-6 col-sm-6" style="text-align:left;padding-left: 5px;">
            <span>Service Name</span>
            <input name="service_name_<?php echo $index_service; ?>" type="text" style="display: inline-block;width: auto;" class="form-control"  value="Service <?php echo $index_service; ?>">
            
        </div>
        <div class="col-lg-6 col-xs-12 col-md-6 col-sm-6 pull-right" style="text-align:right;padding-right: 5px;">
            <span>PO#</span>
            <input name="service_po_<?php echo $index_service; ?>" type="text" style="display:inline-block;width: auto;" class="form-control" placeholder="PO# (Auto-assigned if empty)">
            <button type="button" class="btn btn-sm btn-danger" onclick="Customer_Add.CloseService()">X</button>
        </div>
    </div>
    <div class="clearfix"></div>
    <div style="background: #cfd1d2;overflow: hidden;">
        <div style="background-color: #fff;overflow: hidden;margin: 5px;padding-bottom: 10px;padding-top: 5px;">
            <div class="col-lg-8 service_address_<?php echo $index_service; ?>" style="padding-left: 5px;padding-right: 5px;">
                <div class="col-lg-12 left title_billing_serivce" style="padding-left: 0;">
                    <div class="text-left col-md-5">
                        <strong>Service Address</strong>
                    </div>
                    <div class="text-right col-md-7">
                        <div class="custom-checkbox">
                            <input onchange="Customer_Add.CheckboxSameBilling(this)" type="checkbox" data-id="<?php echo $index_service; ?>" name="same_as_billing_address_<?php echo $index_service; ?>" id="SA_billing_address_<?php echo $index_service; ?>"> 
                            <label for="SA_billing_address_<?php echo $index_service; ?>"></label>
                        </div>
                        Same as billing address
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-2 col-md-2 right_customers padding_zero_left"> <span>Billing name</span><br><br><br><br>Addresh</div>
                    <div class="col-lg-10 col-md-10 padding_zero">
                        <input name="service_address_name_<?php echo $index_service; ?>" type="text" class="form-control" placeholder="Billing Name - Leave blank if same as customer name">
                        <input name="service_atn_<?php echo $index_service; ?>" type="text" class="form-control" placeholder="Attn" style="margin-top: 5px;">
                        <input name="service_address_1_<?php echo $index_service; ?>" onkeyup="matchRoute(this)" data-type="address_1" type="text" class="form-control" placeholder="Address 1" style="margin-top: 5px;">
                        <input name="service_address_2_<?php echo $index_service; ?>" onkeyup="matchRoute(this)" data-type="address_2" type="text" class="form-control" placeholder="Address 2" style="margin-top: 5px;">
                        <div class="form-group" style="margin-top: 5px;margin-bottom: 5px">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="city_billing_customers">
                                        <input name="service_city_<?php echo $index_service; ?>" onkeyup="matchRoute(this)" data-type="city" type="text" class="form-control" placeholder="City">
                                    </div>
                                    <div class="space_customers">&nbsp;</div>
                                    <div class="state_billing_customers">
                                        <select name="service_state_<?php echo $index_service; ?>"  onchange="matchRoute(this)" data-type="state" class="form-control same_as_billing_dis">
                                            <?php  if(!empty($this->state)){foreach ($this->state as $key => $value) { ?>
                                            <option value="<?php echo $value['state_code']; ?>"><?php echo $value['state_code']; ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="space_customers_zip">&nbsp;</div>
                                    <div class="zip_billing_customers">
                                        <input name="service_zip_<?php echo $index_service; ?>" onkeyup="matchRoute(this)" data-type="zip" type="text" class="form-control" placeholder="Zip">
                                    </div>
                                    <div class="space_customers">&nbsp;</div>
                                    <div class="county_billing_customers">
                                        <input name="service_county_<?php echo $index_service; ?>" type="text" class="form-control" placeholder="County"> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="phone col-lg-2 col-md-2 right_customers padding_zero_left"> 
                        <input type="hidden" name="index_phone_service_<?php echo !empty($index_service)?$index_service:1; ?>" class="index_phone_service_<?php echo !empty($index_service)?$index_service:1; ?>" value="0">
                        <span>Phone</span> 
                    </div>
                    <div class="col-lg-10 col-md-10 padding_zero">
                        <div class="form-group" style="margin-bottom: 5px">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="phone_billing_customers">
                                        <input name="service_phone_number_<?php echo !empty($index_service)?$index_service:1; ?>[]" type="text" class="form-control maskphone">
                                    </div>
                                    <div class="space_customers">&nbsp;</div>
                                    <div class="ext_phone_billing_customers">
                                        <input name="service_phone_ext_<?php echo !empty($index_service)?$index_service:1; ?>[]" type="text" class="form-control" placeholder="Ext">
                                    </div>
                                    <div class="space_customers_zip">&nbsp;</div>
                                    <div class="position_phone_billing_customers">
                                        <select name="service_phone_type_<?php echo !empty($index_service)?$index_service:1; ?>[]" class="form-control same_as_billing_dis">
                                            <?php foreach ($this->PositionPhone as $key => $value): ?>
                                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="space_customers">&nbsp;</div>
                                    <div class="primary_phone_billing_customers">
                                        <span class="radio-inline">
                                            <div class="custom-checkbox-radio" style="display: inline-block;">
                                                <input onclick="Js_Top.ChangePrimary(this,'service_phone_primary_','index_phone_service_',<?php echo !empty($index_service)?$index_service:1; ?>)" name="service_phone_primary_<?php echo !empty($index_service)?$index_service:1; ?>" class="service_phone_primary_<?php echo !empty($index_service)?$index_service:1; ?>" checked="checked" type="radio"> 
                                                &nbsp;
                                                Primary 
                                            </div>
                                            <span onclick="Js_Top.AddPhone('clone_phone_service_','wrap_add_phone_service_',<?php echo !empty($index_service)?$index_service:1; ?>)" class="fa fa-plus" style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div id="wrap_add_phone_service_<?php echo $index_service; ?>"></div>
                            <div id="wrap_clone_phone_service_<?php echo $index_service; ?>" style="display:none">
                                <div id="clone_phone_service_<?php echo $index_service; ?>">
                                    <div class="row wap_phone_add" style="margin-top: 5px;">
                                        <div class="col-lg-12">
                                            <div class="phone_billing_customers">
                                                <input name="service_phone_number_<?php echo !empty($index_service)?$index_service:1; ?>[]" type="text" class="form-control maskphone">
                                            </div>
                                            <div class="space_customers">&nbsp;</div>
                                            <div class="ext_phone_billing_customers">
                                                <input name="service_phone_ext_<?php echo !empty($index_service)?$index_service:1; ?>[]" type="text" class="form-control" placeholder="Ext">
                                            </div>
                                            <div class="space_customers_zip">&nbsp;</div>
                                            <div class="position_phone_billing_customers">
                                                <select name="service_phone_type_<?php echo !empty($index_service)?$index_service:1; ?>[]" class="form-control same_as_billing_dis">
                                                    <?php foreach ($this->PositionPhone as $key => $value): ?>
                                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="space_customers">&nbsp;</div>
                                            <div class="primary_phone_billing_customers">
                                                <span class="radio-inline">
                                                    <div class="custom-checkbox-radio" style="display: inline-block;">
                                                        <input onclick="Js_Top.ChangePrimary(this,'service_phone_primary_','index_phone_service_',<?php echo !empty($index_service)?$index_service:1; ?>)" name="service_phone_primary_<?php echo !empty($index_service)?$index_service:1; ?>" class="service_phone_primary_<?php echo !empty($index_service)?$index_service:1; ?>" type="radio"> 
                                                        &nbsp;
                                                        Primary 
                                                    </div>
                                                    <span onclick="Js_Top.RemovePhone(this,'index_phone_service_','service_phone_primary_',<?php echo !empty($index_service)?$index_service:1; ?>)" class="fa fa-minus" style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
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
                    <div class="col-lg-2  col-md-2 right_customers padding_zero_left"> <span>Email</span> </div>
                    <div class="col-lg-10 col-md-10 padding_zero">
                        <input name="service_email_<?php echo $index_service; ?>" type="text" class="form-control"> 
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-2  col-md-2 right_customers padding_zero_left"> <span>Contact Preferences</span> </div>
                    <div class="col-lg-10 col-md-10 padding_zero" style="margin-top:5px">
                        <div class="custom-checkbox">
                            <input name="service_invoice_email_<?php echo $index_service; ?>" type="checkbox" id="service_invoice_email_<?php echo $index_service; ?>" class="dis_same_as_billing" checked="checked" value="1"> 
                            <label for="service_invoice_email_<?php echo $index_service; ?>"></label>
                        </div>
                        Automatically send invoice e-mails
                        <br>
                        <div class="custom-checkbox">
                            <input name="service_work_order_email_<?php echo $index_service; ?>" id="service_orders_email_<?php echo $index_service; ?>" class="dis_same_as_billing" type="checkbox" value="1">
                            <label for="service_orders_email_<?php echo $index_service; ?>"></label>
                        </div>
                        Automatically send work order e-mails
                    </div>
                </div>
                <div class=" form-group">
                    <div class="col-lg-2 col-md-2 right_customers padding_zero_left"> <span>Website</span> </div>
                    <div class="col-lg-10 col-md-10 padding_zero">
                        <input name="service_websites_<?php echo $index_service; ?>" type="text" class="form-control" style="margin-top: 5px;"> 
                    </div>
                </div>
                <div class=" form-group">
                    <div class="col-lg-2 col-md-2 right_customers padding_zero_left"> <span>Notes</span> </div>
                    <div class="col-lg-10 col-md-10 padding_zero">
                        <textarea name="service_notes_<?php echo $index_service; ?>" class="form-control" rows="5" style="margin-top: 5px;"></textarea>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class=" form-group">
                    <div class="col-lg-2 col-md-2 right_customers padding_zero_left" style="margin-top: 5px;"> <span>Property</span> </div>
                    <div class="col-lg-10 col-md-10 padding_zero">
                        <select name="service_property_type_<?php echo $index_service; ?>" class="form-control" style="margin-top: 5px;">
                            <option value="">------</option>     
                            <?php if(!empty($Property_type)): ?>
                                <?php foreach ($Property_type as $key => $value): ?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>     
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class=" form-group">
                    <div class="col-lg-2 col-md-2 right_customers padding_zero_left" style="margin-top: 5px;"> <span>Service Type</span> </div>
                    <div class="col-lg-10 col-md-10 padding_zero">
                        <select name="service_service_type_<?php echo $index_service; ?>" class="form-control" style="margin-top: 5px;">
                            <option value="">------</option>     
                            <?php if(!empty($Service_type)): ?>
                                <?php foreach ($Service_type as $key => $value): ?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>     
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class=" form-group">
                    <div class="col-lg-2 col-md-2 right_customers padding_zero_left" style="margin-top: 5px;"> <span>Route</span> </div>
                    <div class="col-lg-10 col-md-10 padding_zero">
                        <select name="service_route_<?php echo $index_service; ?>" class="form-control svRoute" style="margin-top: 5px;">
                            <option value="0">------</option>
                            <?php foreach($routes as $route) :?>
                                <option value="<?=$route['route_id']?>"><?=$route['route_name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class=" form-group">
                    <div class="col-lg-2 col-md-2 right_customers padding_zero_left"> <span>Salesperson</span> </div>
                    <div class="col-lg-10 col-md-10 padding_zero">
                        <select name="service_salesperson_<?php echo $index_service; ?>" class="form-control" style="margin-top: 5px;">
                            <option value=""><?php echo $this->Technician_No_Name; ?></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main_service_<?php echo $index_service; ?>">
        
    </div>
</div>
