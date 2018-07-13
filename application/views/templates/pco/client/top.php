<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<!-- Calendar Scheduling -->
<link href="<?php echo url::base() ?>plugins/calendar_scheduling/css/bootstrap-year-calendar.css" rel="stylesheet" type="text/css">
<!-- Boostrap css -->
<link rel="stylesheet" href="<?php echo url::base() ?>plugins/bootstrap/bootstrap.min.css">
<!-- Bootstrap Dropdown Hover CSS -->
<link href="<?php echo url::base() ?>plugins/bootstrap_dropdown_hover/css/animate.min.css" rel="stylesheet">
<link href="<?php echo url::base() ?>plugins/bootstrap_dropdown_hover/css/bootstrap-dropdownhover.min.css" rel="stylesheet">
<!-- Jquery tags input -->
<link rel="stylesheet" href="<?=url::base()?>plugins/jQuery-Tags-Input/dist/jquery.tagsinput.min.css">
<!-- calendar css -->
<link rel='stylesheet' href='<?php echo url::base() ?>plugins/fullcalendar/fullcalendar.css'  />
<link rel='stylesheet' href='<?php echo url::base() ?>plugins/fullcalendar/fullcalendar.print.css'  media='print' />
<!-- timepicker -->
<link rel="stylesheet" media="all" type="text/css" href="<?php echo url::base() ?>plugins/jquery/jquery-ui.css" />
<link rel="stylesheet" media="all" type="text/css" href="<?php echo url::base() ?>plugins/timepicker/jquery-ui-timepicker-addon.css" />
<!-- Datepicker -->
<link rel="stylesheet" href="<?php echo url::base()?>plugins/datepicker/bootstrap-datepicker.css">
<!-- fa icon boostrap css -->
<link rel="stylesheet" href="<?php echo url::base() ?>plugins/fontawesome/css/font-awesome.css">
<!-- Datatable -->
<link rel="stylesheet" type="text/css" href="<?php echo url::base()?>plugins/datatable/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo url::base()?>plugins/datatable/css/buttons.dataTables.min.css">
<!-- growl -->
<link rel="stylesheet" href="<?php echo url::base() ?>plugins/growl/jquery.growl.css">
<!-- jquery_confirm -->
<link rel="stylesheet" href="<?php echo url::base() ?>plugins/jquery_confirm/jquery-confirm.min.css">
<!-- style css -->
<link rel="stylesheet" href="<?php echo url::base() ?>public/css/style.css">

<!-- ======================================== JS ======================================================= -->

<!-- jQuery library -->
<script src='<?php echo url::base() ?>plugins/fullcalendar/lib/jquery.min.js'></script>
<!-- calendar scheduling -->
<script src="<?php echo url::base() ?>plugins/calendar_scheduling/js/bootstrap-year-calendar.js"></script>
<!-- global jquery -->
<?php require_once(Kohana::find_file('views/varible_global_jquery','global_jquery'));  ?>
<!-- Boostrap JavaScript -->
<script src="<?php echo url::base() ?>plugins/bootstrap/bootstrap.min.js"></script>
<!-- Bootstrap Dropdown Hover JS -->
<script src="<?php echo url::base() ?>plugins/bootstrap_dropdown_hover/js/bootstrap-dropdownhover.min.js"></script>
<!-- bootstrap tab -->
<script src="<?php echo url::base() ?>plugins/bootstrap/tab.js"></script>
<!-- calendar -->
<script src='<?php echo url::base() ?>plugins/fullcalendar/lib/moment.min.js'></script>
<?php if($this->uri->segment(1) == 'calendar'){ ?>
	<script src='<?php echo url::base() ?>plugins/fullcalendar/fullcalendar.js'></script>
<?php }else{ ?>
	<script src='<?php echo url::base() ?>plugins/fullcalendar/fullcalendar.min.js'></script>
<?php } ?>
<!-- highchart -->
<script src="<?php echo url::base() ?>plugins/highcharts/highcharts.js"></script>
<script src="<?php echo url::base() ?>plugins/highcharts/exporting.js"></script>
<!-- Datatable -->
<script type="text/javascript" src="<?php echo url::base()?>plugins/datatable/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo url::base()?>plugins/datatable/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo url::base()?>plugins/datatable/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?php echo url::base()?>plugins/datatable/js/buttons.colVis.min.js"></script>
<!-- timepicker -->
<script type="text/javascript" src="<?php echo url::base() ?>plugins/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo url::base() ?>plugins/timepicker/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="<?php echo url::base() ?>plugins/timepicker/date.js"></script>
<!-- Format Number -->
<script src="<?php echo url::base() ?>plugins/format_number/jquery.number.min.js" type="text/javascript"></script>
<!-- Maskinput -->
<script src="<?php echo url::base() ?>plugins/maskedinput/jquery.maskedinput.js" type="text/javascript"></script>
<!-- MaskinputMoney -->
<script src="<?php echo url::base() ?>plugins/maskMoney/jquery.maskMoney.min.js" type="text/javascript"></script>
<!-- Min max number input -->
<script src="<?php echo url::base() ?>plugins/min_max_number/jquery.jstepper.min.js" type="text/javascript"></script>
<!-- growl -->
<script type="text/javascript" src="<?php echo url::base() ?>plugins/growl/jquery.growl.js"></script>
<!-- jquery_confirm -->
<script src="<?php echo url::base() ?>plugins/jquery_confirm/jquery-confirm.min.js"></script>
<!-- CKeditor -->
<script src="<?php echo url::base() ?>plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo url::base() ?>plugins/ckeditor/adapters/jquery.js"></script>
<!-- style Script -->
<script src='<?php echo url::base() ?>public/js/script.js'></script>
<!-- stripe -->
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<!-- jquery_plugins -->
<script src='<?php echo url::base() ?>public/js/jquery_plugins.js'></script>
<!-- Google maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCiOq8kMZrsigU3tKmRosWjiOHual56a2M&libraries=drawing,geometry"></script>
<!-- Jquery tags input -->
<script src="<?=url::base()?>plugins/jQuery-Tags-Input/dist/jquery.tagsinput.min.js"></script>
<!-- Jquery Sortable-->
<script src="<?=url::base()?>plugins/html5sortable/jquery.sortable.min.js"></script>
<body>