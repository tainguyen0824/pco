<div class="space"></div>
<div class="col-lg-2 col-md-2 col-sm-2 filter-right col-sm-push-10 col-md-push-10">
    <div class="filter-by-type">
        <span class="title_filter">Filter by Type</span>
        <ul>
            <div class="custom-checkbox">
                <input type="checkbox" checked onchange="Sales.Chk_Show_All_Type()" id="showAllType">
                <label for="showAllType" style="font-weight: normal;">Show all</label>
            </div>
            <?php if(!empty($ArrCustomerType)): ?>
                <?php foreach ($ArrCustomerType as $key => $CustomerType): ?>
                    <li>
                        <div class="custom-checkbox">
                            <input type="checkbox" checked onchange="Sales.SubFilterType()" class="SubFilterType" id="<?php echo !empty($CustomerType['customer_type'])?$CustomerType['customer_type']:'NULL'; ?>" value="<?php echo !empty($CustomerType['customer_type'])?$CustomerType['customer_type']:'NULL'; ?>">
                            <label for="<?php echo !empty($CustomerType['customer_type'])?$CustomerType['customer_type']:'NULL'; ?>" style="font-weight: normal;"><?php echo !empty($CustomerType['customer_type'])?$CustomerType['customer_type']:'NULL'; ?> <span class="badge"><?php echo $CustomerType['total'] ?></span></label>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif;; ?>
        </ul>
    </div>
    <div class="filter-by-balance">
        <span class="title_filter">Filter by Balance</span>
        <ul>
            <div class="custom-checkbox">
                <input checked onchange="Sales.Chk_Show_All_Balance()" type="checkbox" id="showAllBalance">
                <label for="showAllBalance" style="font-weight: normal;">Show all</label>
            </div>
            <li>
                <div class="custom-checkbox">
                    <input checked type="checkbox" onchange="Sales.SubFilterType()" class="subBalance" id="outstanding_balance" value="bigger_0">
                    <label for="outstanding_balance" style="font-weight: normal;">Estimate Active</label>
                </div>
            </li>
            <li>
                <div class="custom-checkbox">
                    <input checked type="checkbox" onchange="Sales.SubFilterType()" class="subBalance" id="no_balance" value="equal_0">
                    <label for="no_balance" style="font-weight: normal;">Expired</label>
                </div>
            </li>
            <li>
                <div class="custom-checkbox">
                    <input checked type="checkbox" onchange="Sales.SubFilterType()" class="subBalance" id="blance_owed"  value="less_0">
                    <label for="blance_owed" style="font-weight: normal;">Work Order Created</label>
                </div>
            </li>
        </ul>
    </div>
</div>  

<div class="col-lg-10 col-md-10 col-sm-10 col-sm-pull-2 col-md-pull-2 ">
    <div>
        <div class="col-lg-6 col-md-6 search-box padding_zero">
            <div class="inner-addon left-addon">
                <i class="glyphicon glyphicon-search"></i>
                <input onchange="Sales.Seach(this)" type="text"  placeholder="Search" class="form-control Search_Estimates">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 padding_zero">   
            <div class="dropdown keep-inside-clicks-open">
                <button class="btn btn-sm btn-primary pull-right btn_show_column dropdown_hover dropdown-toggle" type="button" data-toggle="dropdown"><span class="glyphicon glyphicon-th"></span></button>
                <ul class="dropdown-menu pull-right select_show_column" style="margin-top: 30px;">
                    <li class="dropdown-header">Choose columns displayed</li>
                    <li role="presentation"><div class="custom-checkbox"><input onchange="Sales.showHideColumn(this)" type="checkbox" checked="checked" data-columnindex ="1" id="Clo_Active_1"><label for="Clo_Active_1"></label> Customer No.</div></li>
                    <li role="presentation"><div class="custom-checkbox"><input onchange="Sales.showHideColumn(this)" type="checkbox" checked="checked" data-columnindex ="2" id="Clo_Active_2"><label for="Clo_Active_2"></label> Customer Name / Billing Address.</div></li>
                    <li role="presentation"><div class="custom-checkbox"><input onchange="Sales.showHideColumn(this)" type="checkbox" checked="checked" data-columnindex ="3" id="Clo_Active_3"><label for="Clo_Active_3"></label> Billing Contact</div></li>
                    <li role="presentation"><div class="custom-checkbox"><input onchange="Sales.showHideColumn(this)" type="checkbox" checked="checked" data-columnindex ="4" id="Clo_Active_4"><label for="Clo_Active_4"></label> E-mail</div></li>
                    <li role="presentation"><div class="custom-checkbox"><input onchange="Sales.showHideColumn(this)" type="checkbox" checked="checked" data-columnindex ="5" id="Clo_Active_5"><label for="Clo_Active_5"></label> Service Address</div></li>
                    <li role="presentation"><div class="custom-checkbox"><input onchange="Sales.showHideColumn(this)" type="checkbox" checked="checked" data-columnindex ="6" id="Clo_Active_6"><label for="Clo_Active_6"></label> Blance</div></li>
                </ul>
            </div>
            <div class="dropdown keep-inside-clicks-open">
                <button class="btn btn-sm btn-primary pull-right btn_actions dropdown_hover dropdown-toggle" type="button" data-toggle="dropdown" style="margin-left: 5px; margin-right: 5px;" >Actions <span class="caret"></span></button>
                <ul class="dropdown-menu pull-right select_actions" style="margin-top: 30px;margin-right: 43px;">
                    <li onclick="Sales.Delete()"><a href="javascript:void(0)">Delete</a></li>
                    <li class="dropdown">
                        <a href="javascript:void(0)">Export <i class="fa fa-caret-right" aria-hidden="true" style="float:right"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0)" onclick="Sales.PrintPDF()">PDF</a></li>
                            <li><a href="javascript:void(0)">CSV</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <button onclick="Sales.Btn_Select_All()" type="button" class="btn btn-sm btn-primary pull-right btn_select_all" id="Button_All">Select All</button>
            <button onclick="Sales.Btn_Unselect_All()" type="button" class="btn btn-sm btn-primary pull-right btn_select_all" id="Button_Unselect_All">Unselect All</button>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="table-responsive" style="overflow-x: inherit;">          
        <form id="Frm_Estimates" method="POST">
            <input type="hidden" name="Type_Get_IDReport" value="selected">
            <div id="wap_Estimates_id"></div>
            <table  class="display table tbl_Estimates" cellspacing="0" cellpadding="0" style="width:100%">
                <thead>
                    <tr>
                        <th>
                            <div class="custom-checkbox">
                                <input type="checkbox" name="select_all" id="select_all">
                                <label style="margin-bottom: 4px !important;" for="select_all"></label>
                            </div>
                        </th>
                        <th>Customer No.</th>
                        <th>Customer Name / Billing Address</th>
                        <th>Billing Contact</th>
                        <th>E-mail</th>
                        <th>Service Address</th>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </form>
    </div>
</div>
<input type="hidden" id="TotalRecord" value="<?php echo !empty($total_record)?$total_record:0 ?>">
<?php echo $js; ?>
