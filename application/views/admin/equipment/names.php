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
        <div class="col-md-5" id="names_form_row">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Manage Names</h5>
                </div>
                <div class="panel-body">
                    <form method="post" class="form-validate-jquery" id="add_name_form" name="add_name_form">
                        <input type="hidden" name="txt_name_id" id="txt_name_id">
                        <div class="row mt-20">
                            <div class="col-sm-12 pl-0 pr-0 pl-lg-0">
                                <div class="form-group has-feedback select_form_group">
                                    <label class="col-md-12 col-lg-3 control-label lb-w-150 required">Manufacturer</label>
                                    <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                        <div class="row select2-plus-dropdown">
                                            <div class="col-md-10 col-sm-10 col-xs-10 select2-dropdown-custom" style="padding-right:0px">    
                                                <select class="select select-size-sm" data-placeholder="Select a Manufacturer..." name="manufacturer_id" id="manufacturer_id" required>
                                                    <option></option>
                                                    <?php
                                                    foreach ($manufacturerArr as $k => $v) {
                                                        ?>
                                                        <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2 col-sm-2 col-xs-2 btn_s select2-dropdown-plus" style="padding:0px">
                                                <span class="input-group-btn">
                                                    <button class="btn bg-teal btn-sm add_modal" type="button" id="add_manu_modal" style="border-top-right-radius:5px;border-bottom-right-radius:5px;padding: 7px 12px;"><i class="icon-plus-circle2"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-20">
                            <div class="col-sm-12 pl-0 pr-0 pl-lg-0">
                                <div class="form-group has-feedback select_form_group">
                                    <label class="col-md-12 col-lg-3 control-label lb-w-150 required">Equipment Type</label>
                                    <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                        <div class="row select2-plus-dropdown">
                                            <div class="col-md-10 col-sm-10 col-xs-10 select2-dropdown-custom" style="padding-right:0px">
                                                <select class="select select-size-sm" data-placeholder="Select a Equipment Type..." name="equipment_type_id" id="equipment_type_id" required>
                                                    <option></option>
                                                    <?php
                                                    foreach ($typeArr as $k => $v) {
                                                        ?>
                                                        <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2 col-sm-2 col-xs-2 btn_s select2-dropdown-plus" style="padding:0px">
                                                <span class="input-group-btn">
                                                    <button class="btn bg-teal btn-sm add_modal" type="button" id="add_type_modal" style="border-top-right-radius:5px;border-bottom-right-radius:5px;padding: 7px 12px;"><i class="icon-plus-circle2"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-20">
                            <div class="col-md-12 ">
                                <div class="form-group form-group-material has-feedback">
                                    <label>Equipment Name <font color="red">*</font></label>
                                    <textarea class="form-control" name="txt_description" id="txt_description" required="required" placeholder="Enter Equipment Name" rows="4"></textarea>
                                    <!--<textarea name="editor-full" id="editor-full" rows="4" cols="4">-->
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
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">List of Names</h5>
                </div>
                <table class="table datatable-basic">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Manufacturer</th>
                            <th>Equipment Type</th>
                            <th>Equipment Name</th>
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
<div id="add_form_modal" class="modal fade" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header custom_modal_header bg-teal-400">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h6 class="modal-title text-center"></h6>
            </div>
            <div class="modal-body panel-body hide" id="add_form_body">
                <form method="post" id="add_manu_form">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-material has-feedback">
                                <label>Name <font color="red">*</font></label>
                                <input type="text" class="form-control" name="txt_name" id="txt_name" required="required" placeholder="Enter Name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-material has-feedback">
                                <label>Description <font color="red">*</font></label>
                                <textarea class="form-control" name="txt_desc" id="txt_desc" required="required" placeholder="Enter Description" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="submit" class="btn bg-teal btn-block custom_save_button" name="btn_submit" id="btn_submit">Save</button>
                            <button type="button" class="btn btn-default custom_cancel_button name_cancel" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
                <form method="post" id="add_type_form">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-material has-feedback">
                                <label>Name <font color="red">*</font></label>
                                <input type="text" class="form-control" name="txt_type_name" id="txt_type_name" required="required" placeholder="Enter Name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-material has-feedback">
                                <label>Description <font color="red">*</font></label>
                                <textarea class="form-control" name="txt_type_desc" id="txt_type_desc" required="required" placeholder="Enter Description" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="submit" class="btn bg-teal btn-block custom_save_button" name="btn_type_submit" id="btn_type_submit">Save</button>
                            <button type="button" class="btn btn-default custom_cancel_button type_cancel" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</script>
<script type="text/javascript" src="assets/js/custom_pages/name.js?version='<?php echo time();?>'"></script>