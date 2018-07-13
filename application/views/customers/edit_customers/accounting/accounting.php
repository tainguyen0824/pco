<div class="col-lg-12 padding_zero" style="min-height: 345px;">
	<div>
        <button onclick="Customer_Edit.Next_Accouting()" class="btn btn-sm btn-primary pull-right" type="button"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
        <button onclick="Customer_Edit.Prev_Accouting()" style="margin-left: 5px;margin-right: 5px;" class="btn btn-sm btn-primary pull-right" type="button"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
        <button onclick="Customer_Edit.Full_Sreen_Accounting()" class="btn btn-sm btn-primary pull-right full_sreen_accounting"  type="button"><i class="fa fa-chevron-up" aria-hidden="true"></i></button>
        <button onclick="Customer_Edit.Close_Full_Sreen_Accounting()" class="btn btn-sm btn-primary pull-right close_full_sreen_accounting"  type="button"><i class="fa fa-chevron-down" aria-hidden="true"></i></button>
    </div>
    <div class="dropdown keep-inside-clicks-open">
        <button class="btn btn-sm btn-primary pull-right btn_show_column dropdown_hover dropdown-toggle" type="button" data-toggle="dropdown" style="margin-left: 5px; margin-right: 5px;"><span class="glyphicon glyphicon-th"></span></button>
        <ul class="dropdown-menu pull-right select_show_column" style="height: 310px;">
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

    <div class="dropdown keep-inside-clicks-open">
        <button class="btn btn-primary btn-sm pull-right btn_show_column dropdown_hover dropdown-toggle" type="button" data-toggle="dropdown" style="margin-left: 5px;">New Entry <i class="fa fa-chevron-down" aria-hidden="true"></i></button>
        <ul class="dropdown-menu pull-right select_show_column" style="right: 115px;">
            <li style="cursor:pointer" onclick="Customer_Edit.Payment_Accounting(<?php echo !empty($customer_id)?$customer_id:''; ?>,'add')">Payment</li>
            <li style="cursor:pointer" onclick="Customer_Edit.Invoice_Accounting(<?php echo !empty($customer_id)?$customer_id:''; ?>,'','add')">Invoice</li>
        </ul>
    </div>
    <div class="clearfix"></div>
    <div class="table-responsive">       
        <table style="width:100%" class="display table table-striped tbl_Accounting_Ecustomer" cellspacing="0">
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
</div>
<input type="hidden" id="total_record_accounting" value="<?php echo $Total_accouting; ?>">
