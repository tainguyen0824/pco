<table class="table table-striped table-responsive" width="100%">
    <thead style="background-color: lightslategray">
        <tr>
            <th width="10%">Route ID</th>
            <th width="15%">Route No</th>
            <th width="25%">Route Name</th>
            <th width="25%">Route Zip</th>
            <th width="25%">Route Technician</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($routes as $route) :?>
        <tr>
            <td style="text-align: center"><?=$route['route_id']?></td>
            <td style="text-align: center"><?=$route['route_no']?></td>
            <td style="text-align: left"><?=$route['route_name']?></td>
            <td style="text-align: left"><?=$route['route_zip']?></td>
            <td style="text-align: center"><?=$route['route_technician']?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>