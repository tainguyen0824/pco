<tr>
    <td style="text-align: left;padding-left: 0;">
        <button onclick="Js_Top.Remove_Attachments(this)" type="button" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span></button> 
    </td>
    <td class="left">
        <input type="hidden" name="attachments_<?php echo $index_service; ?>[]"/>
        <input type="hidden" name="id_attachments_<?php echo $index_service; ?>[]" />
        <input type="hidden" name="key_number_attachments_<?php echo $index_service; ?>[]" value="0"/> <!-- chua su dung -->
        <div class="col-lg-6 col-sm-6 col-12">
            <label class="btn btn-primary btn-sm">
                <i class="fa fa-upload" aria-hidden="true"></i> Browse...<input onchange="Js_Top.Upload_Attachments(this)" type="file" style="display: none;">
            </label>
            <label style="display:none"></label>
        </div>
    </td>
</tr>