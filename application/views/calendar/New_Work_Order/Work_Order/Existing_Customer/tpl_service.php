<div style="clear:both"></div>  
<div style="float:left;padding-left: 5px;"><strong style="font-size: 0.9em;">Service Address</strong></div>
<div style="text-align: right;" class="dropdown">
    <a class="dropdown-toggle icon" style="text-decoration: none;padding-right: 5px;" data-toggle="dropdown" href="javascript:void(0)" data-original-title="" title="" aria-expanded="false">
        <i class="fa fa-caret-down" aria-hidden="true"></i>
    </a>
    <ul class="dropdown-menu caret_service_existing_calendar" style="right: 1%;left:auto !important;padding: 0;border: 1px solid #000;border-bottom: 0;border-radius: 0;font-size: 0.82em;">
    	<?php if(!empty($M_Serivce)): ?>
    		<?php foreach ($M_Serivce as $key => $value): ?>
		        <li onclick="New_Work_Order.Select_Service(<?php echo $value['service_id']; ?>,<?php echo $customer_id; ?>)" style="padding: 0;border-bottom: 1px solid #000;">
		            <a href="javascript:void(0)" style="padding: 5px;">
		            	<div>
		            		<?php echo $value['service_address_name']; ?>
		            	</div>
		            	<div>
		            		<?php echo !empty($value['service_city'])?$value['service_city'].', ':''; ?>
							<?php echo !empty($value['service_state'])?$value['service_state'].' ':''; ?>
							<?php echo !empty($value['service_zip'])?$value['service_zip']:''; ?>
		            	</div>
		            </a>
		        </li>
		    <?php endforeach; ?>
	    <?php else: ?>
	    	<li>
	            <a href="javascript:void(0)"> No Data </a>
	        </li>
	    <?php endif; ?>
    </ul>
</div>
<div style="clear:both"></div>
<div style="padding-left: 5px;font-size: 0.85em;" class="Wap_Service_Address">
    <?php echo !empty($Serivce['service_address_name'])?'<p>'.$Serivce['service_address_name'].'</p>':''; ?>
	<?php echo !empty($Serivce['service_address_1'])?'<p>'.$Serivce['service_address_1'].'</p>':''; ?>
	<?php echo !empty($Serivce['service_address_2'])?'<p>'.$Serivce['service_address_2'].'</p>':''; ?>
	<p>
		<?php echo !empty($Serivce['service_city'])?$Serivce['service_city'].', ':''; ?>
		<?php echo !empty($Serivce['service_state'])?$Serivce['service_state'].' ':''; ?>
		<?php echo !empty($Serivce['service_zip'])?$Serivce['service_zip']:''; ?>
	</p>
</div>  


<input name="service_id" type="hidden" value="<?php echo !empty($Serivce['service_id'])?$Serivce['service_id']:''; ?>">