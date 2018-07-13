<table class="table table-striped table-hover service_group">
    <thead>
        <tr>
            <th>Service Name</th>
            <th>Technician</th>
            <th>Service Address</th>
            <th>Property Type</th>
            <th>Service Type</th>
            <th>Service Frequency</th>
            <th>Next Invoice Date</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($Service)): ?>
            <?php foreach ($Service as $key => $service): ?>
                <tr onclick="Customer_Edit.Edit_Service(<?php echo $customer_id; ?>,<?php echo $service['service_id']; ?>)" style="cursor:pointer">
                    <td><?php echo !empty($service['service_name'])?$service['service_name']:''; ?></td>
                    <td><?php echo !empty($service['technician'])?$service['technician']:''; ?></td>
                    <td>
                        <p><?php echo !empty($service['service_address_1'])?$service['service_address_1']:''; ?></p>
                        <p><?php echo !empty($service['service_address_2'])?$service['service_address_2']:''; ?></p>
                        <p><?php echo !empty($service['service_city'])?$service['service_city'].',':''; ?> <?php echo !empty($service['service_state'])?$service['service_state']:''; ?> <?php echo !empty($service['service_zip'])?$service['service_zip']:''; ?></p>
                    </td>
                    <td><?php echo !empty($service['service_property'])?$service['service_property']:''; ?></td>
                    <td><?php echo !empty($service['service_service_type'])?$service['service_service_type']:''; ?></td>
                    <td><?php echo !empty($service['frequency'])?$service['frequency']:''; ?></td>
                    <td>----------</td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
<p><button class="btn btn-primary" onclick="AddNewService(<?php echo $customer_id ?>)"><i class="glyphicon glyphicon-plus"></i></button> Add new service</p>
