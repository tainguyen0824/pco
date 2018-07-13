<!-- attachments -->
<div role="tabpanel" class="tab-pane content_attachments_customers" id="attachments_customers_">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped attachments_">
                <thead>
                    <tr>
                        <th style="width: 5%;"></th>
                        <th class="left"><div class="col-xs-12">Attachment</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($Service_Attachment)): ?>
                        <?php foreach ($Service_Attachment as $key => $Attachment): ?>
                            <tr>
                                <td style="text-align: left;padding-left: 0;">
                                    <button type="button" onclick="Js_Top.Remove_Attachments(this,<?php echo $Attachment['id'] ?>)" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span></button> 
                                </td>
                                <td class="left">
                                    <input type="hidden" name="attachments_[]" value="<?php echo !empty($Attachment['file_name'])?$Attachment['file_name']:''; ?>"/>
                                    <input type="hidden" name="id_attachments_[]" value="<?php echo !empty($Attachment['id'])?$Attachment['id']:''; ?>"/>
                                    <input type="hidden" name="key_number_attachments_[]" value="0"/>
                                    <div class="col-xs-6">
                                        <?php if(!empty($Attachment['file_name'])): ?>
                                            <?php echo $Attachment['file_name']; ?>
                                        <?php else: ?>
                                            <label class="btn btn-primary btn-sm">
                                                <i class="fa fa-upload" aria-hidden="true"></i> Browse...<input onchange="Js_Top.Upload_Attachments(this)" type="file" style="display: none;">
                                            </label>
                                            <label style="display:none"></label>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- end attachements -->