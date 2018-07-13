<div class="Action_Selected">
    <div class="col-xs-6 title-left-action">
        <span>Select events and perform an action:</span>
        <span class="eventsAction"></span>
    </div>
    <div class="col-xs-6 title-right-action">
        <button type="button" class="btn btn-sm btn-primary" onclick="Mark_As_Complete.LoadEventComplete()">Mark as complete</button>
        <button type="button" class="btn btn-sm btn-primary">Print work orders</button>
        <button type="button" class="btn btn-sm btn-primary" onclick="Calendar.toggle_action()">Cancel</button>
    </div>
    <input type="hidden" class="action_selected_active" value="0" />
</div>
<div class="row">
    <div class="col-lg-5 col-lg-push-7 col-md-5 col-md-push-7">
        <div style="text-align:right">
            <div class="dropdown keep-inside-clicks-open" style="display: inline;">
                <button class="btn btn-sm btn-primary btn_action_calendar dropdown_hover dropdown-toggle" onclick="Calendar.toggle_action()" type="button" data-toggle="dropdown">Action on Selected</button>
                <!-- <ul class="dropdown-menu" style="margin-top: 9px;">
                    <li><a href="javascript:void(0)">Print work orders (Tickets)</a></li>
                    <li><a href="javascript:void(0)" onclick="Mark_As_Complete.LoadEventComplete()">Mark as complete (Return slip)</a></li>
                    <li><a href="javascript:void(0)">Delete</a></li>
                </ul> -->
            </div>
            <button type="button" class="btn_new_calendar btn btn-sm btn-primary"  onclick="Calendar.AddNewWorkOrder()">New Work Order</button>
            <button type="button" class="btn_miss_calendar btn btn-sm btn-primary" onclick="Work_Pool.LoadWorkPool()">Work Pool</button>
            <!-- <button type="button" class="btn_print_calendar btn btn-sm btn-primary"><i class="fa fa-print" aria-hidden="true"></i></button> -->
            <button type="button" class="btn_prev_calendar btn btn-sm btn-primary" onclick="Calendar.Slt_Date_Show('prev')"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
            <button type="button" class="btn_next_calendar btn btn-sm btn-primary" onclick="Calendar.Slt_Date_Show('next')"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
        </div>      
    </div>
    <div class="col-lg-4 col-lg-pull-5 col-md-4 col-md-pull-5">
        <button type="button" class="btn_day_calendar btn btn-sm btn-primary change_date_calendar"  onclick="Calendar.Slt_Date_Show('day')">Day</button>
        <button type="button" class="btn_week_calendar btn btn-sm btn-primary change_date_calendar active" onclick="Calendar.Slt_Date_Show('week')">Week</button>
        <button type="button" class="btn_month_calendar btn btn-sm btn-primary change_date_calendar" onclick="Calendar.Slt_Date_Show('month')">Month</button>
        <button type="button" class="btn_year_calendar btn btn-sm btn-primary change_date_calendar" onclick="Calendar.Slt_Date_Show('year')">Year</button>
    </div>
    <div class="col-lg-3 col-lg-pull-6 col-md-3 col-md-pull-5">
        <div class="fc-center-custom"></div>
    </div>
</div>     
<div class="row">
	<div class="col-lg-3 col-lg-push-9 col-md-3 col-md-push-9">
	    <div class="row FilterCalendar">

      	</div>
    </div>
    <div class="col-lg-9 col-lg-pull-3 col-md-9 col-md-pull-3">
        <div id='calendar_calendar'></div>
    </div>  
</div>
