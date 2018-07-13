<div id="wrap-close-overlay">
    <div class="col-lg-6 col-xs-6 title-left-close-overlay">
        <div class="DivParent">
           <div class="DivWhichNeedToBeVerticallyAligned">
                Mark Event as Complete      
           </div>
           <div class="DivHelper"></div>
        </div>
    </div>
    <div class="col-lg-6 col-xs-6 title-right-close-overlay" >
        <div style="text-align: right;">
           <button onclick="New_Work_Order.SaveCalendar(this)" type="button" class="btn btn-sm btn-primary">Post</button>
           <button type="button" class="btn btn-sm btn-primary" onclick="Js_Top.closeNav()"><i class="fa fa-times" aria-hidden="true" style="color:#fff"></i></button>
        </div>
    </div>
</div>
<div id="overlay-content" class="overlay-content">
    <p>
        The scheduled event(s) can be marked as complete with details such as pesticides used, any notes from the inspector, and any payment details received. The equivalent process on the mobile application would be the 'return slip' process.
    </p>
    <p style="margin-top: 10px;font-weight: bold;">
        Selected events:
    </p>
    <div class="row" style="font-size: 0.9em;">
        <?php if(!empty($Events)): ?>
            <?php foreach ($Events as $key => $value): ?>
                <div style="background-color: #ccdbdc;overflow: hidden;padding: 10px;width: 97%;margin: 0 auto;margin-top: 10px;">
                    <div class="col-lg-3">
                        <span style="display: inline-block;background-color: red;height: 10px;width: 10px;"></span> <span>John - Technician</span>
                        <div>10:45am - 11:45am</div>
                        <div>Mon, Jan 2, 2017</div>
                        <div><strong>Service Type:</strong> 02 I/O Ants</div>
                        <div><strong>Silvia Lorre</strong></div>
                        <div>1420 Derpington St</div>
                        <div>Mountain View, CA 94085</div>
                    </div>
                    <div class="col-lg-3">
                        <p><strong>Pesticides Used</strong></p>
                        <div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 padding_zero">
                                <input name="pesticide_name_1[]" placeholder="Pesticide Name" type="text" class="form-control autocomplete_pesticide ui-autocomplete-input" autocomplete="off">
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 padding_zero_right">
                                <input name="pesticide_amount_1[]" placeholder="Amount" type="text" class="form-control moneyUSD">
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="top: 7px;">
                                <span></span>
                                <input name="pesticide_unit_1[]" type="hidden" class="form-control">
                            </div>
                            <i onclick="Js_Top.Remove_pesticide(1,this)" class="fa fa-minus-circle" style="position: absolute;color: red;cursor: pointer;" aria-hidden="true"></i>
                            <input name="id_pesticide_1[]" type="hidden" class="form-control">
                            <input name="id_pesticide_select_1[]" type="hidden" class="form-control id_pesticide_select">
                            <div class="space" style="margin-top: 0px;"></div>
                        </div>
                        <div class="col-lg-12 padding_zero">
                            <button onclick="Js_Top.Add_pesticide('')" type="button" class="btn btn-primary btn-sm"> <span class="glyphicon glyphicon-plus"></span></button>
                            <div class="space" style="margin-top: 0px;"></div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <p><strong>Invoice Total (Credit)</strong></p>
                        <p>33.00</p>
                        <p><strong>Payment (Debit)</strong></p>
                        <input style="margin-bottom: 5px;" type="text" class="form-control">
                        <select name="" class="form-control">
                            <option value="">New credit card</option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <p><strong>Notes</strong></p>
                        <textarea style="min-height: 107px;" name="" class="form-control"></textarea>
                    </div>
                </div> 
            <?php endforeach; ?>
        <?php endif; ?>
    </div>  
</div>
