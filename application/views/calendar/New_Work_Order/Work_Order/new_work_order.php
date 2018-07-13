<div id="wrap-close-overlay">
    <div class="col-lg-6 col-xs-6 title-left-close-overlay">
        <div class="DivParent">
           <div class="DivWhichNeedToBeVerticallyAligned">
              New Event
           </div><div class="DivHelper"></div>
        </div>
    </div>
    <div class="col-lg-6 col-xs-6 title-right-close-overlay" >
        <div style="text-align: right;">
           <button onclick="New_Work_Order.SaveCalendar(this)" type="button" class="btn btn-sm btn-primary">Save Event</button>
           <button type="button" class="btn btn-sm btn-primary" onclick="Js_Top.closeNav()"><i class="fa fa-times" aria-hidden="true" style="color:#fff"></i></button>
        </div>
    </div>
</div>
<div id="overlay-content" class="overlay-content">
    <div class="space"></div>
    <ul class="nav nav-tabs nav_parent_calendar" role="tablist">
        <li role="presentation" class="active"><a href="#workorder_calendar" data-toggle="tab" aria-controls="home" role="tab">Work Order</a></li>
        <li role="presentation"><a href="#genericevent_calendar" data-toggle="tab" aria-controls="profile" role="tab">Generic Event</a></li>
    </ul>
    <form id="form_new_customer_calendar" class="add_new_customer" action="<?php echo url::base() ?>calendar/save_calendar" type="POST">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="workorder_calendar">
                <div class="space"></div>
                <div class="col-lg-6 col-md-6 col-sm-6" style="padding-left: 5px;padding-right: 5px;">
                    <div class="Change_Work_Order_Radio" style="position: relative;top: 5px;">
                        <div class="custom-checkbox-radio" style="float: left;margin-right: 20px;">
                            <input onchange="New_Work_Order.ChangeDataEvent(this)" style="margin-left: 0;" name="check_customers_calendar" value="exitsing" checked="checked" type="radio"> 
                            <span style="font-size: 0.9em;position: relative;bottom: 4px;">Existing customer</span>
                        </div>
                        <div class="custom-checkbox-radio" style="float: left;">
                            <input onchange="New_Work_Order.ChangeDataEvent(this)" style="margin-left: 0;" name="check_customers_calendar" value="new" type="radio"> 
                            <span style="font-size: 0.9em;position: relative;bottom: 4px;">New customer</span>
                        </div>
                    </div>
                </div>    
                <div class="col-lg-6 col-md-6 col-sm-6" style="padding-left: 5px;padding-right: 5px;">
                    <div style="float: right;width: 100%;">
                        <input name="service_po_" type="text" class="form-control" placeholder="PO# (Auto-assigned if empty)" style="position: relative;bottom: 3px;">
                    </div>
                </div>
                <div id="wrap_chk_customers">
                    <div id="Tpl_chk_customers">

                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="genericevent_calendar">2</div>
        </div>
    </form>
</div>
