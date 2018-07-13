<?php $id = -1; if($route_info != 0) $id = $route_info['route_id'];?>
<div id="wrap-close-overlay">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 title-left-close-overlay">
        <div class="DivParent">
            <div class="DivWhichNeedToBeVerticallyAligned">Map Of Route</div>
            <div class="DivHelper"></div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 title-right-close-overlay">
        <button class="btn btn-sm btn-primary" onclick="SaveChangeRoute()">Save Changes</button>
        <button type="button" class="btn btn-sm btn-primary" onclick="CloseMap('<?=$id?>')">X</button>
    </div>
</div>
<div id="overlay-content" class="overlay-content">
    <h4 style="background-color: #a7bcd4; padding: 10px 0px;"><strong>Showing Route <span id="route-name"></span></strong></h4>
    <div id="map" style="width: 100%;"></div>
</div>
<script>
    if(-1 != '<?=$id?>')
        saveData = [];
    function initMap(){
        var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 34.175697, lng: -118.423184},
            zoom: 15
        })
        var drawingManager = new goo.drawing.DrawingManager({

            map: map,

            drawingMode: goo.drawing.OverlayType.HAND,

            drawingControl: true,

            drawingControlOptions: {
                position: goo.ControlPosition.TOP_CENTER,
                drawingModes: [
                    goo.drawing.OverlayType.CIRCLE,
                    goo.drawing.OverlayType.POLYGON,
                    goo.drawing.OverlayType.RECTANGLE
                ]
            },

            markerOptions: image,
            
            circleOptions: {
                strokeColor: '#FF0000',
                fillColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillOpacity: 0.2,
                strokeWeight: 5,
                clickable: false,
                editable: true,
                zIndex: 1
            },

            rectangleOptions: {
                strokeColor: '#FF0000',
                fillColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillOpacity: 0.2,
                strokeWeight: 5,
                clickable: false,
                editable: true,
                zIndex: 1
            },

            polygonOptions: {
                strokeColor: '#FF0000',
                fillColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillOpacity: 0.2,
                strokeWeight: 5,
                clickable: false,
                editable: true,
                zIndex: 1
            },
        })
        var route_id = '<?=($route_info != 0)? $route_info['route_id'] : -1 ?>';
        if(route_id != -1){
            $.post('<?=url::base()?>routes/loadZone',{route_id: route_id})
            .done(function (result) {
                var data = JSON.parse(result);
                var zone = data['map'];
                var service = data['service'];

                for(var i in service){
                    arr_service.push({lat: service[i].latitude_1, lng: service[i].longitude_1,service_id: service[i].service_id});
                    var overlay = new google.maps.Marker({
                        position: {lat: parseFloat(service[i].latitude_1), lng: parseFloat(service[i].longitude_1)},
                        icon: image,
                        title: service[i].service_id
                    });
                    overlay.setMap(map);
                }
                for(var i in zone){
                    saveData.push({index: zone[i].map_id, type: zone[i].type.toUpperCase(), position: zone[i].position, service_in: zone[i].service_in});
                    drawShape(JSON.parse(zone[i].position),zone[i].type.toUpperCase(),zone[i].map_id,map);
                }
            })
        }
        else{
            // console.log(saveData.length);
            if(saveData.length != 0)
            {
                for(var i = 0; i < saveData.length; i++){
                    var zone = saveData[i];
                    var position = [];position.push(zone.position);
                    drawShape(position,zone.type,zone.index,map);
                }
            }
        }
        
        goo.event.addListener(drawingManager, 'overlaycomplete', function(event) {

            var shape = event.overlay;
            shape.type = event.type;
            service_in = '';
            is_in_zone(shape);

            var data = [];
            if(shape.type == 'circle'){
                var center = JSON.parse(JSON.stringify(shape.center));
                var radius = shape.radius;
                var position = {center: center,radius: radius};
                data.push(position);
                drawShape(data,shape.type.toUpperCase(),map_id,map);
                data = [];

                saveData.push({index: map_id, type: shape.type.toUpperCase(), position: position, service_in: service_in});       
            }
            
            if(shape.type == 'rectangle'){
                var bound = JSON.parse(JSON.stringify(shape.bounds));
                var position = {south: bound.south, west: bound.west, north: bound.north, east: bound.east};
                data.push(position);
                drawShape(data,shape.type.toUpperCase(),map_id,map);
                data = [];

                saveData.push({index: map_id, type: shape.type.toUpperCase(), position: position, service_in: service_in});
            }
            if(shape.type == 'polygon'){
                var position = JSON.parse(JSON.stringify(shape.latLngs.b[0].b));
                data.push(position);
                drawShape(data,shape.type.toUpperCase(),map_id,map);
                data = [];

                saveData.push({index: map_id, type: shape.type.toUpperCase(), position: position, service_in: service_in});
            }
            shape.setMap(null);
            map_id += 'a';
        })
    }

    function CloseMap(route_id,action = 0) {
        if(action == 0)
            saveData = [];
        Js_Top.closeNav();
        if(route_id == -1)
            NewRoute();
        else{
            Route_Active.EditRoute(route_id);
        }
    }

    function SaveChangeRoute() {
        var route_id = '<?=($route_info != 0)? $route_info['route_id'] : -1 ?>';
        if(route_id != -1)
            $.post('<?=url::base()?>routes/saveZoneForRoute',{idRoute: route_id, data: saveData});
        CloseMap(route_id,1);
        Js_Top.success('Save successfully!');
    }

    $(document).ready(function () {
        initMap();
        var route_id = '<?=($route_info != 0)? $route_info['route_id'] : -1 ?>';
        if(route_id != -1)
            $('#route-name').html('<?=$route_info['route_no'] . ' - ' .$route_info['route_name']?>');
        else
            $('#route-name').html(cacheNo + ' - ' + cacheName);
    })
</script>