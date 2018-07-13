<p>Below is a summary of customer #<?php echo !empty($DataPost['customer_no'])?$DataPost['customer_no']:''; ?>. Click Save to commit and close this window, or X to close without saving.</p>

<div class="space_finish">
	<p><strong>Customer Name</strong></p>
	<p><?php echo !empty($DataPost['customer_name'])?$DataPost['customer_name']:''; ?></p>
</div>

<div class="space_finish">
	<p><strong>Billing Information</strong></p>
	<?php echo !empty($DataPost['billing_address_1'])?'<p>'.$DataPost['billing_address_1'].'</p>':''; ?>
	<?php echo !empty($DataPost['billing_address_2'])?'<p>'.$DataPost['billing_address_2'].'</p>':''; ?>
	<p>
		<?php echo !empty($DataPost['billing_city'])?$DataPost['billing_city'].', ':''; ?>
		<?php echo (!empty($DataPost['billing_state'] && (!empty($DataPost['billing_city']) || !empty($DataPost['billing_zip']))))?$DataPost['billing_state'].', ':''; ?>
		<?php echo !empty($DataPost['billing_zip'])?$DataPost['billing_zip']:''; ?>
	</p>
	<?php echo !empty($DataPost['billing_email'])?'<p>'.$DataPost['billing_email'].'</p>':''; ?>
	<?php if(!empty($DataPost['billing_phone_number'])): ?>
		<?php foreach ($DataPost['billing_phone_number'] as $key => $B_phone): ?>
			<?php if(!empty($B_phone)): ?>
				<p><?php echo $this->PositionPhone[$DataPost['billing_phone_type'][$key]] ?>: <?php echo $B_phone; ?> <?php echo !empty($DataPost['billing_phone_ext'][$key])?'Ext: '.$DataPost['billing_phone_ext'][$key]:''; ?></p>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
<?php if(!empty($DataPost['index_service']) && empty($DataPost['skip_service'])): ?>
	<?php foreach ($DataPost['index_service'] as $key => $Number_Service): ?>
		<div class="space_finish">
			<?php echo !empty($DataPost['service_name_'.$Number_Service])?'<p><strong>'.$DataPost['service_name_'.$Number_Service].'</strong></p>':''; ?>
			<?php echo !empty($DataPost['service_address_1_'.$Number_Service])?'<p>'.$DataPost['service_address_1_'.$Number_Service].'</p>':''; ?>
			<?php echo !empty($DataPost['service_address_2_'.$Number_Service])?'<p>'.$DataPost['service_address_2_'.$Number_Service].'</p>':''; ?>
			<p>
				<?php echo !empty($DataPost['service_city_'.$Number_Service])?$DataPost['service_city_'.$Number_Service].', ':''; ?>
				<?php echo (!empty($DataPost['service_state_'.$Number_Service]) && ((!empty($DataPost['service_city_'.$Number_Service]) || !empty($DataPost['service_zip_'.$Number_Service]))))?$DataPost['service_state_'.$Number_Service].', ':''; ?>
				<?php echo !empty($DataPost['service_zip_'.$Number_Service])?$DataPost['service_zip_'.$Number_Service]:''; ?>
			</p>
			<div class="space_finish"></div>
			<?php echo !empty($DataPost['service_property_type_'.$Number_Service])?'<p>Property Type: '.$DataPost['service_property_type_'.$Number_Service].'</p>':''; ?>
			<?php echo !empty($DataPost['service_service_type_'.$Number_Service])?'<pService Type: '.$DataPost['service_service_type_'.$Number_Service].'</p>':''; ?>
			<p>Service Charge: $440</p>
			<p>Billing Frequency: Coincide with each service</p>
			<?php echo !empty($DataPost['scheduling_frequency_'.$Number_Service])?'<pService Frequency: '.$DataPost['scheduling_frequency_'.$Number_Service].'</p>':''; ?>
			<?php echo !empty($DataPost['service_route_'.$Number_Service])?'<p>Route: '.$DataPost['service_route_'.$Number_Service].'</p>':''; ?>
			<?php echo !empty($DataPost['service_salesperson_'.$Number_Service])?'<p>Salesperson: '.$DataPost['service_salesperson_'.$Number_Service].'</p>':''; ?>
			<?php echo !empty($DataPost['scheduling_technician_'.$Number_Service])?'<p>Technician: '.$DataPost['scheduling_technician_'.$Number_Service].'</p>':''; ?>
		</div>
	<?php endforeach; ?>
<?php endif; ?>
