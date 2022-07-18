<?php
	if(!is_array($tra_result['vendor_multipul']))
	$tra_result['vendor_multipul'] = [];
	if(!is_array($tra_result['manufacturer_multipul']))
	$tra_result['manufacturer_multipul'] = [];
	if(!is_array($tra_result['itemsstatus_multipul']))
	$tra_result['itemsstatus_multipul'] = [];
	error_reporting(0);
?>
<head>
<script type="text/javascript" src="assets/js/fakeLoader.js"></script>
<script type="text/javascript" src="assets/js/fakeLoader.min.js"></script>
<link href="assets/css/fakeloader.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
<link href="assets/css/accordion.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>

<script type="text/javascript" src="assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
<script type="text/javascript" src="assets/js/pages/form_multiselect.js"></script>
</head>

<!-- Start my part -->
<div class="panel panel-flat">
	<div class="panel-heading">
	    <h6 class="panel-title">Filter Results</h6>
	</div>
	<div class="stock_legend_div">
		<span class="status-mark border-orange position-left"></span>Global Part&nbsp;&nbsp;
	    <span class="status-mark border-success position-left"></span>In Stock&nbsp;&nbsp;
        <span class="status-mark border-danger position-left"></span>Out Of Stock
	</div>
	<!-- <form method="post"> -->
		<div class="new-header">
			<div class="row">
				<input type="hidden" name="txt_make_name" value="<?php echo $transponder_result['make_id']?>" id="txt_make_name">
				<input type="hidden" name="txt_model_name" value="<?php echo $transponder_result['model_id']?>" id="txt_model_name">
				<input type="hidden" name="txt_year_name" value="<?php echo $transponder_result['year_id']?>" id="txt_year_name">

				<!-- Multipul Vendor options -->
				<div class="form-group col-md-3">
					<span class="text-semibold">Select Vendor</span>
					<div class="multi-select-full">
						<?php 
							if(isset($vendor_list) && !empty($vendor_list)) {  
						?>
							<select class="multiselect-filtering" multiple="multiple" id="txt_vender_name_multipul">
								<?php 
				            		foreach ($vendor_list as $vl) {
				            			?>
				            				<option value="<?php echo $vl->name; ?>" class="form-group" <?php echo (in_array($vl->name, $tra_result['vendor_multipul'])) ? 'selected' : '' ?>><?php echo $vl->name; ?></option>
				            			<?php
				            		}
				            	?>
							</select> 
			            <?php } else { ?>
							<select class="txt_vender_name">
			            		<option value="" class="form-group">Select vendor from setting -> Customization</option>
			            	</select>
			           	<?php } ?>
					</div>
				</div>
				<div class="form-group col-md-1 pl-3 pl-md-0">
					<button type="button" id="vendorsearch" class="btn bg-teal v-btn filter-search-btn">Search</button>
				</div>
				<!-- Multipul Vendor options -->

				<!-- Multipul Department options -->
				<div class="form-group col-md-3">
					<span class="text-semibold">Select Department</span>
					<div class="multi-select-full">
						<select class="multiselect-filtering" multiple="multiple" id="txt_manufacturer_name_multipul">
							<?php 
			            	if($department_list)
			            		foreach ($department_list as $dl) {
			            			?>
			                			<option value="<?php echo $dl->name; ?>" class="form-group" <?php echo (in_array($dl->name, $tra_result['manufacturer_multipul'])) ? 'selected' : '' ?>><?php echo $dl->name; ?></option>
			            			<?php
			            		}
			            	?>
						</select> 
					</div>
				</div>
				<div class="form-group col-md-1">
					<button type="button" id="manufacturersearch" class="btn bg-teal v-btn filter-search-btn">Search</button>
				</div>
				<!-- Multipul Department options -->

				<!-- Multipul Items status options -->
				<div class="form-group col-md-3">
					<span class="text-semibold">Select Items status</span>
					<div class="multi-select-full">
						<select class="multiselect-filtering" multiple="multiple" id="txt_items_status_multipul">
							<option value="In Stock" <?php echo (in_array('In Stock', $tra_result['itemsstatus_multipul'])) ? 'selected' : '' ?> >In Stock</option>
			            	<option value="Out Of Stock" <?php echo (in_array('Out Of Stock', $tra_result['itemsstatus_multipul'])) ? 'selected' : '' ?> >Out Of Stock</option>
						</select> 
					</div>
				</div>
				<div class="form-group col-md-1">
					<button type="button" id="itemsstatussearch" class="btn bg-teal v-btn filter-search-btn">Search</button>
				</div>
				<!-- Multipul Items status options -->

				<div class="row">
					<div class="col-sm-12">
						<div class="col-sm-12">
							<button type="button" class="btn btn-default" id="reset_search_result_filter">Reset</button>
						</div>
					</div>
				</div>
				
		    </div>
		</div>
	<!-- </form> -->
