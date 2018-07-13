<div id="wrap-close-overlay">
    <div class="col-lg-6 col-xs-6 title-left-close-overlay">
        <div class="DivParent">
           <div class="DivWhichNeedToBeVerticallyAligned">
                Work Pool 
           </div>
           <div class="DivHelper"></div>
        </div>
    </div>
    <div class="col-lg-6 col-xs-6 title-right-close-overlay" >
        <div style="text-align: right;">
           <button type="button" class="btn btn-sm btn-primary" onclick="Js_Top.closeNav()"><i class="fa fa-times" aria-hidden="true" style="color:#fff"></i></button>
        </div>
    </div>
</div>
<div id="overlay-content" class="overlay-content">
    <p>
        The following are scheduled events (tickets) which have not been marked complete (missed) 
        or events which have been set for manual scheduling within a set frequency.  Drag 
        the events onto calendar or enter the time / date values to schedule the items. Click 
        items to see more details regarding the event.
    </p>
    <div class="row">
        <div class="col-lg-8" style="margin-bottom:10px;">
            <p><strong>Filter</strong></p>
            <table style="width:100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="width:30%">
                        <div class="input-group date">
                            <input readonly="readonly" type="text" class="form-control ">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </div>
                        </div>
                    </td>
                    <td style="width:5%;text-align: center;">
                        to
                    </td>
                    <td style="width:30%">
                        <div class="input-group date">
                            <input readonly="readonly" type="text" class="form-control ">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </div>
                        </div>
                    </td>
                    <td style="width:25%">
                        <select name="" class="form-control" style="float: right;width: 95%;">
                            <option value="">All technicians</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-lg-5">
            <table style="width:100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="width:25%"><button style="width: 95%" type="button" class="btn btn-sm btn-primary">This week</button></td>
                    <td style="width:25%"><button style="width: 95%" type="button" class="btn btn-sm btn-primary">This month</button></td>
                    <td style="width:25%"><button style="width: 95%" type="button" class="btn btn-sm btn-primary">7 days</button></td>
                    <td style="width:25%"><button style="width: 95%" type="button" class="btn btn-sm btn-primary">30 days</button></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12" style="margin-top: 10px;margin-bottom: 10px;">
            <table style="width:100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td>Drag and drop the items on the calendar to the left to quickly schedule them.</td>
                    <td><button style="width: 100%" type="button" class="btn btn-sm btn-primary">Action on Selected</button></td>
                </tr>
            </table> 
        </div>
        
        <div class="col-lg-12">
            <div class="table-responsive" style="overflow-x: inherit;">      
                 <table  class="display table tbl_work_pool" cellspacing="0" cellpadding="0" style="width:100%">
                    <thead>
                        <tr>
                            <th>
                                <div class="custom-checkbox">
                                    <input type="checkbox" name="select_all" id="select_all">
                                    <label style="margin-bottom: 4px !important;" for="select_all"></label>
                                </div>
                            </th>
                            <th>Type</th>
                            <th>Cus. No</th>
                            <th>Frequency</th>
                            <th>Original Slot</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>          
            </div>   
        </div>        
    </div>  
</div>
