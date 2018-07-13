<style type="text/css" media="screen">
/* Menu */
	.calendar_menu{
		background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#4a69a7), to(#acccf5));
		color: #fff;
	}
/* End Menu */
/*Action Sleected*/
.Action_Selected{
    overflow: hidden;
    position: fixed;
    background-color: #d6f3f5;
    z-index: 99999999;
    width: 100%;
    top: 0px;
    padding: 10px;
    right: 0px;
    display: none;
}
.title-left-action{
    padding: 0;
    line-height: 30px;
}
.title-right-action{
    text-align: right;
}
.borderEvent{
	border: 2px solid #000 !important;
}
/*End Action Sleected*/
.fc-event{
    cursor: pointer;
}
.wrap_filter_calendar{
	padding: 5px;color: #fff;font-weight: bold;
}

#calendar{
	background-color: #fff
}
.fc-agendaDay-button, .fc-agendaWeek-button, .fc-month-button, .fc-year-button{
	text-transform: capitalize;
}
th.fc-day-header{
	padding-bottom: 10px !important;
	padding-top: 10px !important;
}
.fc-slats{
	background-color: #fff;
}
.fc-center-custom{
	font-size: 1.7em;
	font-weight: bold;
	text-align: center;
}
.fc-toolbar{
	display: none;
}
.fc-view-container{
	margin-top: 1em;
}
.fc-year-monthly-name{
	border: 1px solid #ddd;
	border-bottom: 0;
}
.fc-time-grid .fc-slats td {
    height: 3.5em;
}

.wrap_content_calendar{
	padding-top: 10px;
}
.tab-content{
	background-color: #cfd1d2;
	overflow: hidden;
}
.title-sub-tabcontent-calendar>li.active>a{
	background-color: #fff;
}
.title-sub-tabcontent-calendar{
	padding-right: 15px;
	padding-left: 15px;
}

.nav_parent_calendar>li.active>a,.nav_parent_calendar>li.active>a:focus, .nav_parent_calendar>li.active>a:hover, .nav_parent_calendar>li>a:hover{
	color: #000;
    cursor: default;
    background-color: #cfd1d2;
    border: 1px solid #ddd;
    border-bottom-color: transparent;
    font-weight: bold;
}


.wrap_billing_service{
	width: 100%;background-color: #fff;overflow: hidden;padding:15px;
}
.wrap_billing_service .form-group{
	margin-bottom: 5px;
}
.wrap_billing_service{
	margin-top: 5px;
}

.wrap_service_address{
	width: 100%;background-color: #fff;overflow: hidden;padding:15px;
}
.wrap_service_address .form-group{
	margin-bottom: 5px;
}
.wrap_service_address{
	margin-top: 5px;
}

.wrap_billing_address{
	width: 100%;background-color: #fff;overflow: hidden;padding:15px;
}
.wrap_billing_address .form-group{
	margin-bottom: 5px;
}
.wrap_billing_address{
	margin-top: 5px;
}

#filter_zip,#filter_city{
	display: none;
	margin-left: 20px;
}

.overlay-calendar{
	background: url(../public/images/loading_48.gif) no-repeat top center;
    background-size: inherit;
    position: absolute;
    width: 99%;
    height: 100%;
    background-color: rgb(255, 255, 255);
    z-index: 99999;
}

.overlay-calendar-filter{
	background-size: inherit;
    position: absolute;
    width: 99%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.68);
    z-index: 99999;
    display: none;
}

