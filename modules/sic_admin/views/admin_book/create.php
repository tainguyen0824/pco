 <div id="page-wrapper">
    <div id="page-inner"> 
        <div class="row">
                <div class="col-md-2 col-xs-12"></div>
                <div class="col-md-8 col-xs-12">
                    <form method="POST" action="<?php echo url::base() ?>admin_book/save_create" enctype="multipart/form-data">
                        <fieldset>
                            <legend>Thêm sách:</legend>
                            <div class="form-group">
                                <label>Tên sách</label>
                                <input name="name_book" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Hình ảnh</label>
                                <input name="image" type="file">
                            </div>
                            <div class="form-group">
                                <label>File demo</label>
                                <input name="file_demo" type="file">
                            </div>
                            <div class="form-group">
                                <label>File dowload</label>
                                <input name="file_dowload" type="file">
                            </div>
                            <div class="form-group">
                                <label>Ngày đăng</label>
                                <input name="date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Mô tả</label>
                                <textarea name="description" class="form-control">
                                    
                                </textarea>

                            </div>
                            <div class="form-group">
                                <label>Loai sach</label>
                                <input name="type_book" class="form-control">

                            </div>
                            <div class="form-group">
                                <label>Trạng thái</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="active" id="optionsRadios1" value="1" checked=""> Active
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="active" id="optionsRadios2" value="0"> No Active
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-default">Submit Button</button> 
                        </fieldset>
                    </form>
                </div>
                <div class="col-md-2 col-xs-12"></div>
        </div>

    </div>
</div>
