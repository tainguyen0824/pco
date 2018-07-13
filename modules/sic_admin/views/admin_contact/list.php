 <div id="page-wrapper">
    <div id="page-inner"> 
        <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Danh sách liên hệ
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table style="font-size: 14px;" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Số thứ tự</th>
                                            <th>Họ và tên</th>
                                            <th>E-mail</th>
                                            <th>Nội dung</th>
                                            <th>Trạng thái</th>
                                            <th colspan="2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($m_list)){ foreach ($m_list as $key => $value) { ?>
                                            <tr class="odd gradeX">
                                                <td><?php echo $value['id']; ?></td>
                                                <td><?php echo $value['name']; ?></td>
                                                <td><?php echo $value['email']; ?></td>
                                                <td><?php echo $value['message']; ?></td>
                                                <td><?php echo $value['status']; ?></td>
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
    </div>
</div>
