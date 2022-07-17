<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="">

        <!-- Global stylesheets -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
        <link href="<?= $app_url . 'assets/css/icons/icomoon/styles.css' ?>" rel="stylesheet" type="text/css">
        <link href="<?= $app_url . 'assets/css/bootstrap.css' ?>" rel="stylesheet" type="text/css">
        <link href="<?= $app_url . 'assets/css/core.css' ?>" rel="stylesheet" type="text/css">
        <link href="<?= $app_url . 'api/assets/css/components.css' ?>" rel="stylesheet" type="text/css">
        <link href="<?= $app_url . 'assets/css/colors.css' ?>" rel="stylesheet" type="text/css">
        <link href="<?= $app_url . 'assets/css/custom_pav.css' ?>" rel="stylesheet" type="text/css">
        <link href="<?= $app_url . 'assets/css/vendor_dashboard.css' ?>" rel="stylesheet" type="text/css">
        <link href="<?= $app_url . 'api/assets/css/jquery.modal.min.css' ?>" rel="stylesheet" type="text/css">

        <!-- <script type="text/javascript" src="<?= $app_url . 'assets/js/core/libraries/jquery.min.js' ?>"></script> -->

        <script type="text/javascript" src="<?= $app_url . 'assets/js/plugins/loaders/blockui.min.js' ?>"></script>

        <noscript>
        <META HTTP-EQUIV="Refresh" CONTENT="0;URL=js_disabled">
        </noscript>
    </head>
    <body>
        <!-- Page container -->
        <div class="page-container custom_content">
            <div class="page-content">
                <div class="content-wrapper">
                    <div id="custom_loading" class="hide">
                        <div id="loading-center"></div>
                    </div>

                    <div class="content">
                        <div class="mb-10 text-left">
                            <span class="div-<?= $custom_class ?>">Powered by, <img src="<?= base_url('assets/images/logo.png') ?>"></span>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-lg-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-flat">
                                            <div class="panel-body">
                                                <form method="post" class="form-validate-jquery" id="search_transponder_form" name="search_transponder_form">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group has-feedback select_form_group">
                                                                <label class="required">Make</label>
                                                                <select data-placeholder="Select a Company..." class="select select-size-sm select2-vehicla-details form-control" id="txt_make_name" name="txt_make_name">
                                                                    <option></option>
                                                                    <?php foreach ($companyArr as $k => $v) { ?>
                                                                        <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group has-feedback select_form_group">
                                                                <label class="required">Model</label>
                                                                <select data-placeholder="Select a Model..." class="select select-size-sm select2-vehicla-details form-control" id="txt_model_name" name="txt_model_name">
                                                                    <option></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group has-feedback select_form_group">
                                                                <label class="required">Year</label>
                                                                <select data-placeholder="Select a Year..." class="select select-size-sm select2-vehicla-details form-control" id="txt_year_name" name="txt_year_name">
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
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-lg-6">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">Search Part:</h6>
                                    </div>
                                    <div class="panel-body">
                                        <div class="mt-10 row">
                                            <div class="form-group form-inline">
                                                <form method="post" class="form-group srh-part" id="searchPartDetailsForm">
                                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" name="txt_part_no" placeholder="Part No" />&nbsp;
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" name="txt_part_description" placeholder="Part Description" />&nbsp;
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn bg-teal">Search</button>
                                                            <button type="reset" class="btn btn-default">Reset</button> 
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                            <div class="panel panel-flat">
                                                <div class="panel-body row">
                                                    <div class="col-md-12 col-md-12 col-lg-9">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" name="txt_vin_no" placeholder="VIN: (For Ex. 1N6AA07C68N321943)" />
                                                            <span class="error hide" id="txt_vin_no_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-md-6 col-lg-3">
                                                        <div class="form-group">
                                                            <button type="button" id="search-vin-for-make-model" class="btn bg-teal">Search</button>
                                                            <button type="reset" class="btn btn-default">Reset</button> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-sm-12 hide" id="div_list_of_parts">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">List Of Parts</h6>
                                    </div>
                                    <div class="panel-body">
                                        <div class="div_part_list">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 hide" id="div_vehical_details">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">Vehicle Information</h6>
                                    </div>
                                    <div class="panel-body">
                                        <div class="view_vehical_info">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div id="fakeLoader" class="loading"></div>
                    </div>
                </div>
            </div>
        </div>
        <!--/Page container-->

        <div class="jquery-modal blocker current hide" id="part-details-modal"></div>

        <script>
            var vendor_id = '<?= $vendor_id ?>';
            var api_url = '<?= $app_url ?>vehicle/search/get_vin_vehical_details';
            var d_id = '<?= $custom_class ?>';
            var app_url = '<?= $app_url ?>';
        </script>
        <script type="text/javascript" src="<?= $app_url . 'assets/js/plugins/forms/selects/select2.min.js' ?>"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
        <script type="text/javascript" src="<?= $app_url . 'assets/js/plugins/forms/validation/validate.min.js' ?>"></script>
        <script type="text/javascript" src="<?= $app_url . 'assets/js/plugins/notifications/sweet_alert.min.js' ?>"></script>
        <script type="text/javascript" src="<?= $app_url . 'assets/js/custom_pages/common_script.js' ?>"></script>

        <script type="text/javascript">
        	function load_script(url) {
				if (!Array.from(document.querySelectorAll('script')).some(elm => elm.src == url)) {
					let script = document.createElement('script');
					script.src = url;
					document.getElementsByTagName('head')[0].appendChild(script);
				}
			}
        </script>

        <script type="text/javascript" src="<?= $app_url . 'api/assets/js/dashboard.js' ?>"></script>
    </body>
</html>