</div>
<!-- End my part  -->

<!-- For Global item -->
<div class="panel panel-default panel-flat" id="hide_show">	
	<div class="panel-heading">
	    <h6 class="panel-title">Your Inventory Results</h6>
	</div>
	<div class="stock_legend_div">
		<!-- <span class="status-mark border-orange position-left"></span>Global Part&nbsp;&nbsp; -->
	    <span class="status-mark border-success position-left"></span>In Stock&nbsp;&nbsp;
        <span class="status-mark border-danger position-left"></span>Out Of Stock
	</div>
<form method="post" action="">
	<?php 
	$count = 0;
	// $accordion = 1;
	foreach ($part_result['vendor_name_Arr'] as $k => $v) { 
		if ($v != '' && $v != null):
			if (isset($part_result['parts_id_Arr'][$k]) && isset($part_result['parts_no_Arr'][$k]) && isset($part_result['parts_stock_Arr'][$k])) {
	?>
	<?php 
		if($count == 0)
		{
	?>
	<div class="panel-heading accordiannonhead" style="display: none;">
		<h4 class="panel-title">
	    	<a data-toggle="collapse" data-parent="#accordion" href="#collapse123">
	    		<?php echo $part_result['global_vendor_name_Arr'][$k]; ?>
	    	</a>
	  	</h4>
	</div>
	<?php } ?>	
	<div id="collapse1" class="panel-collapse collapse in panel-border accordiannondiv">
      	<div class="panel-body">
      		<div class="d-accordian-data">
      			<div class="part-img">
      			<?php 
      				if($part_result['globalimage'][$k] != "")
      				{
      					if(file_exists('uploads/items/'.$part_result['globalimage'][$k]))
						{
						?>
						<a class="img_opn" href="javascript:void(0);" data-imgpath="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . $part_result['globalimage'][$k]; ?>">
	    					<img src="<?php echo site_url('uploads/items/'.$part_result['globalimage'][$k]);?>" alt="image" style="margin-top: 20px; margin-left: 20px;margin-bottom: 20px;">
	    				</a>
						<?php } else { ?>
						<a class="img_opn" href="javascript:void(0);" data-imgpath="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . 'no_image.jpg'; ?>">
							<img src="<?php echo site_url('uploads/items/no_image.jpg');?>" alt="image" style="margin-top: 20px; margin-left: 20px;margin-bottom: 20px;">
						</a>
						<?php  
							} 
						} else {
							?>
						<a class="img_opn" href="javascript:void(0);" data-imgpath="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . 'no_image.jpg'; ?>">
							<img src="<?php echo site_url('uploads/items/no_image.jpg');?>" alt="image" style="margin-top: 20px; margin-left: 20px;">
						</a>
				<?php }	?>
				</div>
				<div class="part-meta-data">
      				<div class="meta-table">
  						<h3 class="text-primary"><?php echo $part_result['globaldescription'][$k]; ?> </h3>
						<div class="record">
	  						<div class="left">
								<b>Item Part No:</b>
	  						</div>
							<?php
				        	if($part_result['parts_stock_Arr'][$k] > 0)
				        	{
			        		?>
			        		<div class="right btn-success">
								&nbsp;<?php echo $part_result['parts_no_Arr'][$k]; ?>&nbsp;
  							</div>
  							<?php } else { ?>
  							<div class="right btn-danger">
								&nbsp;<?php echo $part_result['parts_no_Arr'][$k]; ?>&nbsp;
  							</div>
  							<?php } ?>
						</div>
		      			<div class="record">
			      			<div class="left">
								<b>Alternate Part No or SKU:</b>
			      			</div>
			      			<div class="right">
			      				<?php echo $part_result['internal_part_no'][$k]; ?>
			      			</div>
		      			</div>
		      			<div class="record">
			      			<div class="left">
								<b>Department:</b>
			      			</div>
			      			<div class="right">
			      				<?php echo $part_result['department_name'][$k]; ?>
			      			</div>
		      			</div>
		      			<div class="record">
			      			<div class="left">
								<b>Vendor:</b>
			      			</div>
			      			<div class="right">
			      				<?php echo $part_result['vendor_name_Arr'][$k]; ?>
			      			</div>
		      			</div>
	      				<div class="record">
			      			<div class="left">
								<b>Manufacturer:</b>
			      			</div>
			      			<div class="right">
			      				<?php echo $part_result['manufacturer'][$k]; ?>
			      			</div>
	      				</div>
	      				<div class="record">
			      			<div class="left">
								<b>Price:</b>
			      			</div>
			      			<div class="right">
			      				<?php echo $part_result['retail_price'][$k]; ?>
			      			</div>
	      				</div>	
  					</div>
      			</div>
	      		<div class="part-data-action">
	      			<div class="chk-box-wrap">
		      			<?php
		      			$location_id = 106;
						if ($part_result['parts_stock_Arr'][$k] > 0) {
							$transponder = '';
								if (!is_null($part_result['parts_no_Arr'][$k])) {
								$transponder = $part_result['transponder_id'][$k];
								if (!is_null($part_result['transponder_id'][$k])) {
							    	$transponder = $part_result['transponder_id'][$k];
								} else {
							    	$transponder = '';
								}
							} else {
								if (!is_null($part_result['transponder_id'][$k])) {
								    $transponder = $part_result['transponder_id'][$k] . '&type=' . 1;
								}
							}
						}

		      			if ($part_result['parts_stock_Arr'][$k] > 0) {
		      			?>
			      			<label class="chk-box-container" id="count_checkbox">
							  	<input type="checkbox" name="multipul_est_id[]" value="<?php echo $part_result['parts_id_Arr'][$k]; ?>" id="multipul_est" >
							  	<input type="hidden" name="multipul_est_loc_id" value="<?php echo $location_id; ?>">
							  	<input type="hidden" name="multipul_est_tra_id" value="<?php echo $transponder; ?>">
							  	<span class="checkmark"></span>
							</label>
						<?php } ?>

		      		</div>
		      		<div class="btn-wrap">
	      				<?php
			        	if($part_result['parts_stock_Arr'][$k] > 0)
			        	{
			        		?>
			        		<label class="btn btn-success">
			        			<?php echo'( '.$part_result['parts_stock_Arr'][$k].' ) IN STOCK';?>
			        		</label>
			        	<?php } else { ?>
			        		<label class="btn btn-danger">
			        			<?php echo '( 0 ) OUT OF STOCK'; ?>
			        		</label>
			          	<?php } ?>
			          	<!-- <a href='<?php echo base_url() . 'items/add?id=' . base64_encode($part_result['global_parts_id_Arr'][$k]) ?>' class='btn btn-default'>Add to Item</a> -->
			          	<a href="javasciprt:void(0)" class=" btn bg-teal v-btn btn_home_item_view" title="View" id="<?php echo base64_encode($part_result['parts_id_Arr'][$k]); ?>">
				          	View Details
			        	</a>       	
						<?php
						// $location_id = 106;
						$part_location_id = $part_result['parts_id_Arr'][$k];
						
						if ($part_result['parts_stock_Arr'][$k] > 0) {
						$transponder = '';
							if (!is_null($part_result['parts_no_Arr'][$k])) {
							$transponder = '&transponder_id=' . base64_encode($part_result['transponder_id'][$k]);
							if (!is_null($part_result['transponder_id'][$k])) {
						    	$transponder = '&transponder_id=' . base64_encode($part_result['transponder_id'][$k]);
							} else {
						    	$transponder = '';
							}
						} else {
							if (!is_null($part_result['transponder_id'][$k])) {
							    $transponder = '&transponder_id=' . base64_encode($part_result['transponder_id'][$k]) . '&type=' . base64_encode(1);
							}
						}
						
						$locations = $this->inventory_model->get_all_details(TBL_LOCATIONS, ['is_deleted' => 0, 'business_user_id' => checkUserLogin('C'), 'is_active' => 1])->result_array();
			            $this->db->select('il.*');
			            $this->db->where(['il.item_id' => $part_result['parts_id_Arr'][$k], 'il.is_deleted' => 0]);
			            $location_qty = $this->db->get(TBL_ITEM_LOCATION_DETAILS . ' il')->result_array();
			            foreach ($locations as $k => $l) {
			                if (($key = (array_search($l['id'], array_column($location_qty, 'location_id')))) !== false) {
			                    $l['location_quantity'] = $location_qty[$key]['quantity'];
			                } else {
			                    $l['location_quantity'] = 0;
			                }
			                $locations[$k] = $l;
			            }
			            $viewArr['locations'] = $locations;
			            
			            $b = array_keys(array_column($viewArr['locations'], 'location_quantity'), max(array_column($viewArr['locations'], 'location_quantity')));
	                    if (max(array_column($viewArr['locations'], 'location_quantity')) > 0) {
	                        $location_id_final = $viewArr['locations'][$b[0]]['id'];
	                    }						
						// echo "Location_id_final:- ".$location_id_final;

						?>
							<a href='<?php echo base_url() . 'invoices/add?id=' .  base64_encode($part_location_id) . '&location_id=' . base64_encode($location_id_final) . $transponder ?>' class='btn bg-indigo-300'>Add to Invoice</a>
						<?php } ?>
							<a href='<?php echo base_url() . 'estimates/add?id=' . base64_encode($part_location_id) . '&location_id=' . base64_encode($location_id_final) . $transponder ?>' class='btn bg-pink-300'>Add to Estimate</a>
					</div>
       			</div>
       		</div>
      	</div>
    </div>
    <!-- <hr> -->
    <?php 
			}	
		$count++;
		endif;
	} 
	?>
	
	<?php
	foreach ($part_result['parts_no_Arr'] as $k => $v) { 
	if ($v != '' && $v != null):
		if (isset($part_result['parts_id_Arr'][$k]) && isset($part_result['parts_no_Arr'][$k]) && isset($part_result['parts_stock_Arr'][$k])) {
		if($k == 0)
		{
	?>
		<div class="selected_items">
			<button type="submit" formaction="<?php echo base_url() . 'estimates/add';?>" class="btn bg-pink-300 mb-3" style="float: right;" value="Add to Estimate" id="add_count_items_estimate">Add ( 0 ) to Estimate</button>
			<button type="submit" formaction="<?php echo base_url() . 'invoices/add';?>" class="btn bg-indigo-300 mb-3" style="float: right;" value="Add to Invoice" id="add_count_items_invoice">Add ( 0 ) to Invoice</button>
		</div>
	<?php
				} 
			}
		endif;
		}
	?>

