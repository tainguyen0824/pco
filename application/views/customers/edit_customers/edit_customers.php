<div id="wrap-close-overlay">
    <div class="col-lg-6 col-xs-6 title-left-close-overlay">
        <div class="DivParent">
           <div class="DivWhichNeedToBeVerticallyAligned">
              Customer Details
           </div><div class="DivHelper"></div>
        </div>
    </div>
    <div class="col-lg-6 col-xs-6 title-right-close-overlay" >
        <div style="text-align: right;">
           <button onclick="Customer_Edit.EditDetails(<?php echo !empty($Customers[0]['id'])?$Customers[0]['id']:''; ?>)" type="button" class="btn btn-sm btn-primary">Edit Details</button>
           <button type="button" class="btn btn-sm btn-primary" onclick="Js_Top.closeNav()"><i class="fa fa-times" aria-hidden="true" style="color:#fff"></i></button>
        </div>
    </div>
    <div class="col-lg-12" id="wrap_customer_hide_input_edit">
    	<input type="hidden" id="customer_id" value="<?php echo !empty($Customers[0]['id'])?$Customers[0]['id']:''; ?>">
    </div>
</div>
<div id="overlay-content" class="overlay-content">
    <div id="wrap_edit_customer">
        <div class="col-lg-8 col-md-8 padding_zero_left wrap_edit_info_invoce">
            <div class="col-lg-8 col-md-8 padding_zero" id="wrap-edit-title-customers">
                <p><strong><?php echo !empty($Customers[0]['customer_name'])?$Customers[0]['customer_name']:''; ?></strong></p>
                <p>Customer No. <?php echo !empty($Customers[0]['customer_no'])?$Customers[0]['customer_no']:''; ?></p>
                <p>Active Customer</p>
                <p><?php echo !empty($Customers[0]['customer_type'])?$Customers[0]['customer_type']:''; ?></p>
            </div>
            
            <div class="col-lg-12 col-md-12 padding_zero oflow" id="wrap-edit-vitem-invoice">
                <div class="col-lg-4 col-md-4 padding_zero">
                    <div class="edit-vitem-invoice">
                        <p><strong>Open Invoices</strong></p>
                        <p><strong class="open_invoices">$0.00</strong></p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 padding_zero">
                    <div class="edit-vitem-invoice">
                        <p><strong>Unapplied Payments</strong></p>
                        <p><strong class="unapplied_payments">$0.00</strong></p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 padding_zero">
                    <div class="edit-vitem-invoice">
                        <p><strong>Total Balance</strong></p>
                        <p><strong class="total_balance">$0.00</strong></p>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div id="wrap-edit-notes">
                <p><strong>Customer Notes</strong></p>
                <div id="edit-notes-content" style="overflow-y: auto;">
                    
                </div>
                <div id="edit-notes-form">
                    <p>
                        <input name="content_logs" type="text" class="form-control">
                    </p>
                    <p>
                        <button onclick="Customer_Edit.Savelogs(<?php echo !empty($Customers[0]['id'])?$Customers[0]['id']:0; ?>)" type="button" class="btn btn-primary btn-sm">Log</button>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 padding_zero">
            <div class="edit_billing_info">
                <div class="col-lg-12 col-xs-12">
                    <p><strong>Billing Information</strong></p>
                </div>
                <div class="col-lg-12">
                    <p><?php echo !empty($Customers[0]['billing_name'])?$Customers[0]['billing_name']:''; ?></p>
                    <p><?php echo !empty($Customers[0]['billing_address_1'])?$Customers[0]['billing_address_1']:''; ?></p>
                    <p><?php echo !empty($Customers[0]['billing_address_2'])?$Customers[0]['billing_address_2']:''; ?></p>
                    <p><?php echo !empty($Customers[0]['billing_city'])?$Customers[0]['billing_city'].',':''; ?> <?php echo ((!empty($Customers[0]['billing_state']) && !empty($Customers[0]['billing_city'])) || (!empty($Customers[0]['billing_state']) && !empty($Customers[0]['billing_zip'])))?$Customers[0]['billing_state']:''; ?> <?php echo !empty($Customers[0]['billing_zip'])?$Customers[0]['billing_zip']:''; ?></p>
                    <br>
                    <p><?php echo !empty($Customers[0]['billing_email'])?$Customers[0]['billing_email']:''; ?></p>
                    <?php if(!empty($Customers[0]['billing_phone'])): ?>
                        <?php $Billing_Phone = json_decode($Customers[0]['billing_phone'],true); ?>
                    	<?php foreach ($Billing_Phone as $key => $phone): ?>
		                    <p><?php echo !empty($phone['phone'])?$phone['phone']:'' ?> <?php echo !empty($phone['ext'])?'Ext: '.$phone['ext']:'' ?> <?php echo !empty($phone['type'])?$this->PositionPhone[$phone['type']]:'' ?></p>
		                <?php endforeach; ?>
		            <?php endif; ?>
                </div>   
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-12 padding_zero">
            <div id="wrap_tab_edit">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" onclick="Customer_Edit.Change_Tab_Edit('service_group')" class="active" style="margin-left: -1px;"><a href="#service_group" aria-controls="service_group" role="tab" data-toggle="tab">Service Groups</a></li>
                    <li role="presentation" onclick="Customer_Edit.Change_Tab_Edit('accounting')"><a href="#accounting" aria-controls="accounting" role="tab" data-toggle="tab">Accounting</a></li>
                    <li role="presentation" onclick="Customer_Edit.Change_Tab_Edit('active_history')"><a href="#active_history" aria-controls="active_history" role="tab" data-toggle="tab">Activity History</a></li>
                    <li role="presentation" onclick="Customer_Edit.Change_Tab_Edit('credit_card')"><a href="#credit_card" aria-controls="credit_card" role="tab" data-toggle="tab">Credit Cards</a></li>
                    <li role="presentation" onclick="Customer_Edit.Change_Tab_Edit('attachments')"><a href="#attachments" aria-controls="attachments" role="tab" data-toggle="tab">Attachments</a></li>
                </ul>
                <div class="tab-content wrap_tab_edit_content">
                    <!-- Service Groups -->
                    <div role="tabpanel" class="tab-pane active table-responsive" id="service_group">
                    </div>
                    <!-- end Service Groups -->
                    <!-- Accounting -->
                    <div role="tabpanel" class="tab-pane oflow" id="accounting">
                    </div>
                    <!-- end Accounting -->
                    <!-- Activity History -->
                    <div role="tabpanel" class="tab-pane" id="active_history">
                    </div>
                    <!-- end Activity History -->
                    <!-- Credit Cards -->
                    <div role="tabpanel" class="tab-pane" id="credit_card">
                    </div>
                    <!-- end Credit Cards -->
                    <!-- attachments -->
                    <div role="tabpanel" class="tab-pane" id="attachments">
                    </div>
                    <!-- end attachements -->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	$(document).ready(function() {
		Customer_Edit.init();
	});
</script>
