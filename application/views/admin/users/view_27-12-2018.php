<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class=""><a href="<?php echo site_url('admin/users'); ?>"><i class="icon-users position-left"></i>Users</a></li>
            <li class="active">View</li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary panel-bordered panel-flat view-user-detail-wrap">
                <div class="panel-heading">
                    <h6 class="panel-title">View User Details</h6>
                    <div class="heading-elements">
                        <a href="<?php echo base_url() . 'admin/users' ?>" class="btn btn-default heading-btn" style="margin-top: -7px;"><i class="icon-arrow-left15 position-left"></i>Back</a>
                    </div>
                </div>

                <?php
                if (isset($dataArr) && !empty($dataArr)):
                    ?>
                    <div class="panel-body mt-20">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table border-blue">
                                    <tbody>
                                        <tr class="alpha-blue">
                                            <td width="30%" class="text-semibold">Full Name</td>
                                            <td><?php echo $dataArr['full_name'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-semibold">Business Name</td>
                                            <td><?php echo $dataArr['business_name'] ?></td>
                                        </tr>
                                        <tr class="alpha-blue">
                                            <td class="text-semibold">UserName</td>
                                            <td><?php echo $dataArr['username'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-semibold">Email</td>
                                            <td><?php echo $dataArr['email_id'] ?></td>
                                        </tr>
                                        <tr class="alpha-blue">
                                            <td class="text-semibold">Contact</td>
                                            <td><?php echo $dataArr['contact_number'] ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table border-blue company-detail">
                                    <tbody>
                                        <tr class="bg-blue text-center">
                                            <td colspan="2">Company Details</td>
                                        </tr>
                                        <tr>
                                            <td width="40%" class="text-semibold">Address</td>
                                            <td><?php echo $dataArr['address'] ?></td>
                                        </tr>
                                        <tr class="alpha-blue">
                                            <td class="text-semibold">City</td>
                                            <td><?php echo $dataArr['city'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-semibold">State</td>
                                            <td><?php echo $dataArr['state'] ?></td>
                                        </tr>
                                        <tr class="alpha-blue">
                                            <td class="text-semibold">Zip Code</td>
                                            <td><?php echo $dataArr['zip_code'] ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php
                        if (!empty($dataArr['package'])) {
                            $package = $dataArr['package'];
                            ?>
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table border-blue company-detail">
                                        <tbody>
                                            <tr class="bg-blue text-center">
                                                <td colspan="2">Subscription Details</td>
                                            </tr>
                                            <tr>
                                                <td width="40%" class="text-semibold">Package Name</td>
                                                <td><?= $package['name'] ?></td>
                                            </tr>
                                            <tr class="alpha-blue">
                                                <td class="text-semibold">Package Price</td>
                                                <td>$ <?= number_format($package['price'], 2) ?> / Month</td>
                                            </tr>
                                            <tr>
                                                <td class="text-semibold">Package Activated Date</td>
                                                <td><?= date('d-m-Y', strtotime($dataArr['package_activated_date'])) ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-md-6">
                        </div>
                    </div>
                </div>
                <?php
            endif
            ?>
        </div>
        <div class="col-md-12">
            <div class="panel panel-primary panel-bordered panel-flat view-user-detail-wrap">
                <div class="panel-heading">
                    <h6 class="panel-title">Sub Users</h6>
                </div>
                <div class="panel-body">
                    <table class="table datatable-under_account-users">
                        <thead>
                            <tr>
                                <th style="width:5%">#</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>User's Type</th>
                                <th>Status</th>
                                <th>Last Edited</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="panel panel-primary panel-bordered panel-flat view-user-detail-wrap">
                <div class="panel-heading">
                    <h6 class="panel-title">Transaction History</h6>
                </div>
                <div class="panel-body">
                    <table class="table datatable-transaction-history">
                        <thead>
                            <tr>
                                <th style="width:5%">#</th>
                                <th style="width:40%">Description</th>
                                <th>Amount</th>
                                <th>Payment Date</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('Templates/footer'); ?>
</div>

<style>
    @media (max-width:1024px){
        .company-detail{margin-top: 20px;}
        .view-user-detail-wrap .col-md-6 {padding: 0;}
        .table-responsive{border: none;}
    }
</style>

<script>
    //Transaction History
    $(".datatable-transaction-history").dataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
            emptyTable: 'No data currently available.'
        },
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        order: [[0, "desc"]],
        ajax: site_url + 'admin/users/get_payment_transaction_data/<?= base64_encode($dataArr['id']) ?>',
        columns: [
            {
                data: "sr_no",
                visible: true,
                sortable: false
            },
            {
                data: "description",
                visible: true,
                sortable: false
            },
            {
                data: "amount",
                visible: true,
                render: function (data, type, full, meta) {
                    return '$ ' + parseFloat(data).toFixed(2);
                }
            },
            {
                data: "modified_date",
                visible: true,
                sortable: false
            },
        ],
        "fnDrawCallback": function () {
            var info = document.querySelectorAll('.switchery-info');
            $(info).each(function () {
                var switchery = new Switchery(this, {color: '#95e0eb'});
            });
        }
    });

    //Active Users Under Account
    $(".datatable-under_account-users").dataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
            emptyTable: 'No data currently available.'
        },
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        order: [[0, "desc"]],
        ajax: site_url + 'admin/users/get_account_under_users/<?= base64_encode($dataArr['id']) ?>',
        columns: [
            {
                data: "sr_no",
                visible: true,
                sortable: false
            },
            {
                data: "full_name",
                visible: true,
                sortable: false
            },
            {
                data: "username",
                visible: true,
                sortable: false
            },
            {
                data: "email_id",
                visible: true,
                sortable: false
            },
            {
                data: "role_name",
                visible: true,
                sortable: false
            },
            {
                data: "status",
                visible: true,
                sortable: false,
                render: function (data, type, full, meta) {
                    return '<td class="text-center"><span class="label label-success">Activated</span></td>';
                }
            },
            {
                data: "modified_date",
                visible: true,
                sortable: false
            },
        ],
        "fnDrawCallback": function () {
            var info = document.querySelectorAll('.switchery-info');
            $(info).each(function () {
                var switchery = new Switchery(this, {color: '#95e0eb'});
            });
        }
    });
</script>