</form>

</div>
<div class="panel panel-flat">
	<div class="panel-heading">
	    <h6 class="panel-title">Compatible Parts Not In Your Inventory</h6>
	</div>
	<div class="stock_legend_div">
		<span class="status-mark border-orange position-left"></span>Global Part&nbsp;&nbsp;
	</div>

<?php 
$count = 0;
// $accordion = 1;
foreach ($part_result['global_parts_no_Arr'] as $k => $v) {
	if ($part_result['global_vendor_name_Arr'][$k] != ''): 
?>
<?php 
	if($count == 0)
	{
?>
<div class="panel-heading accordianhead" data-id="" style="display: none;">
	<h4 class="panel-title">
    	<a data-toggle="collapse" data-parent="#accordion" href="#collapse123" class="accordion-toggle">
    		<?php echo $part_result['global_vendor_name_Arr'][$k]; ?>
    	</a>
  	</h4>
</div>
<?php } ?>
	
	<div id="collapse" class="panel-collapse collapse in panel-border accordiandiv">
      	<div class="panel-body">
      		<div class="d-accordian-data">
      			<div class="part-img">
      				<?php 
      				if($part_result['globalimage'][$k] != "")
      				{
      					if(file_exists('uploads/items/'.$part_result['globalimage'][$k]))
						{
						?>
							<a class="img_opn" href="javascript:void(0);" data-imgpath="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . $part_result['globalimage'][$k]; ?>">
	    						<img src="<?php echo site_url('uploads/items/'.$part_result['globalimage'][$k]);?>" alt="image" style="margin-top: 20px; margin-left: 20px;">
	    					</a>
						<?php } else { ?>
							<a class="img_opn" href="javascript:void(0);" data-imgpath="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . 'no_image.jpg'; ?>">
								<img src="<?php echo site_url('uploads/items/no_image.jpg');?>" alt="image" style="margin-top: 20px; margin-left: 20px;">
							</a>
						<?php  
							} 
						} else {
							?>
							<a class="img_opn" href="javascript:void(0);" data-imgpath="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . 'no_image.jpg'; ?>">
								<img src="<?php echo site_url('uploads/items/no_image.jpg');?>" alt="image" style="margin-top: 20px; margin-left: 20px;">
							</a>
					<?php }	?>
				</div>
				<div class="part-meta-data">
      				<div class="meta-table">
  						<h3 class="text-primary"> <?php echo $part_result['globaldescription'][$k]; ?> </h3>
						<div class="record">
	  						<div class="left">
								<b>Item Part No:</b>
	  						</div>
							<div class="right bg-orange">
  								&nbsp;<?php echo $part_result['global_parts_no_Arr'][$k]; ?>&nbsp;
  							</div>
						</div>
		      			<div class="record">
			      			<div class="left">
								<b>Alternate Part No or SKU:</b>
			      			</div>
			      			<div class="right">
			      				<?php echo $part_result['internal_part_no'][$k]; ?>
			      			</div>
		      			</div>
		      			<div class="record">
			      			<div class="left">
								<b>Department:</b>
			      			</div>
			      			<div class="right">
			      				<?php echo $part_result['department_name'][$k]; ?>
			      			</div>
		      			</div>
		      			<div class="record">
			      			<div class="left">
								<b>Vendor:</b>
			      			</div>
			      			<div class="right">
			      				<?php echo $part_result['globalvendorname'][$k]; ?>
			      			</div>
		      			</div>
	      				<div class="record">
			      			<div class="left">
								<b>Manufacturer:</b>
			      			</div>
			      			<div class="right">
			      				<?php echo $part_result['manufacturer'][$k]; ?>
			      			</div>
	      				</div>	
  					</div>
      			</div>
	      		<div class="part-data-action">
	      			<div class="btn-wrap">
						<a href='<?php echo base_url() . 'items/add?id=' . base64_encode($part_result['global_parts_id_Arr'][$k]) ?>' class='btn btn-default'>Add to Inventory</a>
	      				<a href="javasciprt:void(0)" class="btn_global_item_view btn bg-teal v-btn" title="View" id="<?php echo base64_encode($part_result['global_parts_id_Arr'][$k]);  ?>">View Details</a>
					</div>
       			</div>
       		</div>
      	</div>
    </div>
	<!-- <hr> -->
<?php
	// $accordion++;
	$count++;
		endif;
}
?>
</div>
<!-- End Global item -->

