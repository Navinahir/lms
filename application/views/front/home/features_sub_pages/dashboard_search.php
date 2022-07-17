<?php $this->load->view('front/element/header'); ?>
<!--banner section-->
<div class="about_us_banner">
    <div class="container">
        <h1 class="custom_page_header">Dashboard Search</h1>
    </div>
</div>

<!--About us section-->
<div class="aboutus_section">
    <div class="container">
        <div class="col-sm-12">
            <div class="aboutus_content">
                <h4>Dashboard Search </h4>
                <p>Unlimited vehicle searches allow you to view useful information on vehicles such as key types and part numbers, system information, pin code requirements, compatible programmers, and more. Global Part numbers allow you to easily add your preferred parts into your inventory list and customize it to your store’s pricing. These Global Parts also include a picture to make sure you are adding the correct part. Inventory in-stock can be added into an estimate straight from this section, allowing you to build a quick price quote, knowing right away if you have the part in stock or if an order will need to be placed. If the estimate is completed, it will automatically remove that item from your inventory.</p>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('front/element/footer'); ?>

<style>
@media (max-width: 1024px){
        .service_section   .feature_container{height: 415px;}
    }
    @media (max-width: 768px){
    .service_section .feature_container{height: inherit;}
}
    </style>