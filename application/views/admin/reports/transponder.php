<script type="text/javascript" src="assets/js/pdfobject.js"></script>
<style>
    .pdfobject-container { height: 500px;}
    .pdfobject { border: 1px solid #666; }
</style>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li>Reports</li>
            <li class="active">Transponder Report</li>
        </ul>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <form method="post" action="reports/transponder" id="transponder_reports_form">
                        <div class="row">
                            <!-- <div class="col-sm-4 col-md-2">
                                <div class="form-group">
                                    <label style="color:#fff">Disocunt Types</label>
                                    <div class="has-feedback has-feedback-right">
                                        <input type="text" class="form-control input-xs" placeholder="Search" name="txt_search" id="txt_search">
                                        <div class="form-control-feedback">
                                            <i class="icon-search4 text-size-base text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-sm-4 col-md-2">
                                <div class="form-group">
                                    <label>Part number</label>
                                    <div class="has-feedback has-feedback-right">
                                        <input type="text" class="form-control input-xs" placeholder="Strattec part no." name="txt_strattec_part" id="txt_strattec_part">
                                        <div class="form-control-feedback">
                                            <i class="icon-stack text-size-base text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-2">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="select select-size-xs" name="txt_status" id="txt_status">
                                        <option value="all">All</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            
                            
                            <div class="col-sm-4 col-md-3" style="margin-top: 26px">
                                <div class="form-group">
                                    <button type="button" class="btn btn-xs btn-primary" id="btn_search" style="width:80px">Go</button>
                                    <button type="button" class="btn btn-xs btn-default" id="btn_reset" style="width:80px">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <table class="table table-xxs table-framed table-bordered datatable-basic">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Year</th>
                                    <th>Car</th>
                                    <th>Availability</th>
                                </tr>
                            </thead>
                           <!--  <tbody class="tbl_body">
                                <div>
                                    <?php if(count($dataArr)>0){ ?>
                                        <?php foreach($dataArr as $k => $v){ ?>
                                            <tr>
                                                <td><?php echo $k+1; ?></td>
                                                <td><?php echo $v['year_name']; ?></td>
                                                <td><?php echo $v['make_name'].' '.$v['model_name']; ?></td>
                                                <td><?php 
                                                        if($v['qty_on_hand']>0){ 
                                                            echo '<span class="label bg-success-400">IN STOCK</span>'; 
                                                        } else { 
                                                            echo '<span class="label bg-danger-400">OUT OF STOCK</span>';
                                                        } 
                                                    ?>
                                                </td>
                                            </tr>    
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No data Found</td>
                                        </tr>
                                    <?php } ?>
                                </div>
                            </tbody> -->
                        </table>
                    </div>
                    <div class="text-center ajax_loading hide">
                        <div id="loading-center" style="width:3%;position: relative;left: 45%;top:10px">
                            <svg version="1.1" id="L7" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                                <path fill="#009688" d="M31.6,3.5C5.9,13.6-6.6,42.7,3.5,68.4c10.1,25.7,39.2,38.3,64.9,28.1l-3.1-7.9c-21.3,8.4-45.4-2-53.8-23.3c-8.4-21.3,2-45.4,23.3-53.8L31.6,3.5z" transform="rotate(305.979 50 50)">
                                    <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="2s" from="0 50 50" to="360 50 50" repeatCount="indefinite"></animateTransform>
                                </path>
                                <path fill="#26A69A" d="M42.3,39.6c5.7-4.3,13.9-3.1,18.1,2.7c4.3,5.7,3.1,13.9-2.7,18.1l4.1,5.5c8.8-6.5,10.6-19,4.1-27.7c-6.5-8.8-19-10.6-27.7-4.1L42.3,39.6z" transform="rotate(-251.958 50 50)">
                                    <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="-360 50 50" repeatCount="indefinite"></animateTransform>
                                </path>
                                <path fill="#74afa9" d="M82,35.7C74.1,18,53.4,10.1,35.7,18S10.1,46.6,18,64.3l7.6-3.4c-6-13.5,0-29.3,13.5-35.3s29.3,0,35.3,13.5L82,35.7z" transform="rotate(305.979 50 50)">
                                    <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="2s" from="0 50 50" to="360 50 50" repeatCount="indefinite"></animateTransform>
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('Templates/footer'); ?>
</div>
<script type="text/javascript" src="assets/js/custom_pages/transponder_application.js"></script>