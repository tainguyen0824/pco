<p class="T_title">Options</p>
<form action="<?php echo url::base() ?>options/save" method="POST" accept-charset="utf-8">
<div class="row">
	<div class="col-lg-6">
		<fieldset>
  			<legend>Workdays:</legend>
  			<div class="table-responsive">
  				<table id="Tbl_Options_Workdays" class="table tbl_Options">
					<thead>
						<tr>
							<th>Sunday</th>
							<th>Monday</th>
							<th>Tuesday</th>
							<th>Wednesday</th>
							<th>Thursday</th>
							<th>Friday</th>
							<th>Saturday</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<div class="custom-checkbox">
								    <input <?php if(!empty($Option) && !empty($Option[0]['workdays'])): $this->checked_workdays($Option[0]['workdays'],'Sunday'); endif; ?> type="checkbox" name="workdays_sunday" value="Sunday" id="workdays_1">
								    <label for="workdays_1"></label>
								</div>
							</td>
							<td>
								<div class="custom-checkbox">
								    <input <?php if(!empty($Option) && !empty($Option[0]['workdays'])): $this->checked_workdays($Option[0]['workdays'],'Monday'); endif; ?> type="checkbox" name="workdays_monday" value="Monday" id="workdays_2">
								    <label for="workdays_2"></label>
								</div>
							</td>
							<td>
								<div class="custom-checkbox">
								    <input <?php if(!empty($Option) && !empty($Option[0]['workdays'])): $this->checked_workdays($Option[0]['workdays'],'Tuesday'); endif; ?> type="checkbox" name="workdays_tuesday" value="Tuesday" id="workdays_3">
								    <label for="workdays_3"></label>
								</div>
							</td>
							<td>
								<div class="custom-checkbox">
								    <input <?php if(!empty($Option) && !empty($Option[0]['workdays'])): $this->checked_workdays($Option[0]['workdays'],'Wednesday'); endif; ?> type="checkbox" name="workdays_wednesday" value="Wednesday" id="workdays_4">
								    <label for="workdays_4"></label>
								</div>
							</td>
							<td>
								<div class="custom-checkbox">
								    <input <?php if(!empty($Option) && !empty($Option[0]['workdays'])): $this->checked_workdays($Option[0]['workdays'],'Thursday'); endif; ?> type="checkbox" name="workdays_thursday" value="Thursday" id="workdays_5">
								    <label for="workdays_5"></label>
								</div>
							</td>
							<td>
								<div class="custom-checkbox">
								    <input <?php if(!empty($Option) && !empty($Option[0]['workdays'])): $this->checked_workdays($Option[0]['workdays'],'Friday'); endif; ?> type="checkbox" name="workdays_friday" value="Friday" id="workdays_6">
								    <label for="workdays_6"></label>
								</div>
							</td>
							<td>
								<div class="custom-checkbox">
								    <input <?php if(!empty($Option) && !empty($Option[0]['workdays'])): $this->checked_workdays($Option[0]['workdays'],'Saturday'); endif; ?> type="checkbox" name="workdays_saturday" value="Saturday" id="workdays_7">
								    <label for="workdays_7"></label>
								</div>
							</td>
						</tr>
					</tbody>	
				</table>
  			</div>
 		</fieldset>
 		<fieldset>
  			<legend>Scheduling Options:</legend>
  			<label class="lb_title">
  				In auto-scheduling events, if the day meeting the scheduling criteria is not available, the system will automatically shift the date to the next available slot. Select the preferred direction of the offset:
  			</label>
  			<div class="table-responsive">
  				<table id="Tbl_Options_Scheduling" class="table tbl_Options">
					<thead>
						<tr>
							<th>Shift forward (future)</th>
							<th>Shift backward (past)</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<div class="custom-checkbox-radio">
								    <input type="radio" <?php if(!empty($Option) && !empty($Option[0]['scheduling_options']) && $Option[0]['scheduling_options'] == 1): echo 'checked';  endif; ?> value="1" name="P_Up_Down" id="scheduling_1">
								    <label for="scheduling_1"></label>
								</div>
							</td>
							<td>
								<div class="custom-checkbox-radio">
								    <input type="radio" <?php if(!empty($Option) && !empty($Option[0]['scheduling_options']) && $Option[0]['scheduling_options'] == 2): echo 'checked';  endif; ?> name="P_Up_Down" value="2" id="scheduling_2">
								    <label for="scheduling_2"></label>
								</div>
							</td>
						</tr>
					</tbody>	
				</table>
  			</div>
 		</fieldset>
 		<fieldset>
  			<legend>Week Settings:</legend>
  			<div class="table-responsive">
  				<table id="Tbl_Options_Week_Setting" class="table tbl_Options">
					<thead>
						<tr>
							<th>Monday - Sunday</th>
							<th>Sunday - Saturday</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<div class="custom-checkbox-radio">
								    <input type="radio" <?php if(!empty($Option) && !empty($Option[0]['week_setting']) && $Option[0]['week_setting'] == 1): echo 'checked';  endif; ?> value="1" name="P_Week_Setting" id="week_settings_1">
								    <label for="week_settings_1"></label>
								</div>
							</td>
							<td>
								<div class="custom-checkbox-radio">
								    <input type="radio" <?php if(!empty($Option) && !empty($Option[0]['week_setting']) && $Option[0]['week_setting'] == 2): echo 'checked';  endif; ?> name="P_Week_Setting" value="2" id="week_settings_2">
								    <label for="week_settings_2"></label>
								</div>
							</td>
						</tr>
					</tbody>	
				</table>
  			</div>
 		</fieldset>
	</div>
	<div class="col-lg-6">
		<fieldset>
  			<legend>Tax:</legend>
  			<div class="table-responsive">
  				<table id="Tbl_Options_Scheduling" class="table tbl_Options">
					<tbody>
						<tr>
							<td>
								<div class="custom-checkbox-radio">
								    <select onchange="Options.Change_Tax(this)" name="slt_default_tax" class="form-control">
	                                    <option value="None|0.00">None</option>
	                                    <?php if(!empty($this->state)): ?>
	                                        <?php foreach ($this->state as $key => $value): ?>
	                                            <option <?php if(!empty($Option[0]['slt_default_tax']) && $Option[0]['slt_default_tax'] == $value['state_code'].'|'.$value['_state_tax']): echo 'selected'; endif; ?> value="<?php echo $value['state_code'] ?>|<?php echo $value['_state_tax'] ?>"><?php echo $value['state_code']; ?> - <?php echo $value['_state_tax'].'%' ?></option>
	                                        <?php endforeach; ?>
	                                    <?php endif; ?>
	                                </select>
								</div>
							</td>
							<td>
								<input value="<?php echo !empty($Option[0]['val_default_tax'])?$Option[0]['val_default_tax']:0; ?>" name="val_default_tax" class="val_tax form-control OnlyNumberDot" type="text">
							</td>
						</tr>
					</tbody>	
				</table>
  			</div>
 		</fieldset>
 		<fieldset>
  			<legend>Service Duration:</legend>
  			<div class="col-sm-5 col-xs-12 padding_zero">
            	<input value="<?php echo !empty($Option[0]['hours'])?$Option[0]['hours']:0 ?>" name="hours" type="text" class="form-control OnlyNumber" />
            </div>
            <div class="col-sm-5 col-xs-12 padding_boostrap">
                <input value="<?php echo !empty($Option[0]['minutes'])?$Option[0]['minutes']:0 ?>" name="minutes" type="text" class="form-control OnlyNumber MinmaxNumberMinute" />
            </div>
 		</fieldset>
 		<fieldset>
  			<legend>Automatically assign time:</legend>
  			<div class="wap_content_time">
  				<?php if(!empty($Options_time)): ?>
  					<?php foreach ($Options_time as $key => $value): ?>
		  				<div style="margin-top:5px;overflow: hidden;">
		  					<div class="col-sm-5 col-xs-5 padding_zero">
				            	<input value="<?php echo $value['start_time'] ?>" readonly="readonly" name="start_time[]" type="text" class="form-control timepickerssss" />
				            </div>
				            <div class="col-sm-5 col-xs-5 padding_boostrap">
				                <input value="<?php echo $value['end_time'] ?>" readonly="readonly" name="end_time[]" type="text" class="form-control timepickerssss" />
				            </div>
				            <div class="col-sm-2 col-xs-2 padding_zero">
				                <button type="button" class="btn btn-sm btn-danger" onclick="Options.RemoveTime(this)">X</button>
				            </div>
				            <input value="<?php echo $value['id'] ?>" readonly="readonly" name="options_time_id[]" type="hidden" class="form-control" />
		  				</div>
		  			<?php endforeach; ?>
		  		<?php else: ?>
		  			<div style="margin-top:5px;overflow: hidden;">
	  					<div class="col-sm-5 col-xs-5 padding_zero">
			            	<input readonly="readonly" name="start_time[]" type="text" class="form-control timepickerssss" />
			            </div>
			            <div class="col-sm-5 col-xs-5 padding_boostrap">
			                <input readonly="readonly" name="end_time[]" type="text" class="form-control timepickerssss" />
			            </div>
			            <div class="col-sm-2 col-xs-2 padding_zero">
			                <button type="button" class="btn btn-sm btn-danger" onclick="Options.RemoveTime(this)">X</button>
			            </div>
			            <input readonly="readonly" name="options_time_id[]" type="hidden" class="form-control" />
	  				</div>
		  		<?php endif; ?>
  			</div>

  			<div onclick="Options.AddMoreTime(this)" style="cursor:pointer;margin-top:5px;overflow: hidden;">
  				<span class="fa fa-plus" style="background: #337ab7;border-radius: 3px;padding: 3px 6px;color: #fff; "></span>
	            <div style="display: inline-block;">
	                Add more 
	            </div>
  			</div>
 		</fieldset>
	</div>
</div>
<!-- Button Save -->
<div class="row">
	<div class="col-lg-12">
		<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
	</div>
</div>
</form>