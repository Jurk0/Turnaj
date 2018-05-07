<div class="container">
    <div class="row"><br></div>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">Temperature Details <a href="<?php echo site_url('temperatures/'); ?>" class="glyphicon glyphicon-arrow-left pull-right"></a></div>
            <div class="panel-body">
                <div class="form-group">
                    <label>Date:</label>
                    <p><?php echo !empty($temperatures['measurement_date'])?$temperatures['measurement_date']:''; ?></p>
                </div>
                <div class="form-group">
                    <label>Temperature:</label>
                    <p><?php echo !empty($temperatures['temperature'])?$temperatures['temperature']:''; ?></p>
                </div>
                <div class="form-group">
                    <label>Sky:</label>
                    <p><?php echo !empty($temperatures['sky'])?$temperatures['sky']:''; ?></p>
                </div>
                <div class="form-group">
                    <label>User:</label>
                    <p><?php echo !empty($temperatures['fullname'])?$temperatures['fullname']:''; ?></p>
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <p><?php echo !empty($temperatures['description'])?$temperatures['description']:''; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>