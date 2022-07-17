<link href="assets/css/introjs.css" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
<script src="assets/js/intro.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/tags/tagsinput.min.js"></script>
<script type="text/javascript" src="assets/js/jquery_ui.js"></script>
<?php
if (checkUserLogin('R') != 4) {
    $add = 0;
    $edit = 0;
    $controller = $this->router->fetch_class();
    if (!empty(MY_Controller::$access_method) && array_key_exists('add', MY_Controller::$access_method[$controller])) {
        $add = 1;
    }
    if (!empty(MY_Controller::$access_method) && array_key_exists('edit', MY_Controller::$access_method[$controller])) {
        $edit = 1;
    }
    if (isset($dataArr)) {
        if ($edit == 0) {
            echo $this->load->view('front/error403', null, true);
            die;
        }
    } else {
        if ($add == 0) {
            echo $this->load->view('front/error403', null, true);
            die;
        }
    }
}
$edit_div = 0;
$add_item = 0;
if (isset($dataArr)) {
    $edit_div = 1;
    $form_action = site_url('items/edit/' . base64_encode($dataArr['id']));
} else if (isset($itemArr)) {
    $add_item = 1;
    $form_action = site_url('items/add');
} else {
    $form_action = site_url('items/add');
}
?>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('items') ?>">Items</a></li>
            <li class="active">
                <?php
                if (isset($dataArr))
                    echo "Edit";
                else
                    echo "Add";
                ?>
            </li>
        </ul>
        <?php $this->load->view('search_view'); ?>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="<?php echo $form_action; ?>" class="form-horizontal" id="add_item_form" enctype="multipart/form-data" >
                <div class="panel panel-body login-form">
                    <?php $this->load->view('alert_view'); ?>
                    <div class="col-sm-12 pl-sm-0 pr-sm-0"> 
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group has-feedback top-feedback">
                                    <label class="col-md-3 col-lg-2 control-label required">Global Part No :</label>
                                    <div class="col-md-7 col-lg-8">
                                        <?php
                                            if(!empty($itemArr['id'])){ 
                                        ?>
                                            <input type="hidden" class="form-control" name="txt_referred_item_id" id="" style="text-transform: uppercase;" value="<?php
                                            if (isset($itemArr)) {
                                                echo $itemArr['id'];
                                            } else {
                                                set_value('txt_referred_item_id');
                                            }
                                            ?>">
                                        <?php } else { ?> 
                                            <input type="hidden" class="form-control" name="txt_referred_item_id" id="txt_referred_item_id" style="text-transform: uppercase;" value="<?php
                                            if (isset($dataArr)) {
                                                echo $dataArr['referred_item_id'];
                                            } elseif (isset($itemArr)) {
                                                echo $itemArr['id'];
                                            } else {
                                                set_value('txt_referred_item_id');
                                            }
                                            ?>">
                                        <?php } ?>
                                        <!-- <input type="hidden" class="form-control" name="txt_referred_item_id" id="txt_referred_item_id" style="text-transform: uppercase;" value="<?php
                                        if (isset($dataArr)) {
                                            echo $dataArr['referred_item_id'];
                                        } elseif (isset($itemArr)) {
                                            echo $itemArr['id'];
                                        } else {
                                            set_value('txt_referred_item_id');
                                        }
                                        ?>"> -->
                                        <input type="hidden" class="form-control" id="txt_global_part_no_autoselected" value="0">
                                        <input type="hidden" class="form-control" name="txt_global_part_no" id="txt_global_part_no_hidden" style="text-transform: uppercase;" value="<?php
                                        if (isset($dataArr)) {
                                            echo $dataArr['global_part_no'];
                                        } elseif (isset($itemArr)) {
                                            echo $itemArr['part_no'];
                                        } else {
                                            set_value('txt_global_part_no_hidden');
                                        }
                                        ?>" <?php echo (isset($dataArr)) ? "disabled='disabled'" : ''; ?>>
                                        <input type="text" 
                                               class="form-control txt_global_part_no" 
                                               id="txt_global_part_no" 
                                               <?php if (!empty($_SESSION) && empty($_SESSION['is_item_intro_show'])) { ?>
                                                   data-step="1" 
                                                   data-intro='<div class="border-blue-400"><div class="arrow"></div><h3 class="popover-title bg-blue-400">Step One</h3><br/><div class="popover-content">(Search Always Reliable Keys extensive Global database and find parts from all your favorite vendors)</div></div>' 
                                                   data-position='right' 
                                                   data-scrollTo='tooltip' 
                                               <?php } ?>
                                               style="text-transform: uppercase;" 
                                               value="<?php
                                               if (isset($dataArr)) {
                                                   echo $dataArr['global_part_no'];
                                               } elseif (isset($itemArr)) {
                                                   echo $itemArr['part_no'];
                                               } else {
                                                   set_value('txt_global_part_no_hidden');
                                               }
                                               ?>" <?php echo (isset($dataArr) || isset($itemArr)) ? "disabled='disabled'" : ''; ?>>

                                        <?php echo '<label id="txt_global_part_no_error2" class="validation-error-label" for="txt_global_part_no">' . form_error('txt_global_part_no') . '</label>'; ?>
                                    </div>
                                    <div class="col-md-2 col-lg-2 mt-0">
                                        <button type="button" class="btn btn-default bg-blue custom_clear_button hide">Reset</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 pr-xs-0">
                                <div class="text-right" 
                                <?php if (!empty($_SESSION) && empty($_SESSION['is_item_intro_show'])) { ?>
                                         data-step="2" 
                                         data-intro='<div class="border-blue-400"><div class="arrow"></div><h3 class="popover-title bg-blue-400">Step Two</h3><br/><div class="popover-content">(Add parts into your inventory database to be used when creating estimates and invoices. Note that these parts will not be displayed when searching for vehicle compatible on the dashboard)</div></div>' 
                                         data-position='bottom' 
                                         data-scrollTo='tooltip'
                                     <?php } ?>
                                     ><a href="<?php echo base_url() . "items/add_non_global" ?>" class="btn bg-teal-400 btn-labeled custom_add_button"><b><i class="icon-plus-circle2"></i></b>Add Non-Global Item</a></div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="global_part_search_result_div hide"> -->
                    <div class="global_part_search_result_div hide" style="display: none;">
                        <h6 class="content-group text-semibold">
                            Global Part Search Result
                            <small class="display-block">It shows result of above entered global part details</small>
                        </h6>
                        <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-flat">
                                        <table class="table datatable-responsive">
                                            <thead>
                                                <tr>
                                                    <th style="width:5%">#</th>
                                                    <th>Global Part</th>
                                                    <th>Short Description</th>
                                                    <th>Vendor</th>
                                                    <th>Department</th>
                                                    <th>Select(Any of)</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="inventoy_div hide">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="alert alert-danger hidden mt-3" id="exits_item_warning">
                                    <strong>You already have this part in your inventory.</strong> Are you sure you want to add it again?
                                </div>
                            </div>
                        </div>
                        <h6 class="content-group text-semibold">
                            Add New Item
                            <small class="display-block">It creates new items based on selected part details</small>
                        </h6>
                        <hr>   
                        <div class="col-sm-12 pl-sm-0 pr-sm-0">                    
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback">
                                                <label class="col-md-12 col-lg-4 control-label required">Item Part No :</label>
                                                <div class="col-md-12 col-lg-8">
                                                <?php 
                                                if(isset($itemArr['preferred_vendor']) && $itemArr['preferred_vendor'] != "")
                                                {
                                                    ?>
                                                    <input type="text" class="form-control" name="txt_item_part" id="" style="text-transform: uppercase;" value="<?php echo $itemArr['part_no'];
                                                    ?>">  
                                                <?php } else { ?>
                                                    <input type="text" class="form-control" name="txt_item_part" id="txt_item_part" style="text-transform: uppercase;" value="<?php
                                                    if (isset($dataArr)) {
                                                        echo $dataArr['part_no'];
                                                    } elseif (isset($itemArr)) {
                                                        echo $itemArr['part_no'];
                                                    } else {
                                                        set_value('txt_item_part');
                                                    }
                                                    ?>">
                                                <?php } ?>
                                             
                                                <!-- <input type="text" class="form-control" name="txt_item_part" id="txt_item_part" style="text-transform: uppercase;" value="<?php
                                                    if (isset($dataArr)) {
                                                        echo $dataArr['part_no'];
                                                    } elseif (isset($itemArr)) {
                                                        echo $itemArr['part_no'];
                                                    } else {
                                                        set_value('txt_item_part');
                                                    }
                                                    ?>"> -->

                                                <?php echo '<label id="txt_item_part_error2" class="validation-error-label" for="txt_item_part">' . form_error('txt_item_part') . '</label>'; ?>
                                                </div>
                                            </div>
                                        </div>                            
                                        <!--<div class="col-md-12">-->
                                        
                                        <div class="col-md-12 internal_part">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-lg-4 col-md-12 col-xs-12 control-label required">Alternate Part No or SKU:</label>
                                                <div class="col-lg-8 col-md-12 col-xs-12">   
                                                <?php
                                                if(isset($itemArr['internal_part_no']) && !empty($itemArr['internal_part_no'])){
                                                ?>
                                                    <input type="hidden" id="" name="hidden_internal_part" value="<?php
                                                        echo $itemArr['internal_part_no']; ?>">
                                                <?php } else { ?>
                                                    <input type="hidden" id="hidden_internal_part" name="hidden_internal_part" value="<?php
                                                    if (isset($dataArr)) {
                                                        if (isset($dataArr['global']) && !empty($dataArr['global'])) {
                                                            echo $dataArr['global']['internal_part_no'];
                                                        } else {
                                                            echo $dataArr['internal_part_no'];
                                                        }
                                                    } elseif (isset($itemArr)) {
                                                        echo $itemArr['internal_part_no'];
                                                    } else {
                                                        echo '';
                                                    }
                                                    ?>">
                                                <?php } ?>

                                                   <!--  <input type="hidden" id="hidden_internal_part" name="hidden_internal_part" value="<?php
                                                    if (isset($dataArr)) {
                                                        if (isset($dataArr['global']) && !empty($dataArr['global'])) {
                                                            echo $dataArr['global']['internal_part_no'];
                                                        } else {
                                                            echo $dataArr['internal_part_no'];
                                                        }
                                                    } elseif (isset($itemArr)) {
                                                        echo $itemArr['internal_part_no'];
                                                    } else {
                                                        echo '';
                                                    }
                                                    ?>"> -->

                                                   <?php
                                                   if (isset($dataArr) && $dataArr['internal_part_no'] != null) {
                                                       ?>
                                                        <input type="text" class="form-control" id="text_internal_part" name="txt_internal_part" value="<?php echo $dataArr['internal_part_no']; ?>">
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <select class="select select-size-sm" data-placeholder="Select a Internal Part..." id="txt_internal_part" name="txt_internal_part">
                                                            <option></option>
                                                            <?php
                                                            if (isset($dataArr['global']) && !empty($dataArr['global'])) {
                                                                echo "<option selected='selected'>" . $dataArr['global']['internal_part_no'] . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    <?php } ?>
                                                    <?php echo '<label id="txt_internal_part_error2" class="validation-error-label" for="txt_internal_part">' . form_error('txt_internal_part') . '</label>'; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!--</div>-->
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback" style="margin-bottom:15px">
                                                <label class="col-lg-4 col-md-12 control-label required">Item Description :</label>
                                                <div class="col-lg-8 col-md-12">

                                                    <?php if(!empty($itemArr['description'])) { ?>
                                                        <textarea class="form-control" name="txt_item_description" id="" rows="4"><?php
                                                            if (isset($itemArr)) {
                                                                echo $itemArr['description'];
                                                            } 
                                                        ?></textarea>
                                                    <?php } else { ?>
                                                        <textarea class="form-control" name="txt_item_description" id="txt_item_description" rows="4"><?php
                                                            if (isset($dataArr)) {
                                                                echo $dataArr['description'];
                                                            } elseif (isset($itemArr)) {
                                                                echo $itemArr['description'];
                                                            } else {
                                                                set_value('txt_item_description');
                                                            }
                                                        ?></textarea>
                                                    <?php } ?>

                                                    <!-- <textarea class="form-control" name="txt_item_description" id="txt_item_description" rows="4"><?php
                                                        if (isset($dataArr)) {
                                                            echo $dataArr['description'];
                                                        } elseif (isset($itemArr)) {
                                                            echo $itemArr['description'];
                                                        } else {
                                                            set_value('txt_item_description');
                                                        }
                                                        ?></textarea> -->
                                                    <?php echo '<label id="txt_item_description_error2" class="validation-error-label" for="txt_item_description">' . form_error('txt_item_description') . '</label>'; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback">
                                                <label class="col-lg-4 col-md-12 control-label">Part Location :</label>
                                                <div class="col-lg-8 col-md-12">
                                                    <input type="text" class="form-control" name="part_location" id="part_location" value="<?php
                                                    if (isset($dataArr)) {
                                                        echo $dataArr['part_location'];
                                                    } else {
                                                        set_value('part_location');
                                                    }
                                                    ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback">
                                                <label class="col-lg-4 col-md-12 control-label required">Cost :</label>
                                                <div class="col-lg-8 col-md-12">
                                                    <input type="text" min="0" class="form-control" name="txt_unit_cost" id="txt_unit_cost" value="<?php
                                                    if (isset($dataArr)) {
                                                        echo $dataArr['unit_cost'];
                                                    } elseif (isset($itemArr)) {
                                                        echo '0.00';
                                                    } else {
                                                        set_value('txt_unit_cost');
                                                    }
                                                    ?>">
                                                           <?php echo '<label id="txt_unit_cost_error2" class="validation-error-label" for="txt_unit_cost">' . form_error('txt_unit_cost') . '</label>'; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback">
                                                <label class="col-lg-4 col-md-12 control-label required">Price :</label>
                                                <div class="col-lg-8 col-md-12">
                                                    <input type="text" min="0" class="form-control" name="txt_retail_price" id="txt_retail_price" value="<?php
                                                    if (isset($dataArr)) {
                                                        echo $dataArr['retail_price'];
                                                    } elseif (isset($itemArr)) {
                                                        echo '0.00';
                                                    } else {
                                                        set_value('txt_retail_price');
                                                    }
                                                    ?>">
                                                           <?php echo '<label id="txt_retail_price_error2" class="validation-error-label" for="txt_retail_price">' . form_error('txt_retail_price') . '</label>'; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-lg-4 col-md-12 control-label required">Department :</label>
                                                <div class="col-lg-8 col-md-12 col-xs-12">        
                                                <?php
                                                if(isset($itemArr['department_id']) && $itemArr['department_id'] != "")
                                                {
                                                ?>
                                                    <select class="select select-size-sm" data-placeholder="Select a Department..." id="" name="txt_department" required <?php
                                                    if (empty($dept_Arr)) {
                                                        echo 'disabled';
                                                    }
                                                    ?>>
                                                        <option></option>
                                                        <?php
                                                        foreach ($dept_Arr as $k => $v) {
                                                            if (isset($itemArr)) {
                                                                if ($itemArr['department_id'] == $v['id']) {
                                                                    $selected = 'selected';
                                                                } else {
                                                                    $selected = '';
                                                                }
                                                            } else {
                                                                $selected = '';
                                                            }
                                                            ?>
                                                            <option value="<?php echo $v['id']; ?>" <?php echo $selected; ?>><?php echo $v['name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                <?php } else { ?>
                                                    <select class="select select-size-sm" data-placeholder="Select a Department..." id="txt_department" name="txt_department" required <?php
                                                    if (empty($dept_Arr)) {
                                                        echo 'disabled';
                                                    }
                                                    ?>>
                                                        <option></option>
                                                        <?php
                                                        foreach ($dept_Arr as $k => $v) {
                                                            if (isset($dataArr)) {
                                                                if ($dataArr['department_id'] == $v['id']) {
                                                                    $selected = 'selected';
                                                                } else {
                                                                    $selected = '';
                                                                }
                                                            } elseif (isset($itemArr)) {
                                                                if ($itemArr['department_id'] == $v['id']) {
                                                                    $selected = 'selected';
                                                                } else {
                                                                    $selected = '';
                                                                }
                                                            } else {
                                                                $selected = '';
                                                            }
                                                            ?>
                                                            <option value="<?php echo $v['id']; ?>" <?php echo $selected; ?>><?php echo $v['name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                <?php } ?>

                                                <?php echo '<label id="txt_department_error2" class="validation-error-label" for="txt_department">' . form_error('txt_department') . '</label>'; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-lg-4 col-md-12 control-label required">Vendor :</label>
                                                <div class="col-lg-8 col-md-12">    

                                                <?php 
                                                if(isset($itemArr['preferred_vendor']) && $itemArr['preferred_vendor'] != "")
                                                {
                                                ?>
                                                    <select class="select select-size-sm" data-placeholder="Select a Vendor..." id="" name="txt_pref_vendor" required <?php
                                                    if (empty($vendor_Arr)) {
                                                        echo 'disabled';
                                                    }
                                                    ?>>
                                                    <option></option>
                                                        <?php
                                                        foreach ($vendor_Arr as $k => $v) {
                                                            if (isset($itemArr)) {
                                                                if ($itemArr['preferred_vendor'] == $v['id']) {
                                                                    $selected = 'selected';
                                                                } else {
                                                                    $selected = '';
                                                                }
                                                            } else {
                                                                $selected = '';
                                                            }
                                                            ?>
                                                            <option value="<?php echo $v['id']; ?>" <?php echo $selected; ?>><?php echo $v['name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                <?php } else { ?>
                                                    <select class="select select-size-sm" data-placeholder="Select a Vendor..." id="txt_pref_vendor" name="txt_pref_vendor" required <?php
                                                    if (empty($vendor_Arr)) {
                                                        echo 'disabled';
                                                    }
                                                    ?>>
                                                        <option></option>
                                                        <?php
                                                        foreach ($vendor_Arr as $k => $v) {
                                                            if (isset($dataArr)) {
                                                                if ($dataArr['vendor_id'] == $v['id']) {
                                                                    $selected = 'selected';
                                                                } else {
                                                                    $selected = '';
                                                                }
                                                            } elseif (isset($itemArr)) {
                                                                if ($itemArr['preferred_vendor'] == $v['id']) {
                                                                    $selected = 'selected';
                                                                } else {
                                                                    $selected = '';
                                                                }
                                                            } else {
                                                                $selected = '';
                                                            }
                                                            ?>
                                                            <option value="<?php echo $v['id']; ?>" <?php echo $selected; ?>><?php echo $v['name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                <?php } ?>

                                                <?php echo '<label id="txt_pref_vendor_error2" class="validation-error-label" for="txt_pref_vendor">' . form_error('txt_pref_vendor') . '</label>'; ?>
                                                </div>
                                            </div>
                                        </div>  
                                        <?php
                                        $segment = $this->uri->segment(3);
                                        if($segment != "")
                                        {
                                            $i_quantity = array_column($dataArr['items_quantity'],'total_quantity');
                                            $item_quantity = implode($i_quantity, ',');
                                        ?>
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback right-touchspin-feedback">
                                                <label class="col-lg-4 col-md-12 control-label">Quantity In-Stock :</label>
                                                <div class="col-lg-8 col-md-12">
                                                    <input type="number" value="<?php echo (isset($item_quantity) && ($item_quantity > 0 )) ? $item_quantity : 0;
                                                    ?>" class="form-control" name="txt_in_stock" id="txt_in_stock" disabled>
                                                           <?php echo '<label id="txt_in_stock_error2" class="validation-error-label" for="txt_in_stock">' . form_error('txt_in_stock') . '</label>'; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } else { ?>
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback">
                                                <label class="col-lg-4 col-md-12 control-label">Quantity In-Stock :</label>
                                                <div class="col-lg-8 col-md-12">
                                                    <input type="text" value="<?php echo (isset($dataArr)) ? $dataArr['qty_on_hand'] : 0;
                                                    ?>" class="form-control" name="txt_in_stock" id="txt_in_stock">
                                                           <?php echo '<label id="txt_in_stock_error2" class="validation-error-label" for="txt_in_stock">' . form_error('txt_in_stock') . '</label>'; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback">
                                                <label class="col-lg-4 col-md-12 control-label">Low Inventory Notification Point :</label>
                                                <div class="col-lg-8 col-md-12">
                                                    <input type="text" value="<?php echo (isset($dataArr) && $dataArr['low_inventory_limit'] > 0) ? $dataArr['low_inventory_limit'] : '';
                                                        ?>" class="form-control" name="txt_low_inventory" id="txt_low_inventory">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback">
                                                <!-- required -->
                                                <label class="col-lg-4 col-md-12 control-label ">Manufacturer :</label>
                                                <div class="col-lg-8 col-md-12">
                                                    <input type="text" class="form-control tags-input" name="txt_manufacturer" id="txt_manufacturer" value="<?php
                                                    if (isset($dataArr)) {
                                                        echo $dataArr['manufacturer'];
                                                    } elseif (isset($itemArr)) {
                                                        echo $itemArr['manufacturer'];
                                                    } else {
                                                        set_value('txt_manufacturer');
                                                    }
                                                    ?>">
                                                   <!-- <?php echo '<label id="txt_manufacturer_error2" class="validation-error-label" for="txt_manufacturer">' . form_error('txt_manufacturer') . '</label>'; ?> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback">
                                                <label class="col-lg-4 col-md-12 control-label">UPC Barcode :</label>
                                                <div class="col-lg-8 col-md-12">
                                                    <input type="text" class="form-control" name="txt_upc_barcode" id="txt_upc_barcode" value="<?php
                                                    if (isset($dataArr)) {
                                                        echo $dataArr['upc_barcode'];
                                                    } else {
                                                        set_value('txt_upc_barcode');
                                                    }
                                                    ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-lg-4 col-md-4 col-xs-12 control-label">Image:</label>
                                                <div class="col-lg-8 col-md-8">
                                                    <div class="media no-margin-top">
                                                        <!-- <input type = "hidden" name = 'item_image_hidden' id = "item_image_hidden" value = ''/> -->

                                                        <?php 
                                                        /* if (!empty($itemArr) && $itemArr['image'] && file_exists(ITEMS_IMAGE_PATH . '/' . $itemArr['image'])) { */  ?>   
                
                                                        <?php 
                                                        if (!empty($itemArr) && $itemArr['image']) { 
                                                        ?>
                                                            <input type = "hidden" name = "item_image_hidden" id = "item_image_hidden" value = "<?php echo $itemArr['image'];  ?>"/>
                                                        <?php } else if(!empty($itemArr) && $itemArr['image'] == "") { ?>

                                                        <?php } else if(!empty($dataArr['image'])) { ?>
                                                            <input type = "hidden" name = "item_image_hidden" id = "item_image_hidden" value = "<?php echo $dataArr['image'];  ?>"/>
                                                        <?php } else { ?>
                                                            <input type = "hidden" name = 'item_image_hidden' id = "item_image_hidden" value = ''/>
                                                        <?php } ?>

                                                        <div class="media-left" id="image_preview_div">
                                                            <?php
                                                            if (!empty($dataArr['global']) && $dataArr['global']['image'] && file_exists(ITEMS_IMAGE_PATH . '/' . $dataArr['global']['image'])) {
                                                                $required = '';
                                                                ?>
                                                                <a class="img_opn" href="javascript:void(0);" data-imgpath="<?php echo ITEMS_IMAGE_PATH . '/' . $dataArr['global']['image'] ?>">
                                                                <img name='item_image' class='item_image' src="<?php echo ITEMS_IMAGE_PATH . '/' . $dataArr['global']['image'] ?>"style="width: 58px; height: 58px; border-radius: 2px;" alt="">
                                                                </a>
                                                            <?php } else {
                                                                if (!empty($itemArr) && $itemArr['image'] && file_exists(ITEMS_IMAGE_PATH . '/' . $itemArr['image'])) {
                                                                    $required = '';
                                                                    ?>
                                                                <a class="img_opn" href="javascript:void(0);" data-imgpath="<?php echo ITEMS_IMAGE_PATH . '/' . $itemArr['image'] ?>">
                                                                    <img name='item_image' class='item_image' src="<?php echo ITEMS_IMAGE_PATH . '/' . $itemArr['image'] ?>" style="width: 58px; height: 58px; border-radius: 2px;" alt="">
                                                                </a>
                                                                    <?php
                                                                }
                                                                else if(!empty($dataArr) && $dataArr['image'] && file_exists(ITEMS_IMAGE_PATH . '/' . $dataArr['image'])) {
                                                                    $required = '';
                                                                    ?>
                                                                <a class="img_opn" href="javascript:void(0);" data-imgpath="<?php echo ITEMS_IMAGE_PATH . '/' . $dataArr['image'] ?>">
                                                                    <img name='item_image' class='item_image' src="<?php echo ITEMS_IMAGE_PATH . '/' . $dataArr['image'] ?>" style="width: 58px; height: 58px; border-radius: 2px;" alt="">
                                                                </a>
                                                                <?php } else {
                                                                    $required = 'required';
                                                                    ?>
                                                                 <a class="img_opn" href="javascript:void(0);" data-imgpath="<?php echo base_url() . 'assets/images/key_icon_blue.png';?>">
                                                                    <img src="assets/images/key_icon_blue.png" style="width: 58px; height: 58px; border-radius: 2px;" alt="">
                                                                </a>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                        <!-- <div class="media-body">
                                                                <input type="file" name="image_link" id="image_link" class="file-styled" onchange="readURL(this);">
                                                                    <span class="help-block">Accepted formats: png, jpg. Max file size 2Mb</span>
                                                            </div>-->
                                                    </div>
                                                    <?php
                                                    //if (isset($menu_item_image_validation))
                                                    //echo '<label id="image_link-error" class="validation-error-label" for="image_link">' . $item_image_validation . '</label>';
                                                    ?>
                                                </div>  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-lg-12">
                                <button type="submit" class="btn bg-teal custom_save_button">Save</button>
                                <button type="button" class="btn btn-default custom_cancel_button" onclick="if (history.length > 2) {
                                            window.history.back()
                                        } else {
                                            window.location.href = 'items';
                                        }">Cancel</button>
                            </div>
                        </div>
                    </div>
                    <div class="row hide" id="div_no_parts">
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <!--<h6 class="text-semibold">Parts Details</h6>-->
                                <h6 class="panel-title" style="font-weight: 400!important;color: #908e8e !important;"><span class='error_message'>No Such Data Found.</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-12 hide" id="compatibility_details_div">
            <div class='panel border-blue location_main'>
                <div class="panel-heading">
                    <h6 class="panel-title">Compatibility Details</h6>
                </div>
                <div class="panel-body" id="compatibility_details_div_body"></div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title">Vehicle Search</h6>
                </div>
                <div class="panel-body">
                    <form method="post" class="form-validate-jquery" id="search_transponder_form" name="search_transponder_form">
                        <div class="row mt-20">
                            <div class="col-md-12">
                                <div class="form-group has-feedback select_form_group">
                                    <label class="required">Make</label>
                                    <select data-placeholder="Select a Company..." class="select select-size-sm" id="txt_make_name" name="txt_make_name">
                                        <option></option>
                                        <?php
                                        foreach ($companyArr as $k => $v) {
                                            $selected = '';
                                            if (isset($searchData) && !empty($searchData)):
                                                if ($v['id'] == $searchData['make_id']):
                                                    $selected = 'selected="selected"';
                                                endif;
                                            endif;
                                            ?>
                                            <option value="<?php echo $v['id']; ?>" <?php echo $selected ?>><?php echo $v['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group has-feedback select_form_group">
                                    <label class="required">Model</label>
                                    <select data-placeholder="Select a Model..." class="select select-size-sm" id="txt_model_name" name="txt_model_name">
                                        <option></option>
                                        <?php
                                        if ((isset($searchData) && !empty($searchData)) && (isset($modelArr) && !empty($modelArr))):
                                            foreach ($modelArr as $k => $v) {
                                                $selected = '';
                                                if ($v['id'] == $searchData['model_id']):
                                                    $selected = 'selected="selected"';
                                                endif;
                                                ?>
                                                <option value="<?php echo $v['id']; ?>" <?php echo $selected ?>><?php echo $v['name']; ?></option>
                                                <?php
                                            }
                                        endif;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group has-feedback select_form_group">
                                    <label class="required">Year</label>
                                    <select data-placeholder="Select a Year..." class="select select-size-sm" id="txt_year_name" name="txt_year_name">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn bg-teal custom_save_button" id="btn_search">Search</button>
                                <button type="button" class="btn btn-default custom_cancel_button" id="btn_reset">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-12 hide" id="div_list_of_parts">
            <div class="">
               <!--  <div class="panel-heading">
                    <h6 class="panel-title">List Of Parts</h6>
                    
                </div> -->
                <!-- <div class="stock_legend_div">
                    <span class="status-mark border-orange position-left"></span>Global Part&nbsp;&nbsp;
                    <span class="status-mark border-success position-left"></span>In Stock&nbsp;&nbsp;
                    <span class="status-mark border-danger position-left"></span>Out Of Stock
                </div> -->
                <div class="panel-body p-0">
                    <div class="div_part_list p-0">
                    </div>                                
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('Templates/footer.php'); ?>
</div>

<!-- View modal -->
<div id="dash_view_modal1" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-teal-400 custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title text-center">Item Details</h6>
            </div>
            <div class="modal-body panel-body custom_scrollbar" id="dash_view_body1" style="height: 500px;overflow: hidden;overflow-y: scroll;"></div>
        </div>
    </div>
</div>
<!-- View modal -->
<div id="partno_scan_webcam_modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-teal-400 custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title text-center">Scan QR Code</h6>
            </div>
            <div class="modal-body panel-body custom_scrollbar">
                <video id="webcam-preview" width="100%"></video>
            </div>
        </div>
    </div>
</div>
<div id="menu-container" style="position:absolute; width: 500px;"></div>

<script>
    var ITEMS_IMAGE_PATH = '<?php echo ITEMS_IMAGE_PATH ?>';
    var edit_div = '<?php echo $edit_div ?>';
    var add_item = '<?php echo $add_item ?>';
</script>
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script type="text/javascript" src="assets/js/custom_pages/front/items.js?version='<?php echo time();?>'"></script>
<style>
    .stock_legend_div{
        height: auto;
        margin: 0;
        top: 100%;
        padding: 7px 20px;
        right: 0;
        background-color: #91cdf5;
        white-space: nowrap;
    }
    .modal-open{ padding-right:3px !important; }
    .hide{ display: none!important }
    .custom_clear_button:hover, .custom_clear_button[class*=bg-]:focus, .custom_clear_button[class*=bg-].focus {
        background-color: #03A9F4;
        border-color: #03A9F4;
    }
    /*@media(min-width:1025px) and (max-width:1400px){
        .media-left#image_preview_div img {width: 40px !important;height: auto !important;}
        .media-left#image_preview_div {max-width: 50px;position: absolute;left: -50px;}
    }*/
    @media(max-width:1024px){  
        .has-feedback  button.btn.btn-default.bg-blue.custom_clear_button {margin-top: 10px;float: right;}
    }
    @media(max-width:320px){ 
        div#image_preview_div img {width: 40px !important;height: 40px !important;}
        div#image_preview_div{width: 20% !important;display: inline-block !important;}
        .media-body{width: 75% !important;display: inline-block !important;}
    }
</style>

<?php
if (!empty($_SESSION) && !isset($_SESSION['is_item_intro_show'])) {
    $_SESSION['is_item_intro_show'] = 1;
}
?>

<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click', '.img_opn', function () {
        var imgpath = $(this).attr('data-imgpath');
        swal({
                title: '',
                imageUrl: imgpath,
                imageWidth: 300,
                imageHeight: 300,
                imageAlt: 'Custom image',
                animation: true
            });
        });
    });
</script>