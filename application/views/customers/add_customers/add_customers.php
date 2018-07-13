<div id="wrap-close-overlay">
    <fieldset class="fieldset_1">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 title-left-close-overlay">
            <div class="DivParent">
                <div class="DivWhichNeedToBeVerticallyAligned"> New Customer - Step 1 (Customer Information)</div>
                <div class="DivHelper"></div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 title-right-close-overlay">
            <button type="button" class="btn btn-sm btn-primary btn-next" onclick="Customer_Add.NextStep(this)">Next - Services</button>
            <button type="button" class="btn btn-sm btn-primary" onclick="Js_Top.closeNav()">X</button>
        </div>
    </fieldset>
    <fieldset class="fieldset_2">
        <div class="col-lg-6 col-xs-12 title-left-close-overlay">
            <div class="DivParent">
                <div class="DivWhichNeedToBeVerticallyAligned"> New Customer - Step 2 (Customer Information) </div>
                <div class="DivHelper"></div>
            </div>
        </div>
        <div class="col-lg-6 col-xs-12 title-right-close-overlay">
            <button type="button" class="btn btn-sm btn-primary btn-previous" onclick="Customer_Add.PrevStep(this)">Previous</button>
            <button type="button" class="btn btn-sm btn-primary btn-skip" onclick="Customer_Add.SkipStep(this)">Skip Service</button>
            <button type="button" class="btn btn-sm btn-primary btn-next" onclick="Customer_Add.NextStep(this)">Next</button>
            <button type="button" class="btn btn-sm btn-primary" onclick="Js_Top.closeNav()">X</button>
        </div>
    </fieldset>
    <fieldset class="fieldset_3">
        <div class="col-lg-6 col-xs-12 title-left-close-overlay">
            <div class="DivParent">
                <div class="DivWhichNeedToBeVerticallyAligned"> New Customer - Step 3 (Customer Information) </div>
                <div class="DivHelper"></div>
            </div>
        </div>
        <div class="col-lg-6 col-xs-12 title-right-close-overlay">
            <button type="button" class="btn btn-sm btn-primary btn-previous" onclick="Customer_Add.PrevStep(this)">Previous</button>
            <button type="button" class="btn btn-sm btn-primary" onclick="Customer_Add.SaveCustomers()">Save</button>
        </div>
    </fieldset>
