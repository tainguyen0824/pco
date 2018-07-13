<tr>
    <td style="text-align: left;padding-left: 0;">
        <button onclick="Js_Top.Remove_Commissions(this);" type="button" class="btn btn-danger btn-sm"> <span class="glyphicon glyphicon-remove"></span></button>
    </td>
    <td>
        <select name="commission_technician_<?php echo $index_service; ?>[]" class="form-control">
            <option value=""><?php echo $this->Technician_No_Name; ?></option>
            <?php if(!empty($customers_technician)): ?>
                <?php foreach ($customers_technician as $key => $value): ?>
                    <option value="<?php echo $value['id']; ?>"><?php echo !empty($value['name'])?$value['name']:''; ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </td>
    <td>
        <select  onchange="Js_Top.Calculator_Commission_Type(this)" name="commission_type_<?php echo $index_service; ?>[]" class="form-control">
            <option value="percent">Percentage of sales (excl. discount and tax)</option>
            <option value="number">Number</option>
        </select>
    </td>
    <td>
        <div class="Wap_Amount_Commission">
            <input  onkeyup="Js_Top.Calculator_Commission_Amount(this)" name="commission_amount_<?php echo $index_service; ?>[]" type="text" class="Amount_Commission form-control OnlyNumberDot MinmaxPercent" value="0">
            <i class="fa fa-percent percent_commission" aria-hidden="true"></i> 
            <i class="fa fa-usd number_commission" aria-hidden="true" style="display:none"></i>                                    
        </div>
    </td>
    <td style="text-align: right;">
        <span class="Total_line_commission">0.00</span>
        <input type="hidden" name="ID_Commissions_<?php echo $index_service; ?>[]" />
    </td>
</tr>