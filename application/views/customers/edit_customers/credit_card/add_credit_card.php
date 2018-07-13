<form class="form-horizontal Frm_credit_card" role="form" style="overflow: hidden;">
    <div class="form-group">
        <label class="col-sm-3 control-label" for="card-holder-name">Name on Card</label>
        <div class="col-sm-9">
          <input data-stripe="name" type="text" class="form-control" name="card_name" id="card-holder-name" placeholder="Card Holder's Name">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label" for="card-number">Card Number</label>
        <div class="col-sm-9">
            <input data-stripe="number" type="text" class="form-control" name="card_number" id="card-number" placeholder="Debit/Credit Card Number">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label" for="expiry-month">Expiration Date</label>
        <div class="col-sm-9">
            <div class="row">
                <div class="col-xs-3">
                    <input data-stripe="exp-month" type="text" class="form-control col-sm-2" name="expiry_month" id="expiry-month" placeholder="Month">
                </div>
                <div class="col-xs-3">
                    <input data-stripe="exp-year" type="text" class="form-control col-sm-2" name="expiry_year" id="expiry-month" placeholder="Year">
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Autopay Attachment</label>
        <div class="col-sm-9">
            <select class="form-control" id="autopay" name="autopay">
                <option value="0">------</option>
                <?php if(!empty($Services)): ?>
                    <?php foreach ($Services as $key => $value): ?>
                        <option value="<?php echo $value['service_id'] ?>"><?php echo $value['service_name'] ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>
</form>