<!-- Non-global items -->
<!-- End Non-global items -->

<!-- Start my part -->
<div class="panel panel-default panel-flat" id="hide_show">	
	<div class="panel-heading">
	    <h6 class="panel-title">List Of My Parts</h6>
	</div>
	<div class="stock_legend_div">
		<span class="status-mark border-success position-left"></span>In Stock&nbsp;&nbsp;
        <span class="status-mark border-danger position-left"></span>Out Of Stock
	</div>
	<form method="post" action="">
		<?php
    		foreach(array_unique($part_result['non_global_parts_no_Arr']) as $k => $value) 
    		{
    			if($value != "")
    			{
    	?>
		<div id="collapse1" class="panel-collapse collapse in panel-border accordiannondiv">
	      	<div class="panel-body">
	      		<div class="d-accordian-data">
	      			<div class="part-img">
	      			<?php 
	      				if($part_result['mypartimage'][$k] != "")
	      				{
	      					if(file_exists('uploads/items/'.$part_result['mypartimage'][$k]))
							{
							?>
							<a class="img_opn" href="javascript:void(0);" data-imgpath="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . $part_result['mypartimage'][$k]; ?>">
		    					<img src="<?php echo site_url('uploads/items/'.$part_result['mypartimage'][$k]);?>" alt="image" style="margin-top: 20px; margin-left: 20px;">
		    				</a>
							<?php } else { ?>
							<a class="img_opn" href="javascript:void(0);" data-imgpath="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . 'no_image.jpg'; ?>">
								<img src="<?php echo site_url('uploads/items/no_image.jpg');?>" alt="image" style="margin-top: 20px; margin-left: 20px;">
							</a>
							<?php  
								} 
							} else {
								?>
							<a class="img_opn" href="javascript:void(0);" data-imgpath="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . 'no_image.jpg'; ?>">
								<img src="<?php echo site_url('uploads/items/no_image.jpg');?>" alt="image" style="margin-top: 20px; margin-left: 20px;">
							</a>
					<?php }	?>
					</div>
					<div class="part-meta-data">
	      				<div class="meta-table">
	  						<h3 class="text-primary"><?php echo $part_result['mypartdescription'][$k]; ?></h3>
							<div class="record">
		  						<div class="left">
									<b>Item Part No:</b>
		  						</div>
								<div class="right btn-success">
									<?php
						        	if($part_result['my_parts_stock_Arr'][$k] > 0)
						        	{
						        	?>
							        	<div class="right btn-success">
											&nbsp;<?php echo $part_result['non_global_parts_no_Arr'][$k]; ?>&nbsp;
										</div>
						        	<?php } else { ?>
						        		<div class="right btn-danger">
											&nbsp;<?php echo $part_result['non_global_parts_no_Arr'][$k]; ?>&nbsp;
										</div>
						          	<?php } ?>
									
	  							</div>
	  						</div>
			      			<div class="record">
				      			<div class="left">
									<b>Alternate Part No or SKU:</b>
				      			</div>
				      			<div class="right">
									<?php echo $part_result['mypartinternalpart'][$k]; ?>
				      			</div>
			      			</div>
			      			<div class="record">
				      			<div class="left">
									<b>Department:</b>
				      			</div>
				      			<div class="right">
				      				<?php echo $part_result['mypartdepartment'][$k]; ?>
				      			</div>
			      			</div>
			      			<div class="record">
				      			<div class="left">
									<b>Vendor:</b>
				      			</div>
				      			<div class="right">
				      				<?php echo $part_result['non_global_vendor_name_Arr'][$k]; ?>
				      			</div>
			      			</div>
		      				<div class="record">
				      			<div class="left">
									<b>Manufacturer:</b>
				      			</div>
				      			<div class="right">
				      				<?php echo $part_result['mypartmanufacturer'][$k]; ?>
				      			</div>
		      				</div>
		      				<div class="record">
				      			<div class="left">
									<b>Price:</b>
				      			</div>
				      			<div class="right">
				      				<?php echo $part_result['mypartrate'][$k]; ?>
				      			</div>
		      				</div>	
	  					</div>
	      			</div>
		      		<div class="part-data-action">
		      			<div class="chk-box-wrap">
		      				<?php
			      			$location_id = 106;
							if ($part_result['my_parts_stock_Arr'][$k] > 0) {
								$transponder = '';
									if (!is_null($part_result['parts_no_Arr'][$k])) {
									$transponder = $part_result['transponder_id'][$k];
									if (!is_null($part_result['transponder_id'][$k])) {
								    	$transponder = $part_result['transponder_id'][$k];
									} else {
								    	$transponder = '';
									}
								} else {
									if (!is_null($part_result['transponder_id'][$k])) {
									    $transponder = $part_result['transponder_id'][$k] . '&type=' . 1;
									}
								}
							}

			      			if ($part_result['my_parts_stock_Arr'][$k] > 0) {
			      			?>
				      			<label class="chk-box-container" id="count_checkbox_mypart">
								  	<input type="checkbox" name="multipul_est_id[]" value="<?php echo $part_result['non_global_parts_id_Arr'][$k]; ?>" id="multipul_est" >
								  	<input type="hidden" name="multipul_est_loc_id" value="<?php echo $location_id; ?>">
								  	<input type="hidden" name="multipul_est_tra_id" value="<?php echo $transponder; ?>">
								  	<span class="checkmark"></span>
								</label>
							<?php } ?>
			      		</div>
			      		<div class="btn-wrap">
		      				<?php
				        	if($part_result['my_parts_stock_Arr'][$k] > 0)
				        	{
				        	?>
				        		<label class="btn btn-success">
				        			<?php echo'( '.$part_result['my_parts_stock_Arr'][$k].' ) IN STOCK';?>
				        		</label>
				        	<?php } else { ?>
				        		<label class="btn btn-danger">
				        			<?php echo '( 0 ) OUT OF STOCK'; ?>
				        		</label>
				          	<?php } ?>
				          	<a href="javasciprt:void(0)" class=" btn bg-teal v-btn btn_home_item_view" title="View" id="<?php echo base64_encode($part_result['non_global_parts_id_Arr'][$k]); ?>">
					          	View Details
				        	</a>       	
				        	<?php
							// $location_id = 106;
							$part_location_id = $part_result['non_global_parts_id_Arr'][$k];
							
							if ($part_result['my_parts_stock_Arr'][$k] > 0) {
								$transponder = '';
								if (!is_null($part_result['mypartpartsno'][$k])) {
								$transponder = '&transponder_id=' . base64_encode($part_result['transponder_id'][$k]);
								if (!is_null($part_result['transponder_id'][$k])) {
							    	$transponder = '&transponder_id=' . base64_encode($part_result['transponder_id'][$k]);
								} else {
							    	$transponder = '';
								}

							} else {
								if (!is_null($part_result['transponder_id'][$k])) {
								    $transponder = '&transponder_id=' . base64_encode($part_result['transponder_id'][$k]) . '&type=' . base64_encode(1);
								}
							}
							$locations = $this->inventory_model->get_all_details(TBL_LOCATIONS, ['is_deleted' => 0, 'business_user_id' => checkUserLogin('C'), 'is_active' => 1])->result_array();
					            $this->db->select('il.*');
					            $this->db->where(['il.item_id' => $part_result['non_global_parts_id_Arr'][$k], 'il.is_deleted' => 0]);
					            $location_qty = $this->db->get(TBL_ITEM_LOCATION_DETAILS . ' il')->result_array();
					            foreach ($locations as $k => $l) {
					                if (($key = (array_search($l['id'], array_column($location_qty, 'location_id')))) !== false) {
					                    $l['location_quantity'] = $location_qty[$key]['quantity'];
					                } else {
					                    $l['location_quantity'] = 0;
					                }
					                $locations[$k] = $l;
					            }
					            $viewArr['locations'] = $locations;
					            
					            $b = array_keys(array_column($viewArr['locations'], 'location_quantity'), max(array_column($viewArr['locations'], 'location_quantity')));
			                    if (max(array_column($viewArr['locations'], 'location_quantity')) > 0) {
			                        $location_id_final = $viewArr['locations'][$b[0]]['id'];
			                    }						
								// echo "Location_id_final:- ".$location_id_final;
							?>
								<a href='<?php echo base_url() . 'invoices/add?id=' .  base64_encode($part_location_id) . '&location_id=' . base64_encode($location_id_final) . $transponder . '&type=' . base64_encode(1) ?>' class='btn bg-indigo-300'>Add to Invoice</a>
							<?php } ?>
								<a href='<?php echo base_url() . 'estimates/add?id=' . base64_encode($part_location_id) . '&location_id=' . base64_encode($location_id_final) . $transponder . '&type=' . base64_encode(1)?>' class='btn bg-pink-300'>Add to Estimate</a>
						</div>
			      	</div>
	       		</div>
	      	</div>
	    </div>
	    <?php } } ?>
	    <?php
	    $j = 0;
		foreach ($part_result['non_global_parts_no_Arr'] as $k => $v) { 
		if ($v != '' && $v != null):
			if (isset($part_result['non_global_parts_id_Arr'][$k]) && isset($part_result['mypartpartsno'][$k]) && isset($part_result['my_parts_stock_Arr'][$k])) {
			if($j == 0)
			{
		?>
			<div class="selected_items">
				<button type="submit" formaction="<?php echo base_url() . 'estimates/add?&type=' . base64_encode(1);?>" class="btn bg-pink-300 mb-3" style="float: right;" value="Add to Estimate" id="add_count_items_estimate_mypart">Add ( 0 ) to Estimate</button>
				<button type="submit" formaction="<?php echo base_url() . 'invoices/add?&type=' . base64_encode(1);?>" class="btn bg-indigo-300 mb-3" style="float: right;" value="Add to Invoice" id="add_count_items_invoice_mypart">Add ( 0 ) to Invoice</button>
			</div>
		<?php
					} 
				}
				$j++;
			endif;
			}
		?>
	</form>
