<div id="wrap-close-overlay">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 title-left-close-overlay">
        <div class="DivParent">
            <div class="DivWhichNeedToBeVerticallyAligned">Edit Routes</div>
            <div class="DivHelper"></div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 title-right-close-overlay">
        <button class="btn btn-sm btn-primary" value="edit" onclick="Route_Active.SaveRoute(this)">Save Route</button>
        <button class="btn btn-sm btn-primary" onclick="Route_Active.DeleteRoute('editPage')">Delete Route</button>
        <button type="button" class="btn btn-sm btn-primary" onclick="Js_Top.closeNav(); saveData = []">X</button>
    </div>
</div>
<div id="overlay-content" class="overlay-content">
    <div class="content" style="padding: 40px;padding-bottom: 200px">
        <div class="row">
            <h4><strong>Route Information</strong></h4>
            <div class="row">
                <div class="col-lg-3 text-right">
                    <p class="cap-data"><strong>Route No</strong></p>
                </div>
                <div class="col-lg-3 text-left">
                    <input type="text" class="input-name" name="route_no" value="<?=$route['route_no']?>" style="width: 100%" readonly>
                </div>
            </div>
            <div class="pad-item"></div>
            <div class="row">
                <div class="col-lg-3 text-right">
                    <p class="cap-data"><strong>Route Name</strong></p>
                </div>
                <div class="col-lg-3 text-left">
                    <input type="text" class="input-name" name="route_name" value="<?=$route['route_name']?>" style="width: 100%">
                </div>
            </div>
            <div class="pad-item"></div>
            <div class="row">
                <div class="col-lg-3 text-right">
                    <p class="cap-data"><strong>Total Active Services</strong></p>
                </div>
                <div class="col-lg-3 text-right">
                    <?=$active?>
                </div>
                <div class="col-lg-6 text-left">
                    <button class="btn btn-primary" onclick="ShowListService(<?=$route['route_id']?>,1)">List</button>
                </div>
            </div>
            <div class="pad-item"></div>
            <div class="row">
                <div class="col-lg-3 text-right">
                    <p class="cap-data"><strong>Total Inactive Services</strong></p>
                </div>
                <div class="col-lg-3 text-right">
                    <?=$noactive?>
                </div>
                <div class="col-lg-6 text-left">
                    <button class="btn btn-primary" onclick="ShowListService(<?=$route['route_id']?>,0)">List</button>
                </div>
            </div>
            <div class="pad-item"></div>
            <div class="row">
                <div class="col-lg-3 text-right">
                    <p class="cap-data"><strong>Map Area Covered</strong></p>
                    <p class="subcript"><strong>Optional. </strong>Draw a geographical area on a map and have all services in that area fall into this route.</p>
                </div>
                <div class="col-lg-3 text-right">
                    <?=$service_in?> services within drawn area!
                </div>
                <div class="col-lg-6 text-left">
                    <button class="btn btn-primary" onclick="LoadMap(<?=$route['route_id']?>)">Map</button>
                </div>
            </div>
            <div class="pad-item"></div>
            <div class="row">
                <div class="col-lg-3 text-right">
                    <p class="cap-data"><strong>Postal Codes (ZIP) Covered</strong></p>
                    <p class="subcript"><strong>Optional. </strong>Have services covered by the postal code automatically fall into this route. Separate with comma (,).</p>
                </div>
                <div class="col-lg-9 text-left">
                    <input class="input-name" name="route_zip" id="tags" value="<?=$route['route_zip']?>" />
                </div>
            </div>
            <div class="pad-item"></div>
            <div class="row">
                <div class="col-lg-3 text-right">
                    <p class="cap-data"><strong>Default Technician</strong></p>
                    <p class="subcript"><strong>Optional. </strong>New services created under this route will automatically be assigned the selected technician.</p>
                </div>
                <div class="col-lg-3 text-left">
                    <select class="input-name" name="technician" id="" style="width: 100%">
                        <?php for ($i = 0; $i < count($technician); $i++) {
                            if($technician[$i]->id == $route['route_technician'])
                                echo '<option value="'.$technician[$i]->id.'" selected>'.$technician[$i]->name.'</option>';
                            else
                                echo '<option value="'.$technician[$i]->id.'">'.$technician[$i]->name.'</option>';
                        } ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="dialog"></div>
<script>
    $('#tags').tagsInput({
        'defaultText':'add a zip',
    });
</script>