@media (max-width: 575px) { 
	.Change_Work_Order_Radio{
		top:0 !important;
	}
	.btn_new_calendar{
		width: 100%;
		margin-top: 5px;
	}
	.btn_miss_calendar{
		width: 100%;
		margin-top: 5px;
	}
	.btn_action_calendar{
		width: 100%;
	}
	.btn_print_calendar{
		width: 32%;
		margin-top: 5px;
	}
	.btn_prev_calendar{
		width: 32%;
		margin-top: 5px;
	}
	.btn_next_calendar{
		width: 32%;
		margin-top: 5px;
	}
	.btn_day_calendar{
		margin-top: 5px;
	}
	.btn_week_calendar{
		margin-top: 5px;
	}
	.btn_month_calendar{
		margin-top: 5px;
	}
	.btn_year_calendar{
		margin-top: 5px;
	}
	.save_line_item_calendar{
		float: left;
	}
	.phone_billing_calendar{
		float:left;width:49%
	}

	.ext_phone_billing_calendar{
		float:left;width:50%
	}

	.position_phone_billing_calendar{
		float:left;width:49%;margin-top: 5px;
	}

	.primary_phone_billing_calendar{
		float:left;width:50%;text-align: left;padding-top: 5px;margin-top: 5px;
	}
	.city_billing_calendar{
		float:left;width:49%
	}
	.state_billing_calendar{
		float:left;width:50%
	}
	.zip_billing_calendar{
		float:left;width:49%;margin-top: 5px;
	}
	.county_billing_calendar{
		float:left;width:50%;margin-top: 5px;
	}
	.space_calendar{
		float:left;
		width:1%
	}
	.space_calendar_zip{
		float: left;
		width: 0;
	}
	.right_calendar{
		text-align: left;
	}

	/*tab*/
	ul.title-sub-tabcontent-calendar{
	    padding-right: 0px; 
	    margin-right: -3px;
	}
	ul.title-sub-tabcontent-calendar li{
		background-color: #fff;
	    width: 100%;
	    border: 0;
	    border-radius: 0;
	    margin-left: -9px !important;
	    margin-bottom: 5px;
	}
	ul.title-sub-tabcontent-calendar li a{
	    border: 0;
	    border-radius: 0;
	}
	.title-sub-tabcontent-calendar>li.active>a, .title-sub-tabcontent-calendar>li.active>a:focus, .title-sub-tabcontent-calendar>li.active>a:hover, .title-sub-tabcontent-calendar>li>a:hover{
		color: #000;
	    cursor: default;
	    background-color: #f1f1f1;
	    border: 0;
	    border-bottom-color: transparent;
	}
	/*End tab */
}

/*Work Pool*/
.tbl_work_pool thead tr{
	background: #a7bcd4;
}
/*Woek Pool*/
@media (min-width: 576px) and (max-width: 767px) { 
	.Change_Work_Order_Radio{
		top:0 !important;
	}
	.sl_template_service{
		padding:0px;
	}
	.btn_close{
		width: 26px;height: 25px;position: absolute;right: 3px;top: -133px;margin-top: 5px;
	}
	.billing_content h3{
		padding-top:10px;text-align:left;
	}
	.billing_content select{
		width:70%;text-align:left;padding:0px;
	}
	.title_billing_frequency{
		text-align: left;padding-bottom: 5px;
	}
	.line-item{
		padding-top: 10px;margin-top: 10px;background: #f1f1f1;
	}
	.title_service_name{
		text-align:left;
		padding:0px;
	}
	.input_PO_service_name{
		margin-right:0px;padding:0px;
	}
	.title_PO_service_name{
		padding:0px;text-align:left;
	}
	.sl_office_customer{
		padding-left: 15px;
		margin-top: 5px;
	}
	.input_service_name{
		padding:0px;
	}

	.filter-right{
		margin-left: 15px;
	}
	.search-box{
		margin-bottom: 5px;
	}
	.btn_day_calendar{
		margin-top: 5px;
	}
	.btn_week_calendar{
		margin-top: 5px;
	}
	.btn_month_calendar{
		margin-top: 5px;
	}
	.btn_year_calendar{
		margin-top: 5px;
	}
	.save_line_item_calendar{
		float: left;
	}
	.right_calendar{
		text-align: left;
	}
}

@media (min-width: 768px) and (max-width: 991px) { 
	.btn_day_calendar{
		margin-top: 5px;
	}
	.btn_week_calendar{
		margin-top: 5px;
	}
	.btn_month_calendar{
		margin-top: 5px;
	}
	.btn_year_calendar{
		margin-top: 5px;
	}
	.right_calendar{
		text-align: left;
	}
}

@media (min-width: 1200px){
	.container {
		margin-left: 20px;
		margin-right: 20px;
	}
}

@media (min-width: 992px) and (max-width: 1199px) { 
	.btn_new_calendar{
		width: 34%;
	}
	.btn_miss_calendar{
		width: 25%;
	}
	.btn_print_calendar{
		width: 33%;
		margin-top: 5px;
	}
	.btn_prev_calendar{
		width: 32%;
		margin-top: 5px;
	}
	.btn_next_calendar{
		width: 32%;
		margin-top: 5px;
	}	
}
</style>