<?php if(!empty($customers_logs)): ?>
	<?php foreach ($customers_logs as $key => $value): ?>
		<div style="margin: 5px;border: 0.5px solid #a5a5a5;padding: 5px;background-color: #efefef;">
			<table style="width:100%;font-size: 13px;" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width: 40%;">
						<button onclick="Customer_Edit.Deletelogs(<?php echo $value['id']; ?>)" type="button" class="btn btn-xs btn-primary"><i class="fa fa-times" aria-hidden="true" style="color:#fff"></i></button>
						<strong>(<?php echo !empty($value['First_name'])?$value['First_name']:''; ?> <?php echo !empty($value['Last_name'])?$value['Last_name']:''; ?>, <?php echo date('h:ia m/d/Y',$value['time']); ?>)</strong>
					</td>
					<td style="width: 60%;">
						<?php echo !empty($value['content'])?$value['content']:''; ?>
					</td>
				</tr>
			</table>
		</div>
		
	<?php endforeach; ?>
<?php endif; ?>