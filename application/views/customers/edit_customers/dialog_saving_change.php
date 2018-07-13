<div class="EditCustomers" title="Saving Changes">
    <p style="font-size: 12px;font-weight: bold;color: #000;">
	  You have made changes to this service group. How would you like to apply the changes to associated future events? This will overwrite any individual changes made at event-level that fall within the timeframe.
	</p>
    <div class="custom-checkbox-radio" style="margin-top: 5px;">
        <input onchange="Customer_Edit.SaveChangeEvents(1)" value="1" name="ChangeDateService" class="ChangeDateService" type="radio" checked="checked" style="margin-left:0"> 
        <span style="position: relative;bottom: 5px;font-size: 13px;">
            Apply immediately to all future events
        </span>
    </div>
    <div class="custom-checkbox-radio" style="margin-top: 5px;">
        <input onchange="Customer_Edit.SaveChangeEvents(2)" value="2" name="ChangeDateService" class="ChangeDateService" type="radio" style="margin-left:0"> 
        <span style="position: relative;bottom: 5px;font-size: 13px;">
            All events on and after:
        </span>
        <div class="clearfix"></div>
        <div class="timeEventAfter col-lg-8" style="display:none">
            <div class="input-group date">
                <input style="background-color: #fff;" readonly="readonly" type="text" class="form-control eventAfterDate limit_min_datepicker">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </div>
            </div>
            <div class="input-group date" style="margin-top: 5px;">
                <input style="background-color: #fff;" readonly="readonly" type="text" class="form-control eventAfterTime timepickerssss" placeholder="hh:mm">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                </span>
            </div>
        </div>
    </div>
</div>