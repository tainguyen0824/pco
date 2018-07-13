<style type="text/css">
    .pagination{
        margin: 0px;
        padding: 0px;
        font-size: 14px;
    }
</style>
<div id="page-wrapper">
    <div id="page-inner"> 
        <div class="row">
            <div class="col-md-6" style="margin-bottom: 20px;margin-top: 20px;">
                <form>
                    <label>Tìm kiếm</label>
                    <input style="display: inline-block;width: 385px;" class="form-control">
                    <button style="display: inline-block;margin-top: -4px;padding: 7px 10px;" class="btn btn-primary btn-sm" >Tìm kiếm</button>
                </form>
                
            </div>
            <div class="col-md-6" style="text-align:right;margin-top: 20px;">
            <a href="<?php echo url::base() ?>admin_book/create" class="btn btn-info">Thêm sách</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                         Danh sách thư viện sách
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table style="font-size: 14px;" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr style="color: #0866c6;">
                                        <th style="text-align: center;">Số thứ tự</th>
                                        <th style="text-align: center;">Tên sách</th>
                                        <th style="text-align: center;">Hình ảnh</th>
                                        <th style="text-align: center;">File demo</th>
                                        <th style="text-align: center;">File dowload</th>
                                        <th style="text-align: center;">Ngày đăng</th>
                                        <th style="text-align: center;">Mô tả</th>
                                        <th style="text-align: center;">Trạng thái</th>
                                        <th style="text-align: center;" colspan="2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($m_list)){ foreach ($m_list as $key => $value) { ?>
                                        <tr class="odd gradeX">
                                            <td style="text-align: center;"><?php echo $value['id']; ?></td>
                                            <td style="text-align: center;"><?php echo $value['name_book']; ?></td>
                                            <td style="text-align: center;"><?php echo $value['image']; ?></td>
                                            <td style="text-align: center;"><?php echo $value['file_demo']; ?></td>
                                            <td style="text-align: center;"><?php echo $value['file_dowload']; ?></td>
                                            <td style="text-align: center;"><?php echo $value['date']; ?></td>
                                            <td style="text-align: center;"><?php echo $value['description']; ?></td>
                                            <td style="text-align: center;"><?php echo $value['active']; ?></td>
                                            <td style="text-align: center;"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></td>
                                            <td style="text-align: center;"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></td>
                                        </tr>
                                    <?php }} ?>
                                </tbody>
                            </table>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div  class="dataTables_info" id="dataTables-example_info" role="alert" aria-live="polite" aria-relevant="all">Tổng cộng <span style="color: red;font-size: 15px;font-weight: bold;"><?php echo $this->pagination->total_items; ?></span> mục</div>
            </div>
            <div class="col-sm-6">
                <div style="text-align: right;">
                    <?php echo isset($this->pagination)?$this->pagination:''?>
                </div>
            </div>
        </div>
    </div>
</div>
