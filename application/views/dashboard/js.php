<script>
	Dashboard = {
	    settings: {}, 
	    Chart: function(){
	    	$('#container_highchart').highcharts({
			    tooltip: {
			        pointFormat: "Value: {point.y:,.1f} mm"
			    },
			    xAxis: {
			        // type: 'datetime',
			        // labels: {
			        //     format: '{value:%Y/%m/%d}',
			        //     rotation: 0,
			        //     align: 'left'
			        // }
			        categories: ['2017/01/01', '2017/01/08', '2017/01/15', '2017/01/22', '2017/01/29']
			    },
			    series: [{
			        data: [1029.9, 1071.5, 1106.4, 1129.2, 1144.0],
			    }, {
			        data: [1059.9, 1011.5, 1567.4, 1179.2, 1794.0],
			    }, {
			        data: [1259.9, 1411.5, 1267.4, 1119.2, 1894.0],
			    }, ]
			});
	    },
	    Calendar: function(){
	    	$('#calendar_dashboard').fullCalendar({
		        header: false,
		        height: 'auto',
		        allDaySlot: false,
		        defaultView: 'agendaDay',
		        scrollTime: '24:00:00',
		        slotDuration: '00:15:00',
		        defaultTimedEventDuration: '00:30:00'
		    })
	    },
	    Ready: function(){
	        Dashboard.Chart();
	        Dashboard.Calendar();   
	    },
	    init: function() {
	        Dom = this.settings;
	        Js_Top.hide_loading();
	        this.Ready();
	    } 
	};
	$(document).ready(function() {
	    Dashboard.init();
	});
</script>



    