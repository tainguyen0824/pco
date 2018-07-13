<tr style="background-color:#f1f1f1">
    <td class="align-middle" style="text-align: left;padding-left: 0;">
        <button onclick="Js_Top.Remove_Billing(this)" type="button" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span></button> 
    </td>
    <td class="align-middle" style="width: 20%">
        <select name="lineitems_type_<?php echo $index_service; ?>[]" class="form-control" style="min-width: 130px">
            <?php foreach ($this->TypeBilling as $key => $value): ?>
                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
            <?php endforeach; ?>
        </select>
    </td>
    <td class="align-middle">
        <input name="lineitems_description_<?php echo $index_service; ?>[]" type="text" class="form-control">
    </td>
    <td class="align-middle">
        <input onkeyup="Js_Top.Change_Quantity_Billing(this)" name="lineitems_quantity_<?php echo $index_service; ?>[]" type="text" class="form-control OnlyNumber">
    </td>
    <td class="align-middle">
        <input onkeyup="Js_Top.Change_Unit_Price_Billing(this)" name="lineitems_unit_price_<?php echo $index_service; ?>[]" type="text" class="form-control val_lineitems_unit_price moneyUSD">
    </td>
    <td style="vertical-align: middle;" class="txt_lineitems_amount align-middle">
        <span class="B_amount">0.00</span>
    </td>
    <td class="align-middle">
        <div class="custom-checkbox">
            <input onchange="Js_Top.Change_Taxable_Billing(this)" name="checkbox_billing_taxable_<?php echo $index_service; ?>[]" type="checkbox" class="chk_taxable" id="taxable_<?php $time = time(); echo $time; ?>">
            <label for="taxable_<?php echo $time; ?>"></label>
        </div>
        <input value="0" name="val_lineitems_checkbox_tax_<?php echo $index_service; ?>[]" type="hidden" class="val_checkbox_tax form-control">
        <input name="id_billing_<?php echo $index_service; ?>[]" type="hidden" class="form-control">
    </td>
</tr>