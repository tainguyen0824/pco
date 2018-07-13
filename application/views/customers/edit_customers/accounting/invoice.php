<div id="wrap-close-overlay">
    <div class="col-lg-6 col-xs-6 title-left-close-overlay">
        <div class="DivParent">
           <div class="DivWhichNeedToBeVerticallyAligned">
              Add Invoice
           </div><div class="DivHelper"></div>
        </div>
    </div>
    <div class="col-lg-6 col-xs-6 title-right-close-overlay" >
        <div style="text-align: right;">
           <button onclick="Customer_Edit.Save_Invoice(<?php echo !empty($customer_id)?$customer_id:''; ?>)" type="button" class="btn btn-sm btn-primary">Save Changes</button>
           <button onclick="Customer_Edit.Close_Invoice_Payment_Accouting(<?php echo !empty($customer_id)?$customer_id:''; ?>)" type="button" class="btn btn-sm btn-primary">Discard Changes</button>
        </div>
    </div>
</div>
<div id="overlay-content" class="overlay-content">
    <form method="POST" id="Frm_E_Invoice" accept-charset="utf-8">
        <input type="hidden" name="ID_customers_accounting" value="<?php echo !empty($Customers_Accounting[0]['id'])?$Customers_Accounting[0]['id']:''; ?>" />
        
        <div class="col-lg-12" style="background: #f1f1f1;padding-top: 5px;padding-bottom: 10px;margin-bottom: 10px;">
            <div class="col-lg-7 padding_zero">
                <div class="col-lg-12 left title_billing_serivce" style="padding-left: 0;"><strong>Billing Information</strong></div>
                <div class="col-lg-2 col-md-2 right_customers padding_zero_left"> <span>Bill to</span></div>
                <div class="col-lg-10 col-md-10 padding_zero">
                    <select onchange="Customer_Edit.BillToInvoice(this)" name="bill_to" class="form-control">
                        <option value="">-----None-----</option>
                        <?php 
                            if(!empty($Arr_Bill_To)): 
                                foreach ($Arr_Bill_To as $key => $value):
                        ?>
                            
                                <?php 
                                    if($value['Type_Name'] == 'billing'):
                                        $B_address = !empty($value['billing_address_1'])?' - '.$value['billing_address_1']:'';
                                        echo '<option '.(('Billing_'.$value['id'] == $Bill_To)?'selected="selected"':"").' value="Billing_'.$value['id'].'">Billing Address'.$B_address.'</option>';
                                    elseif($value['Type_Name'] == 'service'):
                                        $S_name = !empty($value['service_name'])?$value['service_name']:'';
                                        $S_address = !empty($value['service_address_1'])?' - '.$value['service_address_1']:'';
                                        echo '<option '.(('Service_'.$value['service_id'] == $Bill_To)?'selected="selected"':"").' value="Service_'.$value['service_id'].'">'.$S_name.$S_address.'</option>';
                                    elseif($value['Type_Name'] == 'contact'):
                                        $C_name = !empty($value['contact_name'])?$value['contact_name']:'';
                                        $C_address = !empty($value['contact_address_1'])?' - '.$value['contact_address_1']:'';
                                        echo '<option '.(('Contact_'.$value['id'] == $Bill_To)?'selected="selected"':"").' value="Contact_'.$value['id'].'">'.$C_name.$C_address.'</option>';
                                    endif; 
                                ?>
                            
                        <?php  
                                endforeach;
                            endif;
                        ?>
                    </select>
                </div>
                <div id="wap_Billing_Invoice"></div>
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

        <div class="main_service_"></div>
        
    </form>       
</div>

