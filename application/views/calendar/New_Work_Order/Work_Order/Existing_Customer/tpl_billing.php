<div style="padding-left: 5px;"><strong style="font-size: 0.9em;">Billing Address</strong></div>
<div style="padding-left: 5px;font-size: 0.85em;">
    <?php echo !empty($Billing[0]['billing_name'])?'<p>'.$Billing[0]['billing_name'].'</p>':''; ?>
	<?php echo !empty($Billing[0]['billing_address_1'])?'<p>'.$Billing[0]['billing_address_1'].'</p>':''; ?>
	<?php echo !empty($Billing[0]['billing_address_2'])?'<p>'.$Billing[0]['billing_address_2'].'</p>':''; ?>
	<p>
		<?php echo !empty($Billing[0]['billing_city'])?$Billing[0]['billing_city'].', ':''; ?>
		<?php echo !empty($Billing[0]['billing_state'])?$Billing[0]['billing_state'].' ':''; ?>
		<?php echo !empty($Billing[0]['billing_zip'])?$Billing[0]['billing_zip']:''; ?>
	</p>
</div> 
<input name="billing_id" type="hidden" value="<?php echo !empty($Billing[0]['id'])?$Billing[0]['id']:''; ?>">