</div>
<div id="overlay-content" class="overlay-content">
    <form class="add_new_customer">
        <input type="hidden" name="skip_service" value="0" />
        <!-- Start Field Set 1 -->
        <fieldset class="fieldset_1">
            <div class="step-1">
                <div class="space"></div>
                <div class="oflow">
                    <span><strong>Customer Information</strong></span>
                    <div class="clearfix"></div>
                    <table width="100%">
                        <tr>
                                <td>
                                 <div class="col-md-7 padding_zero">
                                    <input name="customer_name" type="text" class="form-control" placeholder="Customer Name" style="margin-top: 5px;">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="col-md-7 padding_zero">
                                    <input name="customer_no" onchange="Js_Top.CheckExistsCustomerNumber(this)"  type="text" class="form-control customer_number NoSpace" placeholder="Customer No." style="margin-top: 5px;">
                                    <input type="hidden" class="CheckExistsCustomerNumber" value="0" />
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class="clearfix"></div>
                    <div class="col-lg-7 col-md-6 col-sm-6 col-xs-12 left padding_zero" style="padding-top: 5px;">
                        <div class="form-group">
                            <label class="checkbox-inline" style="padding-left: 0">
                                <div class="custom-checkbox">
                                    <input name="auto_customer_no" onchange="Js_Top.ChangeCustomerNo(this)" type="checkbox" id="auto-asssign" value="yes">
                                    <label for="auto-asssign"></label>
                                </div>
                                Auto-assign customer number
                            </label>
                            <div style="padding-top: 5px;">
                                <label class="radio-inline">
                                    <div class="custom-checkbox-radio">
                                        <input name="customer_business_type" value="Individual" type="radio" checked="checked" value="">
                                    </div>
                                    &nbsp;
                                    Individual
                                </label>
                                <label class="radio-inline">
                                    <div class="custom-checkbox-radio">
                                        <input name="customer_business_type" value="Organization" type="radio" value="">
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
                                    <input name="customer_type" type="text" class="form-control">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                    <div class="oflow">
                        <div class="col-lg-7 padding_zero">
                            <div class="col-lg-12 left title_billing_serivce" style="padding-left: 0;"><strong>Billing Address</strong></div>
                            <div class="form-group">
                                <div class="col-lg-2 col-md-2 right_customers padding_zero_left"><span>Billing name</span><br><br><br>Addresh</div>
                                <div class="col-lg-10 col-md-10 padding_zero">                                
                                    <input onchange="Customer_Add.OnChangeBilling()" name="billing_name" type="text" class="form-control" placeholder="Billing Name - Leave blank if same as customer name">
                                    <input onchange="Customer_Add.OnChangeBilling()" name="billing_attention" type="text" class="form-control" placeholder="Attn"  style="margin-top: 5px;">
                                    <input onchange="Customer_Add.OnChangeBilling()" name="billing_address_1" type="text" class="form-control" placeholder="Address 1"  style="margin-top: 5px;">
                                    <input onchange="Customer_Add.OnChangeBilling()" name="billing_address_2" type="text" class="form-control" placeholder="Address 2"  style="margin-top: 5px;">
                                    <div class="form-group" style="margin-top: 5px;">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="city_billing_customers">
                                                    <input onchange="Customer_Add.OnChangeBilling()" name="billing_city" type="text" class="form-control" placeholder="City"> 
                                                </div>
                                                <div class="space_customers">&nbsp;</div>
                                                <div class="state_billing_customers">
                                                    <select onchange="Customer_Add.OnChangeBilling()" name="billing_state" class="form-control">
                                                        <?php  if(!empty($this->state)){foreach ($this->state as $key => $value) { ?>
                                                        <option value="<?php echo $value['state_code']; ?>"><?php echo $value['state_code']; ?></option>
                                                        <?php }} ?>
                                                    </select>
                                                </div>
                                                <div class="space_customers_zip">&nbsp;</div>
                                                <div class="zip_billing_customers">
                                                    <input onchange="Customer_Add.OnChangeBilling()" name="billing_zip" type="text" class="form-control" placeholder="Zip"> 
                                                </div>
                                                <div class="space_customers">&nbsp;</div>
                                                <div class="county_billing_customers">
                                                    <input onchange="Customer_Add.OnChangeBilling()" name="billing_county" type="text" class="form-control" placeholder="County"> 
                                                </div>      
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="phone col-lg-2 col-md-2 right_customers padding_zero_left"> 
                                    <input type="hidden" name="billing_index_primary" class="billing_index_primary" value="0"/>
                                    <span>Phone</span> 
                                </div>
                                <div class="col-lg-10 col-md-10 padding_zero">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-12 oflow">
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
                                                                <div class="custom-checkbox-radio" .style="display: inline-block;">
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
                            <div class="form-group">
                                <div class="col-lg-2  col-md-2 right_customers padding_zero_left"> <span>Email</span> </div>
                                <div class="col-lg-10 col-md-10 padding_zero">
                                    <input onchange="Customer_Add.OnChangeBilling()" name="billing_email" type="text" class="form-control"> </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-2  col-md-2 right_customers padding_zero_left"> <span>Contact Preferences</span> </div>
                                    <div class="col-lg-10 col-md-10 padding_zero" style="margin-top: 5px;">
                                        <div class="custom-checkbox">
                                            <input onchange="Customer_Add.OnChangeBilling()" name="billing_invoice_email" type="checkbox" checked="checked" id="billing_invoice_email" value="yes"> 
                                            <label for="billing_invoice_email"></label>
                                        </div>
                                        Automatically send invoice e-mails
                                        <br>
                                        <div class="custom-checkbox">
                                            <input onchange="Customer_Add.OnChangeBilling()" name="billing_work_order_email" type="checkbox" id="billing_orders_email" value="yes"> 
                                            <label for="billing_orders_email"></label>
                                        </div>
                                        Automatically send work order e-mails
                                    </div>
                                </div>
                                <div class=" form-group">
                                    <div class="col-lg-2 col-md-2 right_customers padding_zero_left"> <span>Website</span> </div>
                                    <div class="col-lg-10 col-md-10 padding_zero">
                                        <input onchange="Customer_Add.OnChangeBilling()" name="billing_websites" type="text" class="form-control" style="margin-top: 5px;"> </div>
                                    </div>
                                    <div class=" form-group">
                                        <div class="col-lg-2 col-md-2 right_customers padding_zero_left"> <span>Notes</span> </div>
                                        <div class="col-lg-10 col-md-10 padding_zero">
                                            <textarea onchange="Customer_Add.OnChangeBilling()" name="billing_notes" class="form-control" rows="5" style="margin-top: 5px;"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="oflow">
                                <div class="space"></div>
                                <div class="space"></div>
                                <div><strong>Addititional Contacts</strong></div>
                                <div id="wrap_add_contacts" class="oflow">

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
                        </div>
                    </fieldset>
                    <!-- End Fieldset 1 -->

                    <!-- Start FieldSet 2 -->
                    <fieldset class="fieldset_2" style="display:none">
                        <div style="margin-top: 5px;">
                            <ul class="nav nav-tabs tab_service_customer" role="tablist">
                                <li>
                                    <button onclick="Customer_Add.AddService()" type="button" class="btn btn-sm btn-primary" style="margin-left: 5px;margin-top: 2px;">Add another service</button>
                                </li>
                            </ul>
                            <div class="tab-content tab_content_service"></div>
                        </div>
                    </fieldset>
                    <!-- End FieldSet 2 -->
                    
                    <fieldset class="fieldset_3 template_finish" style="display:none">

                    </fieldset>
                </form>
            </div>
            <script>
                $(document).ready(function() {
                    Customer_Add.init();
                    $(".tab_service_customer").on("click", "a", function(e) {
                        e.preventDefault();
                        if (!$(this).hasClass('add-service')) {
                            $(this).tab('show');
                        }
                    })
                });
            </script>


