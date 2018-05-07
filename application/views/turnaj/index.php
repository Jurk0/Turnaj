<div class="container">
    <?php if(!empty($success_msg)){ ?>
        <div class="col-xs-12">
            <div class="alert alert-success"><?php echo $success_msg; ?></div>
        </div>
    <?php }elseif(!empty($error_msg)){ ?>
        <div class="col-xs-12">
            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
        </div>
    <?php } ?>
    <div class="row">
        <h1>List of Temperatures</h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default ">
                <div class="panel-heading">Temperatures <a href="<?php echo site_url('temperatures/add/'); ?>" class="glyphicon glyphicon-plus pull-right" ></a></div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="30%">Date</th>
                        <th width="20%">Temperature</th>
                        <th width="15%">Sky</th>
                        <th width="15%">User</th>
                        <th width="15%">Action</th>
                    </tr>
                    </thead>
                    <tbody id="userData">
                    <?php if(!empty($temperatures)): foreach($temperatures as $temperature): ?>
                        <tr>
                            <td><?php echo '#'.$temperature['id']; ?></td>
                            <td><?php echo $temperature['measurement_date']; ?></td>
                            <td><?php echo $temperature['temperature']; ?></td>
                            <td><?php echo $temperature['sky'];?></td>
                            <td><?php echo $temperature['fullname'];?></td>
                            <td>
                                <a href="<?php echo site_url('temperatures/view/'.$temperature['id']); ?>" class="glyphicon glyphicon-eye-open"></a>
                                <a href="<?php echo site_url('temperatures/edit/'.$temperature['id']); ?>" class="glyphicon glyphicon-edit"></a>
                                <a href="<?php echo site_url('temperatures/delete/'.$temperature['id']); ?>" class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure to delete?')"></a>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="4">Temperature(s) not found......</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>