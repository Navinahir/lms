<?php $this->load->view('front/element/header'); ?>
<div class="packages_banner">
    <div class="container">
        <h1 class="custom_page_header">Packages</h1>
    </div>
</div>

<!--Packages section-->
<div class="packages_bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="price_para">
                    <h4>OUR AFFORDABLE RATES</h4>
                    <p>At Always Reliable Keys, we believe in offering top-notch services at a reasonable rate. We offer
                        three different package levels so you only pay for what you need.</p>
                    <div class="n-package-wrap">
                        <div class="package-content">
                            <div class="table-responsive table-main">
                                <table>
                                    <tbody>
                                    <tr class="tr-header">
                                        <th>It’s All Included!</th>
                                        <td class="light-sky-header">
                                            <h4>Standard</h4>
                                            <p>$19.99/Month</p>
                                        </td>
                                        <td class="medium-sky-header">
                                            <h4>Professional</h4>
                                            <p>$29.99/Month</p>
                                        </td>
                                        <td class="dark-sky-header">
                                            <h4>Enterprise</h4>
                                            <p>$49.99/Month</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="first-td">Full access to search 1000's of vehicles and see
                                            all compatible keys, remotes, transponder chips,
                                            key shells, proximity fobs
                                        </th>
                                        <td class="light-sky"><i class="fas fa-check"></i></td>
                                        <td class="medium-sky"><i class="fas fa-check"></i></td>
                                        <td class="dark-sky"><i class="fas fa-check"></i></td>
                                    </tr>
                                    <tr>
                                        <th class="first-td">Search by Year, Make, and Model to see all the
                                            details and compatible parts for that vehicle
                                        </th>
                                        <td class="light-sky"><i class="fas fa-check"></i></td>
                                        <td class="medium-sky"><i class="fas fa-check"></i></td>
                                        <td class="dark-sky"><i class="fas fa-check"></i></td>
                                    </tr>
                                    <tr>
                                        <th class="first-td">Manage and track your inventory quickly
                                        </th>
                                        <td class="light-sky"><i class="fas fa-check"></i></td>
                                        <td class="medium-sky"><i class="fas fa-check"></i></td>
                                        <td class="dark-sky"><i class="fas fa-check"></i></td>
                                    </tr>
                                    <tr>
                                        <th class="first-td">Create professional estimates and invoices and
                                            send them to your customers
                                        </th>
                                        <td class="light-sky"><i class="fas fa-check"></i></td>
                                        <td class="medium-sky"><i class="fas fa-check"></i></td>
                                        <td class="dark-sky"><i class="fas fa-check"></i></td>
                                    </tr>
                                    <tr>
                                        <th class="first-td">Manage and track special orders for your
                                            customers
                                        </th>
                                        <td class="light-sky"><i class="fas fa-check"></i></td>
                                        <td class="medium-sky"><i class="fas fa-check"></i></td>
                                        <td class="dark-sky"><i class="fas fa-check"></i></td>
                                    </tr>
                                    <tr>
                                        <th class="first-td">Programming procedures from the most popular
                                            automotive locksmith tools on the market
                                        </th>
                                        <td class="light-sky"><i class="fas fa-check"></i></td>
                                        <td class="medium-sky"><i class="fas fa-check"></i></td>
                                        <td class="dark-sky"><i class="fas fa-check"></i></td>
                                    </tr>
                                    <tr>
                                        <th class="first-td">Track productivity, jobs, and sales for your
                                            technicians
                                        </th>
                                        <td class="light-sky"><i class="fas fa-check"></i></td>
                                        <td class="medium-sky"><i class="fas fa-check"></i></td>
                                        <td class="dark-sky"><i class="fas fa-check"></i></td>
                                    </tr>
                                    <tr>
                                        <th class="first-td">View and print reports about your business that
                                            will help you improve your operations
                                        </th>
                                        <td class="light-sky"><i class="fas fa-check"></i></td>
                                        <td class="medium-sky"><i class="fas fa-check"></i></td>
                                        <td class="dark-sky"><i class="fas fa-check"></i></td>
                                    </tr>
                                    <!-- <tr class="tr-footer">
                                        <th class="first-td">
                                        </th>
                                        <td><a href="#">Get Started</a> </td>
                                        <td><a href="#">Get Started</a></td>
                                        <td><a href="#">Get Started</a></td>
                                    </tr> -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="title">The only difference between our packages is the size of your business</h4>
                        </div>
                        <div class="col-sm-12">
                            <div class="row d-flex align-items-center justify-content-center flex-wrap">
                                <?php
                                foreach ($packages as $key => $value) {
                                    if($value['is_delete'] == 0 && $value['quickbook_status'] == 0 ){
                                        ?>
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <div class="p-wrap">
                                                <div class="p-head">
                                                    <h4><?php echo $value['name'];?></h4>
                                                    <p><?php echo '$'.$value['price'].'/Month';?></p>
                                                </div>
                                                <div class="p-body">
                                                    <?php echo $value['short_description']; ?>
                                                </div>
                                                <a href="<?php echo base_url('register');?>">
                                                    <div class="p-foot">
                                                        Get Started
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <?php
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="package-content">
                        <p>Want to Integrate your Always Reliable Keys with Quickbooks Online? We can do that. Sync all your customers, estimates, invoices, and inventory items and quantities to your quickbooks online account.</p>
                        <div class="img-div-section">
                            <div class="img-div">
                                <img alt="logo_white" src="<?php echo base_url('assets/images/logo.png') ?>"/>
                            </div>
                            <div class="icon-div">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="img-div quickbooks-img">
                                <img alt="logo_white" src="<?php echo base_url('assets/images/quickbooks-online-logo-transparent.png') ?>"/>
                            </div>
                        </div>
                        <div>
                            <p>Add the Always Reliable Keys quickbooks integration to any package for just: $19.99/mo</p>
                        </div>
                        <div class="row d-flex align-items-center justify-content-center flex-wrap">
                            <?php
                            foreach ($packages as $key => $value) {
                                if($value['is_delete'] == 0 && $value['quickbook_status'] == 1 ){
                                    ?>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="p-wrap">
                                            <div class="p-head">
                                                <h4><?php echo $value['name'];?></h4>
                                                <p><?php echo '$'.$value['price'].'/Month';?></p>
                                            </div>
                                            <div class="p-body">
                                                <?php echo $value['short_description']; ?>
                                            </div>
                                            <a href="<?php echo base_url('register');?>">
                                                <div class="p-foot">
                                                    Get Started
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--faq section-->
<div class="faq_section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h2 class="section_header"><span>Frequently  </span> asked questions</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div aria-multiselectable="true" class="panel-group faq_accordion" id="accordion" role="tablist">
                    <div class="panel panel-default">
                        <a aria-controls="collapseOne" aria-expanded="true" data-parent="#accordion" data-toggle="collapse" href="#collapseOne" role="button">
                            <div class="panel-heading" id="headingOne" role="tab">
                                <h4 class="panel-title">
                                        How do I obtain a Always Reliable Key estimate?
                                </h4>
                            </div>
                        </a>
                        <div aria-labelledby="headingOne" class="panel-collapse collapse in" id="collapseOne"
                             role="tabpanel">
                            <div class="panel-body">
                                To get a cheap Always Reliable Key estimate, all you need to do is call us and let us
                                know which of our services you are in need of. We will then be able to give you the most
                                precise service estimate we can.
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <a aria-controls="collapseTwo" aria-expanded="false" class="collapsed" data-parent="#accordion" data-toggle="collapse" href="#collapseTwo" role="button">
                            <div class="panel-heading" id="headingTwo" role="tab">
                                <h4 class="panel-title">
                                        Can you tell me the price of a service over the phone?
                                </h4>
                            </div>
                        </a>
                        <div aria-labelledby="headingTwo" class="panel-collapse collapse" id="collapseTwo"
                             role="tabpanel">
                            <div class="panel-body">
                                Yes, we most definitely can tell you the cost of a service on the phone. When you call
                                us up, you can let us know exactly what it is that you need help with, and we�ll give
                                you a price quote over the phone.
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <a aria-controls="collapseThree" aria-expanded="false" class="collapsed" data-parent="#accordion" data-toggle="collapse" href="#collapseThree" role="button">
                            <div class="panel-heading" id="headingThree" role="tab">
                                <h4 class="panel-title">
                                        What methods of payment are accepted?
                                </h4>
                            </div>
                        </a>
                        <div aria-labelledby="headingThree" class="panel-collapse collapse" id="collapseThree"
                             role="tabpanel">
                            <div class="panel-body">
                                We accept all major credit cards and cash.
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php $this->load->view('front/element/footer'); ?>