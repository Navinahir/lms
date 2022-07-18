<div class="row">
    <?php
    if (!empty($partArr)) {
        ?>
        <div class="col-lg-12">
            <div class='panel border-blue location_main'>
                <div class="panel-heading">
                    <h6 class="panel-title">Compatibility Details</h6>
                </div>
                <div class='panel-body row'>
                    <?php
                    $is_data = false;
                    foreach ($partArr as $part_info) {
                        if (!empty($part_info['company']) && !empty($part_info['model']) && !empty($part_info['year'])) {
                            $is_data = true;
                            ?>
                            <div class="col-md-3">
                                <span class='label border-left-primary label-striped mt-5 text-bold compatibility' style=''>
                                    <?= $part_info['company'] ?>&nbsp;<?= $part_info['model'] ?>&nbsp;<?= $part_info['year'] ?></span>
                            </div>
                            <?php
                        }
                    }

                    if ($is_data == FALSE) {
                        ?>
                        <div class="col-md-12 text-center">No part found.</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<style>
    .td_location{
    }
    .location_main{
        margin: 30px 0px 0px 0px;
    }
    .alpha-blue {
        background-color: #E1F5FE !important;
    }
</style>