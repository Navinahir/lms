<div class="row">
    <div class="col-lg-12">
        <h2 class="modul-title">
            <?= (!empty($result['module_name'])) ? strtoupper($result['module_name']) . ' : ' : '---' ?>
        </h2>
        <table class="table table-hover table-bordered" data-alert="" data-all="189">
            <tbody>
                <tr>
                    <td><?= (!empty($result['module_description'])) ? $result['module_description'] : '---' ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<style>
    h2.modul-title { margin-top: 5px !important; }

    @media screen and (max-width:768px){
        h2.modul-title{
            font-size: 20px;
        }
    }

</style>