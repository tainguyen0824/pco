<div id="wrap-close-overlay">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 title-left-close-overlay">
        <div class="DivParent">
            <div class="DivWhichNeedToBeVerticallyAligned">Add Routes</div>
            <div class="DivHelper"></div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 title-right-close-overlay">
        <button class="btn btn-sm btn-primary" value="insert" onclick="Route_Active.SaveRoute(this)">Save Route</button>
        <button type="button" class="btn btn-sm btn-primary" id="close">X</button>
    </div>
</div>
<div id="overlay-content" class="overlay-content">
    <div class="content" style="padding: 40px;padding-bottom: 200px">
        <form id="newRoute">
            <div class="row">
                <h4><strong>Route Information</strong></h4>
                <div class="row">
                    <div class="col-lg-3 text-right">
                        <p class="cap-data"><strong>Route No</strong></p>
                    </div>
                    <div class="col-lg-3 text-left">
                        <input class="input-name" type="text" name="route_no" onkeyup="CheckExistingRoute(this)" style="width: 100%" onchange="SaveCache()">
                    </div>
                    <p class="no-validate hidden" style="color: red;font-style: italic;">Route number already exists. Please pick a unique number.</p>
                </div>
                <div class="pad-item"></div>
                <div class="row">
                    <div class="col-lg-3 text-right">
                        <p class="cap-data"><strong>Route Name</strong></p>
                    </div>
                    <div class="col-lg-3 text-left">
                        <input type="text" class="input-name" name="route_name" onchange="SaveCache()" style="width: 100%">
                    </div>
                </div>
                <div class="pad-item"></div>
                <div class="row">
                    <div class="col-lg-3 text-right">
                        <p class="cap-data"><strong>Total Active Services</strong></p>
                    </div>
                    <div class="col-lg-3 text-right">
                        <span>0</span>
                    </div>
                    <div class="col-lg-6 text-left">
                        <button class="btn btn-primary" type="button" onclick="ShowListService(-1,1)">List</button>
                    </div>
                </div>
                <div class="pad-item"></div>
                <div class="row">
                    <div class="col-lg-3 text-right">
                        <p class="cap-data"><strong>Total Inactive Services</strong></p>
                    </div>
                    <div class="col-lg-3 text-right">
                        <span>0</span>
                    </div>
                    <div class="col-lg-6 text-left">
                        <button class="btn btn-primary" type="button" onclick="ShowListService(-1,0)">List</button>
                    </div>
                </div>
                <div class="pad-item"></div>
                <div class="row">
                    <div class="col-lg-3 text-right">
                        <p class="cap-data"><strong>Map Area Covered</strong></p>
                        <p class="subcript"><strong>Optional. </strong>Draw a geographical area on a map and have all services in that area fall into this route.</p>
                    </div>
                    <div class="col-lg-3 text-right">
                        0 services within drawn area!
                    </div>
                    <div class="col-lg-6 text-left">
                        <button class="btn btn-primary" type="button" onclick="LoadMap(-1)">Map</button>
                    </div>
                </div>
                <div class="pad-item"></div>
                <div class="row">
                    <div class="col-lg-3 text-right">
                        <p class="cap-data"><strong>Postal Codes (ZIP) Covered</strong></p>
                        <p class="subcript"><strong>Optional. </strong>Have services covered by the postal code automatically fall into this route. Separate with comma (,).</p>
                    </div>
                    <div class="col-lg-9 text-left">
                        <input class="input-name" name="route_zip" id="tags" value="" />
                    </div>
                </div>
                <div class="pad-item"></div>
                <div class="row">
                    <div class="col-lg-3 text-right">
                        <p class="cap-data"><strong>Default Technician</strong></p>
                        <p class="subcript"><strong>Optional. </strong>New services created under this route will automatically be assigned the selected technician.</p>
                    </div>
                    <div class="col-lg-3 text-left">
                        <select name="technician" onchange="SaveCache()" class="input-name" id="" style="width: 100%">
                            <?php for ($i = 0; $i < count($technician); $i++) {
                                echo '<option value="'.$technician[$i]->id.'">'.$technician[$i]->name.'</option>';
                            } ?>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    var zip = $('#tags').tagsInput({
           'interactive':true,
           'defaultText':'add a zip',
           'onAddTag':function () {
               cacheZip = $(this).val();
           },
           'onRemoveTag':function () {
               cacheZip = $(this).val();
           },
           'onChange' : function () {
               cacheZip = $(this).val();
           },
           'removeWithBackspace' : true,
           'minChars' : 5,
           'maxChars' : 5, // if not provided there is no limit
           'placeholderColor' : '#666666'
        });
    function SaveCache(){
        cacheNo = $('[name = route_no]').val();
        cacheName = $('[name = route_name]').val();
        // cacheZip = $('[name = route_zip]').val();
        cacheTech = $('[name = technician]').val();
    }
    function LoadCache(){
        if(cacheNo != '')
            $('[name = route_no]').val(cacheNo);
        if(cacheName != '')
            $('[name = route_name]').val(cacheName);
        if(cacheZip != ''){
            if(cacheZip.lastIndexOf(',') === -1)
                zip.addTag(cacheZip);
            else
                zip.importTags(cacheZip);
        }
        
        if(cacheTech != '')
            $('[name = technician]').val(cacheTech);
    }
    $('#close').click(function () {
        Js_Top.closeNav();
        saveData = [];
        cacheNo = '';
        cacheName = '';
        cacheZip = '';
        cacheTech = '';
    })
    $(document).ready(function () {
        LoadCache();
        CheckExistingRoute('',cacheNo);
    })
    
</script>