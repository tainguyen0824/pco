<style type="text/css" media="screen">
/* Menu */
	.customers_menu{
		background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#4a69a7), to(#acccf5));
		color: #fff;
	}
/* End Menu */

/* Tab active and inactive */
	.tab-customer>li.active>a, .tab-customer>li.active>a:focus, .tab-customer>li.active>a:hover {
		color: #555;
		cursor: default;
		font-weight: bold;
		background-color: #cfd1d2;
		border: 1px solid #ddd;
		border-bottom-color: transparent;
	}
	.tab-customer>li>a {
		margin-right: 2px;
		line-height: 1.42857143;
		font-weight: bold;
		color:#555;
		border: 1px solid transparent;
		border-radius: 0px;
	}
/* End Tab active and inactive */

/*Table Active*/
	table.tbl_customer tbody>tr.selected{
		background-color: #d5dcea !important;
	}
	table.tbl_customer{
		background-color: #fff;
		margin-bottom: 0px !important;
	}
	table.tbl_customer tbody>tr>td:nth-child(1), 
	table.tbl_customer tbody>tr>td:nth-child(1)>input,
	table.tbl_customer thead>tr>th:nth-child(1), 
	table.tbl_customer thead>tr>th:nth-child(1)>input
	{
		cursor: pointer;
	}
	table.tbl_customer tbody tr td, table.tbl_inactive_customer tbody tr td{
		padding-top: 2px;
		padding-bottom: 2px;
		vertical-align: middle;
	}
	table.tbl_customer thead tr th{
		font-size: 0.9em;
		border-bottom: 0 !important;
	}
	.tbl_customer thead tr{
	    background: #a7bcd4;
	}
	.paging_simple_numbers, #unselect_all_btn_active,#unselect_all_btn_inactive,#Button_Unselect_All,.dataTables_filter,.dataTables_length{
		display: none;
	}
	.grid ,.actions,.select_all ,.btn_next_page_customers_active ,.btn_prev_page_customers_active ,.btn_print ,.btn_add_new ,.btn_next_inactive , .btn_prev_inactive{
		background: #337ab7;
		color:#fff;
	}
	.dataTables_info{
		padding: 0px;
		background-color: #a7bcd4;
		padding-left: 10px;
	}
	div.dataTables_wrapper div.dataTables_info{
		padding-top: 0px;
		text-align: left;
	}
	.close_contact_customers{
		position: absolute;
	    top: -7px;
	    right: -11px;
	}
	.tab-content-customers{
		background-color: #cfd1d2;
		overflow: hidden;
		padding-bottom: 13px;
	}

	.TES_active{
		background-color: #a7bcd4;
	    padding-left: 10px;
	}
	.TES_inactive{
		background-color: #a7bcd4;
	    padding-left: 10px;
	}
	div.dataTables_wrapper div.dataTables_processing{
	    background-color: transparent !important;
	    border: none !important;
	    border-radius: 0px !important;
	    box-shadow: none !important;
	}
	
	table.tbl_inactive_customer thead tr th{
		font-size: 0.9em
	}
	.select_show_column{
	    margin-top: 33px;
	    padding: 15px;
	}
/*End Table Active*/

/*Filter Table Active*/
	.badge {
		display: inline-block;
		min-width: 10px;
		padding: 3px 7px;
		font-size: 12px;
		font-weight: 700;
		line-height: 1;
		color: #fff;
		text-align: center;
		white-space: nowrap;
		vertical-align: middle;
		background-color: blue;
		border-radius: 10px;
	}
	.filter-by-type ul li , .filter-by-balance ul li ,.filter-by-technician ul li ,.filter-by-service-type ul li{
		list-style: none;
		margin-left: 15px;
	}
	.filter-by-type ul , .filter-by-balance ul ,.filter-by-technician ul , .filter-by-service-type ul{
		margin-left: -25px;
	}
	.title_filter{
		font-size: 17px;font-weight: bold;
	}
/*Filter Table Active*/

