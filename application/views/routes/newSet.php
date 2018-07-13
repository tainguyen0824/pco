<div id="set_<?=$idSet?>" class="tab-pane fade set-route">
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="set-name" style="margin-top: 10px">
                <label for="name_<?=$idSet?>">Set Name</label>
                <input class="input-name" type="text" value="" name="name_<?=$idSet?>" placeholder="Set <?=$idSet?>">
                <button type="button" value="" onclick="SaveSetFirst()" class="btn btn-info">Set active</button>
                <button type="button" value="" onclick="SaveSetFirst()" class="btn btn-primary">Enforce Routes to Services</button>
            </div>
            <div class="inner-addon left-addon" style="margin-top: 10px">
                <i class="glyphicon glyphicon-search"></i>
                <input onkeyup="Route_Active.Search(this)" id="searchRoute_<?=$idSet?>" type="text"  placeholder="Search" class="form-control Search">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 right">
            <button class="btn btn-danger" onclick="SaveSetFirst()" style="margin-top: 10px"><i class="fa fa-ban"></i></button><br>
            <div style="margin-top: 10px">
                <button class="btn btn-primary" onclick="SaveSetFirst()">New route</button>
                <button class="btn btn-primary" onclick="Route_Active.CheckAll(this)">Select all</button>
                <button class="btn btn-primary dropdown_hover dropdown-toggle" data-toggle="dropdown" >Actions</button>
                <ul class="dropdown-menu pull-right select_actions" style="margin-right: 12px;">
                    <li onclick="SaveSetFirst()"><a href="javascript:void(0)">Delete</a></li>
                    <li onclick="SaveSetFirst()"><a href="javascript:void(0)">Export</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row content">
        <div class="col-md-7 col-lg-7">
            <table class="table table-hover table-striped tbl_routes_<?=$idSet?>">
                <thead>
                    <tr>
                        <th><div class="custom-checkbox">
                            <input type="checkbox" onchange="Route_Active.CheckAll(this)" name="select_all_<?=$idSet?>" id="allRoute_<?=$idSet?>">
                            <label for="allRoute_<?=$idSet?>" style="font-weight: normal;"></label>
                        </div></th>
                        <th>Route No.</th>
                        <th>Route Name</th>
                        <th>Services</th>
                        <th>Coverage Area</th>
                        <th>Covered Zip</th>
                        <th>Default Technician</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <p class="entries"><span id="item_count_<?=$idSet?>"></span> entries selected</p>
        </div>
        <div class="col-md-5 col-lg-5">
            <p><strong>Showing </strong><strong id="route-name_<?=$idSet?>"></strong></p>
            <div class="row" style="margin-left: -25px;">
                <div class="col-lg-10">                 
                    <div id="map_<?=$idSet?>" style="height: 400px;margin-top: 15px"></div>
                </div>
                <div class="col-lg-2">
                    <div class="row" style="margin-left: -25px;">
                        <strong>
                            <p>Covered ZIP</p>
                            <p class="subcript">(In addition to map selection)</p>
                            <p id="zip_<?=$idSet?>"></p>
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $jsLoad; ?>
</div>