<link href="assets/css/dashboard_quickbook.css" rel="stylesheet" type="text/css">
<link href="assets/css/ladda-themeless.min.css" rel="stylesheet" type="text/css">
<div class="dashboard-quickbook">
	<div class="content">
		<?php $this->load->view('alert_view'); ?>
		<div id="exTab1" class="">
			<ul  class="nav nav-pills">
				<li class="active">
					<a  href="#1a" data-toggle="tab">Customer sync</a>
				</li>
				<li><a href="#2a" data-toggle="tab">Account config</a>
			</li>
		</ul>
		<div class="tab-content clearfix">
			<div class="tab-pane active" id="1a">
				<div class="row">
					<div class="col-lg-4">
						<!-- Members online -->
						<div class="panel bg-teal-400">
							<div class="panel-body">
								<div class="quickbook">
									<img src="<?php echo base_url('assets/images/quickbook/quick-book-logo.png') ?>" alt="">
									<div class="">
<?php
										if(!empty($customer_quickbook_details) && $customer_quickbook_details['is_sync_customer'] == 1)
										{
										?>
										<span class="btn sync-customer" id="sync_customer">You have sync your data</span>
<?php
										}
										else
										{
?>
										<a href="#" id="quickbook_customer" class="btn btn-primary btn-lg ladda-button" data-style="expand-right" data-size="l"><span class="ladda-label">Sync Quickbook customer to ARK</span></a>
<?php
										}
?>
									</div>
								</div>
							</div>
							<div class="container-fluid">
								<div id="members-online"></div>
							</div>
						</div>
						<!-- /members online -->
					</div>
				</div>
			</div>
			<div class="tab-pane" id="2a">
				<div class="row">
					<div class="col-md-9">
						<div class="panel panel-flat">
							<div class="panel-body">
								<fieldset class="content-group">
									<legend class="text-bold">Account selection for Parts</legend>
									<input type="hidden" id="realmId" value="<?php if(isset($realmId)) { echo $realmId; } ?>">
									<div class="form-group control-box">
										<label class="control-label">INCOME ACCOUNT</label>
										<div class="form-group control-menu">
											<select class="selectpicker" data-show-subtext="true" data-live-search="true" id="income_account">
<?php
												foreach ($accounts as $account)
												{
?>
												<option value="<?php echo $account->Id?>" <?php if(!empty($customer_config) && $customer_config['income_account'] == $account->Id) {echo "selected"; } ?> ><?php echo $account->Name ." - ". $account->AccountType?></option>
												
<?php
												}
?>
											</select>
										</div>
									</div>
									<div class="form-group control-box">
										<label class="control-label">EXPENSE ACCOUNT</label>
										<div class="form-group control-menu">
											<select class="selectpicker" data-show-subtext="true" data-live-search="true" id="expense_account">
<?php
												foreach ($accounts as $account)
												{
?>
												<option value="<?php echo $account->Id?>" <?php if(!empty($customer_config) && $customer_config['expense_account'] == $account->Id) {echo "selected"; } ?> ><?php echo $account->Name ." - ". $account->AccountType?></option>
												
<?php
												}
?>
											</select>
										</div>
									</div>
									<div class="form-group control-box">
										<label class="control-label">INVENTORY ASSET ACCOUNT</label>
										<div class="form-group control-menu">
											<select class="selectpicker" data-show-subtext="true" data-live-search="true" id="inventory_asset_account">
<?php
												foreach ($accounts as $account)
												{
?>
												<option value="<?php echo $account->Id?>" <?php if(!empty($customer_config) && $customer_config['inventory_asset_account'] == $account->Id) {echo "selected"; } ?> ><?php echo $account->Name ." - ". $account->AccountType?></option>
												
<?php
												}
?>
											</select>
										</div>
									</div>
								</fieldset>								
								<fieldset class="content-group">
									<legend class="text-bold">Account selection for Services</legend>
									<div class="form-group control-box">
										<label class="control-label">INCOME ACCOUNT</label>
										<div class="form-group control-menu">
											<select class="selectpicker" data-show-subtext="true" data-live-search="true" id="income_account_service">
<?php
												foreach ($accounts as $account)
												{
?>
												<option value="<?php echo $account->Id?>" <?php if(!empty($customer_config) && $customer_config['income_account_service'] == $account->Id) {echo "selected"; } ?> ><?php echo $account->Name ." - ". $account->AccountType?></option>
												
<?php
												}
?>
											</select>
										</div>
									</div>
								</fieldset>								
								<fieldset class="content-group">
									<legend class="text-bold">Receive payment</legend>
									<div class="form-group control-box">
										<label class="control-label">DEPOSITE TO</label>
										<div class="form-group control-menu">
											<select class="selectpicker" data-show-subtext="true" data-live-search="true" id="deposite_to">
<?php
												foreach ($deposites as $deposite)
												{
?>
												<option value="<?php echo $deposite->Id?>" <?php if(!empty($customer_config) && $customer_config['deposite_to'] == $deposite->Id) {echo "selected"; } ?> ><?php echo $deposite->Name ." - ". $deposite->AccountType?></option>
												
<?php
												}
?>
											</select>
										</div>
									</div>
								</fieldset>		
								<fieldset class="content-group">
									<legend class="text-bold">Defined Customer</legend>
									<div class="form-group control-box">
										<label class="control-label">Customer</label>
										<div class="form-group control-menu">
											<select class="selectpicker" data-show-subtext="true" data-live-search="true" id="customer_id">
<?php
												foreach ($customers as $customer)
												{
?>
												<option value="<?php echo $customer->Id?>" <?php if(!empty($customer_config) && $customer_config['customer_id'] == $customer->Id) {echo "selected"; } ?> ><?php echo $customer->DisplayName;?></option>
												
<?php
												}
?>
											</select>
										</div>
									</div>
								</fieldset>
								<div class="text-right">
									<button type="submit" id="config_submit" class="btn btn-primary">
									<?php if(!empty($customer_config)) { echo "update"; }else { echo "Submit"; } ?>
										<i class="icon-arrow-right14 position-right"></i></button>
									<div id="snackbar_add">Successfully inserted!</div>
									<div id="snackbar_update">Successfully Updated!</div>
									<div id="snackbar_failed">There is some error for saving data!</div>
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
<script type="text/javascript" src="<?php echo base_url('assets/js/custom_pages/front/dashboard_quickbook.js'); ?>"></script>
<script type="text/javascript" src="assets/js/spin.min.js"></script>
<script type="text/javascript" src="assets/js/ladda.min.js"></script>