/*edit customers*/
	#wrap-edit-vitem-invoice{
	    -webkit-transition: all 0.5s ease;
	    -moz-transition: all 0.5s ease;
	    -o-transition: all 0.5s ease;
	    transition: all 0.5s ease;
	}
	.close_full_sreen_accounting{
		display: none;
	}
	#wrap-edit-title-customers{
		margin-bottom: 5px;
	}
	#wrap_edit_customer{
		overflow: hidden;
	    padding-top: 7px;
	}
	.edit_billing_info{
		border: 1px solid #000;
		overflow: hidden;
	    padding: 10px;
	    padding-left: 0px;
	    margin-bottom: 5px;
	    min-height: 200px;
	}
	.edit-vitem-invoice{
		border: 1px solid #000;
	    margin-right: 10px;
	    padding: 5px;
	    padding-top: 0;
	    margin-bottom: 5px;
	}
	#wrap-edit-vitem-invoice div:nth-child(3) .edit-vitem-invoice{
		margin-right: 0;
	}
	.edit-vitem-invoice p:nth-child(1){
		text-align: left;
	}
	.edit-vitem-invoice p:nth-child(2){
		text-align: right;
		font-size: 1.5em;
	}
	#edit-notes-content{
		border: 1px solid #000;
	    height: 150px;
	    background-color: #fff;
	}
	#wrap-edit-notes{
		background-color: #cfd1d2;
	    padding: 10px;
	    margin-bottom: 5px;
	    padding-top: 5px
	}
	#edit-notes-form{
		overflow: hidden;
	    margin-top: 5px;
	}
	#edit-notes-form p:nth-child(1){
		float: left;
		width: 90%;
	}
	#edit-notes-form p:nth-child(2){
		float: right;
		text-align: right;
		width: 10%;
	}
	#edit-notes-form p:nth-child(2) button{
		margin-top: 2px
	}
	#wrap_tab_edit{
		background-color: #cfd1d2;
	    padding: 10px;
	}
	#wrap_tab_edit ul li a{
		color: #000;
		font-weight: bold;
	}
	.wrap_tab_edit_content{
		background-color: #fff;
		color: #000;
		padding: 5px;
	}
/*end edit customers*/

/*Add Edit Customer*/
	.fieldset_1 .form-group{
		margin-bottom: 5px;
	}
	.fieldset_2, .fieldset_3{
		display: none;
	}
	#loading_add_contacts{
		float: left;
	    margin-top: 10px;
	    clear: both;
	    display: none;
	}
	.space_finish{
		padding-top: 10px;
	}
	.tab_service_customer>li.active>a, .tab_service_customer>li.active>a:focus, .tab_service_customer>li.active>a:hover {
	    color: #555;
	    cursor: default;
	    background-color: #cfd1d2;
	    border: 1px solid #ddd;
	    font-weight: bold;
	    border-bottom-color: transparent;
	}
	.tab_service_customer>li>a {
	    margin-right: 2px;
	    line-height: 1.42857143;
	    border: 1px solid transparent;
	    border-radius: 0px;
	    font-weight: bold;
	    color: #555;
	}
	.content-sub-tabcontent-customers{
		background-color: #fff;
	}

	.save_line_item_customers{
		float: right;
		padding-bottom: 0.5em;
	}
	.save_line_item_customers button{
		font-size: 0.96em;
	}
	.tab_content_service{
		background-color: #cfd1d2;
		overflow: hidden;
	}
	
	#wrap_add_phone{
		overflow: hidden;
		clear: both;
	}
	.right_customers{
		text-align: right;
	}
	.Amount_Commission {
	    padding-right: 30px;
	}
	
	
	table.service_group tbody tr td{
		vertical-align: middle;
		font-size: 13px;
	}
	
	
	.calendar_preview_customers_today .fc-view-container{
		overflow-x: auto;
		overflow-y: auto;  
		min-height: 358px;
	    max-height: 408px;
	}
	.calendar_preview_customers_today .fc-center h2{
		font-size: 20px;
	}
	.calendar_preview_customers_today .fc-toolbar{
		margin-bottom: 0;
	}
	
	.tabs_footer>.nav-tabs>li.active>a, .tabs_footer>.nav-tabs>li.active>a:focus, .tabs_footer>.nav-tabs>li.active>a:hover {
	    color: #555;
	    cursor: default;
	    font-weight: bold;
	    background-color: #fff;
	    border: 1px solid #ddd;
	    border-bottom-color: transparent;
	}
	.tabs_footer>.nav-tabs>li>a {
	    margin-right: 2px;
	    line-height: 1.42857143;
	    border: 1px solid transparent;
	    border-radius:0px;
	    font-weight: bold;
	    color: #555;
	}
	.tbl_inactive_customer thead tr{
	    background: #a7bcd4;
	}

	.select_show_column li.dropdown-header{
	    margin-left: -20px;
	}
