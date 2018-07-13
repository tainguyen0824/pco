<div id="wrap-close-overlay">
    <div class="col-lg-6 col-xs-6 title-left-close-overlay">
        <div class="DivParent">
           <div class="DivWhichNeedToBeVerticallyAligned">
              Add Payment
           </div><div class="DivHelper"></div>
        </div>
    </div>
    <div class="col-lg-6 col-xs-6 title-right-close-overlay" >
        <div style="text-align: right;">
           <button onclick="Customer_Edit.Save_Payment(<?php echo !empty($customer_id)?$customer_id:''; ?>)" type="button" class="btn btn-sm btn-primary">Save Changes</button>
           <button onclick="Customer_Edit.Close_Invoice_Payment_Accouting(<?php echo !empty($customer_id)?$customer_id:''; ?>)" type="button" class="btn btn-sm btn-primary">Discard Changes</button>
        </div>
    </div>
    <div id="wrap_customer_hide_input_edit" style="display:none">
        <input type="text" id="customer_id" value="<?php echo !empty($customer_id)?$customer_id:''; ?>">
    </div>
</div>
<div id="overlay-content" class="overlay-content">
    <form method="POST" id="Frm_E_Payment" accept-charset="utf-8">
        <input type="hidden" name="ID_payment" value="<?php echo !empty($Edit_payment[0]['id'])?$Edit_payment[0]['id']:''; ?>" />
        <div class="col-lg-12" style="background: #f1f1f1;padding-top: 5px;padding-bottom: 10px;margin-bottom: 10px;">
            <div class="col-lg-7 padding_zero">
                <div class="col-lg-12 left title_billing_serivce" style="padding-left: 0;"><strong>Payment Information</strong></div>
                <div class="form-group">
                    <div class="col-lg-3  col-md-3 right_customers padding_zero_left"> <span>Date</span> </div>
                    <div class="col-lg-9 col-md-9 padding_zero">
                        <div class="input-group date">
                            <input value="<?php echo !empty($Edit_payment[0]['date'])?date('m/d/Y',$Edit_payment[0]['date']):''; ?>" name="date" readonly="readonly" type="text" class="form-control datepicker" style="background-color: #fff;">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </div>
                        </div> 
                    </div>
                </div>
                <div class="space" style="margin-top: 0px;"></div>
                <div class=" form-group">
                    <div class="col-lg-3 col-md-3 right_customers padding_zero_left"> <span>Apply to</span> </div>
                    <div class="col-lg-9 col-md-9 padding_zero">
                        <select name="apply_to" class="form-control" style="padding-left:2px">
                            <option value="unapplied_payment">Unapplied payment</option>
                            <?php  
                                if(!empty($Customers_Accounting)):
                                    foreach ($Customers_Accounting as $key => $value):
                            ?>  
                                    <option <?php if(!empty($Edit_payment[0]['id_invoice_payment']) && $Edit_payment[0]['id_invoice_payment'] == $value['id']): echo 'selected'; endif; ?> value="<?php echo $value['id']; ?>"><?php echo $value['customer_no'].' - '.$value['invoice_no']; ?></option>
                            <?php  
                                    endforeach;
                                endif;
                            ?>
                        </select>
                    </div>
                </div>
                <div class=" form-group">
                    <div class="col-lg-3 col-md-3 right_customers padding_zero_left"> <span>Posting Amount</span> </div>
                    <div class="col-lg-9 col-md-9 padding_zero">
                        <input value="<?php echo !empty($Edit_payment[0]['credit_payment'])?number_format($Edit_payment[0]['credit_payment'],2):''; ?>" name="posting_amount" type="text" class="form-control moneyUSD" style="margin-top: 5px;padding-left:7px"> 
                    </div>
                </div>
                <div class=" form-group">
                    <div class="col-lg-3 col-md-3 right_customers padding_zero_left"> <span>Notes</span> </div>
                    <div class="col-lg-9 col-md-9 padding_zero">
                        <textarea name="notes" class="form-control" rows="5" style="margin-top: 5px;padding-left:7px"><?php echo !empty($Edit_payment[0]['notes_payment'])?$Edit_payment[0]['notes_payment']:''; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12" style="background: #f1f1f1;padding-top: 5px;padding-bottom: 10px;margin-bottom: 10px;">
            <h3 style="font-size: 18px;text-align: left;margin-bottom: 0;margin-bottom: 10px;" class="line-item"><strong>Associated Service</strong></h3>
            <div class="col-lg-7 padding_zero">
                <table style="width:100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="col-lg-4 col-md-2 col-sm-2 col-xs-5 padding_zero">Associated Service</td>
                        <td class="col-lg-8 col-md-10 col-sm-10 col-xs-7 padding_zero">
                            <select name="associated_service" class="form-control">
                                <option value="">None</option>
                                <?php if(!empty($Customers_Service)): ?>
                                    <?php foreach ($Customers_Service as $key => $value): ?>
                                        <option <?php echo (!empty($Service_Invoice_Payment) && $Service_Invoice_Payment == $value['service_id'])?'selected':''; ?> value="<?php echo $value['service_id'] ?>"><?php echo $value['service_name'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </td>   
                    </tr>
                </table>
            </div>
        </div>
    </form> 
    <div class="col-lg-6 left title_billing_serivce" style="padding-left: 0;"><strong>Ledger Summary</strong></div>
    <div class="col-lg-6">
        <button onclick="Customer_Edit.Next_Accouting()" class="btn btn-sm btn-primary pull-right" type="button"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
        <button onclick="Customer_Edit.Prev_Accouting()" style="margin-right: 5px;" class="btn btn-sm btn-primary pull-right" type="button"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
        <div class="dropdown keep-inside-clicks-open">
            <button class="btn btn-sm btn-primary pull-right btn_show_column dropdown_hover dropdown-toggle" type="button" data-toggle="dropdown" style="margin-left: 5px; margin-right: 5px;"><span class="glyphicon glyphicon-th"></span></button>
            <ul class="dropdown-menu pull-right select_show_column">
                <li class="dropdown-header">Choose columns displayed</li>
                <li>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="Date_Display_Preview" checked="checked" onchange="Customer_Edit.Display_Column_Accounting(this)" data-columnindex ="1" >
                        <label for="Date_Display_Preview"></label>
                    </div>
                    Date
                </li>
                <li>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="Type_Display_Preview" checked="checked" onchange="Customer_Edit.Display_Column_Accounting(this)" data-columnindex ="2" >
                        <label for="Type_Display_Preview"></label>
                    </div>
                    Type
                </li>
                <li>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="Service_Display_Preview" checked="checked" onchange="Customer_Edit.Display_Column_Accounting(this)" data-columnindex ="3" >
                        <label for="Service_Display_Preview"></label>
                    </div>
                    Service
                </li>
                <li>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="Record_Display_Preview" checked="checked" onchange="Customer_Edit.Display_Column_Accounting(this)" data-columnindex ="4" >
                        <label for="Record_Display_Preview"></label>
                    </div>
                    Record #
                </li>
                <li>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="Debit_Display_Preview" checked="checked" onchange="Customer_Edit.Display_Column_Accounting(this)" data-columnindex ="5" >
                        <label for="Debit_Display_Preview"></label>
                    </div>
                    Debit
                </li>
                <li>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="Credit_Display_Preview" checked="checked" onchange="Customer_Edit.Display_Column_Accounting(this)" data-columnindex ="6" >
                        <label for="Credit_Display_Preview"></label>
                    </div>
                    Credit
                </li>
                <li>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="Billing_Frequency_Display_Preview" onchange="Customer_Edit.Display_Column_Accounting(this)" data-columnindex ="7" >
                        <label for="Billing_Frequency_Display_Preview"></label>
                    </div>
                    Billing Frequency
                </li>
                <li>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="Route_Display_Preview" onchange="Customer_Edit.Display_Column_Accounting(this)" data-columnindex ="8" >
                        <label for="Route_Display_Preview"></label>
                    </div>
                    Route
                </li>
                <li>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="Technician_Display_Preview" onchange="Customer_Edit.Display_Column_Accounting(this)" data-columnindex ="9" >
                        <label for="Technician_Display_Preview"></label>
                    </div>
                    Technician
                </li>
                <li>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="Service_Type_Display_Preview" onchange="Customer_Edit.Display_Column_Accounting(this)" data-columnindex ="10" >
                        <label for="Service_Type_Display_Preview"></label>
                    </div>
                    Service Type
                </li>
                <li>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="Salesperson_Display_Preview" onchange="Customer_Edit.Display_Column_Accounting(this)" data-columnindex ="11">
                        <label for="Salesperson_Display_Preview"></label>
                    </div>
                    Salesperson
                </li>
                <li>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="Property_Type_Display_Preview" onchange="Customer_Edit.Display_Column_Accounting(this)" data-columnindex ="12">
                        <label for="Property_Type_Display_Preview"></label>
                    </div>
                    Property Type
                </li>
                <li>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="notes_Display_Preview" checked="checked" onchange="Customer_Edit.Display_Column_Accounting(this)" data-columnindex ="13">
                        <label for="notes_Display_Preview"></label>
                    </div>
                    Notes
                </li>
            </ul>
        </div>
    </div>
    
    <div class="clearfix"></div>
    <div class="table-responsive">              
        <table style="width:100%"  class="display table table-striped tbl_Accounting_Ecustomer" cellspacing="0">
            <thead>
                <tr>
                    <th></th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Service</th>
                    <th>Record #</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Billing Frequency</th>
                    <th>Route</th>
                    <th>Technician</th>
                    <th>Service Type</th>
                    <th>Salesperson</th>
                    <th>Property Type</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <input type="hidden" id="total_record_accounting" value="<?php echo $Total_accouting; ?>">
</div>

