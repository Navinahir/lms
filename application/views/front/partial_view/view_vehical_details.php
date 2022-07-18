<table class="table table-striped table-bordered table-hover vehical-result" style="margin-top: 20px;">
    <tbody>
        <?php
        if (!empty($results) && !empty($results[0])) {
            $info = $results[0];
            ?>
            <tr class="alpha-blue">
                <th>VIN:</th>
                <td><?= $info->VIN ?></td>
            </tr>
            <tr>
                <th>Manufacturer:</th>
                <td><?= $info->Manufacturer ?></td>
            </tr>
            <tr class="alpha-blue">
                <th>Year:</th>
                <td><?= $info->ModelYear ?></td>
            </tr>
            <tr>
                <th>Make:</th>
                <td><?= $info->Make ?></td>
            </tr>
            <tr class="alpha-blue">
                <th>Model:</th>
                <td><?= $info->Model ?></td>
            </tr>
            <tr>
                <th>Series:</th>
                <td><?= (!empty($info->Series)) ? $info->Series : '---' ?></td>
            </tr>
            <tr class="alpha-blue">
                <th>TRIM:</th>
                <td>
                    <?= (!empty($info->Trim)) ? $info->Trim : '---' ?><?= (!empty($info->Trim2)) ? '/'.$info->Trim2 : '' ?>
                </td>
            </tr>
            <tr>
                <th>Doors:</th>
                <td><?= (!empty($info->Doors)) ? $info->Doors : '---' ?></td>
            </tr>
            <tr class="alpha-blue">
                <th>Keyless Ignition:</th>
                <td><?= (!empty($info->KeylessIgnition)) ? $info->KeylessIgnition : '---' ?></td>
            </tr>
            <tr>
                <th>Fuel Type:</th>
                <td><?= (!empty($info->FuelInjectionType)) ? $info->FuelInjectionType : '---' ?></td>
            </tr>
        <?php } else { ?>
            <tr>
                <th colspan="2" class="text-center">No Information found.</th>
            </tr>
        <?php } ?>
    </tbody>
</table>

<button type="button" class="btn btn-primary" style="float: right; margin-top: 10px;" id="close-result">Close</button>

<script type="text/javascript">
    $("#close-result").click(function(){
        $("#div_vehical_details").hide();
    });
</script>