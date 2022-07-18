<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Equipment</li>
            <li class="active">Types</li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <?php $this->load->view('alert_view'); ?>
        <div class="col-md-5" id="types_form_row">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Manage Types</h5>
                </div>
                <div class="panel-body">
                    <form method="post" class="form-validate-jquery" id="add_type_form" name="add_type_form">
                        <div class="row mt-20">
                            <div class="col-md-12">
                                <div class="form-group form-group-material has-feedback">
                                    <label>Name <font color="red">*</font></label>
                                    <input type="text" class="form-control" name="txt_name" id="txt_name" required="required" placeholder="Enter Name">
                                    <input type="hidden" name="txt_type_id" id="txt_type_id">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-group-material has-feedback">
                                    <label>Description <font color="red">*</font></label>
                                    <textarea class="form-control" name="txt_description" id="txt_description" required="required" placeholder="Enter Description" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn bg-teal custom_save_button btn_login">Save</button>
                                <button type="button" class="btn btn-default custom_cancel_button" onclick="cancel_click()">Cancel</button>
                            </div>
                        </div>
                    </form>
                    <br>
                    <div class="bulk_upload_div">
                        <div class="content-divider text-muted form-group"><span>OR</span></div>
                        <form method="post" class="form-validate-jquery" id="bulk_type_form" name="bulk_type_form" action="<?php echo site_url('admin/equipments/type_bulk_add'); ?>" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12">
                                    <h6>From here you can do bulk upload</h6>
                                </div>
                                <div class="col-md-9 col-sm-9 col-xs-9 mb-3">
                                    <div class="uploader" id="uniform-upload_csv">
                                        <input type="file" class="file-styled-primary" name="upload_csv" id="upload_csv">
                                        <span class="filename" style="user-select: none;">No file selected</span>
                                        <span class="action btn bg-teal" style="user-select: none;">Choose File</span>
                                    </div>
                                    <code><a href="<?php echo MANUFACTURER_DUMMY_CSV; ?>" style="text-align: left">Click Here</a> , to get a CSV format.</code>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <button type="submit" class="btn bg-teal" style="border-radius: 2px">Upload<i class="icon-arrow-up13 position-right"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">List of Types</h5>
                </div>
                <table class="table datatable-basic">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Descriptions</th>
                            <th>Last Edited</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <?php $this->load->view('Templates/footer'); ?>
</div>
<script>
    remoteURL = site_url + "admin/equipments/checkUnique_Type_Name";
<?php if (isset($dataArr)) { ?>
        var m_id = '<?php echo $dataArr['id'] ?>';
        remoteURL = site_url + "admin/equipments/checkUnique_Type_Name/" + m_id;
<?php } ?>
</script>
<script type="text/javascript" src="assets/js/custom_pages/type.js"></script>

<style>
  @media(min-width:1025px) and (max-width:1300px){
    form#bulk_type_form .col-md-3 {width: 100%;text-align: right;}
    form#bulk_type_form .col-md-9 {width: 100%;}
  }
  @media(max-width:480px){
    form#bulk_type_form .col-md-3 {width: 100%;text-align: right;}
    form#bulk_type_form .col-md-9 {width: 100%;}
  }
</style>