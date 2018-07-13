<div class="row">
    <p style="margin-left: 15px"><strong>Filters</strong></p>
    <div class="col-md-8 col-lg-8">
        <div class="row">
            <select class="filter" name="" id="" style="margin-left: 15px">
                <option value="">All Territories</option>
            </select>
            <select class="filter" name="" id="">
                <option value="">Recurring Jobs</option>
            </select>
            <select class="filter" name="" id="">
                <option value="">Today's Jobs</option>
            </select>
        </div>
        <div id="map_<?=$e['id']?>" style="height: 400px;margin-top: 15px"></div>
    </div>
    <div class="col-md-4 col-lg-4">
        <select name="" id="" class="filter">
            <option value="">Action On Selected</option>
        </select>
        <label class="form-label">Registered Territories</label>
        <ul class="trtr-list">
            <div class="custom-checkbox">
                <input type="checkbox" onchange="" id="showAllType">
                <label for="showAllType" style="font-weight: normal;">Territory 1</label>
            </div>
            <li>
                <div class="custom-checkbox">
                    <input type="checkbox" onchange="" class="SubFilterType" id="111222" value="111222">
                    <label for="111222" style="font-weight: normal;">Territory 2</label>
                </div>
            </li>
            <li>            
                <div class="custom-checkbox">
                    <input type="checkbox" onchange="" class="SubFilterType" id="Customer Type 1" value="Customer Type 1">
                    <label for="Customer Type 1" style="font-weight: normal;">Territory 3</label>
                </div>
            </li>
            <li>
                <div class="custom-checkbox">
                    <input type="checkbox" onchange="" class="SubFilterType" id="lzlz" value="lzlz">
                    <label for="lzlz" style="font-weight: normal;">Territory 4</label>
                </div>
            </li>
        </ul>
    </div>
</div>