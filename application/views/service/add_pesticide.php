<div>
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 padding_zero">
        <input name="pesticide_name_<?php echo $index_service; ?>[]" placeholder="Pesticide Name"  type='text' class="form-control autocomplete_pesticide"/>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 padding_zero_right">
        <input name="pesticide_amount_<?php echo $index_service; ?>[]" placeholder="Amount"  type='text' class="form-control moneyUSD"/>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="top: 7px;">
        <span></span>
        <input name="pesticide_unit_<?php echo $index_service; ?>[]" type='hidden' class="form-control"/>
    </div>
    <i onclick="Js_Top.Remove_pesticide(<?php if(!empty($index_service)): echo $index_service; else: ?>''<?php endif; ?>,this)" class="fa fa-minus-circle" style="position: absolute;color: red;cursor: pointer;right: 0;" aria-hidden="true"></i>
    <input name="id_pesticide_<?php echo $index_service; ?>[]" type='hidden' class="form-control"/>
    <input name="id_pesticide_select_<?php echo $index_service; ?>[]" type='hidden' class="form-control id_pesticide_select"/>
    <div class="space"  style="margin-top: 0px;"></div>
</div>