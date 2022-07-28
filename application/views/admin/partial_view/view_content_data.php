<div class="row">
    <div class="col-lg-12">
        <table class="table table-striped table-bordered" data-alert="" data-all="189">
            <tbody>
                <tr>
                    <th>Module Name</th>
                    <td><?= (!empty($result['module_name'])) ? $result['module_name'] : '---' ?></td>
                </tr>
                <tr class="alpha-blue">
                    <th>Module Description</th>
                    <td><?= (!empty($result['module_description'])) ? $result['module_description'] : '---' ?></td>
                </tr>
                <tr>
                    <th>Added Date:</th>
                    <td><?= date('d-m-Y', strtotime($result['created_date'])) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<style>
    .alpha-blue {
        background-color: #E1F5FE !important;
    }
    .table-bordered tr:first-child > td, .table-bordered tr:first-child > th {
        width: 50% !important;
    }
</style>