<div id="existing_customers_calendar">
    <div class="space"></div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="padding-left: 5px;padding-right: 5px;">
        <div class="inner-addon left-addon">
            <i class="glyphicon glyphicon-search"></i>
            <input name="customer_name" id="autocomplete" type="text" placeholder="Customer Name" class="form-control" />
        </div>
    </div>
    <div class="space"></div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="padding-left: 0px;padding-right: 0px;">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="padding-left: 5px;padding-right: 5px;">
            <div style="height: 100px;border: 1px solid #000;margin-bottom: 6px;margin-top: 3px;background-color: #fff;" class="Wap_Billing_Address">
                <div style="padding-left: 5px;"><strong style="font-size: 0.9em;">Billing Address</strong></div>    
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="padding-left: 5px;padding-right: 5px;">
            <div style="height: 100px;border: 1px solid #000;margin-bottom: 4px;margin-top: 3px;background-color: #fff;" class="Wap_Service_Address">
                <div style="float:left;padding-left: 5px;"><strong style="font-size: 0.9em;">Service Address</strong></div>
                <div style="text-align: right;" class="dropdown">
                    <a class="dropdown-toggle icon" style="text-decoration: none;padding-right: 5px;" data-toggle="dropdown" href="javascript:void(0)" data-original-title="" title="" aria-expanded="false">
                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                    </a>
                    <ul class="dropdown-menu caret_service_existing_calendar" style="right: 1%;left:auto !important;padding: 0;border: 1px solid #000;border-bottom: 0;border-radius: 0;font-size: 0.82em;">
                        <li style="padding: 0;border-bottom: 1px solid #000;">
                            <a href="javascript:void(0)" style="padding: 5px;"> No Data </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div style="clear:both"></div>
    <div class="col-lg-6 col-md-6 col-sm-6" style="padding-left: 5px;padding-right: 5px;">
        <table style="width: 100%;margin-top:5px">
            <tr>
                <td style="font-weight: bold;width:26%">Service Type</td>
                <td>
                    <select name="service_service_type_" class="form-control">
                        <option value="">------</option>     
                        <?php if(!empty($Service_type)): ?>
                            <?php foreach ($Service_type as $key => $value): ?>
                                <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>     
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
            </tr>
        </table>
        <table style="width: 100%;margin-top:5px">
            <tr>
                <td style="font-weight: bold;width:26%">Service Name</td>
                <td>
                    <input name="service_name_" type="text" class="form-control" value="Service 1">
                </td>
            </tr>
        </table>
    </div>
    <div class="clearfix"></div>
    <div class="main_service_">
        
    </div>
</div>
