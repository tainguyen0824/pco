<div class="clearfix"></div>
<div class="col-lg-12" style="padding: 5px;background-color: #cfd1d2;overflow: hidden;padding-bottom: 10px;">
    <div class="tabs_footer">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left: 0px;padding-right: 0px;">
            <ul class="nav nav-tabs title-sub-tabcontent-customers responsive" role="tablist" style="padding-left: 0;">
                <?php if($type == 'edit_calendar'): ?>
                    <li role="presentation" class="active"><a  href="#Event_Calendar_Edit_<?php echo $index_service; ?>" aria-controls="Event_Calendar_Edit_<?php echo $index_service; ?>" role="tab" data-toggle="tab">Event</a></li>
                <?php endif; ?>
                <li role="presentation" <?php if($type != 'edit_calendar'): ?> class="active" <?php endif; ?>><a  href="#lineitems_customers_<?php echo $index_service; ?>" aria-controls="lineitems_customers_<?php echo $index_service; ?>" role="tab" data-toggle="tab">Billing / Line Items</a></li>
                <?php if($type != 'edit_calendar'): ?>
                    <li  role="presentation"><a  href="#scheduling_customers_<?php echo $index_service; ?>" aria-controls="scheduling_customers_<?php echo $index_service; ?>" role="tab" data-toggle="tab" onclick="Js_Top.LoadDataScheduling(this,<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)">Scheduling</a></li>
                <?php endif; ?>
                <li role="presentation"><a  href="#commissions_customers_<?php echo $index_service; ?>" aria-controls="profile" role="tab" data-toggle="tab" onclick="Js_Top.LoadDataCommissions(this)">Commissions</a></li>
                <li role="presentation"><a  href="#notes_customers_<?php echo $index_service; ?>" aria-controls="profile" role="tab" data-toggle="tab">Notes</a></li>
                <li role="presentation"><a  href="#attachments_customers_<?php echo $index_service; ?>" aria-controls="profile" role="tab" data-toggle="tab">Attachments</a></li>
            </ul>
            <div class="tab-content content-sub-tabcontent-customers responsive">
                <!-- line items -->
                    <div role="tabpanel" class="tab-pane <?php if($type != 'edit_calendar'): ?>active<?php endif; ?>" id="lineitems_customers_<?php echo $index_service; ?>">
                        <div class="oflow">
                            <div class="billing_content col-lg-12" style="background: #f1f1f1;padding: 15px;padding-top: 0px;margin-bottom: 10px;">
                                <h3 style="font-size: 18px;text-align: left;"><strong>Billing</strong></h3>
                                <div class="custom-checkbox-radio" style="margin-top: 5px;">
                                    <input onchange="Js_Top.Billing_generate_invoice(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>,1)" value="1" <?php if(!empty($Service[0]['chk_service_billing_frequency']) && $Service[0]['chk_service_billing_frequency'] == 1): echo 'checked="checked"'; elseif(empty($Service[0]['chk_service_billing_frequency'])): echo 'checked="checked"'; endif; ?> name="billing_generate_invoice_<?php echo $index_service; ?>" type="radio" style="margin-left:0"> 
                                    <span style="position: relative;bottom: 5px;">
                                        Manual: Generate invoice only after scheduled event is marked as completed
                                    </span>
                                </div>
                                <div class="custom-checkbox-radio" style="margin-top: 5px;">
                                    <input onchange="Js_Top.Billing_generate_invoice(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>,2)" value="2" <?php if(!empty($Service[0]['chk_service_billing_frequency']) && $Service[0]['chk_service_billing_frequency'] == 2): echo 'checked="checked"'; endif; ?> name="billing_generate_invoice_<?php echo $index_service; ?>" type="radio" style="margin-left:0"> 
                                    <span style="position: relative;bottom: 5px;">
                                        Automatic: Select options
                                        <div style="margin-left: 25px; font-size: 12px; font-weight: normal;display:none" class="wap_auto_billing_generate_invoice_<?php echo $index_service; ?>">
                                            <div class="col-lg-2 padding_zero_left">
                                                <span style="font-weight: bold;font-size: 14px;padding-top: 5px;display: inline-block;">Invoice Generation</span> 
                                            </div>
                                            <div class="col-lg-5 padding_zero_left">
                                                <select name="billing_frequency_<?php echo $index_service; ?>" class="form-control">
                                                    <option <?php if(!empty($Service[0]['service_billing_frequency']) && $Service[0]['service_billing_frequency'] == 'coincide'): echo 'selected="selected"'; endif; ?> value="coincide">Coincide with every scheduled event</option>
                                                    <option <?php if(!empty($Service[0]['service_billing_frequency']) && $Service[0]['service_billing_frequency'] == 'monthly'): echo 'selected="selected"'; endif; ?> value="monthly">Monthly</option>
                                                    <option <?php if(!empty($Service[0]['service_billing_frequency']) && $Service[0]['service_billing_frequency'] == 'quarterly'): echo 'selected="selected"'; endif; ?> value="quarterly">Quarterly</option>
                                                    <option <?php if(!empty($Service[0]['service_billing_frequency']) && $Service[0]['service_billing_frequency'] == 'yearly'): echo 'selected="selected"'; endif; ?> value="yearly">Yearly</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-7 padding_zero_left" style="font-weight: bold;font-size: 11px;color: #000;">
                                                Note: Automatic billing will generate invoices at a set schedule or frequency regardless of whether the scheduled service has been completed or not. Please check the legal requirements in your area before selecting this option.
                                            </div>       
                                        </div>
                                    </span>
                                </div> 
                            </div>
                            <?php if($type == 'Edit_customers'): ?>
                                <div class="billing_content col-lg-12" style="background: #f1f1f1;padding: 15px;padding-top: 0px;margin-bottom: 10px;">
                                    <h3 style="font-size: 18px;text-align: left;"><strong>Billing History</strong></h3>

                                        <table  class="display table" id="tbl_billing_history" cellspacing="0" cellpadding="0" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="width:15%">Billed Date</th>
                                                    <th style="width:15%">Total Amount</th>
                                                    <th style="width:15%">Change +/-</th>
                                                    <th style="width:55%">Notes (Auto-generated)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr role="row" class="odd">
                                                    <td style="width:15%" class=" TdBiilingdate">11/15/2016</td>
                                                    <td style="width:15%" class=" TdTotalAmount">$99.34</td>
                                                    <td style="width:15%" class=" TdChange">+24</td>
                                                    <td style="width:55%" class=" TdNotes">sg sg sg sgsgf gsd</td>
                                                </tr>
                                                <tr role="row" class="odd">
                                                    <td style="width:15%" class=" TdBiilingdate">10/15/2016</td>
                                                    <td style="width:15%" class=" TdTotalAmount">$99.34</td>
                                                    <td style="width:15%" class=" TdChange">+24</td>
                                                    <td style="width:55%" class=" TdNotes">sg sg sg sgsgf gsd sg sg sg sgsgf gsdsg sg sg sgsgf gsd sg sg sg sgsgf gsd sg sg sg sgsgf gsd sg sg sg sgsgf gsd sg sg sg sgsgf gsd sg sg sg sgsgf gsd</td>
                                                </tr>
                                                <tr role="row" class="odd">
                                                    <td style="width:15%" class=" TdBiilingdate">09/15/2016</td>
                                                    <td style="width:15%" class=" TdTotalAmount">$99.34</td>
                                                    <td style="width:15%" class=" TdChange">+24</td>
                                                    <td style="width:55%" class=" TdNotes">sg sg sg sgsgf gsd</td>
                                                </tr>
                                                <tr role="row" class="odd">
                                                    <td style="width:15%" class=" TdBiilingdate">11/15/2016</td>
                                                    <td style="width:15%" class=" TdTotalAmount">$99.34</td>
                                                    <td style="width:15%" class=" TdChange">+24</td>
                                                    <td style="width:55%" class=" TdNotes">sg sg sg sgsgf gsd</td>
                                                </tr>
                                                <tr role="row" class="odd">
                                                    <td style="width:15%" class=" TdBiilingdate">11/15/2016</td>
                                                    <td style="width:15%" class=" TdTotalAmount">$99.34</td>
                                                    <td style="width:15%" class=" TdChange">+24</td>
                                                    <td style="width:55%" class=" TdNotes">sg sg sg sgsgf gsd</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    
                                </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="col-lg-12" style="background: #f1f1f1;">
                                <h3 style="font-size: 18px;text-align: left;margin-bottom: 0;" class="line-item"><strong>Line Items</strong></h3>
                                <div style="padding-top: 10px;border:1px dotted red;overflow: hidden;">
                                    <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 padding_zero_left">
                                        <div class="col-lg-6 padding_zero_left">
                                            <select class="form-control">
                                                <option>Select line item teamplate</option>
                                                <option>custom1 </option>
                                                <option>annuals</option>
                                                <option>monthlie</option>
                                                <option>5</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-6" style="padding: 0px">
                                            <div style="padding-top: 0.5em;padding-bottom: 0.5em;text-align: left;">
                                                <div class="custom-checkbox">
                                                    <input type="checkbox" id="chk_template_billing">
                                                    <label for="chk_template_billing"></label>
                                                </div>
                                                <span>Set as default template for line items</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 padding_zero_right">
                                        <div class="save_line_item_customers">
                                            <button type="button" class="btn btn-sm btn-primary">Save line items as a template</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Type</th>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Amount</th>
                                                <th>
                                                    <div class="custom-checkbox">
                                                        <input onchange="Js_Top.CheckAll_Taxable(this)" <?php if(!empty($Service[0]['chk_all_taxable']) && $Service[0]['chk_all_taxable'] == 1): ?> checked="checked" <?php endif; ?> class="Chk_All_Taxable" name="chk_all_taxable_<?php echo $index_service; ?>" type="checkbox" id="Chk_All_Taxable_<?php echo $index_service; ?>"/>
                                                        <label for="Chk_All_Taxable_<?php echo $index_service; ?>"></label>
                                                    </div>
                                                    Taxable
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $Total_Billing   = 0; 
                                                $Result_Discount = 0;
                                                $Result_Taxable  = 0;
                                                $Result_Total    = 0;
                                            ?>
                                            <?php if(!empty($Service_Item)): ?>
                                                <?php foreach ($Service_Item as $keyItem => $Item): ?>
                                                    <?php 
                                                        $Amount = $Item['quantity'] * $Item['unit_price'];
                                                        $Total_Billing += $Amount; 
                                                        if(!empty($Item['chk_taxable']) && $Item['chk_taxable'] == 1):
                                                            $service_billing_tax = !empty($Service[0]['service_billing_tax'])?$Service[0]['service_billing_tax']:0;
                                                            $Result_Taxable += ($Amount * $service_billing_tax) / 100;
                                                        endif;
                                                    ?>
                                                    <tr style="background-color:#f1f1f1">
                                                        <td style="text-align: left;padding-left: 0;">
                                                            <button onclick="Js_Top.Remove_Billing(this)" type="button" type="button" class="btn btn-danger btn-sm"> <span class="glyphicon glyphicon-remove"></span></button> 
                                                        </td>
                                                        <td style="width: 20%">
                                                            <select name="lineitems_type_<?php echo $index_service; ?>[]" class="form-control" style="min-width: 130px">
                                                                <?php if(!empty($this->TypeBilling)): ?>
                                                                    <?php foreach ($this->TypeBilling as $key => $value): ?>
                                                                        <option <?php if(!empty($Item['type']) && $Item['type'] == $key): echo 'selected'; endif; ?> value="<?php echo $key ?>"><?php echo $value; ?></option>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input name="lineitems_description_<?php echo $index_service; ?>[]" type="text" class="form-control" value="<?php echo !empty($Item['description'])?$Item['description']:''; ?>">
                                                        </td>
                                                        <td>
                                                            <input onkeyup="Js_Top.Change_Quantity_Billing(this)" name="lineitems_quantity_<?php echo $index_service; ?>[]" type="text" class="form-control OnlyNumber" value="<?=!empty($Item['quantity'])?$Item['quantity']:0 ?>">
                                                        </td>
                                                        <td>
                                                            <input onkeyup="Js_Top.Change_Unit_Price_Billing(this)" name="lineitems_unit_price_<?php echo $index_service; ?>[]" type="text" class="form-control val_lineitems_unit_price_<?php echo $index_service; ?> moneyUSD" value="<?php echo !empty($Item['unit_price'])?number_format($Item['unit_price'],2):0 ?>">
                                                        </td>
                                                        <td style="vertical-align: middle;" class="txt_lineitems_amount_<?php echo $index_service; ?>">
                                                            <span class="B_amount"><?php echo isset($Amount)?number_format($Amount,2):number_format(0,2); ?></span>
                                                        </td>
                                                        <td style="vertical-align: middle;">
                                                            <div class="custom-checkbox">
                                                                <input onchange="Js_Top.Change_Taxable_Billing(this)" <?php if(!empty($Item['chk_taxable']) && $Item['chk_taxable'] == 1): echo "checked"; endif; ?> name="checkbox_billing_taxable_<?php echo $index_service; ?>[]" class="chk_taxable" id="taxable_<?php echo !empty($Item['id'])?$Item['id']:$keyItem ?>" type="checkbox">
                                                                <label for="taxable_<?php echo !empty($Item['id'])?$Item['id']:$keyItem ?>"></label>
                                                            </div>
                                                            <input value="<?php if(!empty($Item['chk_taxable']) && $Item['chk_taxable'] == 1): echo $Item['chk_taxable']; else: echo 0; endif; ?>" name="val_lineitems_checkbox_tax_<?php echo $index_service; ?>[]" type="hidden" class="val_checkbox_tax form-control">
                                                            <input value="<?php echo !empty($Item['id'])?$Item['id']:''; ?>" name="id_billing_<?php echo $index_service; ?>[]" type="text" class="form-control">
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr style="background-color:#f1f1f1">
                                                    <td style="text-align: left;padding-left: 0;">
                                                        <button onclick="Js_Top.Remove_Billing(this)" type="button" type="button" class="btn btn-danger btn-sm"> <span class="glyphicon glyphicon-remove"></span></button> 
                                                    </td>
                                                    <td style="width: 20%">
                                                        <select name="lineitems_type_<?php echo $index_service; ?>[]" class="form-control" style="min-width: 130px">
                                                            <?php if(!empty($this->TypeBilling)): ?>
                                                                <?php foreach ($this->TypeBilling as $key => $value): ?>
                                                                    <option value="<?php echo $key ?>"><?php echo $value; ?></option>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input name="lineitems_description_<?php echo $index_service; ?>[]" type="text" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input onkeyup="Js_Top.Change_Quantity_Billing(this)" name="lineitems_quantity_<?php echo $index_service; ?>[]" type="text" class="form-control OnlyNumber" value="1">
                                                    </td>
                                                    <td>
                                                        <input onkeyup="Js_Top.Change_Unit_Price_Billing(this)" name="lineitems_unit_price_<?php echo $index_service; ?>[]" type="text" class="form-control val_lineitems_unit_price_<?php echo $index_service; ?> moneyUSD">
                                                    </td>
                                                    <td style="vertical-align: middle;" class="txt_lineitems_amount_<?php echo $index_service; ?>">
                                                        <span class="B_amount">0.00</span>
                                                    </td>
                                                    <td style="vertical-align: middle;">
                                                        <div class="custom-checkbox">
                                                            <input onchange="Js_Top.Change_Taxable_Billing(this)" name="checkbox_billing_taxable_<?php echo $index_service; ?>[]" class="chk_taxable" id="<?php $time = time(); echo $time; ?>" type="checkbox">
                                                            <label for="<?php echo $time; ?>"></label>
                                                        </div>
                                                        <input value="0" name="val_lineitems_checkbox_tax_<?php echo $index_service; ?>[]" type="hidden" class="val_checkbox_tax form-control">
                                                        <input name="id_billing_<?php echo $index_service; ?>[]" type="hidden" class="form-control">
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                            
                                            <tr style="background-color:#f1f1f1">
                                                <td style="text-align: left;padding-left: 0;">
                                                    <button onclick="Js_Top.Add_Billing(this,<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" type="button" class="btn btn-primary btn-sm"> <span class="glyphicon glyphicon-plus"></span></button>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td style="text-align:right;font-size: 1.2em;font-weight: bold;vertical-align: middle;">
                                                    Add discount:
                                                </td>
                                                <td>
                                                    <div class="inner-addon right-addon">
                                                        <i class="fa fa-percent" aria-hidden="true"></i>
                                                        <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
                                                        <input onkeyup="Js_Top.Change_Discount_Billing(this)"  name="lineitems_discount_<?php echo $index_service; ?>[]" type="text" class="val_discount form-control OnlyNumberDot MinmaxPercent" value="<?php echo !empty($Service[0]['service_billing_discount'])?$Service[0]['service_billing_discount']:0; ?>">
                                                    </div> 
                                                </td>
                                                <?php  
                                                    // Discount
                                                    if(!empty($Service[0]['service_billing_discount'])):
                                                        $Result_Discount = ($Total_Billing * $Service[0]['service_billing_discount']) / 100;
                                                    endif;
                                                ?>
                                                <td style="vertical-align: middle;" class="txt_billing_discount"><?php echo number_format(round($Result_Discount,2),2); ?></td>
                                                <td></td>
                                            </tr>
                                            <tr style="background-color:#f1f1f1">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <select onchange="Js_Top.Change_Code_Taxable_Billing(this)" name="slt_lineitems_state_tax_<?php echo $index_service; ?>[]" class="form-control">
                                                        <option value="None|0.00">None</option>
                                                        <?php  if(!empty($this->state)){foreach ($this->state as $key => $value) { ?>
                                                            <option <?php if(empty($Service) && !empty($Options[0]['slt_default_tax']) && $Options[0]['slt_default_tax'] == $value['state_code'].'|'.$value['_state_tax']): echo 'selected="selected"'; endif; ?> <?php if(!empty($Service[0]['service_billing_slt_tax']) && $Service[0]['service_billing_slt_tax'] == $value['state_code'].'|'.$value['_state_tax']): echo "selected"; endif; ?> value="<?php echo $value['state_code'] ?>|<?php echo $value['_state_tax'] ?>"><?php echo $value['state_code']; ?> - <?php echo $value['_state_tax'].'%' ?></option>
                                                        <?php }} ?>
                                                    </select>
                                                    <div class="space"></div>
                                                    <div style="text-align: left;">
                                                        <div class="custom-checkbox">
                                                            <input name="Set_default_tax_billing_<?php echo $index_service; ?>" type="checkbox" id="SetDefaultTaxable_<?php echo $index_service; ?>" value="1">
                                                            <label for="SetDefaultTaxable_<?php echo $index_service; ?>"></label>
                                                        </div>
                                                        <span>Set as default</span>
                                                    </div>
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <div class="inner-addon right-addon">
                                                        <i class="fa fa-percent" aria-hidden="true"></i>
                                                        <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
                                                        <input onkeyup="Js_Top.Change_Value_Taxable_Billing(this,<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" value="<?php echo !empty($Service)?$Service[0]['service_billing_tax']:(!empty($Options[0]['val_default_tax'])?$Options[0]['val_default_tax']:0); ?>" name="lineitems_taxable_<?php echo $index_service; ?>[]" class="val_tax form-control OnlyNumberDot" type="text">
                                                    </div>                                                    
                                                </td>
                                                <td style="vertical-align: top;" class="txt_billing_taxable"><?php echo number_format(round($Result_Taxable,2),2); ?></td>
                                                <td></td>
                                            </tr>
                                            <tr style="background-color:#f1f1f1">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td style="text-align: right;font-size: 1.2em;font-weight: bold;">
                                                    Total
                                                </td>
                                                <?php  
                                                    // Total
                                                    $Result_Total = $Total_Billing - $Result_Discount + $Result_Taxable;
                                                ?>
                                                <td class="txt_billing_total"><?php echo number_format(round($Result_Total,2),2); ?></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- end line items -->

                <!-- only Edit Calendar -->
                    <?php if($type == 'edit_calendar'): ?>
                        <div role="tabpanel" class="tab-pane Content_Event_Calendar_Edit <?php if($type == 'edit_calendar'): ?>active<?php endif; ?>" id="Event_Calendar_Edit_<?php echo $index_service; ?>">
                            <div style="overflow: hidden;background-color: #fbffc3;padding-top: 10px;padding-bottom: 10px;margin-bottom: 10px;margin-top: 10px;">
                                <div class="col-lg-10 col-md-10">
                                    <strong>Note:</strong> Changing event details only affects this particular event. To change service schedule details for recurring events, go to 
                                    Scheduling tab in customer's service details.
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    <button type="button" class="btn btn-sm btn-primary" style="top: 6px;position: relative;">Go to Service Details</button>
                                </div>
                            </div>
                            
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group" style="overflow: hidden;margin-bottom: 5px;">
                                    <div class="col-lg-3 col-md-3 padding_zero_left"> <span>Start Time</span></div>
                                    <div class="col-lg-9 col-md-9 padding_zero">
                                        <div style="width: 49%;float: left;">
                                            <input name="txt_Event_time_start" readonly="readonly" type="text" class="form-control Event_Time_Calendar Event_val_time_start" placeholder="Start Time" value="<?php echo !empty($STime_Calendar)?$STime_Calendar:''; ?>">
                                        </div>
                                        <div style="width: 2%;"></div>
                                        <div style="width: 49%;float: right;">
                                            <div class="input-group date">
                                                <input name="txt_Event_date_start" readonly="readonly" type="text" class="form-control Event_Date_Start Event_val_date_start" value="<?php echo !empty($SDate_Calendar)?$SDate_Calendar:''; ?>">
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6" style="bottom: 5px">
                                <div>Set end time:</div>
                                <input type="hidden" class="Event_hours">
                                <input type="hidden" class="Event_minutes">
                                <button onclick="Calendar_Edit.Set_Time_End(15)" type="button" class="btn btn-xs btn-primary Event_plus_time_end">+00:15</button>
                                <button onclick="Calendar_Edit.Set_Time_End(30)" type="button" class="btn btn-xs btn-primary Event_plus_time_end">+00:30</button>
                                <button onclick="Calendar_Edit.Set_Time_End(60)" type="button" class="btn btn-xs btn-primary Event_plus_time_end">+01:00</button>
                            </div>

                            <div class="col-lg-6 col-md-6">
                                <div class="form-group" style="overflow: hidden;margin-bottom: 5px;">
                                    <div class="col-lg-3 col-md-3 padding_zero_left"> <span>End Time</span></div>
                                    <div class="col-lg-9 col-md-9 padding_zero">
                                        <div style="width: 49%;float: left;">
                                            <input name="txt_Event_time_end" readonly="readonly" type="text" class="form-control Event_Time_Calendar Event_val_time_end" placeholder="End Time" value="<?php echo !empty($ETime_Calendar)?$ETime_Calendar:''; ?>">
                                        </div>
                                        <div style="width: 2%;"></div>
                                        <div style="width: 49%;float: right;">
                                            <div class="input-group date">
                                                <input name="txt_Event_date_end" readonly="readonly" type="text" class="form-control Event_Date_End Event_val_date_end" value="<?php echo !empty($EDate_Calendar)?$EDate_Calendar:''; ?>">
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="overflow: hidden;margin-bottom: 5px;">
                                    <div class="col-lg-3 col-md-3 padding_zero_left"> <span>Service Duration</span></div>
                                    <div class="col-lg-9 col-md-9 padding_zero Event_service_duration"></div>
                                </div>
                                <div class="form-group" style="overflow: hidden;margin-bottom: 5px;">
                                    <div class="col-lg-3 col-md-3 padding_zero_left"> <span>Technician</span></div>
                                    <div class="col-lg-9 col-md-9 padding_zero">
                                        <select name="txt_Event_technician" class="form-control">
                                            <option value="0"><?php echo $this->Technician_No_Name; ?></option>
                                            <?php if(!empty($customers_technician)): ?>
                                                <?php foreach ($customers_technician as $key => $value): ?>
                                                    <option <?php if(!empty($_technician_id) && $_technician_id == $value['id']): echo 'selected'; endif; ?> value="<?php echo $value['id']; ?>"><?php echo !empty($value['name'])?$value['name']:''; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" style="overflow: hidden;margin-bottom: 5px;">
                                    <div class="col-lg-3 col-md-3 padding_zero_left"> <span>Event Status</span></div>
                                    <div class="col-lg-9 col-md-9 padding_zero">
                                        <div class="custom-checkbox">
                                            <input <?php echo (!empty($GetEvents[0]['events_chk_complete']) && $GetEvents[0]['events_chk_complete'] == 1)?'checked':''; ?> name="chk_complete_event_calendar" onchange="Calendar_Edit.Chk_Event_Status(this)" type="checkbox" class="form-control" id="complete_event_calendar">
                                            <label for="complete_event_calendar"></label>
                                        </div>
                                        <span style="position: relative;">Completed</span>
                                        <p style="font-size: 10px;color: #000;">
                                            <b>NOTE:</b> This event is configured from its associated service group to automatically be marked 'complete' once the time passes the event's duration.
                                        </p>
                                        <div id="Wap_Event_Complete" style="display:none">

                                            <!-- Events Pesticides -->
                                            <div class="form-group" style="overflow: hidden;margin-bottom: 5px;">
                                                <label>Pesticides Used</label>
                                                <div class="wap_Pesticide_">
                                                    <?php if(!empty($Service_Pesticides)): ?>
                                                        <?php foreach ($Service_Pesticides as $key => $value): ?>
                                                            <div>
                                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 padding_zero">
                                                                    <input name="pesticide_name_[]" placeholder="Pesticide Name"  type='text' class="form-control autocomplete_pesticide" value="<?php echo !empty($value['pesticide_name'])?$value['pesticide_name']:''; ?>"/>
                                                                </div>
                                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 padding_zero_right">
                                                                    <input name="pesticide_amount_[]" placeholder="Amount"  type='text' class="form-control moneyUSD" value="<?php echo !empty($value['pesticide_amount'])?number_format($value['pesticide_amount'],2):''; ?>"/>
                                                                </div>
                                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="top: 7px;">
                                                                    <span><?php echo !empty($value['pesticide_unit'])?$value['pesticide_unit']:''; ?></span>
                                                                    <input name="pesticide_unit_[]" type='hidden' class="form-control" value="<?php echo !empty($value['pesticide_unit'])?$value['pesticide_unit']:''; ?>"/>
                                                                </div>
                                                                <i onclick="Js_Top.Remove_pesticide('',this)" class="fa fa-minus-circle" style="position: absolute;color: red;cursor: pointer;right: 0px;" aria-hidden="true"></i>
                                                                <input name="id_pesticide_[]" type='hidden' class="form-control" value="<?php echo !empty($value['id'])?$value['id']:''; ?>"/>
                                                                <input name="id_pesticide_select_[]" type='hidden' class="form-control id_pesticide_select" value="<?php echo !empty($value['pesticide_id'])?$value['pesticide_id']:''; ?>"/>
                                                                <div class="space"  style="margin-top: 0px;"></div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <div>
                                                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 padding_zero">
                                                                <input name="pesticide_name_[]" placeholder="Pesticide Name"  type='text' class="form-control autocomplete_pesticide"/>
                                                            </div>
                                                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 padding_zero_right">
                                                                <input name="pesticide_amount_[]" placeholder="Amount"  type='text' class="form-control moneyUSD"/>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="top: 7px;">
                                                                <span></span>
                                                                <input name="pesticide_unit_[]" type='hidden' class="form-control"/>
                                                            </div>
                                                            <i onclick="Js_Top.Remove_pesticide('',this)" class="fa fa-minus-circle" style="position: absolute;color: red;cursor: pointer;right: 0px;" aria-hidden="true"></i>
                                                            <input name="id_pesticide_[]" type='hidden' class="form-control"/>
                                                            <input name="id_pesticide_select_[]" type='hidden' class="form-control id_pesticide_select"/>
                                                            <div class="space"  style="margin-top: 0px;"></div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="col-lg-12 padding_zero">
                                                    <button onclick="Js_Top.Add_pesticide('')" type="button" class="btn btn-primary btn-sm"> <span class="glyphicon glyphicon-plus"></span></button>
                                                    <div class="space"  style="margin-top: 0px;"></div>
                                                </div>
                                            </div>

                                            <!-- Events invoice -->
                                            <div class="form-group" style="overflow: hidden;margin-bottom: 5px;">
                                                <label for="email" style="margin-bottom: 0px;">Invoice Total (Credit): $<span class="Event_Invoice"><?php echo number_format(round($Result_Total,2),2); ?></span></label>
                                            </div>

                                            <!-- Events Debit -->
                                            <div class="form-group" style="overflow: hidden;margin-bottom: 5px;">
                                                <label>Payment (Debit)</label>
                                                <input name="txt_Event_payment" type="text" class="form-control moneyUSD" placeholder="Amount" value="<?php echo !empty($GetEvents[0]['events_debit'])?number_format($GetEvents[0]['events_debit'],2):number_format(0,2); ?>">
                                                <div class="space"></div>
                                                <select name="txt_Event_card" class="form-control">
                                                    <option <?php echo (!empty($GetEvents[0]['events_card']) && $GetEvents[0]['events_card'] == 'stripe')?'selected':''; ?> value="stripe">Credit card on file (Stripe)</option>
                                                    <option <?php echo (!empty($GetEvents[0]['events_card']) && $GetEvents[0]['events_card'] == 'input')?'selected':''; ?> value="input">Credit card (Manual input)</option>
                                                    <option <?php echo (!empty($GetEvents[0]['events_card']) && $GetEvents[0]['events_card'] == 'cash_check')?'selected':''; ?> value="cash_check">Cash / check</option>
                                                </select>
                                            </div>

                                            <!-- Event Notes -->
                                            <div class="form-group" style="overflow: hidden;margin-bottom: 5px;">
                                                <label for="email">Notes</label>
                                                <textarea name="txt_Event_notes" class="form-control" style="min-height: 90px;"><?php echo !empty($GetEvents[0]['events_notes'])?$GetEvents[0]['events_notes']:''; ?></textarea>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div> 

                        </div>
                    <?php endif; ?>
                <!-- End only Edit Calendar -->

                <!-- sheduling (Warning: if Edit calendar this no exists) -->
                    <?php if($type != 'edit_calendar'): ?>         
                        <div role="tabpanel" class="tab-pane content_scheduling_customers" id="scheduling_customers_<?php echo $index_service; ?>">
                            <input type="hidden" name="id_scheduling" value="<?php echo !empty($Service_Scheduling[0]['id'])?$Service_Scheduling[0]['id']:''; ?>"/>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="padding-left:0;border:1px dotted red;overflow: hidden;">
                                <div class="col-lg-5">
                                    <select class="form-control">
                                        <option value="">Template</option>
                                    </select>
                                </div>
                                <div class="col-lg-7">
                                    <div style="padding-top: 0.5em;padding-bottom: 0.5em;text-align: left;">
                                        <div class="custom-checkbox">
                                            <input type="checkbox"id="chk_template_scheduling">
                                            <label for="chk_template_scheduling"></label>
                                        </div>
                                        <span>Set as default template for line items</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="save_line_item_customers">
                                    <button type="button" class="btn btn-md btn-primary">Save line items as a template</button>
                                </div>
                            </div>
                            <div class="space"></div>
                            <div class="col-lg-6 col-md-6">

                                <div class="form-group">
                                    <div class="space"></div>
                                    <label class="col-lg-4 col-md-4 col-sm-4 control-label right">Technician</label>
                                    <div class="col-lg-8 col-md-8 col-sm-8 padding_boostrap">
                                        <select onchange="Js_Top.UpdateClickCancel(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" name="scheduling_technician_<?php echo $index_service; ?>" class="form-control">
                                            <option value="0"><?php echo $this->Technician_No_Name; ?></option>
                                            <?php if(!empty($customers_technician)): ?>
                                                <?php foreach ($customers_technician as $key => $value): ?>
                                                    <option <?php if(!empty($Service_Scheduling[0]['technician']) && $Service_Scheduling[0]['technician'] == $value['id']): echo 'selected'; endif; ?> value="<?php echo $value['id']; ?>"><?php echo !empty($value['name'])?$value['name']:''; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <p style="font-size: 10px;color: #000;font-weight: bold;">Optional. Technician can be assigned on an ad-hoc basis on the day of event.</p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="space"></div>
                                    <label class="col-sm-4 control-label right">First Service Date</label>
                                    <div class="col-sm-8 padding_boostrap">
                                        <div class="input-group date">
                                            <input <?php if($type != 'Edit_customers'): ?>style="background-color:#fff"<?php endif; ?> name="scheduling_first_date_<?php echo $index_service; ?>"  onchange="Js_Top.ShowCalendarPrevViews(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" readonly="readonly" type="text" class="form-control <?php if(empty($Service_Scheduling[0]['first_date'])): ?> datepicker <?php endif; ?> First_Date_Scheduling" value="<?php echo !empty($Service_Scheduling[0]['first_date'])?$Service_Scheduling[0]['first_date']:date('m/d/Y'); ?>">
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </div>
                                        </div>
                                        <div style="font-size: 0.7em;text-align: left;font-weight: bold;">For recurring services, ensure that a frequency is selected in addition to setting the first service date.</div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="space"></div>
                                    <label class="col-sm-4 control-label right">Frequency</label>
                                    <div class="col-sm-8 padding_boostrap">
                                        <select name="scheduling_frequency_<?php echo $index_service; ?>" id="ServiceFrequency_<?php echo $index_service; ?>"  onchange="Js_Top.SchedulingFrequency(this,<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>,'event_change');Js_Top.ShowCalendarPrevViews(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" class="form-control">
                                            <option <?php if(!empty($Service_Scheduling[0]['frequency']) && $Service_Scheduling[0]['frequency'] == 'onetime'): echo 'selected'; endif; ?> value="onetime">One-time</option>
                                            <option <?php if(!empty($Service_Scheduling[0]['frequency']) && $Service_Scheduling[0]['frequency'] == 'weekly'): echo 'selected'; endif; ?> value="weekly">Weekly</option>
                                            <option <?php if(!empty($Service_Scheduling[0]['frequency']) && $Service_Scheduling[0]['frequency'] == 'monthly'): echo 'selected'; endif; ?> value="monthly">Monthly</option>
                                            <option <?php if(!empty($Service_Scheduling[0]['frequency']) && $Service_Scheduling[0]['frequency'] == 'quarterly'): echo 'selected'; endif; ?> value="quarterly">Quarterly</option>
                                        </select>
                                        <div style="display:none" class="wrap_option_scheduling_<?php echo $index_service; ?>">
                                            <div class="custom-checkbox-radio" style="margin-top: 5px;">
                                                <input onchange="Js_Top.SchedulingSubFrequency(this,<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>);Js_Top.ShowCalendarPrevViews(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" value="slt_auto" name="option_scheduling_<?php echo $index_service; ?>" <?php if(!empty($Service_Scheduling[0]['option_scheduling']) && $Service_Scheduling[0]['option_scheduling'] == 'slt_auto'): echo 'checked'; endif; ?> type="radio" style="margin-left:0"> 
                                                <span style="position: relative;bottom: 5px;">
                                                    Auto-schedule
                                                    <div style="margin-left: 25px;font-size: 12px;font-weight: normal;display:none" class="custom-checkbox wap_auto_schedule_working_days_<?php echo $index_service; ?>">
                                                        <input onchange="Js_Top.ShowCalendarPrevViews(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" name="auto_schedule_working_days_<?php echo $index_service; ?>" <?php if(!empty($Service_Scheduling[0]['chk_auto_schedule_work']) && $Service_Scheduling[0]['chk_auto_schedule_work'] == 'checked_working'): echo 'checked'; endif; ?> value="checked_working" type="checkbox" id="auto_options_<?php echo $index_service; ?>"/>
                                                        <label style="margin-bottom: 0;" for="auto_options_<?php echo $index_service; ?>">Only schedule on working days</label>
                                                    </div>
                                                </span>
                                            </div>
                                            <div class="custom-checkbox-radio" style="margin-top: 5px;">
                                                <input onchange="Js_Top.SchedulingSubFrequency(this,<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>);Js_Top.ShowCalendarPrevViews(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" value="slt_push" name="option_scheduling_<?php echo $index_service; ?>" <?php if(!empty($Service_Scheduling[0]['option_scheduling']) && $Service_Scheduling[0]['option_scheduling'] == 'slt_push'): echo 'checked'; endif; ?> type="radio" style="margin-left:0"> 
                                                <span style="position: relative;bottom: 5px;">
                                                    Push into work pool
                                                    <p style="font-size: 10px;color: #000;margin-left: 25px;font-weight: bold;">
                                                        Check this option to have the system generate an unscheduled ticket in "Work Pool" during the frequency window.
                                                    </p>
                                                </span>
                                            </div>
                                            <div class="custom-checkbox-radio" style="margin-top: 5px;">
                                                <input onchange="Js_Top.SchedulingSubFrequency(this,<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>);Js_Top.ShowCalendarPrevViews(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" value="slt_week" name="option_scheduling_<?php echo $index_service; ?>" <?php if(!empty($Service_Scheduling[0]['option_scheduling']) && $Service_Scheduling[0]['option_scheduling'] == 'slt_week'): echo 'checked'; endif; ?> type="radio" style="margin-left:0"> 
                                                <span style="position: relative;bottom: 5px;">Select days of the week</span>
                                            </div>
                                            <div class="slt_week_scheduling_<?php echo $index_service; ?>">
                                                <div class="col-lg-6 option_frequency_1st_<?php echo $index_service; ?>">
                                                    <select onchange="Js_Top.ShowCalendarPrevViews(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" name="Sltdayoftheweek1_<?php echo $index_service; ?>" id="Sltdayoftheweek1_<?php echo $index_service; ?>" class="form-control">
                                                        <option <?php if(!empty($Service_Scheduling[0]['frequency_slt_week_1']) && $Service_Scheduling[0]['frequency_slt_week_1'] == 'first'): echo 'selected'; endif; ?> value="first">1st</option>
                                                        <option <?php if(!empty($Service_Scheduling[0]['frequency_slt_week_1']) && $Service_Scheduling[0]['frequency_slt_week_1'] == 'second'): echo 'selected'; endif; ?> value="second">2nd</option>
                                                        <option <?php if(!empty($Service_Scheduling[0]['frequency_slt_week_1']) && $Service_Scheduling[0]['frequency_slt_week_1'] == 'third'): echo 'selected'; endif; ?> value="third">3rd</option>
                                                        <option <?php if(!empty($Service_Scheduling[0]['frequency_slt_week_1']) && $Service_Scheduling[0]['frequency_slt_week_1'] == 'fourth'): echo 'selected'; endif; ?> value="fourth">4th</option>
                                                        <option <?php if(!empty($Service_Scheduling[0]['frequency_slt_week_1']) && $Service_Scheduling[0]['frequency_slt_week_1'] == 'Last'): echo 'selected'; endif; ?> value="Last">Last</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-6">
                                                    <select onchange="Js_Top.ShowCalendarPrevViews(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" name="Sltdayoftheweek2_<?php echo $index_service; ?>" id="Sltdayoftheweek2_<?php echo $index_service; ?>" class="form-control">
                                                        <option <?php if(!empty($Service_Scheduling[0]['frequency_slt_week_2']) && $Service_Scheduling[0]['frequency_slt_week_2'] == 'Monday'): echo 'selected'; endif; ?> value="Monday">Mondays</option>
                                                        <option <?php if(!empty($Service_Scheduling[0]['frequency_slt_week_2']) && $Service_Scheduling[0]['frequency_slt_week_2'] == 'Tuesday'): echo 'selected'; endif; ?> value="Tuesday">Tuesdays</option>
                                                        <option <?php if(!empty($Service_Scheduling[0]['frequency_slt_week_2']) && $Service_Scheduling[0]['frequency_slt_week_2'] == 'Wednesday'): echo 'selected'; endif; ?> value="Wednesday">Wednesdays</option>
                                                        <option <?php if(!empty($Service_Scheduling[0]['frequency_slt_week_2']) && $Service_Scheduling[0]['frequency_slt_week_2'] == 'Thursday'): echo 'selected'; endif; ?> value="Thursday">Thursdays</option>
                                                        <option <?php if(!empty($Service_Scheduling[0]['frequency_slt_week_2']) && $Service_Scheduling[0]['frequency_slt_week_2'] == 'Friday'): echo 'selected'; endif; ?> value="Friday">Fridays</option>
                                                        <option <?php if(!empty($Service_Scheduling[0]['frequency_slt_week_2']) && $Service_Scheduling[0]['frequency_slt_week_2'] == 'Saturday'): echo 'selected'; endif; ?> value="Saturday">Saturdays</option>
                                                        <option <?php if(!empty($Service_Scheduling[0]['frequency_slt_week_2']) && $Service_Scheduling[0]['frequency_slt_week_2'] == 'Sunday'): echo 'selected'; endif; ?> value="Sunday">Sundays</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="wrap_slt_month_scheduling_<?php echo $index_service; ?>">
                                                <div class="custom-checkbox-radio" style="margin-top: 5px;">
                                            	    <input onchange="Js_Top.SchedulingSubFrequency(this,<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>);Js_Top.ShowCalendarPrevViews(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" value="slt_month" <?php if(!empty($Service_Scheduling[0]['option_scheduling']) && $Service_Scheduling[0]['option_scheduling'] == 'slt_month'): echo 'checked'; endif; ?> name="option_scheduling_<?php echo $index_service; ?>" type="radio" style="margin-left:0"> 
                                                    <span style="position: relative;bottom: 5px;">Specify nth day of the month</span>
                                                </div>
                                                <div style="display:none" class="slt_month_scheduling_<?php echo $index_service; ?>">
                                                   <div class="col-lg-12">
                                                        <input onkeyup="Js_Top.ShowCalendarPrevViews(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" name="Specify_ntn_day_of_the_month_<?php echo $index_service; ?>" id="Specify_ntn_day_of_the_month_<?php echo $index_service; ?>" type="text" style="width:50%;display: inline-block;" class="form-control OnlyNumber MinmaxNumberMonth" value="<?php echo !empty($Service_Scheduling[0]['frequency_slt_nth_1'])?$Service_Scheduling[0]['frequency_slt_nth_1']:01; ?>"> day of the month
                                                        <div class="clearfix"></div>
                                                        <div class="custom-checkbox">
                                                            <input onchange="Js_Top.ShowCalendarPrevViews(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" name="Only_schedule_on_woking_days_<?php echo $index_service; ?>" id="Only_schedule_on_woking_days_<?php echo $index_service; ?>" type="checkbox" <?php if(!empty($Service_Scheduling[0]['frequency_slt_nth_2']) && $Service_Scheduling[0]['frequency_slt_nth_2'] == 'checked_working'): echo 'checked'; endif; ?> value="checked_working">
                                                            <label for="Only_schedule_on_woking_days_<?php echo $index_service; ?>"></label>
                                                        </div>
                                                        Only schedule on working days
                                                   </div>
                                                </div>
                                           </div>
                                       </div>
                                   </div>
                                </div>

                                <div class="form-group">
                                    <div class="space" style="margin-top: 0px"></div>
                                    <label class="col-sm-4 control-label right">End Condition</label>
                                    <div class="col-sm-8 padding_boostrap">
                                        <div style="position: absolute;background-color: transparent;height: 34px;width: 100%;" id="no_available_<?php echo $index_service; ?>"></div>
                                        <select style="background-color:#fff !important"  onchange="Js_Top.SchedulingEndcondition(this,<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>);Js_Top.ShowCalendarPrevViews(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" name="End_condition_<?php echo $index_service; ?>" id="End_condition_<?php echo $index_service; ?>" class="form-control no_click">
                                            <option <?php if(!empty($Service_Scheduling[0]['end_condition']) && $Service_Scheduling[0]['end_condition'] == 'never'): echo 'selected'; endif; ?> value="never">Never</option>
                                            <option <?php if(!empty($Service_Scheduling[0]['end_condition']) && $Service_Scheduling[0]['end_condition'] == 'xnumber'): echo 'selected'; endif; ?> value="xnumber">After x number of appointments</option>
                                            <option <?php if(!empty($Service_Scheduling[0]['end_condition']) && $Service_Scheduling[0]['end_condition'] == 'xamount'): echo 'selected'; endif; ?> value="xamount">After x amount of time</option>
                                        </select>
                                        <div style="display:none" class="option_number_endcondition_scheduling_<?php echo $index_service; ?>">
                                            <div class="col-lg-12 col-xs-12" style="padding-left: 0px;padding-right: 0px;">
                                                <div class="space"></div>
                                                <input onkeyup="Js_Top.ShowCalendarPrevViews(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" name="Number_of_appointments_<?php echo $index_service; ?>" id="Number_of_appointments_<?php echo $index_service; ?>" type="text" class="form-control OnlyNumber" value="<?php echo !empty($Service_Scheduling[0]['number_of_appointments'])?$Service_Scheduling[0]['number_of_appointments']:0; ?>">
                                            </div>
                                        </div>
                                        <div style="display:none" class="option_amount_endcondition_scheduling_<?php echo $index_service; ?>">
                                            <div class="col-lg-6 col-xs-12" style="padding-left: 0px;padding-right: 0px;">
                                                <div class="space"></div>
                                                <input onkeyup="Js_Top.ShowCalendarPrevViews(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" name="Value_mount_of_time_<?php echo $index_service; ?>" id="Value_mount_of_time_<?php echo $index_service; ?>" type="text" class="form-control OnlyNumber" value="<?php echo !empty($Service_Scheduling[0]['value_mount_of_time'])?$Service_Scheduling[0]['value_mount_of_time']:0; ?>">
                                            </div>
                                            <div class="col-lg-6 col-xs-12" style="padding-left: 5px;padding-right: 0px">
                                                <div class="space"></div>
                                                <select onchange="Js_Top.ShowCalendarPrevViews(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" name="Date_mount_of_time_<?php echo $index_service; ?>" id="Date_mount_of_time_<?php echo $index_service; ?>" class="form-control">
                                                    <option <?php if(!empty($Service_Scheduling[0]['date_mount_of_time']) && $Service_Scheduling[0]['date_mount_of_time'] == 'days'): echo 'selected'; endif; ?> value="days">Days</option>
                                                    <option <?php if(!empty($Service_Scheduling[0]['date_mount_of_time']) && $Service_Scheduling[0]['date_mount_of_time'] == 'months'): echo 'selected'; endif; ?> value="months">Months</option>
                                                    <option <?php if(!empty($Service_Scheduling[0]['date_mount_of_time']) && $Service_Scheduling[0]['date_mount_of_time'] == 'years'): echo 'selected'; endif; ?> value="years">Years</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div style="clear:both"></div>
                                    <label class="col-sm-4 control-label right">Service Duration</label>
                                    <div class="col-sm-8 col-xs-12 padding_boostrap">
                                        <div class="col-lg-6 col-xs-12" style="padding-left: 0px;padding-right: 0px;">
                                            <div class="space"></div>
                                            <input onchange="Js_Top.UpdateClickCancel(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" name="hours_<?php echo $index_service; ?>" placeholder="Hours" type="text" class="form-control service_duration_scheduling_<?php echo $index_service; ?> OnlyNumber" value="<?php echo !empty($Service_Scheduling[0]['hours'])?$Service_Scheduling[0]['hours']:(!empty($Options[0]['hours'])?$Options[0]['hours']:0); ?>">
                                        </div>
                                        <div class="col-lg-6 col-xs-12" style="padding-left: 5px;padding-right: 0px">
                                            <div class="space"></div>
                                            <input onchange="Js_Top.UpdateClickCancel(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" name="minutes_<?php echo $index_service; ?>" placeholder="Minutes" type="text" class="form-control OnlyNumber MinmaxNumberMinute" value="<?php echo !empty($Service_Scheduling[0]['minutes'])?$Service_Scheduling[0]['minutes']:(!empty($Options[0]['minutes'])?$Options[0]['minutes']:0); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group Scheduling_Options_<?php echo $index_service; ?>">
                                    <div class="space"></div>
                                    <label class="col-sm-4 control-label right">Scheduling Options</label>
                                    <div class="col-sm-8 padding_boostrap" style="text-align: left;">
                                        <div class="custom-checkbox-radio">
                                            <input onchange="Js_Top.Option_Settime_Scheduling(this,<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" name="start_time_<?php echo $index_service; ?>" type="radio" <?php if(!empty($Service_Scheduling[0]['start_time']) && $Service_Scheduling[0]['start_time'] == 'automatically_settime'): echo 'checked'; endif; ?> <?php if(empty($Service_Scheduling[0]['start_time'])): echo 'checked'; endif; ?> value="automatically_settime" style="margin-left:0"> 
                                            <!-- requied set time in option (only use in js.js) -->
                                                <input type="text"   class="TimeAuto_<?php echo $index_service; ?>" name="TimeAuto_<?php echo $index_service; ?>" value="<?php echo (!empty($Service_Scheduling[0]['start_time_time']) && $Service_Scheduling[0]['start_time'] == 'automatically_settime')?$Service_Scheduling[0]['start_time_time']:''; ?>"/>
                                            <!-- End requied set time in option -->
                                            <span style="position: relative;bottom: 5px;">Automatically assign time</span>  
                                        </div>
                                        <div class="space"></div>
                                        <div class="custom-checkbox-radio">
                                            <input onchange="Js_Top.Option_Settime_Scheduling(this,<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" name="start_time_<?php echo $index_service; ?>" type="radio" <?php if(!empty($Service_Scheduling[0]['start_time']) && $Service_Scheduling[0]['start_time'] == 'decide_settime'): echo 'checked'; endif; ?> value="decide_settime" style="margin-left:0"> 
                                            <span style="position: relative;bottom: 5px;">Decide on the day of event</span>  
                                        </div>
                                        <div style="font-size: 0.7em;text-align: left;font-weight: bold;">Check this option to have the event populate under "Unscheduled / Missed Work" on the day of the event.</div>
                                        <div class="space"></div>
                                        <div class="custom-checkbox-radio">
                                            <input onchange="Js_Top.Option_Settime_Scheduling(this,<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" name="start_time_<?php echo $index_service; ?>" type="radio" <?php if(!empty($Service_Scheduling[0]['start_time']) && $Service_Scheduling[0]['start_time'] == 'manually_settime'): echo 'checked'; endif; ?> value="manually_settime" style="margin-left:0;">          
                                            <span style="position: relative;bottom: 5px;">Manually set time </span>   
                                        </div>
                                        
                                        <div class="col-lg-12 col-xs-12 manually_settime_scheduling_<?php echo $index_service; ?>" style="padding-left: 0px;padding-right: 0px;display:none">
                                            <div class="space"></div>   
                                            <div class='input-group date'>
                                                <input style="background-color:#fff" readonly="readonly" name="time_start_scheduling_<?php echo $index_service; ?>"  type='text' class="form-control timepickerssss" placeholder="hh:mm" value="<?php echo (!empty($Service_Scheduling[0]['start_time_time']) && $Service_Scheduling[0]['start_time'] == 'manually_settime')?$Service_Scheduling[0]['start_time_time']:''; ?>"/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-time"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="space"></div>
                                    <label class="col-sm-4 control-label right">Assigned Pesticides</label>
                                    <div class="col-sm-8 padding_boostrap" style="text-align: left;">

                                        <div class="wap_Pesticide_<?php echo $index_service; ?>">
                                            <?php if(!empty($Service_Pesticides)): ?>
                                                <?php foreach ($Service_Pesticides as $key => $value): ?>
                                                    <div>
                                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 padding_zero">
                                                            <input name="pesticide_name_<?php echo $index_service; ?>[]" placeholder="Pesticide Name"  type='text' class="form-control autocomplete_pesticide" value="<?php echo !empty($value['pesticide_name'])?$value['pesticide_name']:''; ?>"/>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 padding_zero_right">
                                                            <input name="pesticide_amount_<?php echo $index_service; ?>[]" placeholder="Amount"  type='text' class="form-control moneyUSD" value="<?php echo !empty($value['pesticide_amount'])?number_format($value['pesticide_amount'],2):''; ?>"/>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="top: 7px;">
                                                            <span><?php echo !empty($value['pesticide_unit'])?$value['pesticide_unit']:''; ?></span>
                                                            <input name="pesticide_unit_<?php echo $index_service; ?>[]" type='hidden' class="form-control" value="<?php echo !empty($value['pesticide_unit'])?$value['pesticide_unit']:''; ?>"/>
                                                        </div>
                                                        <i onclick="Js_Top.Remove_pesticide(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>,this)" class="fa fa-minus-circle" style="position: absolute;color: red;cursor: pointer;" aria-hidden="true"></i>
                                                        <input name="id_pesticide_<?php echo $index_service; ?>[]" type='hidden' class="form-control" value="<?php echo !empty($value['id'])?$value['id']:''; ?>"/>
                                                        <input name="id_pesticide_select_<?php echo $index_service; ?>[]" type='hidden' class="form-control id_pesticide_select" value="<?php echo !empty($value['pesticide_id'])?$value['pesticide_id']:''; ?>"/>
                                                        <div class="space"  style="margin-top: 0px;"></div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div>
                                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 padding_zero">
                                                        <input name="pesticide_name_<?php echo $index_service; ?>[]" placeholder="Pesticide Name"  type='text' class="form-control autocomplete_pesticide"/>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 padding_zero_right">
                                                        <input name="pesticide_amount_<?php echo $index_service; ?>[]" placeholder="Amount"  type='text' class="form-control moneyUSD"/>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="top: 7px;">
                                                        <span></span>
                                                        <input name="pesticide_unit_<?php echo $index_service; ?>[]" type='hidden' class="form-control"/>
                                                    </div>
                                                    <i onclick="Js_Top.Remove_pesticide(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>,this)" class="fa fa-minus-circle" style="position: absolute;color: red;cursor: pointer;" aria-hidden="true"></i>
                                                    <input name="id_pesticide_<?php echo $index_service; ?>[]" type='hidden' class="form-control"/>
                                                    <input name="id_pesticide_select_<?php echo $index_service; ?>[]" type='hidden' class="form-control id_pesticide_select"/>
                                                    <div class="space"  style="margin-top: 0px;"></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>  
                                        <div class="col-lg-12 padding_zero">
                                            <button onclick="Js_Top.Add_pesticide(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" type="button" class="btn btn-primary btn-sm"> <span class="glyphicon glyphicon-plus"></span></button>
                                            <div class="space"  style="margin-top: 0px;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label right">Confirmation</label>
                                    <div class="col-sm-8 padding_boostrap" style="text-align: left;">
                                        <div class="custom-checkbox">
                                            <input type="checkbox" <?php if(!empty($Service_Scheduling[0]['confirmation']) && $Service_Scheduling[0]['confirmation'] == 1): echo 'checked'; endif; ?> id="confirmation_<?php echo $index_service; ?>" name="confirmation_<?php echo $index_service; ?>" value="1">
                                            <label for="confirmation_<?php echo $index_service; ?>"></label>
                                        </div>
                                        Require confirmation to mark scheduled event as complete
                                        <div style="font-size: 0.7em;text-align: left;font-weight: bold;">Unchecking this option will automatically mark events as complete once the current time passes the scheduled date / time.</div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-6 wap_calendar_scheduling" style="overflow: hidden;">
                                <div class="loading_content" id="loading_content_sheduling_<?php echo $index_service; ?>" style="padding-top:0;padding-bottom:0">
                                    <div style="position: absolute;top: 50%;left: 50%;">
                                        <img src="<?php echo url::base() ?>public/images/loading_48.gif" alt="">
                                    </div>
                                </div> 
                                <input type="hidden" name="Year_select_<?php echo $index_service; ?>" class="Year_select_<?php echo $index_service; ?>" value="<?php echo date('Y'); ?>"/>
                                <button style="display:none" type="button" class="btn btn-sm btn-primary prev-month-scheduling-<?php echo $index_service; ?>" onclick="Js_Top.Months_1_6(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)">Prev months</button>
                                <button style="display:none" type="button" class="btn btn-sm btn-primary next-month-scheduling-<?php echo $index_service; ?>" onclick="Js_Top.Months_6_12(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)">Next months</button>
                                <div class="calendar-scheduling-<?php echo $index_service; ?>"></div>
                            </div>
                        </div>
                    <?php endif; ?>
                <!-- end sheduling -->

                <!-- commision -->
                    <div role="tabpanel" class="tab-pane content_commissions_customers" id="commissions_customers_<?php echo $index_service; ?>">
                        <input type="hidden" class="Total_Billing_Commission" value="<?php echo !empty($Total_Billing)?$Total_Billing:0; ?>"/>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="padding-left:0;border:1px dotted red;overflow: hidden;">
                            <div class="col-lg-5">
                                <select class="form-control">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                            <div class="col-lg-7">
                                <div style="padding-top: 0.5em;padding-bottom: 0.5em;text-align: left;">
                                    <div class="custom-checkbox">
                                        <input type="checkbox"id="chk_template_commission">
                                        <label for="chk_template_commission"></label>
                                    </div>
                                    <span>Set as default template for line items</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="save_line_item_customers">
                                <button type="button" class="btn btn-md btn-primary">Save line items as a template</button>
                            </div>
                        </div>
                        <div class="space"></div>
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Service Technician</th>
                                            <th>Commission Type</th>
                                            <th>Amount</th>
                                            <th class="right">Prospective Commission Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody_commissions_<?php echo $index_service; ?>">
                                        <?php $Total_Commissions = 0; ?>
                                        <?php if(!empty($Service_Commissions)): ?>
                                            <?php foreach ($Service_Commissions as $key => $Commissions): ?>

                                                    <?php 
                                                        if($Commissions['commission_type'] == 'percent'): 
                                                            $Amount_Commission = !empty($Commissions['amount'])?$Commissions['amount']:0;
                                                            $Total_Line_Commissions = ($Total_Billing * $Amount_Commission) / 100;
                                                        else: 
                                                            $Amount_Commission = !empty($Commissions['amount'])?number_format($Commissions['amount'],2):number_format(0,2);
                                                            $Total_Line_Commissions = $Amount_Commission;
                                                        endif; 
                                                        $Total_Commissions += $Total_Line_Commissions;
                                                    ?>
                                                    <tr>
                                                        <td style="text-align: left;padding-left: 0;">
                                                            <button onclick="Js_Top.Remove_Commissions(this);" type="button" class="btn btn-danger btn-sm"> <span class="glyphicon glyphicon-remove"></span></button>
                                                        </td>
                                                        <td>
                                                            <select name="commission_technician_<?php echo $index_service; ?>[]" class="form-control">
                                                                <option value=""><?php echo $this->Technician_No_Name; ?></option>
                                                                <?php if(!empty($customers_technician)): ?>
                                                                    <?php foreach ($customers_technician as $key => $value): ?>
                                                                        <option <?php if(!empty($Commissions['service_technician']) && $Commissions['service_technician'] == $value['id']): echo 'selected'; endif; ?> value="<?php echo $value['id']; ?>"><?php echo !empty($value['name'])?$value['name']:''; ?></option>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select  onchange="Js_Top.Calculator_Commission_Type(this)" name="commission_type_<?php echo $index_service; ?>[]" class="form-control">
                                                                <option <?php if(!empty($Commissions['commission_type']) && $Commissions['commission_type'] == 'percent'): echo 'selected'; endif; ?> value="percent">Percentage of sales (excl. discount and tax)</option>
                                                                <option <?php if(!empty($Commissions['commission_type']) && $Commissions['commission_type'] == 'number'): echo 'selected'; endif; ?> value="number">Number</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <div class="Wap_Amount_Commission">
                                                                <input onkeyup="Js_Top.Calculator_Commission_Amount(this)" name="commission_amount_<?php echo $index_service; ?>[]" type="text" value="<?php echo $Amount_Commission; ?>"   class="Amount_Commission form-control OnlyNumberDot <?php if(!empty($Commissions['commission_type']) && $Commissions['commission_type'] == 'number'): echo 'moneyUSD'; else: echo 'MinmaxPercent'; endif; ?>">
                                                                <i class="fa fa-percent percent_commission" aria-hidden="true"></i> 
                                                                <i class="fa fa-usd number_commission" aria-hidden="true" style="display:none"></i>                                    
                                                            </div>
                                                        </td>
                                                        <td style="text-align: right;">
                                                            <span class="Total_line_commission"><?php echo $Total_Line_Commissions; ?></span>
                                                            <input type="hidden" name="ID_Commissions_<?php echo $index_service; ?>[]" value="<?php echo !empty($Commissions['id'])?$Commissions['id']:''; ?>"/>
                                                        </td>
                                                    </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td style="text-align: left;padding-left: 0;">
                                                    <button onclick="Js_Top.Remove_Commissions(this);" type="button" class="btn btn-danger btn-sm"> <span class="glyphicon glyphicon-remove"></span></button>
                                                </td>
                                                <td>
                                                    <select name="commission_technician_<?php echo $index_service; ?>[]" class="form-control">
                                                        <option value=""><?php echo $this->Technician_No_Name; ?></option>
                                                        <?php if(!empty($customers_technician)): ?>
                                                            <?php foreach ($customers_technician as $key => $value): ?>
                                                                <option value="<?php echo $value['id']; ?>"><?php echo !empty($value['name'])?$value['name']:''; ?></option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select  onchange="Js_Top.Calculator_Commission_Type(this)" name="commission_type_<?php echo $index_service; ?>[]" class="form-control">
                                                        <option value="percent">Percentage of sales (excl. discount and tax)</option>
                                                        <option value="number">Number</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="Wap_Amount_Commission">
                                                        <input onkeyup="Js_Top.Calculator_Commission_Amount(this)" name="commission_amount_<?php echo $index_service; ?>[]" type="text" value="0" class="Amount_Commission form-control OnlyNumberDot MinmaxPercent">
                                                        <i class="fa fa-percent percent_commission" aria-hidden="true"></i> 
                                                        <i class="fa fa-usd number_commission" aria-hidden="true" style="display:none"></i>                                    
                                                    </div>
                                                </td>
                                                <td style="text-align: right;">
                                                    <span class="Total_line_commission">0.00</span>
                                                    <input type="hidden" name="ID_Commissions_<?php echo $index_service; ?>[]" />
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        <tr style="background-color:#fff">
                                            <td style="text-align: left;padding-left: 0;">
                                                <button onclick="Js_Top.Add_Commissions(this,<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>);" type="button" class="btn btn-primary btn-sm"> <span class="glyphicon glyphicon-plus"></span></button> 
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr style="background-color:#fff">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td style="text-align: right;font-size: 1.2em;font-weight: bold;">
                                                Total <span class="Total_Commissions"><?php echo number_format($Total_Commissions,2); ?></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <!-- end commision -->

                <!-- note -->
                    <div role="tabpanel" class="tab-pane" id="notes_customers_<?php echo $index_service; ?>">
                         <input type="hidden" name="id_notes" value="<?php echo !empty($Service_Notes[0]['id'])?$Service_Notes[0]['id']:''; ?>"/>
                        <div class="form-group" style="overflow: hidden;margin-bottom:0;padding-top: 10px;padding-bottom: 10px;">
                            <label for="inputEmail3" class="col-sm-1 control-label left">Notes</label>
                            <div class="col-sm-11 padding_boostrap left" >
                                <textarea name="notes_<?php echo $index_service; ?>" resize="none" class="form-control" id="" cols="30" rows="10"><?php echo !empty($Service_Notes[0]['notes'])?$Service_Notes[0]['notes']:''; ?></textarea>
                                <div style="font-size: 0.7em;text-align: left;font-weight: bold;">Notes will be displayed on work order for technicians.</div>
                            </div>
                        </div>
                    </div>
                <!-- end note -->

                <!-- attachments -->
                    <div role="tabpanel" class="tab-pane content_attachments_customers" id="attachments_customers_<?php echo $index_service; ?>">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="padding-left:0">
                            <div class="col-lg-5">
                                <select class="form-control">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                            <div class="col-lg-7">
                                <div style="padding-top: 0.5em;padding-bottom: 0.5em;text-align: left;">
                                    <div class="custom-checkbox">
                                        <input type="checkbox"id="chk_template_attachments">
                                        <label for="chk_template_attachments"></label>
                                    </div>
                                    <span>Set as default template for line items</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="save_line_item_customers">
                                <button type="button" class="btn btn-md btn-primary btn-sm"> <i class="fa fa-floppy-o" aria-hidden="true"></i> Save line items as a template</button>
                            </div>
                        </div>
                        <div class="space"></div>
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-striped attachments_<?php echo $index_service; ?>">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;"></th>
                                            <th class="left"><div class="col-xs-12">Attachment</div></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($Service_Attachment)): ?>
                                            <?php foreach ($Service_Attachment as $key => $Attachment): ?>
                                                <tr>
                                                    <td style="text-align: left;padding-left: 0;">
                                                        <button type="button" onclick="Js_Top.Remove_Attachments(this,'edit_detail')" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span></button> 
                                                    </td>
                                                    <td class="left">
                                                        <input type="hidden" name="attachments_<?php echo $index_service; ?>[]" value="<?php echo !empty($Attachment['file_name'])?$Attachment['file_name']:''; ?>"/>
                                                        <input type="hidden" name="id_attachments_<?php echo $index_service; ?>[]" value="<?php echo !empty($Attachment['id'])?$Attachment['id']:''; ?>"/>
                                                        <input type="hidden" name="key_number_attachments_<?php echo $index_service; ?>[]" value="0"/>
                                                        <div class="col-xs-6">
                                                            <?php if(!empty($Attachment['file_name'])): ?>
                                                                <?php echo $Attachment['file_name']; ?>
                                                            <?php else: ?>
                                                                <label class="btn btn-primary btn-sm">
                                                                    <i class="fa fa-upload" aria-hidden="true"></i> Browse...<input onchange="Js_Top.Upload_Attachments(this)" type="file" style="display: none;">
                                                                </label>
                                                                <label style="display:none"></label>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td style="text-align: left;padding-left: 0;">
                                                    <button type="button" onclick="Js_Top.Remove_Attachments(this,'edit_detail')" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span></button> 
                                                </td>
                                                <td class="left">
                                                    <input type="hidden" name="attachments_<?php echo $index_service; ?>[]" />
                                                    <input type="hidden" name="id_attachments_<?php echo $index_service; ?>[]" />
                                                    <input type="hidden" name="key_number_attachments_<?php echo $index_service; ?>[]" value="0"/>
                                                    <div class="col-xs-6">
                                                        <label class="btn btn-primary btn-sm">
                                                            <i class="fa fa-upload" aria-hidden="true"></i> Browse...<input onchange="Js_Top.Upload_Attachments(this)" type="file" style="display: none;">
                                                        </label>
                                                        <label style="display:none"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        <tr style="background-color:#fff">
                                            <td style="text-align: left;padding-left: 0;">
                                                <button type="button" onclick="Js_Top.Add_Attachments(this,<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>)" class="btn btn-primary btn-sm"> <span class="glyphicon glyphicon-plus"></span></button>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <!-- end attachements -->
            </div>
        </div>
    </div>
</div>
