<div class="row">
    <div class="col-lg-12">
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