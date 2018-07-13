<ul class="nav nav-tabs tab-scnd">
    <?php foreach($set as $s): ?>
        <li class="<?=($s['active'] == 1)? 'active' : ''?>"><a onclick="SetActive(this)" data-toggle="tab"  idSet="<?=$s['id']?>" class="tab_set" href="#set_<?=$s['id']?>">Set <?=$s['name']?><?=($s['active'] == 1)? ' - Active' : ''?></a></li>
    <?php endforeach; ?>
    <span class="after-li"></span>
    <button class="text-right btn btn-primary mg-5" onclick="NewSet(this)">New Set</button>
</ul>
<?php foreach($set as $e): ?>
    <div id="set_<?=$e['id']?>" class="tab-pane fade <?=($e['active'] == 1)? 'in active' : ''?> set-route">
	    <div class="row">
	        <div class="col-lg-6 col-md-6">
	            <div class="set-name" style="margin-top: 10px">
	                <label for="name_<?=$e['id']?>">Set Name</label>
	                <input class="input-name" type="text" value="<?=$e['name']?>" name="name_<?=$e['id']?>" placeholder="Set 1">
	                <button type="button" value="<?=$e['id']?>" onclick="ActiveSet(this)" class="btn btn-info">Set active</button>
	                <button type="button" value="<?=$e['id']?>" onclick="EnforceRoute()" class="btn btn-primary">Enforce Routes to Services</button>
	            </div>
	            <div class="inner-addon left-addon" style="margin-top: 10px">
	                <i class="glyphicon glyphicon-search"></i>
	                <input onkeyup="Route_Active.Search(this)" id="searchRoute_<?=$e['id']?>" type="text"  placeholder="Search" class="form-control Search">
	            </div>
	        </div>
	        <div class="col-lg-6 col-md-6 right">
	            <button class="btn btn-danger" title="Delete this set!" onclick="deleteSet()" style="margin-top: 10px"><i class="fa fa-ban"></i></button><br>
	            <div style="margin-top: 10px">
	                <button class="btn btn-primary" onclick="NewRoute()">New route</button>
	                <button class="btn btn-primary" onclick="Route_Active.CheckAll(this)" value="0">Select all</button>
	                <button class="btn btn-primary dropdown_hover dropdown-toggle" data-toggle="dropdown" >Actions</button>
	                <ul class="dropdown-menu pull-right select_actions" style="margin-right: 12px;">
	                    <li onclick="Route_Active.DeleteRoute()"><a href="javascript:void(0)">Delete</a></li>
	                    <li onclick="Export()"><a target="_blank" href="javascript:void(0)">Export</a></li>
	                </ul>
	            </div>
	        </div>
	    </div>
	    <div class="row content">
	        <div class="col-md-7 col-lg-7">
	            <table class="table table-hover table-striped tbl_routes_<?=$e['id']?>">
	                <thead>
	                    <tr>
	                        <th><div class="custom-checkbox">
	                            <input type="checkbox" onchange="Route_Active.CheckAll(this)" name="select_all_<?=$e['id']?>" id="allRoute_<?=$e['id']?>">
	                            <label for="allRoute_<?=$e['id']?>" style="font-weight: normal;"></label>
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
	            <p class="entries"><span id="item_count_<?=$e['id']?>"></span> entries selected</p>
	        </div>
	        <div class="col-md-5 col-lg-5">
	            <p><strong>Showing </strong><strong id="route-name_<?=$e['id']?>"></strong></p>
	            <div class="row" style="margin-left: -25px;">
	            	<div class="col-lg-10">	            	
	            		<div id="map_<?=$e['id']?>" style="height: 400px;margin-top: 15px"></div>
	            	</div>
	            	<div class="col-lg-2">
	            		<div class="row" style="margin-left: -25px;">
	            			<strong>
			            		<p>Covered ZIP</p>
			            		<p class="subcript">(In addition to map selection)</p>
			            		<p id="zip_<?=$e['id']?>"></p>
		            		</strong>
						</div>
	            	</div>
	            </div>
	        </div>
	    </div>
	    <?php echo $jsLoad; ?>
    </div>
<?php endforeach; ?>