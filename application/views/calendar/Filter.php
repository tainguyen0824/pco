<div class="col-lg-12" style="margin-top: 1em;">
	<p><b>Filter by Technician</b></p>
	<?php if(!empty($Arr_Technician)): ?>
		<?php $technicianDB = ''; ?>
		<?php foreach ($Arr_Technician as $key => $value): ?>
			<div class="space"></div>
			<div class="wrap_filter_calendar" style="background-color: <?php echo $value['color'] ?>;">
				<div class="custom-checkbox">
					<input checked="checked" onchange="Calendar.Filter_Calendar()" class="filter_technician_calendar" value="<?php echo $value['technician']; ?>" type="checkbox" id="<?php echo $value['technician_name']; ?>">
					<label for="<?php echo $value['technician_name']; ?>"></label>
				</div>
				<?php echo $value['technician_name']; ?>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
<div class="col-lg-12" style="margin-top: 1em;">
	<p><b>Filter by Area</b></p>
	<div class="space"></div>
	<div class="custom-checkbox-radio">
		<input onchange="Calendar.ChangeFilter(this);Calendar.Filter_Calendar()" style="margin-left:0" name="filter_area_calendar" class="filter_area_calendar" checked="checked" value="no_filter" type="radio"> 
		<span style="position: relative;bottom: 5px;">No area filter</span>
	</div>

	<?php if(!empty($Arr_Filter_Zip)): ?>
		<div class="custom-checkbox-radio">
			<input onchange="Calendar.ChangeFilter(this);Calendar.Filter_Calendar()" style="margin-left:0" name="filter_area_calendar" class="filter_area_calendar" value="filter_zip" type="radio"> 
			<span style="position: relative;bottom: 5px;">Zip</span>
		</div>
		<div id="filter_zip" >
			<?php foreach ($Arr_Filter_Zip as $key => $value): ?>
				<div class="space"></div>
				<div class="custom-checkbox-radio">
					<input onchange="Calendar.Filter_Calendar()" style="margin-left:0" <?php if($key == 0) echo 'checked' ?> name="filter_zip_calendar" value="<?php echo substr($value, 4); ?>" type="radio"> 
					<span style="position: relative;bottom: 5px;"><?php echo substr($value, 4); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if(!empty($Arr_Filter_City)): ?>
		<div class="custom-checkbox-radio">
			<input onchange="Calendar.ChangeFilter(this);Calendar.Filter_Calendar()" style="margin-left:0" name="filter_area_calendar" class="filter_area_calendar" value="filter_city" type="radio"> 
			<span style="position: relative;bottom: 5px;">City</span>
		</div>
		<div id="filter_city" class="collapse">
			<?php foreach ($Arr_Filter_City as $key => $value): ?>
				<div class="space"></div>
				<div class="custom-checkbox-radio">
					<input onchange="Calendar.Filter_Calendar()" style="margin-left:0" <?php if($key == 0) echo 'checked' ?> name="filter_city_calendar" value="<?php echo $value; ?>" type="radio"> 
					<span style="position: relative;bottom: 5px;"><?php echo $value; ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
