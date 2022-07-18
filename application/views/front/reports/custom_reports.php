<link href="assets/css/fakeloader.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="assets/js/plugins/notifications/jgrowl.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/ui/moment/moment.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/daterangepicker.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/anytime.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.js"></script>
<script type="text/javascript" src="assets/js/plugins/visualization/echarts/echarts.js"></script>
<script type="text/javascript" src="assets/js/charts/echarts/timeline_option.js"></script>
<script type="text/javascript" src="assets/js/fakeLoader.js"></script>
<script type="text/javascript" src="assets/js/fakeLoader.min.js"></script>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Reports</li>
            <li class="active">Custom Reports</li>
        </ul>
        <?php $this->load->view('search_view'); ?>
    </div>
</div>
<div class="content">
    <div>
        <?php $this->load->view('alert_view'); ?>
        <div class="row">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Filter</h5>
                </div>
                <div class="panel-body mt-20">
                    <div class="">
                        <div class="form-group">
                            <label class="display-block">Date Selection </label>
                            <div class="data-selection-wrap">
                                <button type="button" class="btn btn-default daterange-predefined" id="date_range" name="date">
                                    <i class="icon-calendar22 position-left"></i>
                                    <span></span>
                                    <b class="caret"></b>
                                </button>
                                <button type="button" class="btn bg-blue btn-save">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gross_sales_content">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Custom Reports Summary</h5>
                </div>
                <div class="panel-body mt-20">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="card bg-teal-400">
                                <div class="card-body">
                                    <div class="d-inline-block">
                                    <div class="d-flex bg-danger-700">
                                        <h3 class="font-weight-semibold mb-0">Gross Sales</h3>
                                        <div class="tooltip">Hover over me
                                            <span class="tooltiptext">Tooltip text</span>
                                        </div>
                                        <div>
                                            <!-- <button class="count count_gross_sales">0</button> -->
                                            <button class="count" formtarget="_blank" onclick="link_gross_sales()">
                                                <span class="count count_gross_sales">0</span>
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-label">
                                        <strong class="gross_sales">$0.00</strong>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="card bg-teal-400">
                                <div class="card-body">
                                    <div class="d-inline-block">
                                    <div class="d-flex bg-orange-700">
                                        <h3 class="font-weight-semibold mb-0">Net Sales</h3>
                                        <div>
                                            <!-- <button class="count count_net_sales">0</button> -->
                                            <button class="count" formtarget="_blank" onclick="link_net_sales()">
                                                <span class="count count_net_sales">0</span>
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </div>    
                                    </div>
                                    <div class="card-label">
                                        <strong class="net_sales">$0.00</strong>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="card bg-teal-400">
                                <div class="card-body">
                                    <div class="d-inline-block">
                                    <div class="d-flex bg-darkgreen">
                                        <h3 class="font-weight-semibold mb-0">Gross Profit</h3>
                                        <div>
                                            <!-- <button class="count count_gross_profit">0</button> -->
                                            <button class="count" formtarget="_blank" onclick="link_gross_profit()">
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </div>    
                                    </div>
                                    <div class="card-label">
                                        <strong class="gross_profit">$0.00</strong>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="card bg-teal-400">
                                <div class="card-body">
                                    <div class="d-inline-block">
                                    <div class="d-flex bg-gray">
                                        <h3 class="font-weight-semibold mb-0">Taxable Sales</h3>
                                        <div>
                                            <!-- <button class="count count_taxable_sales">0</button> -->
                                            <button class="count" formtarget="_blank" onclick="link_taxable_sale()">
                                                <span class="count count_taxable_sales">0</span>
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </div>    
                                    </div>
                                    <div class="card-label">
                                        <strong class="taxable_sales">$0.00</strong>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="card bg-teal-400">
                                <div class="card-body">
                                    <div class="d-inline-block">
                                    <div class="d-flex bg-black">
                                        <h3 class="font-weight-semibold mb-0">Non-Taxable Sales</h3>
                                        <div>
                                            <!-- <button class="count count_non_taxable_part">0</button> -->
                                            <button class="count" formtarget="_blank" onclick="link_non_taxable_sale()">
                                                <span class="count count_non_taxable_part">0</span>
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-label">
                                        <strong class="non_taxable_part">$0.00</strong>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="card bg-teal-400">
                                <div class="card-body">
                                    <div class="d-inline-block">
                                        <div class="d-flex bg-nevi-blue">
                                            <h3 class="font-weight-semibold mb-0">Total Discount</h3>
                                            <div>
                                                <!-- <button class="count count_total_discount">0</button> -->
                                                <button class="count" formtarget="_blank" onclick="link_discount_sale()">
                                                    <span class="count count_total_discount">0</span>
                                                    <i class="far fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-label">
                                            <strong class="total_discount">$0.00</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="card bg-teal-400">
                                <div class="card-body">
                                    <div class="d-inline-block">
                                        <div class="d-flex bg-mid-brown">
                                            <h3 class="font-weight-semibold mb-0">Shipping Charges</h3>
                                            <div>
                                                <!-- <button class="count count_shipping_charge">0</button> -->
                                                <button class="count" formtarget="_blank" onclick="link_shipping_charge()">
                                                    <span class="count count_shipping_charge">0</span>
                                                    <i class="far fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-label">
                                            <strong class="shipping_charge">$0.00</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p class="hide gross_sales">0.00</p>
        <p class="hide net_sell_part">0.00</p>
        <p class="hide count_net_sell_part">0</p>
        <p class="hide net_discount_sell_part">0.00</p>
        <p class="hide net_sell_service">0.00</p>
        <p class="hide count_net_sell_service">0</p>
        <p class="hide net_discount_sell_service">0.00</p>
        <p class="hide net_sell_taxable_part">0.00</p>
        <p class="hide count_net_sell_taxable_part">0</p>
        <p class="hide net_sell_taxable_service">0.00</p>
        <p class="hide count_net_sell_taxable_service">0.00</p>
        <p class="hide net_sell_non_taxable_part">0.00</p>
        <p class="hide count_net_sell_non_taxable_part">0</p>
        <p class="hide net_sell_non_taxable_service">0.00</p>
        <p class="hide count_net_sell_non_taxable_service">0</p>
        <p class="hide part_tax_amount">0.00</p>
        <p class="hide service_tax_amount">0.00</p>
        <p class="hide total_tax_amount">0.00</p>
        <p class="hide shipping_rate"></p>

        <div class="row gross_sales_content">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Custom Reports Summary Detail</h5>
                </div>
                <div class="panel-body mt-20">
                    <h4 style="margin: 0 0 0 0px;">Part Detail</h4>
                    <hr>
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="card bg-teal-400">
                                <div class="card-body">
                                    <div class="d-inline-block">
                                    <div class="d-flex bg-lightpink">
                                        <h3 class="font-weight-semibold mb-0">Part Net Sales</h3>
                                        <div>
                                            <!-- <button class="count count_part_net_sales">0</button> -->
                                            <button class="count" formtarget="_blank" onclick="link_part_net_sale()">
                                                <span class="count count_part_net_sales">0</span>
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-label">
                                        <strong class="part_net_sales">$0.00</strong>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="card bg-teal-400">
                                <div class="card-body">
                                    <div class="d-inline-block">
                                    <div class="d-flex bg-coffee">
                                        <h3 class="font-weight-semibold mb-0">Part Taxable Sales</h3>
                                        <div>
                                            <!-- <button class="count count_part_taxable_sales">0</button> -->
                                            <button class="count" formtarget="_blank" onclick="link_part_taxable_sale()">
                                                <span class="count count_part_taxable_sales">0</span>
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-label">
                                        <strong class="part_taxable_sales">$0.00</strong>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="card bg-teal-400">
                                <div class="card-body">
                                    <div class="d-inline-block">
                                        <div class="d-flex bg-lightgreen">
                                            <h3 class="font-weight-semibold mb-0">Part Non Taxable Sales</h3>
                                            <div>
                                                <!-- <button class="count count_part_non_taxable_sales">0</button> -->
                                                <button class="count" formtarget="_blank" onclick="link_non_taxable_part_sale()">
                                                    <span class="count count_part_non_taxable_sales">0</span>
                                                    <i class="far fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-label">
                                            <strong class="part_non_taxable_sales">$0.00</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="card bg-teal-400">
                                <div class="card-body">
                                    <div class="d-inline-block">
                                    <div class="d-flex bg-lightblue">
                                        <h3 class="font-weight-semibold mb-0">Part Total Tax</h3>
                                        <div>
                                            <!-- <button class="count count_part_total_tax">0</button> -->
                                            <button class="count" formtarget="_blank" onclick="link_part_tax()">
                                                <span class="count count_part_total_tax">0</span>
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-label">
                                        <strong class="part_total_tax">$0.00</strong>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="card bg-teal-400">
                                <div class="card-body">
                                    <div class="d-inline-block">
                                    <div class="d-flex bg-lightyellow">
                                        <h3 class="font-weight-semibold mb-0">Part Total Discount</h3>
                                        <div>
                                            <!-- <button class="count count_part_total_discount">0</button> -->
                                            <button class="count" formtarget="_blank" onclick="link_part_discount()">
                                                <span class="count count_part_total_discount">0</span>
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-label">
                                        <strong class="part_total_discount">$0.00</strong>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="card bg-teal-400">
                                <div class="card-body">
                                    <div class="d-inline-block">
                                    <div class="d-flex bg-offsky">
                                        <h3 class="font-weight-semibold mb-0">Part Total Cost</h3>
                                        <div>
                                            <!-- <button class="count count_part_total_cost">0</button> -->
                                            <button class="count" formtarget="_blank" onclick="link_part_cost()">
                                                <span class="count count_part_total_cost">0</span>
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-label">
                                        <strong class="part_total_cost">$0.00</strong>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 style="margin: 0 0 0 0px;">Service Detail</h4>
                    <hr>
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="card bg-teal-400">
                                <div class="card-body">
                                    <div class="d-inline-block">
                                    <div class="d-flex bg-lightgray">
                                        <h3 class="font-weight-semibold mb-0">Service Net Sales</h3>
                                        <div>
                                            <!-- <button class="count count_service_net_sales">0</button> -->
                                            <button class="count" formtarget="_blank" onclick="link_service_net_sale()">
                                                <span class="count count_service_net_sales">0</span>
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-label">
                                        <strong class="service_net_sales">$0.00</strong>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="card bg-teal-400">
                                <div class="card-body">
                                    <div class="d-inline-block">
                                    <div class="d-flex bg-offbrown">
                                        <h3 class="font-weight-semibold mb-0">Service Taxable Sales</h3>
                                        <div>
                                            <!-- <button class="count count_service_taxable_sales">0</button> -->
                                            <button class="count" formtarget="_blank" onclick="link_service_taxable_sale()"> 
                                                <span class="count count_service_taxable_sales">0</span>
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-label">
                                        <strong class="service_taxable_sales">$0.00</strong>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="card bg-teal-400">
                                <div class="card-body">
                                    <div class="d-inline-block">
                                    <div class="d-flex bg-offgreen">
                                        <h3 class="font-weight-semibold mb-0">Service Non Taxable Sales</h3>
                                        <div>
                                            <!-- <button class="count count_service_non_taxable_sales">0</button> -->
                                            <button class="count" formtarget="_blank" onclick="link_non_taxable_service_sale()">
                                                <span class="count count_service_non_taxable_sales">0</span>
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-label">
                                        <strong class="service_non_taxable_sales">$0.00</strong>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="card bg-teal-400">
                                <div class="card-body">
                                    <div class="d-inline-block">
                                    <div class="d-flex bg-extra-light-green">
                                        <h3 class="font-weight-semibold mb-0">Service Total Tax</h3>
                                        <div>
                                            <!-- <button class="count count_service_total_tax">0</button> -->
                                            <button class="count" formtarget="_blank" onclick="link_service_tax()">
                                                <span class="count count_service_total_tax">0</span>
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-label">
                                        <strong class="service_total_tax">$0.00</strong>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="card bg-teal-400">
                                <div class="card-body">
                                    <div class="d-inline-block">
                                    <div class="d-flex bg-purpal">
                                        <h3 class="font-weight-semibold mb-0">Service Total Discount</h3>
                                        <div>
                                            <!-- <button class="count count_service_total_discount">0</button> -->
                                            <button class="count" formtarget="_blank" onclick="link_service_discount()">
                                                <span class="count count_service_total_discount">0</span>
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-label">
                                        <strong class="service_total_discount">$0.00</strong>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $this->load->view('Templates/footer'); ?>