</div>
<!-- End my part  -->

<!-- Start Detail -->
<div class="col-md-12" id="div_transponder_result">
    <div class="panel panel-flat">
        <div class="panel-heading text-center" style="color: #fff;background-color: #009688;padding:10px 20px">
            <h6 class="panel-title">Details</h6>
        </div>
        <div class="panel-body" style="padding:0px">
        	<div class="search-part-result-wrap">
				<div class="row">
					<div class="col-md-6 col-sm-12">
						 <table class="table table-bordered table-striped">
								<tbody>
							      <tr>
							        <td class="table-label">Make</td>
							        <td><?php echo $transponder_result['make_name']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">Model</td>
							        <td><?php echo $transponder_result['model_name']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">Year</td>
							        <td><?php echo $transponder_result['year_name']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">Transponder Equipped</td>
							        <td><?php echo $transponder_result['transponder_equipped']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">Key Type</td>
							        <td><?php echo str_replace('_', ' ', join(', ', array_map('ucfirst', explode(',', $transponder_result['key_value'])))); ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">System</td>
							        <td><?php echo $transponder_result['mvp_system']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">Pincode Required</td>
							        <td><?php echo $transponder_result['pincode_required']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">Pincode Reading Available</td>
							        <td><?php echo $transponder_result['pincode_reading_available']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">Key Onboard Programming</td>
							        <td><?php echo $transponder_result['key_onboard_progaming']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">Remote Onboard Programming</td>
							        <td><?php echo $transponder_result['remote_onboard_progaming']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">Test Key</td>
							        <td><?php echo ((!empty($transponder_result['test_key'])) ? $transponder_result['test_key'] : ''); ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">IIco</td>
							        <td><?php echo $transponder_result['iico']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">JET</td>
							        <td><?php echo ((!empty($transponder_result['jet'])) ? $transponder_result['jet'] : ''); ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">JMA</td>
							        <td><?php echo $transponder_result['jma']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">Keyline</td>
							        <td><?php echo $transponder_result['keyline']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">Strattec Non-Remote Key</td>
							        <td><?php echo $transponder_result['strattec_non_remote_key']; ?></td>
							      </tr>
							    </tbody>
					  	 </table>
					</div>
					<div class="col-md-6 col-sm-12">
						 <table class="table table-bordered table-striped">
								<tbody>
							      <tr>
							        <td class="table-label">Strattec Remote Key</td>
							        <td><?php echo $transponder_result['strattec_remote_key']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">OEM Non-Remote Key</td>
							        <td><?php echo $transponder_result['oem_non_remote_key']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">OEM Remote Key</td>
							        <td><?php echo $transponder_result['oem_remote_key']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">Other</td>
							        <td><?php echo $transponder_result['other_non_remote_key']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">FCC ID#</td>
							        <td><?php echo $transponder_result['fcc_id']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">IC#</td>
							        <td><?php echo $transponder_result['ic']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">Frequency</td>
							        <td><?php echo $transponder_result['frequency']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">Code Series</td>
							        <td><?php echo $transponder_result['code_series']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">Chip ID</td>
							        <td><?php echo $transponder_result['chip_ID']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">Transponder Re-Use</td>
							        <td><?php echo $transponder_result['transponder_re_use']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">Max No of Keys</td>
							        <td><?php echo $transponder_result['max_no_of_keys']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">Key Shell</td>
							        <td><?php echo $transponder_result['key_shell']; ?></td>
							      </tr>
							      <tr>
							        <td class="table-label">Notes</td>
							        <td><?php echo $transponder_result['notes']; ?></td>
							      </tr>
							    </tbody>
					  	 </table>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
