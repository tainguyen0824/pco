<div class="col-lg-12" style="background: #f1f1f1;margin-bottom: 5px;">
	<h3 style="font-size: 18px;text-align: left;margin-bottom: 0;margin-top:10px;" class="line-item"><strong>Line Items</strong></h3>
	<div style="padding-top: 10px;">
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
	            <button type="button" class="btn btn-md btn-primary">Save line items as a template</button>
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
	                    <div  class="custom-checkbox">
	                        <input onchange="Js_Top.CheckAll_Taxable(this)" <?php if(!empty($Customers_Accounting[0]['chk_all_tax']) && $Customers_Accounting[0]['chk_all_tax'] == 1): ?> checked="checked" <?php endif; ?> name="chk_all_taxable_" class="Chk_All_Taxable" type="checkbox" id="Chk_All_Taxable"/>
	                        <label for="Chk_All_Taxable"></label>
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
	            <?php foreach ($Service_Item as $key => $Item): ?>
	                <?php 
	                    $Amount = $Item['quantity'] * $Item['unit_price'];
	                    $Total_Billing += $Amount; 
	                    if(!empty($Item['chk_taxable']) && $Item['chk_taxable'] == 1):
	                        $Result_Taxable += ($Amount * $Customers_Accounting[0]['default_val_tax']) / 100;
	                    endif;
	                ?>
	                
	                <tr style="background-color:#f1f1f1">
	                    <td style="text-align: left;padding-left: 0;">
	                        <button onclick="Js_Top.Remove_Billing(this)" type="button" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span></button> 
	                    </td>
	                    <td style="width: 20%">
	                        <select name="lineitems_type_[]" class="form-control" style="min-width: 130px">
	                            <?php foreach ($this->TypeBilling as $key => $value): ?>
	                                <option <?php if(!empty($Item['type']) && $Item['type'] == $key): echo 'selected'; endif; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
	                            <?php endforeach; ?>
	                        </select>
	                    </td>
	                    <td>
	                        <input name="lineitems_description_[]" type="text" class="form-control" value="<?php echo !empty($Item['description'])?$Item['description']:''; ?>">
	                    </td>
	                    <td>
	                        <input onkeyup="Js_Top.Change_Quantity_Billing(this)" name="lineitems_quantity_[]" type="text" class="form-control OnlyNumber" value="<?php echo !empty($Item['quantity'])?$Item['quantity']:0 ?>">
	                    </td>
	                    <td>
	                        <input onkeyup="Js_Top.Change_Unit_Price_Billing(this)" name="lineitems_unit_price_[]" type="text" class="form-control val_lineitems_unit_price moneyUSD" value="<?php echo !empty($Item['unit_price'])?number_format($Item['unit_price'],2):number_format(0,2); ?>">
	                    </td>
	                    <td style="vertical-align: middle;">
	                        <span class="B_amount"><?php echo isset($Amount)?number_format($Amount,2):number_format(0,2); ?></span>
	                    </td>
	                    <td>
	                        <div class="custom-checkbox">
	                            <input onchange="Js_Top.Change_Taxable_Billing(this)" <?php if(!empty($Item['chk_taxable']) && $Item['chk_taxable'] == 1): echo "checked"; endif; ?> name="checkbox_billing_taxable_[]" type="checkbox" class="chk_taxable" id="taxable_<?php echo $Item['id'] ?>">
	                            <label for="taxable_<?php echo $Item['id'] ?>"></label>
	                        </div>
	                        <input value="<?php if(!empty($Item['chk_taxable']) && $Item['chk_taxable'] == 1): echo $Item['chk_taxable']; else: echo 0; endif; ?>" name="val_lineitems_checkbox_tax_[]" type="hidden" class="val_checkbox_tax form-control">
	                        <input value="<?php echo $Item['id']; ?>" name="id_billing_[]" type="hidden" class="form-control">
	                    </td>
	                </tr>
	            <?php endforeach; ?>
	        <?php else: ?>
	            <tr style="background-color:#f1f1f1">
	                <td style="text-align: left;padding-left: 0;">
	                    <button onclick="Js_Top.Remove_Billing(this)" type="button" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span></button> 
	                </td>
	                <td style="width: 20%">
	                    <select name="lineitems_type_[]" class="form-control" style="min-width: 130px">
	                        <?php foreach ($this->TypeBilling as $key => $value): ?>
	                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
	                        <?php endforeach; ?>
	                    </select>
	                </td>
	                <td>
	                    <input name="lineitems_description_[]" type="text" class="form-control">
	                </td>
	                <td>
	                    <input onkeyup="Js_Top.Change_Quantity_Billing(this)" name="lineitems_quantity_[]" type="text" class="form-control OnlyNumber">
	                </td>
	                <td>
	                    <input onkeyup="Js_Top.Change_Unit_Price_Billing(this)" name="lineitems_unit_price_[]" type="text" class="form-control val_lineitems_unit_price moneyUSD">
	                </td>
	                <td style="vertical-align: middle;">
	                    <span class="B_amount">0.00</span>
	                </td>
	                <td>
	                    <div class="custom-checkbox">
	                        <input onchange="Js_Top.Change_Taxable_Billing(this)" name="checkbox_billing_taxable_[]" type="checkbox" class="chk_taxable" id="taxable_">
	                        <label for="taxable_"></label>
	                    </div>
	                    <input name="val_lineitems_checkbox_tax_[]" type="hidden" class="val_checkbox_tax form-control">
	                    <input name="id_billing_[]" type="hidden" class="form-control">
	                </td>
	            </tr>
	        <?php endif; ?>
	            <tr style="background-color:#f1f1f1">
	                <td class="align-middle" style="text-align: left;padding-left: 0;">
	                    <button onclick="Js_Top.Add_Billing(this,'')" type="button" class="btn btn-primary btn-sm"> <span class="glyphicon glyphicon-plus"></span></button>
	                </td>
	                <td></td>
	                <td></td>
	                <td class="align-middle" style="text-align:right;font-size: 1.2em;font-weight: bold;">
	                    Add discount:
	                </td>
	                <td class="align-middle">
	                    <div class="inner-addon right-addon">
	                        <i class="fa fa-percent" aria-hidden="true"></i>
	                        <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
	                        <input onkeyup="Js_Top.Change_Discount_Billing(this)" name="lineitems_discount" type="text" class="val_discount form-control OnlyNumberDot MinmaxPercent" value="<?php echo !empty($Customers_Accounting[0]['item_discount'])?$Customers_Accounting[0]['item_discount']:0; ?>">
	                    </div> 
	                </td>
	                <?php  
	                    // Discount
	                    if(!empty($Customers_Accounting[0]['item_discount'])):
	                        $Result_Discount = ($Total_Billing * $Customers_Accounting[0]['item_discount']) / 100;
	                    endif;
	                ?>
	                <td style="vertical-align: middle;" class="txt_billing_discount align-middle"><?php echo number_format(round($Result_Discount,2),2); ?></td>
	                <td></td>
	            </tr>
	            <tr style="background-color:#f1f1f1">
	                <td></td>
	                <td></td>
	                <td></td>
	                <td>
	                    <select onchange="Js_Top.Change_Code_Taxable_Billing(this)" name="slt_lineitems_state_tax" class="form-control">
	                        <option value="None|0.00">None</option>
	                        <?php if(!empty($this->state)): ?>
	                            <?php foreach ($this->state as $key => $value): ?>
	                                <option <?php if(empty($Customers_Accounting) && !empty($Options[0]['slt_default_tax']) && $Options[0]['slt_default_tax'] == $value['state_code'].'|'.$value['_state_tax']): echo 'selected="selected"'; endif; ?> <?php if(!empty($Customers_Accounting[0]['default_code_tax']) && $Customers_Accounting[0]['default_code_tax'] == $value['state_code'].'|'.$value['_state_tax']): echo 'selected'; endif; ?> value="<?php echo $value['state_code'] ?>|<?php echo $value['_state_tax'] ?>"><?php echo $value['state_code']; ?> - <?php echo $value['_state_tax'].'%' ?></option>
	                            <?php endforeach; ?>
	                        <?php endif; ?>
	                    </select>
	                    <div class="space"></div>
	                    <div style="text-align: left;">
	                        <div class="custom-checkbox">
	                            <input <?php if(!empty($Customers_Accounting[0]['default_tax']) && $Customers_Accounting[0]['default_tax'] == 1): echo 'checked'; endif; ?> name="chk_default_tax" type="checkbox" id="SetDefaultTaxable_">
	                            <label for="SetDefaultTaxable_"></label>
	                        </div>
	                        <span>Set as default</span>
	                    </div>
	                </td>
	                <td style="vertical-align: top;">
	                    <div class="inner-addon right-addon">
	                        <i class="fa fa-percent" aria-hidden="true"></i>
	                        <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
	                        <input onkeyup="Js_Top.Change_Value_Taxable_Billing(this)" value="<?php echo !empty($Customers_Accounting)?$Customers_Accounting[0]['default_val_tax']:(!empty($Options[0]['val_default_tax'])?$Options[0]['val_default_tax']:0); ?>" name="lineitems_taxable" class="val_tax form-control OnlyNumberDot" type="text">
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
	                <td class="txt_billing_total">
	                    <?php echo number_format(round($Result_Total,2),2); ?>
	                </td>
	                <td></td>
	            </tr>
	        </tbody>
	    </table>
	</div>
</div>