</div>
<div id="fakeLoader" class="loading"></div>
<?php
if (isset($get) && !empty($get)):
    echo "<script>var get = " . json_encode($get) . "</script>";
endif;

?>
<script type="text/javascript">
    var currency = '<?php echo (isset($currency) && !empty($currency)) ? $currency['symbol'] : '$' ?>';
</script>
<script type="text/javascript" src="assets/js/custom_pages/front/custom_reports.js?version='<?php echo time();?>'"></script>
<style>
    .dataTables_length{ float:left; }
    .ranges ul li:hover, .ranges ul li:focus{color: #333333 !important;}
    .ranges ul li.active {color: #33a9f5 !important;background-color: #324f61;}
    .daterangepicker td.active, .daterangepicker td.active:hover, .daterangepicker td.active:focus {background-color: #324f61;}
    .daterangepicker.dropdown-menu {max-height: unset;overflow-y: unset;}

    @media(max-width:767px){
        .panel { margin: 0 20px 20px 20px;}
    }

    @media(max-width:480px){
        .data-selection-wrap button#date_range {white-space: normal;margin-bottom: 10px;width: 100%;  display: -webkit-box;display: -ms-flexbox;display: flex;-webkit-box-align: center;-ms-flex-align: center;align-items: center;-webkit-box-pack: center;-ms-flex-pack: center;justify-content: center;}
        .data-selection-wrap .bg-blue{float: right;}
        .data-selection-wrap button#date_range .position-left {margin-right: 20px;}
        .data-selection-wrap button#date_range span{text-align: left;font-size: 11px;}
    }
</style>