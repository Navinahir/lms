<link href="assets/css/fakeloader.css"  rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="assets/js/fakeLoader.js"></script>
<script type="text/javascript" src="assets/js/fakeLoader.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/visualization/echarts/echarts.js"></script>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Reports</li>
            <li class="active">Low Inventory Items</li>
        </ul>
        <?php $this->load->view('search_view'); ?>
    </div>
</div>
<div class="content">
    <div>
        <?php $this->load->view('alert_view'); ?>
        <!-- Nightingale roses width visible labels -->
        <div class="row">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Low Inventory Items Graph</h5>
                    <!-- <div class="heading-elements">
                        <ul class="icons-list">
                            <li><a data-action="collapse"></a></li>
                        </ul>
                    </div> -->
                </div>

                <div class="panel-body">
                    <div class="chart-container mt-20 chart_one_large">
                        <div class="chart has-fixed-height has-minimum-width" id="rose_diagram_visible"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /nightingale roses width hidden labels -->
        <div class="row">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Low Inventory Items Details</h5>
                    <!-- <div class="heading-elements">
                        <ul class="icons-list">
                            <li><a data-action="collapse"></a></li>
                        </ul>
                    </div> -->
                </div>
                <table class="table datatable-responsive-control-right">
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th style="width:20%">Parts</th>
                            <th>Department</th>
                            <th>Vendor</th>
                            <th>Cost</th>
                            <th>Availability</th>
                            <th style="width:18%">Action</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>Parts</th>
                            <th>Department</th>
                            <th>Vendor</th>
                            <th>Cost</th>
                            <th>Availability</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <?php $this->load->view('Templates/footer'); ?>
</div>
<!-- View modal -->
<div id="items_view_modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-teal-400 custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title text-center">Items Details</h6>
            </div>
            <div class="modal-body panel-body custom_scrollbar" id="items_view_body" style="height: 500px;overflow: hidden;overflow-y: scroll;"></div>
        </div>
    </div>
</div>
<div id="fakeLoader" class="loading"></div>
<script type="text/javascript" src="assets/js/custom_pages/front/low_inventory_items.js?version='<?php echo time();?>'"></script>
<style>
    .dataTables_length{
        margin-right: 20px;
        margin-left: 0px;
    }
    .dataTables_filter{
        margin-left: 0px;
    } 
    .dataTables_length{ float:left; }
</style>