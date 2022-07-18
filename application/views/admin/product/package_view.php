<script src="assets/ckeditor/ckeditor.js"></script>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Package</li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <?php $this->load->view('alert_view'); ?>
        <div class="col-md-4" id="year_form_row">
            <?php if (isset($dataArr) && !empty($dataArr)): ?>
                <div class="panel panel-flat">
                    <div class="panel-heading" style="background-color: #26A69A;border-color: #26A69A; color: #fff;padding: 10px;font-weight:bold">
                        <h3 class="panel-title text-center"><?php echo $dataArr['name'] ?></h3>
                    </div>
                    <div class="panel-body">
                        <div class="media">
                            <div class="media-body">
                                <div class="text-center" style="text-align:center;margin:10px">
                                    <span style="font-size: 15px;">$</span>
                                    <span style="font-size: 30px;"> <?php echo $dataArr['price'] . '/' ?></span>
                                    <span style="font-size: 15px;"> <?php echo $dataArr['months'] ?><?php echo($dataArr['months'] == 1) ? ' Month' : ' Months' ?></span>
                                </div>
                                <i><?php echo $dataArr['description'] ?></i>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php $this->load->view('Templates/footer'); ?>
</div>