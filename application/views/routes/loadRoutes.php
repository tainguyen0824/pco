<script>
	var Dom_Active,Table_Active;
	var textSelect;
	var Total_Record_Filter   = 0;
	var Total_Record          = 0;
	Route_Active = {
		settings: {
			Map: $('#map'),
	        TblActive: $('.tbl_routes_1'),
	        CheckboxAll: $('#allRoute'),
	        BtnPrev: $('.btn_prev_page_customers_active'),
	        BtnNext: $('.btn_next_page_customers_active'),
	        TotalRecord: $('#TotalRecord'),
	        Button_Unselect_All: $('#Button_Unselect_All'),
	        Button_All: $('#Button_All')
	    },
	    LoadDefault: function(){
	    	textSelect= $('#item_count_' + setActive);
	    	selected = [];
	  		unselected = [];
	  		textSelect.text(0);

	  		$('[name = select_all_'+setActive+']').prop('checked',false);
	    	Route_Active.settings.TblActive = $('.tbl_routes_' + setActive);
	    	Table_Active = Dom_Active.TblActive.DataTable({
	    		destroy: true,
	            serverSide: true,
	            processing: false,
	            autoWidth: false,
	            deferRender: true,
	            ordering: false,
	            pageLength: 8,
                paging: true,
	            ajax: {
	                url: "<?php echo url::base()?>routes/LoadRoutes",
	                type: "POST",
	                data: {
	                	idSet: setActive,
	                    search: $('#searchRoute_'+setActive+'').val(),
	            //         d._ac_in_tive      = document.getElementById('ac_in_tive').value,
	            //         d.ValFilterType    = ValFilterType,
	            //         d.ValFilterBalance = ValFilterBalance
	                },
	                beforeSend : function(){
	                    Js_Top.Add_Image_Loading_Datatables(Route_Active.settings.TblActive);
	                },
	                complete: function(d){
	                    Total_Record_Filter = d.responseJSON.recordsFiltered;
	                    Total_Record = d.responseJSON.recordsTotal;
	                    Route_Active.settings.TblActive.children('div:last-child').remove();
	                    total_page = Math.floor(Total_Record_Filter / 8) + 1;
	                    // $("#loading").hide();
	            //         $('.tab-content').children('div:last-child').remove();
	            //         Arr_id_Search = d.responseJSON.Str_id;
	                }
	            },
	            columnDefs: [{
	                orderable: false,
	                targets: 0,
	            }],
	            order: [
	                [1, 'asc']
	            ],
	            "columns": [
	            {
	                "class":"td_route_chk_" + setActive,
	                "data": null,
	                "orderable": false,
	                "render": function ( data, type, full, meta ) {
	                    return '<div class="custom-checkbox"><input onchange="Route_Active.ClickItem('+full.tdID+',this)" type="checkbox" value="'+full.tdID+'" class="chk_record_detail" id="td_row_'+full.tdID+'"><label style="margin-bottom: 4px !important;" for="td_row_'+full.tdID+'"></label></div>';
	                }
	            },{
	            	"class":"td_route_no_" + setActive,
	                "data": "<button>Click!</button>",
	                "orderable": false,
	                "render": function ( data, type, full, meta ) {
	                    return '<p style="cursor:pointer;font-weight:bold" onclick="Route_Active.EditRoute('+full.tdID+',0)">'+full.tdNo+'</p>';
	                }
	            },{
	            	"class":"td_route_name_" + setActive,
	                "data": null,
	                "orderable": false,
	                "render": function ( data, type, full, meta ) {
	                    return '<p style="cursor:pointer;font-weight:bold" onclick="Route_Active.EditRoute('+full.tdID+',0)">'+full.tdName+'</p>';
	                }
	            },{
	            	"class":"td_route_service_" + setActive,
	                "data": null,
	                "orderable": false,
	                "render": function ( data, type, full, meta ) {
	                    return '<p style="cursor:pointer;font-weight:bold" onclick="Route_Active.EditRoute('+full.tdID+',0)">'+full.tdService+'</p>';
	                }
	            },{
	            	"class":"td_route_map_" + setActive,
	                "data": null,
	                "orderable": false,
	                "render": function ( data, type, full, meta ) {
	                    return '<button type="button" value="'+full.tdID+'" class="btn btn-'+checkMap(full.tdID)+'" onclick="Showmap('+full.tdID+',this)">See Map</button>';
	                }
	            },{
	            	"class":"td_route_zip_" + setActive,
	                "data": null,
	                "orderable": false,
	                "render": function ( data, type, full, meta ) {
	                    return '<superscripted  style="cursor:pointer;font-weight:bold" onclick="Route_Active.EditRoute('+full.tdID+',0)">'+full.tdZIP+'</superscripted>';
	                }
	            },{
	            	"class":"td_route_technician_" + setActive,
	                "data": null,
	                "orderable": false,
	                "render": function ( data, type, full, meta ) {
	                    return '<p style="cursor:pointer;font-weight:bold" onclick="Route_Active.EditRoute('+full.tdID+',0)">'+full.tdTechnician+'</p>';
	                }
	            }],
	            rowCallback: function( row, data ) {
					var id = data.tdID;

					if($('#allRoute_' + setActive).is(':checked')){
						if( $.inArray(parseInt(id),unselected) === -1){
							$(row).find('.chk_record_detail').prop('checked', true);
						}
						if($('.dataTables_filter input').val() == ''){
							var total_row_report = Route_Active.settings.TblActive.dataTable().fnSettings().fnRecordsTotal();
							textSelect.text(total_row_report - unselected.length);
						}else{
							var total_row_report_filter = Route_Active.settings.TblActive.dataTable().fnSettings().fnRecordsDisplay();
                            textSelect.text(total_row_report_filter - unselected.length);
						}
					}else{
						if ( $.inArray(parseInt(id),selected) !== -1 ) {
							$(row).find('.chk_record_detail').prop('checked', true);
						}
						$('#item_count').text(selected.length);
					}
				}
	    	})
	    },
	    EditRoute: function(route_id){
	    	Js_Top.show_loading();
	    	$.ajax({
		        url: '<?=url::base()?>routes/editRouteHtml',
		        method: 'POST',
		        data: {
		          idRoute: route_id
		        },
		        success: function(result){
		          $('#wrap-overlay').html(result);
		          Js_Top.openNav();
		          Js_Top.hide_loading();
		          jquery_plugins.MaskPhone();
		          jquery_plugins.NoSpaceInput();
	        	}
	      	})
	      	idRoute = route_id;
	    },
	    LoadMap: function () {
	    	Route_Active.settings.Map = new goo.Map(document.getElementById('map_' + setActive), {
		      center: {lat: 34.175697, lng: -118.423184},
		      zoom: 15
		    });
		    
	    	geocoder = new goo.Geocoder();

		    var drawingManager = new goo.drawing.DrawingManager({

				map: Route_Active.settings.Map,

				drawingMode: goo.drawing.OverlayType.HAND,

				drawingControl: false,

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
					fillOpacity: 0.2,
					strokeWeight: 5,
					clickable: false,
					editable: true,
					zIndex: 1
				},
			});
	    },
	    DrawMap: function (arr_service, arr_zone) {
	    	Route_Active.LoadMap();
	    	for(var i in arr_service){
		       	var overlay = new google.maps.Marker({
					position: {lat: parseFloat(arr_service[i].lat), lng: parseFloat(arr_service[i].lng)},
					icon: image,
					title: arr_service[i].service_id,
					map: Route_Active.settings.Map
					// title: arr_service[i].route_id
				});
				// Route_Active.settings.Map.setCenter(overlay.position);
			    // console.log('2' + l);
	    	}
	  		for(var i in arr_zone){
	  			var type = arr_zone[i].type;
	  			var data = arr_zone[i].data;
	  			var map_id = arr_zone[i].map_id;
	  			drawShape(data,type,map_id,Route_Active.settings.Map,1);
	        }
	    },
	    CheckAll: function (t_this) {
	    	var chk = $('#allRoute_' + setActive);
	    	if($(t_this).hasClass('btn')){
	    	    if($(t_this).val() == 0){
                    chk.prop('checked', true);
                    $(t_this).val(1);
                    $(t_this).text('Unselect all');
                }
                else{
                    chk.prop('checked', false);
                    $(t_this).val(0);
                    $(t_this).text('Select all');
                }
            }
	    	if(chk.is(':checked')){
	    		selectAll = true;
                $(':checkbox', Route_Active.settings.TblActive.DataTable().rows().nodes()).prop('checked', true);
                selected=[];
                if($('#searchRoute_'+setActive+'').val() == '')
                    textSelect.text(Total_Record);
                else
                    textSelect.text(Total_Record_Filter);
            } else {
            	selectAll = false;
                $(':checkbox', Route_Active.settings.TblActive.DataTable().rows().nodes()).prop('checked', false);
                unselected=[];
                textSelect.text(0);
            }
            // e.stopPropagation();
	    },
	    ClickItem: function (id, element) {
	    	if($('#allRoute_' + setActive).is(":checked")){
	    		var nonindex = $.inArray(id, unselected);
		    	if(nonindex === -1)
		    		unselected.push(id);
		    	else
		    		unselected.splice(nonindex,1);
                if($('#searchRoute_'+setActive+'').val() == '')
                    textSelect.text(Total_Record - unselected.length);
                else
                    textSelect.text(Total_Record_Filter - unselected.length);
	    	}
	    	else{
	    		var index = $.inArray(id, selected);
				if ( index === -1 ) {
					selected.push(id);
				} else {
					selected.splice(index, 1);
				}
				textSelect.text(selected.length);
	    	}
	    },
	    Search: function(){
		    Route_Active.LoadDefault();
		    selected = [];
		    unselected = [];
		    textSelect.text(0);
	    },
	    SaveRoute: function (t_this) {
	    	var action = $(t_this).val();
	    	var route_no = $('[name = route_no]').val();
	    	var route_name = $('[name = route_name]').val();
	    	var route_zip = $('[name = route_zip]').val();
	    	var route_technician = $('[name = technician]').val();

	    	$.post('<?=url::base()?>routes/checkSet',{idSet: setActive})
    		.done(function (result) {
	    		if(result == 0)
	    			Js_Top.error('You must be save set first!');
	    		else{
	    			if(route_name == '' || route_no == '')
			    		Js_Top.error('The value must be required!');
			    	else{
                        saveData = [];
                        cacheNo = '';
                        cacheName = '';
                        cacheZip = '';
                        cacheTech = '';
			    		if(action == 'insert'){
				    		if(!($('.no-validate').hasClass('hidden'))){
					    		Js_Top.error('Route number already exists. Please pick a unique number.');
					    	}
					    	else{
					    		$.post('<?=url::base()?>routes/insertRoute',{
						    		no: route_no,
						    		name: route_name,
						    		zip: route_zip,
						    		technician: route_technician,
						    		idSet: setActive,
						    		map: saveData
						    	})
						    	.done(function (result) {
						    		if(result){
						    			Js_Top.closeNav();
						    			Js_Top.success('Save Success.');
						    			Route_Active.settings.TblActive.DataTable().draw();
						    		}
						    		else{
						    			Js_Top.warning('Some error!');
						    		}
						    	})
				    		}
				    	}
				    	else if(action == 'edit'){
			    			$.post('<?=url::base()?>routes/updateRoute',{
					    		no: route_no,
					    		name: route_name,
					    		zip: route_zip,
					    		technician: route_technician,
					    		id: idRoute
					    	})
					    	.done(function (result) {
					    		if(result){
					    			Js_Top.closeNav();
					    			Js_Top.success('Save Success.');
					    			Route_Active.settings.TblActive.DataTable().draw();
					    		}
					    		else{
					    			Js_Top.warning('Some error!');
					    		}
					    	})
			    		}	
			    	}
	    		}
	    	})
	    },
	    DeleteRoute: function (page = '') {
            $.confirm({
                title: 'Warning!',
                content: 'Are you sure to delete selected route ?',
                columnClass: 'col-md-4 col-centered',
                containerFluid: true,
                buttons: {
                    OK: {
                        text: 'OK',
                        btnClass: 'btn-blue',
                        keys: ['enter'],
                        action: function(){
                            if(page == ''){
                                if(!selectAll)
                                {
                                    if(selected.length == 0)
                                        Js_Top.error('Nothing to delete!');
                                    else{
                                        $.post('<?=url::base()?>routes/deleteRoute',{selectAll: selectAll, selected: selected, unselected: unselected,idSet: setActive}).done(function (result) {
                                            Route_Active.settings.TblActive.DataTable().draw();
                                            textSelect.text(0);
                                            selected = [];
                                            unselected = [];
                                        })
                                    }
                                }
                                else{
                                    if(unselected.length == Total_Record)
                                        Js_Top.error('Nothing to delete!');
                                    else{
                                        $.post('<?=url::base()?>routes/deleteRoute',{selectAll: selectAll, selected: selected, unselected: unselected,idSet: setActive}).done(function (result) {
                                            textSelect.text(0);
                                            selected = [];
                                            unselected = [];
                                            Route_Active.settings.TblActive.DataTable().draw();
                                        })
                                    }
                                }
                            }
                            else{
                                $.post('<?=url::base()?>routes/deleteOneRoute',{route_id: idRoute});
                                Js_Top.closeNav();
                                Js_Top.success('Save Success.');
                                Route_Active.settings.TblActive.DataTable().draw();
                            }
                        }
                    },
                    Cancel: {
                        text: 'Cancel',
                        btnClass: 'btn-default',
                        action: function(){
                            this.close();
                        }
                    }
                }
            });
	    },
        NextPage: function() {
		    page++;
		    if(page == total_page) {
                page = total_page - 1;
            }
            else
                Table_Active.page(page).draw('page');

            // Route_Active.LoadDefault();
        },
        PreviousPage: function() {
		    page--;
            if(page < 0) {
                page = 0;
            }
            else
                Table_Active.page(page).draw('page');
            // Route_Active.LoadDefault();
        },
	    Ready: function(arr_total_check){
		    Route_Active.LoadDefault();
		    Route_Active.LoadMap();
	    },
	    init: function(){
	    	Dom_Active = this.settings;
	        Dom_Active.TblActive;
	        Dom_Active.CheckboxAll;
	        Dom_Active.BtnPrev;
	        Dom_Active.BtnNext;
	        Dom_Active.TotalRecord;
	        Dom_Active.Button_Unselect_All;
	        Dom_Active.Button_All;
	        this.Ready();
	    }
	}
	// function view_map() {
	// 	var goo = google.maps;

	//     var map = new goo.Map(document.getElementById('map_' + setActive), {
	//       center: {lat: 10.830102, lng: 106.639720},
	//       zoom: 15
	//     });
	// }
	// $(document).ready(function () {
	// 	view_map();
	// })
</script>