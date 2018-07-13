<form class="form-horizontal Frm_activity_history" role="form" style="overflow: hidden;">
    <div class="form-group">
        <label class="col-sm-12" for="PO">PO#</label>
        <div class="col-sm-12">
          <input type="text" class="form-control Auto_PO" name="PO" id="PO">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-12" for="service_address">Service Address</label>
        <div class="col-sm-12">
            <input type="text" class="form-control" name="service_address" id="service_address">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-12">Tecnician</label>
        <div class="col-sm-12">
            <select class="form-control" id="tecnician" name="tecnician">
                <option value="0">------</option>
                <?php if(!empty($_technician)): ?>
                    <?php foreach ($_technician as $_t): ?>
                        <option value="<?php echo $_t['id'] ?>"><?php echo $_t['name'] ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-12">Route</label>
        <div class="col-sm-12">
            <select class="form-control" name="route">
                <option value="0">------</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-12" for="activity">Activity</label>
        <div class="col-sm-12">
            <textarea name="activity" class="form-control Ckeditor"></textarea>
        </div>
    </div>
</form>
