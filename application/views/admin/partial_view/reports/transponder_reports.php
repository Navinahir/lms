<html lang="en">
	<head>
  		<title>Transponder Report</title>
  		<meta charset="utf-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1">
  		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head>
	<body>
		<style>
			hr{margin-top:10px !important;margin-bottom: 10px !important;}
			table tr td{padding:5px !important;border:none !important;}
			.total_sales{font-weight:bold;}
		</style>
  
		<div class="container">
  			<div class="row">
    			<div class="col-md-12">
    				<div class="text-center rest_heading">
    					<h3><?php echo SITE_NAME; ?></h3>
    				</div>
    				<?php if(!empty($details)){ ?>
    					<div>
	    					<table class="table table-bordered table-striped">
	    						<thead>
							     	<tr><th colspan="4" style="padding:10px 10px"><?php echo '	Strattec part number : ' ?></th></tr>
							     	<tr>
							     		<th style="padding:10px 10px">Sr. No.</th>
							     		<th style="padding:10px 10px">Year</th>
							     		<th style="padding:10px 10px">Car</th>
							     	</tr>
							    </thead>
    							<tbody>
	    							<?php foreach($details as $k => $v){ ?>
										<tr style="border:1px solid #dddddd">
											<td style="width:15%"><?php echo $k+1; ?></td>
											<td style="width:20%"><?php echo $v['year_name']; ?></td>
											<td style="width:65%"><?php echo $v['make_name'].'  '.$v['model_name']; ?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
		    			</div>
	    				<hr>
	    				<div class="text-center">
							Created By ARK<br>
							<?php echo date('l, d M-Y, g:i A', strtotime("+".$_COOKIE['currentOffset']." sec")); ?>
						</div>
					<?php }else{ ?>
						<div class="text-center" style="color:#ccc">
							<h2>Sorry, No Data Available.</h2>
						</div>
					<?php } ?>
    			</div>
  			</div>
		</div>
	</body>
</html>