<!-- End detail -->

<!-- Start tool -->
<?php pr($tra_result['div_tool_list']); ?>
<!-- End Tool -->

<script>
$(document).ready(function(){

	// Initialize Select2
  	$('#txt_vender_name').select2();
  	$('.txt_vender_name').select2();
  	$('#txt_department_name').select2();
  	$('#txt_item_status').select2();

  	// Multipul vendor search
  	$('#vendorsearch').click(function(event) {
		var make_name = $('#txt_make_name').val();
		var model_name = $('#txt_model_name').val();
		var year_name = $('#txt_year_name').val();
		
		var vendor_multipul = [];
        $.each($("#txt_vender_name_multipul"), function(){
            vendor_multipul.push($(this).val());
        });
        // console.log("My favourite vendors name are 123 :- " + vendor_multipul.join(", "));
        $('#custom_loading').removeClass('hide');
	    $('#custom_loading').css('display', 'block');
  		$.ajax({
	        url: site_url + 'dashboard/get_transponder_details',
	        dataType: "json",
			type: "POST",
	        data: {_make_id: make_name, _model_id: model_name, _year_id: year_name,vendor_multipul: vendor_multipul},
	        success: function (response) {
	          	$('.div_part_list').html(response);
				$("#custom_loading").fadeOut(1000);
	        }
	    });
  	});

  	// Multipul manufacturer search
  	$('#manufacturersearch').click(function(event) {
  		var make_name = $('#txt_make_name').val();
		var model_name = $('#txt_model_name').val();
		var year_name = $('#txt_year_name').val();
		
		var manufacturer_multipul = [];
        $.each($("#txt_manufacturer_name_multipul"), function(){
            manufacturer_multipul.push($(this).val());
        });
        // console.log("My favourite vendors name are 123 :- " + manufacturer_multipul.join(", "));
        $('#custom_loading').removeClass('hide');
	    $('#custom_loading').css('display', 'block');
  		$.ajax({
	        url: site_url + 'dashboard/get_transponder_details',
	        dataType: "json",
			type: "POST",
	        data: {_make_id: make_name, _model_id: model_name, _year_id: year_name,manufacturer_multipul: manufacturer_multipul},
	        success: function (response) {
	            $('.div_part_list').html(response);
				$("#custom_loading").fadeOut(1000);
	        }
	    });
  	});

  	// Multipul Items Status Search
  	$('#itemsstatussearch').click(function(event) {
  		var make_name = $('#txt_make_name').val();
		var model_name = $('#txt_model_name').val();
		var year_name = $('#txt_year_name').val();
		
		var itemsstatus_multipul = [];
        $.each($("#txt_items_status_multipul"), function(){
            itemsstatus_multipul.push($(this).val());
        });
        // console.log("My favourite vendors name are 123 :- " + itemsstatus_multipul.join(", "));
        $('#custom_loading').removeClass('hide');
	    $('#custom_loading').css('display', 'block');
  		$.ajax({
	        url: site_url + 'dashboard/get_transponder_details',
	        dataType: "json",
			type: "POST",
	        data: {_make_id: make_name, _model_id: model_name, _year_id: year_name,itemsstatus_multipul: itemsstatus_multipul},
	        success: function (response) {
                $('.div_part_list').html(response);
            	$("#custom_loading").fadeOut(2000);
	        }
	    });
  	});

	// RESET FILTER
	$('#reset_search_result_filter').click(function(event) {
		var make_name = $('#txt_make_name').val();
		var model_name = $('#txt_model_name').val();
		var year_name = $('#txt_year_name').val();
		
		$('#custom_loading').removeClass('hide');
	    $('#custom_loading').css('display', 'block');
		$.ajax({
	        url: site_url + 'dashboard/get_transponder_details',
	        dataType: "json",
			type: "POST",
	        data: {_make_id: make_name, _model_id: model_name, _year_id: year_name},
	        success: function (response) {
	            $('.div_part_list').html(response);
	            $("#custom_loading").fadeOut(2000);
	        }
	    });		
	});

	// Count checked checkbox
	var $checkboxes = $('#count_checkbox input[type="checkbox"]');
    $checkboxes.change(function(){
        var countCheckedCheckboxes = $checkboxes.filter(':checked').length;
        $('#add_count_items_invoice').text('Add ( ' + countCheckedCheckboxes + ' ) to Invoice');
        $('#add_count_items_estimate').text('Add ( ' + countCheckedCheckboxes +' ) to Estimate');
    });

    // Count checked checkbox my part
	var $checkboxes_mypart = $('#count_checkbox_mypart input[type="checkbox"]');
    $checkboxes_mypart.change(function(){
        var countCheckedCheckboxes = $checkboxes_mypart.filter(':checked').length;
        $('#add_count_items_invoice_mypart').text('Add ( ' + countCheckedCheckboxes + ' ) to Invoice');
        $('#add_count_items_estimate_mypart').text('Add ( ' + countCheckedCheckboxes +' ) to Estimate');
    });


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