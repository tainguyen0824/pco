<style type="text/css" media="screen">
/* Menu */
	.sales_menu{
		background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#4a69a7), to(#acccf5));
		color: #fff;
	}
/* End Menu */

/* Tab active and inactive */
	.tab-sales>li.active>a, .tab-sales>li.active>a:focus, .tab-sales>li.active>a:hover {
		color: #555;
		cursor: default;
		font-weight: bold;
		background-color: #cfd1d2;
		border: 1px solid #ddd;
		border-bottom-color: transparent;
	}
	.tab-sales>li>a {
		margin-right: 2px;
		line-height: 1.42857143;
		font-weight: bold;
		color:#555;
		border: 1px solid transparent;
		border-radius: 0px;
	}
	.grid ,.actions,.select_all ,.btn_next_page,.btn_prev_page,.btn_print ,.btn_add_new{
		background: #337ab7;
		color:#fff;
	}
	#Button_Unselect_All{
		display: none;
	}
	.tab-content-sales{
		background-color: #cfd1d2;
		overflow: hidden;
		padding-bottom: 13px;
	}
	.title_filter{
		font-size: 17px;font-weight: bold;
	}
	.filter-by-type ul li , .filter-by-balance ul li{
		list-style: none;
		margin-left: 15px;
	}
	.filter-by-type ul , .filter-by-balance ul{
		margin-left: -25px;
	}
/* End Tab active and inactive */

/*Estimates*/
	table.tbl_Estimates tbody>tr.selected{
		background-color: #d5dcea !important;
	}
	table.tbl_Estimates{
		background-color: #fff;
		margin-bottom: 0px !important;
	}
	table.tbl_Estimates tbody>tr>td:nth-child(1), 
	table.tbl_Estimates tbody>tr>td:nth-child(1)>input,
	table.tbl_Estimates thead>tr>th:nth-child(1), 
	table.tbl_Estimates thead>tr>th:nth-child(1)>input
	{
		cursor: pointer;
	}
	table.tbl_Estimates tbody tr td, table.tbl_inactive_customer tbody tr td{
		padding-top: 2px;
		padding-bottom: 2px;
		vertical-align: middle;
	}
	table.tbl_Estimates thead tr th{
		font-size: 0.9em;
		border-bottom: 0 !important;
	}
	.tbl_Estimates thead tr{
	    background: #a7bcd4;
	}
	input.Search_Estimates{
		margin-bottom: 5px;
	}
/*End Estimates*/
</style>