<style type="text/css">
	.Improve-main{
		padding-left: 16px;
	}	
	.Improve-wrapper{
		margin: 35px 0 20px; 
	}
	.logo-company img{
		margin-top: 1rem;
		max-width: 200px;
	}
	.Improve-row{
		display: flex;
		flex-wrap: wrap;
		margin-top: 3rem;
	}
	.Improve-block{
		text-align: center;
		margin-right: 5rem;
		margin-bottom: 1rem;
	}
	.Improve-block .Improve-img{
		width: 75px;
		height: 75px;
		margin-bottom: 1rem;
	}
	.Improve-block .Improve-img img{
		width: 100%;
		height: 100%;
	}
	.Improve-block a, .continue-anyway a{
		text-decoration: none;
	}
	.continue-anyway {
		margin-top: 4rem;
	}
</style>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?></title>
</head>
<body>
	<div class="container Improve-main">
		<div class="logo-company">
			<img src="<?php echo site_url()."/assets/images/logo.png"; ?>"/>
		</div>
		<div class="Improve-wrapper">
			<h1>Improve your experience</h1>
			<span>You’re using an unsupported web browser. We recommend you use an up-to-date version of one of these browsers: </span>
			<div class="row Improve-row">
				<a href="https://www.google.com/chrome/">
					<div class="col-md-4 Improve-block">
						<div class="Improve-img">
							<img src="<?php echo site_url()."/assets/images/browser/chrome.png" ?>" />
						</div>
						<a href="javascript:void(0);">Chrome</a>
					</div>
				</a>
				<a href="https://www.mozilla.org/en-US/firefox/new/">
					<div class="col-md-4 Improve-block">
						<div class="Improve-img">
							<img src="<?php echo site_url()."/assets/images/browser/firefox.png" ?>" />
						</div>
						<a href="javascript:void(0);">Firefox</a>
					</div>
				</a>
				<a href="https://support.apple.com/downloads/safari">
					<div class="col-md-4 Improve-block">
						<div class="Improve-img">
							<img src="<?php echo site_url()."/assets/images/browser/safari.png" ?>" />
						</div>
						<a href="javascript:void(0);">Safari</a>
					</div>
				</a>
				<a href="https://www.microsoft.com/en-us/edge">
					<div class="col-md-4 Improve-block">
						<div class="Improve-img">
							<img src="<?php echo site_url()."/assets/images/browser/edge.png" ?>" />
						</div>
						<a href="javascript:void(0);">Edge</a>
					</div>
				</a>
			</div>	
			<!-- <div class="continue-anyway">
				<a href="javascript:void(0);">Continue anyway ➔</a>
			</div> -->
			<br />
			<br />
			<br />
			<div class="copy-txt">
				© Always Reliable Keys 
			</div>
		</div>
	</div>
</body>
</html>