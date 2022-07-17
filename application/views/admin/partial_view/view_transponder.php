<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/wysihtml5.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/toolbar.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/parsers.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/locales/bootstrap-wysihtml5.ua-UA.js"></script>

<div class="row">
    <div class="col-lg-6 mt-10">
        <table class="table table-striped table-bordered" data-alert="" data-all="189">
            <tbody>
                <tr class="alpha-blue"><td><b>Make</b></td><td><?php echo $viewArr['make_name']; ?></td></tr>
                <tr><td><b>Model</b></td><td><?php echo $viewArr['model_name']; ?></td></tr>

                <tr class="alpha-blue"><td><b>Year</b></td><td><?php echo $viewArr['year_name']; ?></td></tr>
                <tr><td><b>Transponder Equipped</b></td><td><?php echo ucfirst($viewArr['transponder_equipped']); ?></td></tr>

                <tr class="alpha-blue"><td><b>Key Type</b></td><td><?php echo str_replace('_', ' ', join(', ', array_map('ucfirst', explode(',', $viewArr['key_value'])))); ?></td></tr>
                <tr><td><b>System</b></td><td><?php echo $viewArr['mvp_system']; ?></td></tr>

                <tr class="alpha-blue"><td><b>Pin-Code Required</b></td><td><?php echo ucfirst($viewArr['pincode_required']); ?></td></tr>
                <tr><td><b>Pin-Code Reading Available</b></td><td><?php echo $viewArr['pincode_reading_available']; ?></td></tr>

                <tr class="alpha-blue"><td><b>Key On-board Programing</b></td><td><?php echo $viewArr['key_onboard_progaming']; ?></td></tr>
                <tr><td><b>Remote On-board Programing</b></td><td><?php echo $viewArr['remote_onboard_progaming']; ?></td></tr>

                <tr class="alpha-blue"><td><b>Test Key</b></td><td><?php echo $viewArr['test_key']; ?></td></tr>
                <tr><td><b>JET</b></td><td><?php echo $viewArr['jet']; ?></td></tr>
                
                <tr class="alpha-blue"><td><b>IIco</b></td><td><?php echo $viewArr['iico']; ?></td></tr>
                <tr><td><b>JMA</b></td><td><?php echo $viewArr['jma']; ?></td></tr>
                
                <tr class="alpha-blue"><td><b>Keyline</b></td><td><?php echo $viewArr['keyline']; ?></td></tr>
            </tbody>
        </table>
    </div>
    <div class="col-lg-6 mt-10">
        <table class="table table-striped table-bordered" data-alert="" data-all="189">
            <tbody>
                <tr class="alpha-blue"><td><b>Strattec Non-Remote Key</b></td><td><?php echo $viewArr['strattec_non_remote_key']; ?></td></tr>
                <tr><td><b>Strattec Remote Key</b></td><td><?php echo $viewArr['strattec_remote_key']; ?></td></tr>

                <tr class="alpha-blue"><td><b>OEM Non-Remote Key</b></td><td><?php echo $viewArr['oem_non_remote_key']; ?></td></tr>
                <tr><td><b>OEM Remote Key</b></td><td><?php echo $viewArr['oem_remote_key']; ?></td></tr>

                <tr class="alpha-blue"><td><b>Other</b></td><td><?php echo $viewArr['other_non_remote_key']; ?></td></tr>
                <tr><td><b>FCC ID #</b></td><td><?php echo $viewArr['fcc_id']; ?></td></tr>

                <tr class="alpha-blue"><td><b>IC #</b></td><td><?php echo $viewArr['ic']; ?></td></tr>
                <tr><td><b>Frequency </b></td><td><?php echo $viewArr['frequency']; ?></td></tr>

                <tr class="alpha-blue"><td><b>Code Series</b></td><td><?php echo $viewArr['code_series']; ?></td></tr>
                <tr><td><b>Chip ID</b></td><td><?php echo $viewArr['chip_ID']; ?></td></tr>   

                <tr class="alpha-blue"><td><b>Transponder Re-Use</b></td><td><?php echo ucfirst($viewArr['transponder_re_use']); ?></td></tr>
                <tr><td><b>Max. No. of keys</b></td><td><?php echo $viewArr['max_no_of_keys']; ?></td></tr>

                <tr class="alpha-blue"><td><b>Key Shell</b></td><td><?php echo $viewArr['key_shell']; ?></td></tr>
                <tr><td><b>Notes</b></td><td><?php echo $viewArr['notes']; ?></td></tr>

            </tbody>
        </table>
    </div>
</div>
<style>
    .alpha-blue {
        background-color: #E1F5FE !important;
    }
</style>