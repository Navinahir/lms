<link href="https://cdn.datatables.net/buttons/1.5.4/css/buttons.dataTables.min.css" rel="stylesheet" />

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Part Compatibility Count</li>
        </ul>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <?php $this->load->view('alert_view'); ?>

            <div class="loader-outer hide" id="spinner">
                <div class="loader " ></div>
            </div>

            <div class="panel panel-flat">
                <div class="status_filter_div col-md-4">
                    <select data-placeholder="-Select Make-" class="select select-size-sm" id="filter_make">
                        <option value=''>Select Make</option>
                        <?php
                        if (!empty($companies)) {
                            foreach ($companies as $make) {
                                ?>
                                <option value="<?= $make['id'] ?>"><?= $make['name'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="status_filter_div col-xs-9 col-sm-10 col-md-4">
                    <select data-placeholder="-Select Model-" class="select select-size-sm" id="filter_model">
                        <option value=''>Select Model</option>
                    </select>
                </div>
                <div class="status_filter_div col-xs-3 col-sm-2 col-md-4 mt-md-2">
                    <button class="btn btn-primary" onclick="return window.location.reload();"><i class="icon icon-spinner11"></i></button>
                </div>
                <table class="table datatable-responsive-control-right">
                    <thead>
                        <tr>
                            <th style="width:7%">#</th>
                            <th style="width:25%">Make</th>
                            <th style="width:25%">Model</th>
                            <th style="width:23%">Year</th>
                            <th style="width:20%">Compatible Parts</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <?php $this->load->view('Templates/footer'); ?>
</div>
<!-- View modal -->
<div id="order_view_modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-teal-400 custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title text-center">Items Details</h6>
            </div>
            <div class="modal-body panel-body custom_scrollbar" id="order_view_body" style="height: 500px;overflow: hidden;overflow-y: scroll;"></div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.4/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.4/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js"></script>
<script>
                        var date_format = '<?php echo (isset($format) && !empty($format)) ? $format['name'] : 'Y/m/d' ?>';
</script>
<script type="text/javascript" src="assets/js/custom_pages/vendor/part_compatibility_counts.js"></script>
<style>
    .dataTables_length{ float:left; }
    .modal-open{ padding-right:3px !important; }
    .status_filter_div {
        margin-top: 10px;
    }
    .label-orange {
        border-color: orange;
        background-color: orange;
    }
    table#DataTables_Table_0 {
        margin-bottom: 10px;
    }
    #DataTables_Table_0_filter{
        float: left !important;
        text-align: center;
        margin-left: 0;
    }
    #DataTables_Table_0_filter label span{
        float: left;
    }
    div#DataTables_Table_0_wrapper .dt-buttons {
        margin-right: 10px;
        text-align: left !important;
    }
    .buttons-print, .buttons-csv{
        -webkit-transition: all ease-in-out 0.15s;
        -o-transition: all ease-in-out 0.15s;
        transition: all ease-in-out 0.15s;
        color: #fff !important;
        background: #2196F3 !important;
        border-color: #2196F3 !important;
        border-radius: 3px !important;
        padding: 7px 12px !important;
        font-size: 13px !important;
        line-height: 1.5384616 !important;
        outline: none !important;
    }
    .buttons-print:hover, .buttons-print:focus, .buttons-print:active,
    .buttons-csv:hover, .buttons-csv:focus, .buttons-csv:active{
        background-color: #2196F3 !important;
        border-color: #2196F3 !important;
    }
</style>