/*End Edit Add Customer*/

@media (max-width: 575px) { 
	/*Table active*/
		.responsive-customers-active{
			width: 100%;
			border: 1px solid #000;
			margin-top: 10px;
		}
		.responsive-customers-inactive{
			width: 100%;
			border: 1px solid #000;
		}
	/*End Table Active*/

	/*Tab*/
		ul.tab_service_customer li{
			background-color: #cfd1d2;
			width: 50%;
	    	margin-bottom: 5px;
	    	float: left;
	    	border: 1px solid #fff;
		}
		ul.tab_service_customer li:last-child{
			background-color: #fff;
			width: 100%;
	    	margin-bottom: 5px;
		}
	/*End Tab*/

	/* edit customers*/
		.wrap_edit_info_invoce{
		    padding-right: 0;
		}
		.edit-vitem-invoice{
			margin-right: 0;
		}
		#edit-notes-form p:nth-child(1){
			float: left;
			width: 82%;
		}
		#edit-notes-form p:nth-child(2){
			float: right;
			text-align: right;
			width: 17%;
		}
		#wrap_tab_edit ul li{
			margin-left: 0;
			width: 100%;
		}
		#wrap_tab_edit ul li:nth-child(5){
			margin-left: -1px;
		}
	/*end edit customers*/

	.add_customer{
		margin-left: 5px;
		padding-right: 0px;
		margin-bottom: 5px;
	}
	.search-box{
		margin-bottom: 5px;
	}
	
	.right_customers{
		text-align: left;
	}
	
	.save_line_item_customers{
		float: left;
	}	
}
@media (max-width: 767px) { 
	.table-responsive{
		margin-bottom: 0px !important;
	}
}
@media (min-width: 576px) and (max-width: 767px) { 
	/*Tab*/
		ul.tab_service_customer li{
			background-color: #cfd1d2;
			width: 50%;
	    	margin-bottom: 5px;
	    	float: left;
	    	border: 1px solid #fff;
		}
		ul.tab_service_customer li:last-child{
			background-color: #fff;
			width: 100%;
	    	margin-bottom: 5px;
		}
	/*End Tab*/

	/*edit customers*/
		.wrap_edit_info_invoce{
		    padding-right: 0;
		}
		.edit-vitem-invoice{
			margin-right: 0;
		}
		#wrap_tab_edit ul li{
			margin-left: 0;
			width: 100%;
		}
		#wrap_tab_edit ul li:nth-child(5){
			margin-left: -1px;
		}
	/*end edit customers*/

	.right_customers{
		text-align: left;
	}
	.save_line_item_customers{
		float: left;
	}
}
@media (min-width: 768px) and (max-width: 991px) { 
	.input_Website , .input_Notes , .sl_Property , .sl_Service_type , .sl_Route , .sl_Salesperson{
		margin-bottom: 5px;
	}

	.search-box{
		margin-bottom: 5px;
	}

	.right_customers{
		text-align: left;
	}

	/*edit customers*/
	.wrap_edit_info_invoce{
	    padding-right: 0;
	}
	.edit-vitem-invoice{
		margin-right: 0;
	}
	/*end edit customers*/
}
</style>	