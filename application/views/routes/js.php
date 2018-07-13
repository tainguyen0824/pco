<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script> -->
<script>
    var geocoder;
    var dialog;
    var service_in = '';
    var saveData = [];
    var goo = google.maps;
    var map_id = 'a';
    var dialog = $('#new-route');

    var selectAll = false;
    var selected = [];
    var unselected = [];

    var all = '';  //id Set when "New Set"
    var idRoute = 0;
    var setActive = 0;

    var arr_service = [];
    var arr_zone = [];
    var page = 0;
    var total_page = 0;

    var cacheNo = '', cacheName = '', cacheZip = '', cacheTech = '';
    var image = {
        url: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
        // This marker is 20 pixels wide by 32 pixels high.
        size: new google.maps.Size(20, 32),
        // The origin for this image is (0, 0).
        origin: new google.maps.Point(0, 0),
        // The anchor for this image is the base of the flagpole at (0, 32).
        anchor: new google.maps.Point(0, 32)
    };

    function NewSet(t_this) {
        $('<li class=""><a data-toggle="tab" onclick="SetActive(this)" idSet="' + all + '" class="tab_set" href="#set_' + all + '">Set New *</a></li>').insertBefore(t_this);
        $.post('<?=url::base()?>routes/getNewSet', {idSet: all})
            .success(function (result) {
                $('#routes').append(result);
            })
        all = parseInt(all) + 1;
    }

    function Showmap(route_id, t_this) {
        var ele = $(t_this);
        $.ajax({
            url: '<?=url::base()?>routes/loadZone',
            async: false,
            type: 'post',
            data: {
                route_id: route_id
            },
            success: function (result) {
                // console.log(result);
                var data = JSON.parse(result);
                var all = data['map'];
                arr_zone = [];
                arr_service = [];

                if (all.length != 0) {
                    for (var i in all) {
                        arr_zone.push({
                            data: JSON.parse(all[i].position),
                            type: all[i].type,
                            route_id: route_id,
                            map_id: all[i].map_id
                        });
                    }

                }
                else {
                    arr_zone.push({data: null, route_id: route_id});
                    arr_service.push({data: null, route_id: route_id});
                    ele.removeClass('btn-info');
                    ele.addClass('btn-primary');
                }

                var service = data['service'];

                for (var i in service) {
                    arr_service.push({
                        lat: service[i].latitude_1,
                        lng: service[i].longitude_1,
                        route_id: service[i].service_route,
                        service_id: service[i].service_id
                    });
                }
                // console.log(service[0].service_route);

                var routeInfo = data['route_info'];
                $('#route-name_' + setActive).html('Route ' + routeInfo[0].route_no + ' - ' + routeInfo[0].route_name);

                var zip = routeInfo[0].route_zip.split(',');
                if (zip.length > 0) {
                    var str = '';
                    for (var i in zip) {
                        str += zip[i] + '<br>';
                    }
                    $('#zip_' + setActive).html(str);
                }
                else
                    $('#zip_' + setActive).html('');

                Route_Active.settings.TblActive.DataTable().draw();
            },
            error: function (xhr, desc, err) {
                alert("error");
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        });
        // else{
        // 	// ele.removeClass('btn-primary');
        //  //  		ele.addClass('btn-info');
        //       $('#route-name_' + setActive).html('');
        //       $('#zip_' + setActive).html('');
        //   		idRoute = 0;
        //   		removeZone(route_id);
        //   		removeService(route_id);
        //       Route_Active.settings.TblActive.DataTable().draw();
        // }
        Route_Active.DrawMap(arr_service, arr_zone);
    }

    function is_in_zone(shape) {

        for (var i in arr_service) {
            var location = {lat: parseFloat(arr_service[i].lat), lng: parseFloat(arr_service[i].lng)};
            var overlay = new google.maps.Marker({
                position: location
            })
            if (shape.type == 'polygon') {
                if (google.maps.geometry.poly.containsLocation(overlay.position, shape)) {
                    service_in += arr_service[i].service_id + ',';
                }
            }
            else {
                if (shape.getBounds().contains(overlay.position)) {
                    service_in += arr_service[i].service_id + ',';
                }
            }
        }
    }

    function checkMap(route_id) {
        for (var i in arr_zone) {
            if (arr_zone[i].route_id == route_id)
                return 'primary';
        }
        for (var i in arr_service) {
            if (arr_service[i].route_id == route_id)
                return 'primary';
        }
        return 'info';
    }

    function removeZone(route_id) {
        var index = [];

        for (var i in arr_zone) {
            if (arr_zone[i].route_id == route_id)
                index.push(i);
        }

        for (var j = index.length - 1; j >= 0; j--) {
            arr_zone.splice(index[j], 1);
        }
    }

    function removeService(route_id) {
        var index = [];

        for (var i in arr_service) {
            if (arr_service[i].route_id == route_id)
                index.push(i);
        }

        for (var j = index.length - 1; j >= 0; j--) {
            arr_service.splice(index[j], 1);
        }
    }

    function NewRoute() {
        Js_Top.show_loading();
        $.ajax({
            url: '<?=url::base()?>routes/addRouteHtml',
            method: 'POST',
            data: {
                idSet: setActive
            },
            success: function (result) {
                $('#wrap-overlay').html(result);
                Js_Top.openNav();
                Js_Top.hide_loading();
                jquery_plugins.MaskPhone();
                jquery_plugins.NoSpaceInput();
            }
        })
    }

    var vali_num = $('.no-validate');
    vali_num.addClass('hidden');

    function CheckExistingRoute(t_this, value = 0) {
        var val;
        if (value == 0)
            val = $(t_this).val();
        else
            val = value;
        $.post('<?=url::base()?>routes/checkRouteID', {idRoute: val})
            .done(function (result) {
                if (result) {
                    $('.no-validate').removeClass('hidden');
                }
                else
                    $('.no-validate').addClass('hidden');
            })
    }

    function drawShape(data, type, map_id, map, noClick = 0) {
        // console.log(data);
        if (type == 'CIRCLE') {
            var center = {lat: parseFloat(data[0].center.lat), lng: parseFloat(data[0].center.lng)};
            var radius = parseFloat(data[0].radius);
            var overlay = new google.maps.Circle({
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillOpacity: 0.35,
                center: center,
                radius: radius,
                map_id: map_id
            });
            overlay.setMap(map);
            map.setCenter(center);
        }
        if (type == 'RECTANGLE') {
            var location1 = {lat: parseFloat(data[0].south), lng: parseFloat(data[0].west)};
            var location2 = {lat: parseFloat(data[0].north), lng: parseFloat(data[0].east)};

            var bound = new google.maps.LatLngBounds(location1, location2);
            var overlay = new google.maps.Rectangle({
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillOpacity: 0.35,
                bounds: bound,
                map_id: map_id
            });
            overlay.setMap(map);
            map.setCenter(location1);
        }
        if (type == 'POLYGON') {
            var path = [];
            for (var i in data[0]) {
                path.push({lat: parseFloat(data[0][i].lat), lng: parseFloat(data[0][i].lng)});
            }
            var overlay = new google.maps.Polygon({
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillOpacity: 0.35,
                paths: path,
                map_id: map_id
            });
            overlay.setMap(map);
            map.setCenter(path[0]);
        }
        if (noClick == 0) {
            if (overlay) {
                overlay.addListener('click', function () {
                    var map_id = overlay.map_id;
                    $.confirm({
                        title: 'Warning!',
                        content: 'Are you sure to delete selected zone ?',
                        columnClass: 'col-md-4 col-centered',
                        containerFluid: true,
                        buttons: {
                            OK: {
                                text: 'OK',
                                btnClass: 'btn-blue',
                                keys: ['enter'],
                                action: function () {
                                    overlay.setMap(null);
                                    for (var i in arr_zone) {
                                        if (arr_zone[i].map_id == map_id) {
                                            arr_zone.splice(i, 1);
                                            break;
                                        }
                                    }
                                    for (var i in saveData) {
                                        if (saveData[i].index == map_id) {
                                            saveData.splice(i, 1);
                                            break;
                                        }
                                    }
                                }
                            },
                            Cancel: {
                                text: 'Cancel',
                                btnClass: 'btn-default',
                                keys: ['esc'],
                                action: function () {
                                    this.close();
                                }
                            }
                        }
                    });
                })
            }
        }
    }

    //Click tab to select a set
    function SetActive(t_this) {
        setActive = $(t_this).attr('idSet');
        arr_zone = [];
        arr_service = [];
        idRoute = 0;
        selectAll = false;

        var waper = $('#routes');
        Route_Active.init(waper);
    }

    //Click to active this set
    function ActiveSet(t_this) {
        var val = $(t_this).val();
        var name = $('[name = name_' + setActive + ']').val();
        if (!name)
            Js_Top.error('You must be input the set name!');
        else{
            $.post('<?=url::base()?>routes/setActiveSet', {setID: val, name: name});
            Js_Top.success('Set active successfully!');
            location.reload();
        }
    }

    function deleteSet() {
        idSet = setActive;

        $.confirm({
            title: 'Warning!',
            content: 'Are you sure to delete selected set ?',
            columnClass: 'col-md-4 col-centered',
            containerFluid: true,
            buttons: {
                OK: {
                    text: 'OK',
                    btnClass: 'btn-blue',
                    keys: ['enter'],
                    action: function () {
                        $.post('<?=url::base()?>routes/deleteSet', {idSet: setActive});
                        Js_Top.success('Delete Successfully!');
                        location.reload();
                    }
                },
                Cancel: {
                    text: 'Cancel',
                    btnClass: 'btn-blue',
                    keys: ['esc'],
                    action: function () {
                        this.close();
                    }
                }
            }
        })
    }

    function SaveSetFirst() {
        Js_Top.error('You must be insert set first!');
    }

    function LoadMap(route_id) {
        Js_Top.closeNav();
        Js_Top.show_loading();
        $.get('<?=url::base()?>routes/loadMap', {idRoute: route_id})
            .done(function (result) {
                $('#wrap-overlay').html(result);
                Js_Top.openNav();
                Js_Top.hide_loading();
                jquery_plugins.MaskPhone();
                jquery_plugins.NoSpaceInput();
            })
    }

    function ShowListService(route_id, active) {
        Js_Top.closeNav();
        Js_Top.show_loading();
        $.post('<?=url::base()?>routes/showListService', {idRoute: route_id, active: active})
            .done(function (result) {
                $('#wrap-overlay').html(result);
                Js_Top.openNav();
                Js_Top.hide_loading();
                jquery_plugins.MaskPhone();
                jquery_plugins.NoSpaceInput();
            })
    }

    function EnforceRoute() {
        $.post('<?=url::base()?>routes/countServiceInSet', {idSet: setActive})
            .done(function (result) {
                var activeSV = result;
                dialog.html("<strong><p>This will change the route information of all services to bring them in line with the currently active set of routes. Services whose address information do not match any established routes either by postal ZIP or map data will become 'unsorted'.</p><br><p>This will overwrite the route information of any 'rogue' services (manual exceptions) which may have been put into routes in contrast with their geographical matches.</p><br><p>This will affect total " + activeSV + " services. Are you sure you want to continue?</p></strong>");
            })
        dialog.dialog({
            draggable: true,
            modal: true,
            autoOpen: true,
            width: '625',
            height: 'auto',
            title: 'WARNING!',
            buttons: {
                "Yes": function () {
                    // Js_Top.show_loading();

                    setPriority();

                    // Js_Top.hide_loading();
                    $(this).dialog("close");
                },
                'No': function () {
                    $(this).dialog("close");
                }
            }
        })
    }

    function setPriority() {
        $.post('<?=url::base()?>routes/getAllRoute', {idSet: setActive, action: 'route'})
        .done(function (result) {
            var json = JSON.parse(result);
            var routes = json.routes;

            var text = '<p>Please select priority you want to enforce service for route automatically!</p>';
            text += '<ol class="sortable">';
            for(var i = 0;i < routes.length; i++){
                text += '<li style="cursor: pointer; padding: 5px 10px; font-size: 15px;"><input type="hidden" class="order" value="' + routes[i].route_id + '">Route ' + routes[i].route_no + ' - ' + routes[i].route_name +'</li>';
            }
            text += '</ol>';

            dialog.html(text);
            $(document).find('.sortable').sortable();

            dialog.dialog({
                autoOpen: true,
                modal: true,
                draggable: true,
                buttons: {
                    'OK': function () {
                        var index = $(this).find('.sortable li .order');
                        $.each(index,function (ind, ele) {
                            $.post('<?=url::base()?>routes/updatePriority',{idRoute: $(ele).val(),order: ind});
                        });
                        // //update route for service by zip
                        checkZipService();
                        // //update route for service by address
                        checkLocationService();

                        $(this).dialog('close');
                        Route_Active.settings.TblActive.DataTable().draw();
                    }
                }
            });
        })
    }
    //no use
    function updateService() {
        $.post('<?=url::base()?>routes/getAllService')
            .done(function (result) {
                var data = JSON.parse(result);

                var address = '';

                for (var i in data) {
                    if (data[i].service_address_1 == '') {
                        if (data[i].service_address_1 == '') {
                            data.splice(i, 1);
                        }
                    }
                }

                for (var i = 0; i < data.length; i++) {
                    if (data[i].service_address_1 != '') {
                        address = data[i].service_address_1 + ' ' + data[i].service_city + ' ' + data[i].service_state + ' ' + data[i].service_zip;
                        $.post('<?=url::base()?>routes/lookup', {address: address, idService: data[i].service_id});
                    }
                    else {
                        address = data[i].service_address_2 + ' ' + data[i].service_city + ' ' + data[i].service_state + ' ' + data[i].service_zip;
                        $.post('<?=url::base()?>routes/lookup', {address: address, idService: data[i].service_id});
                    }
                }
            })
    }

    function checkLocationService() {
        var dataSV, dataZone;

        $.post('<?=url::base()?>routes/getAllService')
        .done(function (rsSV) {

            dataSV = JSON.parse(rsSV);
            //get route and route_map
            $.post('<?=url::base()?>routes/getAllRoute', {idSet: setActive, action: 'zone'})
            .done(function (rsRoute) {

                var json = JSON.parse(rsRoute);
                dataZone = json.route_zone;

                //Loop Service
                for (var i = 0; i < dataSV.length; i++) {
                    var location = {
                        lat: parseFloat(dataSV[i].latitude_1),
                        lng: parseFloat(dataSV[i].longitude_1)
                    };
                    //Loop Zone
                    for (var j = 0; j < dataZone.length; j++) {
                        var ans = testZone(dataZone[j], location, dataSV[i].service_id);
                        // console.log(ans);
                        if (ans != -1) {
                            var idRoute = dataZone[j][ans].route_id;
                            $.post('<?=url::base()?>routes/updateServiceRoute', {
                                idService: dataSV[i].service_id,
                                idRoute: idRoute
                            });
                            break;
                        }
                    }
                }
            })
        })
    }

    function testZone(zone, location, service_id) {
        var in_zone = -1;

        for (var i = 0; i < zone.length; i++) {
            var position = JSON.parse(zone[i].position)[0];
            var type = zone[i].type;
            var overlay;
            switch (type) {
                case 'CIRCLE': {
                    var center = {lat: parseFloat(position.center.lat), lng: parseFloat(position.center.lng)};
                    var radius = parseFloat(position.radius);
                    overlay = new google.maps.Circle({
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillOpacity: 0.35,
                        center: center,
                        radius: radius,
                    });
                }
                    break;

                case 'RECTANGLE': {
                    var location1 = {lat: parseFloat(position.south), lng: parseFloat(position.west)};
                    var location2 = {lat: parseFloat(position.north), lng: parseFloat(position.east)};

                    var bound = new google.maps.LatLngBounds(location1, location2);
                    overlay = new google.maps.Rectangle({
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillOpacity: 0.35,
                        bounds: bound,
                    });
                }
                    break;

                case 'POLYGON': {
                    var path = [];
                    for (var i in position) {
                        path.push({lat: parseFloat(position[i].lat), lng: parseFloat(position[i].lng)});
                    }

                    overlay = new google.maps.Polygon({
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillOpacity: 0.35,
                        paths: path,
                    });
                }
                    break;
            } // end switch case

            //check in zone
            var marker = new google.maps.Marker({
                position: location
            })

            if (type == 'POLYGON') {
                if (google.maps.geometry.poly.containsLocation(marker.position, overlay)) {
                    in_zone = i;
                    $.post('<?=url::base()?>routes/updateZoneService', {idService: service_id, idMap: zone[i].map_id});
                }
            }
            else {
                if (overlay.getBounds().contains(marker.position)) {
                    in_zone = i;
                    $.post('<?=url::base()?>routes/updateZoneService', {idService: service_id, idMap: zone[i].map_id});
                }
            }
        } // end loop
        return in_zone;
    }

    function checkZipService() { //for priority of first route
        var dataSV, dataRoute;
        //get Service
        $.post('<?=url::base()?>routes/getAllService')
        .done(function (rsSV) {

            dataSV = JSON.parse(rsSV);
            // console.log(dataSV);
            $.post('<?=url::base()?>routes/getAllRoute', {idSet: setActive, action: 'route'})
            .done(function (rsRoute) {

                dataRoute = JSON.parse(rsRoute).routes;
                //loop Service
                for (var i = 0; i < dataSV.length; i++) {
                    var service = dataSV[i];
                    if (service.service_zip != '') {
                        //loop Route
                        for (var j = 0; j < dataRoute.length; j++) {
                            //check Zip
                            var zipRoute = dataRoute[j].route_zip;
                            var zip = zipRoute.split(',');
                            var check = false;
                            for (var z = 0; z < zip.length; z++) {
                                if (service.service_zip == zip[z]) {
                                    check = true;
                                     var idService = dataSV[i].service_id;
                                     var idRoute = dataRoute[j].route_id;
                                     $.post('<?=url::base()?>routes/updateServiceRoute',{idService: idService, idRoute: idRoute});
                                     break;
                                }
                            }//end loop zip
                            if(check == true)
                                break;
                        }//end loop Route

                    }//endif
                }//end loop Service
            })
        })
    }
    function Export() {
        Js_Top.show_loading();
        $.post('<?=url::base()?>routes/exportSet',{idSet: setActive});
        Js_Top.hide_loading();
    }
    function LoadTechnician() {
        $.post('<?=url::base()?>routes/getTechnicianHtml')
        .done(function (result) {
            $('#technicians').html(result);
        })
    }
</script>
<script>
    $(document).ready(function () {
        $.post('<?=url::base()?>routes/getAllSet', function (result) {
            $('#routes').html(result);
            var waper = $('#routes');
            // console.log(waper);
            Route_Active.init(waper);
        })

        $.post('<?=url::base()?>routes/countAllSet', function (result) {
            var res = JSON.parse(result);
            all = parseInt(res[0].id) + 1;
            setActive = parseInt(res[1].id);
        })

        $('.no-validate').addClass('hidden');
        dialog = $('#dialog-confirm').dialog({
            autoOpen: false
        })
        LoadTechnician();
    })
</script>
<!-- <script src="load_zone.js"></script>
<script src="load_user.js"></script>
<script src="show_save_zone.js"></script> -->
