/**********************************************************
 Intitalize Data Table
 ***********************************************************/

if (typeof (get) !== "undefined" && get != "") {
    var start = moment(get.from_date);
    ;
    var end = moment(get.to_date);
} else {
    var start = moment().subtract(1, 'days');
    var end = moment();
}

$(document).ready(function () {
    $("#fakeLoader").fakeLoader({
        timeToHide: 3000,
        spinner: "spinner5",
        bgColor: "",
    });
});

setTimeout(function () {
    var date = start.format('YYYY-MM-DD') + ':=:' + end.format('YYYY-MM-DD');
    custom_reports_data();
    // setGraphData(date);
    // setDataTable(date);
    // dailyInvoiceSales(date);
}, 500);

$('.btn-save').on('click', function () {
    var date = $('#date_range').val();
    $("#fakeLoader").attr('style', '').html('');
    $(document).find("#fakeLoader").fakeLoader({
        timeToHide: 3000,
        spinner: "spinner5",
        bgColor: "",
    });
    var url = updateQueryStringParameter(site_url + 'reports/custom_reports', "date", date);
    // window.location.href = encodeURI(url);
    custom_reports_data();
});

function custom_reports_data(){
    var date = $('#date_range').val();
    
    // Count Gross Sales
    $.ajax({
        url: site_url + 'reports/custom_reports_data_gross_sales',
        type: 'POST',
        dataType: 'json',
        data: {date: date},
        success: function(data){
            $('.gross_sales').html(currency + parseFloat(data.gross_sales).toFixed(2));
            $('.count_gross_sales').html(data.count_invoice);
        },
    });
    
    // Count Shipping charge
    $.ajax({
        url: site_url + 'reports/custom_reports_shipping_charge',
        type: 'POST',
        dataType: 'json',
        data: {date: date},
        success: function(data){
            $('.shipping_rate').html(data.shipping_charge);
            $('.shipping_charge').html(currency + parseFloat(data.shipping_charge).toFixed(2));
            $('.count_shipping_charge').html(data.count_shipping_charge);
        },
    });

    // Count Part Tax Amount 
    $.ajax({
        url: site_url + 'reports/custom_reports_data_part_tax_amount',
        type: 'POST',
        dataType: 'json',
        data: {date: date},
        success: function(data){
            $('.part_tax_amount').html(parseFloat(data.part_tax_amount).toFixed(2));
            $('.part_total_tax').html(currency + parseFloat(data.part_tax_amount).toFixed(2));
            $('.count_part_total_tax').html(data.count_part_tax_amount);
        },
    });

    // Count Service Tax Amount 
    $.ajax({
        url: site_url + 'reports/custom_reports_data_service_tax_amount',
        type: 'POST',
        dataType: 'json',
        data: {date: date},
        success: function(data){
            $('.service_tax_amount').html(parseFloat(data.service_tax_amount).toFixed(2));
            $('.service_total_tax').html(currency + parseFloat(data.service_tax_amount).toFixed(2));
            $('.count_service_total_tax').html(data.count_service_tax_amount);
        },
    });

    // Count Part Net Sales
    $.ajax({
        url: site_url + 'reports/custom_reports_data_net_sell_part',
        type: 'POST',
        dataType: 'json',
        data: {date: date},
        success: function(data){
            $('.net_sell_part').html(parseFloat(data.net_sell_part).toFixed(2));
            $('.part_net_sales').html(currency + parseFloat(data.net_sell_part).toFixed(2));
            $('.count_net_sell_part').html(data.count_net_sell_part);
            $('.count_part_net_sales').html(data.count_net_sell_part);
        },
    });
    
    // Count Part Net Discount Sales
    $.ajax({
        url: site_url + 'reports/custom_reports_data_net_discount_sell_part',
        type: 'POST',
        dataType: 'json',
        data: {date: date},
        success: function(data){
            $('.net_discount_sell_part').html(parseFloat(data.net_discount_sell_part).toFixed(2));
            $('.part_total_discount').html(currency + parseFloat(data.net_discount_sell_part).toFixed(2));
            $('.count_part_total_discount').html(data.count_part_total_discount);
        },
    });
    
    // Count Service Net Sales
    $.ajax({
        url: site_url + 'reports/custom_reports_data_net_sell_service',
        type: 'POST',
        dataType: 'json',
        data: {date: date},
        success: function(data){
            $('.net_sell_service').html(parseFloat(data.net_sell_service).toFixed(2));
            $('.service_net_sales').html(currency + parseFloat(data.net_sell_service).toFixed(2));
            $('.count_net_sell_service').html(data.count_net_sell_service);
            $('.count_service_net_sales').html(data.count_net_sell_service);
        },
    });

    // Count Service Net Discount Sales
    $.ajax({
        url: site_url + 'reports/custom_reports_data_net_discount_sell_service',
        type: 'POST',
        dataType: 'json',
        data: {date: date},
        success: function(data){
            $('.net_discount_sell_service').html(parseFloat(data.net_discount_sell_service).toFixed(2));
            $('.service_total_discount').html(currency + parseFloat(data.net_discount_sell_service).toFixed(2));
            $('.count_service_total_discount').html(data.count_service_total_discount);
        },  
    });

    // Count Taxable Part 
    $.ajax({
        url: site_url + 'reports/custom_reports_data_taxable_part',
        type: 'POST',
        dataType: 'json',
        data: {date: date},
        success: function(data){
            $('.net_sell_taxable_part').html(parseFloat(data.net_sell_taxable_part).toFixed(2));
            $('.part_taxable_sales').html(currency + parseFloat(data.net_sell_taxable_part).toFixed(2));
            $('.count_net_sell_taxable_part').html(data.count_net_sell_taxable_part);  
            $('.count_part_taxable_sales').html(data.count_net_sell_taxable_part);  
        },
    });

    // Count Taxable Service 
    $.ajax({
        url: site_url + 'reports/custom_reports_data_taxable_service',
        type: 'POST',
        dataType: 'json',
        data: {date: date},
        success: function(data){
            $('.net_sell_taxable_service').html(parseFloat(data.net_sell_taxable_service).toFixed(2));
            $('.service_taxable_sales').html(currency + parseFloat(data.net_sell_taxable_service).toFixed(2));
            $('.count_net_sell_taxable_service').html(data.count_net_sell_taxable_service);
            $('.count_service_taxable_sales').html(data.count_net_sell_taxable_service);
        },
    });
    
    // Count Non Taxable Part 
    $.ajax({
        url: site_url + 'reports/custom_reports_data_non_taxable_part',
        type: 'POST',
        dataType: 'json',
        data: {date: date},
        success: function(data){
            $('.net_sell_non_taxable_part').html(parseFloat(data.net_sell_non_taxable_part).toFixed(2));
            $('.part_non_taxable_sales').html(currency + parseFloat(data.net_sell_non_taxable_part).toFixed(2));
            $('.count_net_sell_non_taxable_part').html(data.count_net_sell_non_taxable_part);
            $('.count_part_non_taxable_sales').html(data.count_net_sell_non_taxable_part);
        },
    });
    
    // Count Non Taxable Service 
    $.ajax({
        url: site_url + 'reports/custom_reports_data_non_taxable_service',
        type: 'POST',
        dataType: 'json',
        data: {date: date},
        success: function(data){
            $('.net_sell_non_taxable_service').html(parseFloat(data.net_sell_non_taxable_service).toFixed(2));
            $('.service_non_taxable_sales').html(currency + parseFloat(data.net_sell_non_taxable_service).toFixed(2));
            $('.count_net_sell_non_taxable_service').html(data.count_net_sell_non_taxable_service);
            $('.count_service_non_taxable_sales').html(data.count_net_sell_non_taxable_service);
        },
    });

    setTimeout(function () {
        var net_sell_part = $('.net_sell_part').html();
        var net_sell_service = $('.net_sell_service').html();
        var net_sales = (parseFloat(net_sell_part) + parseFloat(net_sell_service)).toFixed(2);  
        var net_sell_taxable_part = $('.net_sell_taxable_part').html();
        var net_sell_taxable_service = $('.net_sell_taxable_service').html();
        var taxable_sales = (parseFloat(net_sell_taxable_part) + parseFloat(net_sell_taxable_service)).toFixed(2);
        var net_sell_non_taxable_part = $('.net_sell_non_taxable_part').html();
        var net_sell_non_taxable_service = $('.net_sell_non_taxable_service').html();
        var non_taxable_sales = (parseFloat(net_sell_non_taxable_part) + parseFloat(net_sell_non_taxable_service)).toFixed(2);  
        var part_tax_amount = $('.part_tax_amount').html();
        var service_tax_amount = $('.service_tax_amount').html();
        var total_tax_amount = (parseFloat(part_tax_amount) + parseFloat(service_tax_amount)).toFixed(2);
        var shipping_rate = $('.shipping_rate').html();
        var count_net_sell_part = $('.count_net_sell_part').html();
        var count_net_sell_service = $('.count_net_sell_service').html();
        var count_net_sales = parseFloat(count_net_sell_part) + parseFloat(count_net_sell_service);
        var count_net_sell_taxable_part = $('.count_net_sell_taxable_part').html();
        var count_net_sell_taxable_service = $('.count_net_sell_taxable_service').html();
        var count_taxable_sales = parseFloat(count_net_sell_taxable_part) + parseFloat(count_net_sell_taxable_service);
        var count_net_sell_non_taxable_part = $('.count_net_sell_non_taxable_part').html();
        var count_net_sell_non_taxable_service = $('.count_net_sell_non_taxable_service').html();
        var count_non_taxable_sales = parseFloat(count_net_sell_non_taxable_part) + parseFloat(count_net_sell_non_taxable_service);
        var part_discount = $('.count_part_total_discount').html();
        var service_discount = $('.count_service_total_discount').html();
        var count_total_discount = parseFloat(part_discount) + parseFloat(service_discount);
        var net_discount_sell_part = $('.net_discount_sell_part').html();
        var net_discount_sell_service = $('.net_discount_sell_service').html();
        var total_discount = (parseFloat(net_discount_sell_part) + parseFloat(net_discount_sell_service)).toFixed(2);
        var gross_profit_invoice = $('.count_gross_sales').html(); 
        var gross_profit_part = $('.count_part_net_sales').html(); 
        var gross_profit_service = $('.count_service_net_sales').html(); 
        var gross_profit_shipping_charge = $('.count_shipping_charge').html(); 
        var count_gross_profit = (parseFloat(gross_profit_invoice) + parseFloat(gross_profit_part) + parseFloat(gross_profit_service) + parseFloat(gross_profit_shipping_charge)); 

        $('.net_sales').html(currency + net_sales);
        $('.taxable_sales').html(currency + taxable_sales);
        $('.non_taxable_part').html(currency + non_taxable_sales);
        $('.total_tax_amount').html(total_tax_amount);
        $('.count_net_sales').html(count_net_sales);
        $('.count_taxable_sales').html(count_taxable_sales);
        $('.count_non_taxable_part').html(count_non_taxable_sales);
        $('.count_total_discount').html(count_total_discount);
        $('.total_discount').html(currency + total_discount);
        // $('.count_gross_profit').html(count_gross_profit);

        // Gross Profit
        $.ajax({
            url: site_url + 'reports/custom_reports_data_gross_profit',
            type: 'POST',
            dataType: 'json',
            data: {date: date},
            success: function(data){                
                if(data.gross_profit != "" && data.gross_profit != null) {
                    var unit_rate = jQuery.map(data.gross_profit, function(n, i){
                        return n.unit_cost;
                    });            
                } 

                var count_part_total_cost = 0;
                if(unit_rate != "" && unit_rate != null) {
                    var sum_unit_rate = 0;
                    $.each(unit_rate,function(i,v) {
                        sum_unit_rate += parseFloat(v);
                        count_part_total_cost++;
                    });
                    var total_unit_rate = parseFloat(sum_unit_rate).toFixed(2);
                }            

                if(unit_rate != "" && unit_rate != null) {
                    var gross_profit = (parseFloat(net_sales) - parseFloat(total_unit_rate) + parseFloat(shipping_rate)).toFixed(2);
                    $('.gross_profit').html(currency + gross_profit);
                } else {
                    $('.gross_profit').html(currency + "0.00");
                }

                if(total_unit_rate !== undefined)
                {
                    $('.part_total_cost').html(currency + total_unit_rate);
                } else { 
                    $('.part_total_cost').html("0.00");
                }

                $('.count_part_total_cost').html(count_part_total_cost);

            },
        });
        
    }, 2000);
}

$(document).on('click', '.pdf_button', function (e) {
    $("#fakeLoader").attr('style', '').html('');
    $(document).find("#fakeLoader").fakeLoader({
        timeToHide: 3000,
        spinner: "spinner5",
        bgColor: "",
    });
    var date = $('#date_range').val();
    var url = updateQueryStringParameter(site_url + 'reports/print_sales', "date", date);
    window.location.href = encodeURI(url);
});
//----------------------------- v! Change URL ---------------------------------------------

function updateQueryStringParameter(uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    } else {
        return uri + separator + key + "=" + value;
    }
}

// Initialize with options
$('#date_range').daterangepicker({
    startDate: start,
    endDate: end,
    // minDate: '01/01/2014',
    maxDate: moment(),
    dateLimit: {days: 60},
    firstDayOfWeek: 1,
    ranges: {
        'Today': [moment(), moment()],
        'This Week': [moment().startOf('week'), moment()],
        'Last 7 Days': [moment().subtract('days', 6), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
        'Month To Date': [moment().startOf('month'), moment()],
        // 'This Year': [moment().startOf('year'), moment().endOf('month').endOf('year')],
        'Last Year': [moment().subtract('year', 1).startOf('year'), moment().subtract('year', 1).endOf('year')],
        'Year to Date': [moment().startOf('year'), moment()],
    },
    alwaysShowCalendars: true,
    opens: 'right',
    applyClass: 'btn-small bg-blue',
    cancelClass: 'btn-small btn-default'
}, function (start, end) {
    $('#date_range span').html(start.format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + end.format('MMMM D, YYYY'));
    $('#date_range').val(start.format('YYYY/MM/DD') + ':=:' + end.format('YYYY/MM/DD'));
    // $.jGrowl('Date range has been changed', { header: 'Update', theme: 'bg-primary', position: 'center', life: 1500 });
});

// Get current date time zone wish
const monthNames = ["January","February","March","April","May","June","July","August","September","October","November","December"];
var timezone = new Date();
year  = timezone.getYear() + 1900;
month = monthNames[timezone.getMonth()];
month_formate = (timezone.getMonth() + 1).toString().padStart(2, "0");
day   = timezone.getDate().toString().padStart(2, "0");
var global_date = month + ' ' + day + ', ' + year;
var global_date_formate = year + '/' + month_formate + '/' + day;

// $('#date_range span').html(start.format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + end.format('MMMM D, YYYY'));
// $('#date_range').val(start.format('YYYY/MM/DD') + ':=:' + end.format('YYYY/MM/DD'));

// Display date format
$('#date_range span').html(start.format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + global_date);
$('#date_range').val(start.format('YYYY/MM/DD') + ':=:' + global_date_formate);

// View link
function link_gross_sales(){
    var date = $('#date_range').val();
    window.open(base_url + "reports/view_gross_sales/" + date);
}
function link_net_sales(){
    var date = $('#date_range').val();
    window.open(base_url + "reports/view_net_sales/" + date);
}
function link_gross_profit(){
    var date = $('#date_range').val();
    window.open(base_url + "reports/view_gross_profit/" + date);
}
function link_taxable_sale(){
    var date = $('#date_range').val();
    window.open(base_url + "reports/view_taxable_sales/" + date);
}
function link_non_taxable_sale(){
    var date = $('#date_range').val();
    window.open(base_url + "reports/view_non_taxable_sales/" + date);   
}
function link_discount_sale(){
    var date = $('#date_range').val();
    window.open(base_url + "reports/view_discount_sales/" + date);   
}
function link_shipping_charge(){
    var date = $('#date_range').val();
    window.open(base_url + "reports/view_shipping_charge/" + date);   
}
function link_part_net_sale(){
    var date = $('#date_range').val();
    window.open(base_url + "reports/view_part_net_sale/" + date);   
}
function link_part_taxable_sale(){
    var date = $('#date_range').val();
    window.open(base_url + "reports/view_part_taxable_sale/" + date);   
}
function link_non_taxable_part_sale(){
    var date = $('#date_range').val();
    window.open(base_url + "reports/view_part_non_taxable_sale/" + date);   
}
function link_part_tax(){
    var date = $('#date_range').val();
    window.open(base_url + "reports/view_part_tax/" + date);   
}
function link_part_discount(){
    var date = $('#date_range').val();
    window.open(base_url + "reports/view_part_discount/" + date);   
}
function link_part_cost(){
    var date = $('#date_range').val();
    window.open(base_url + "reports/view_part_cost/" + date);
}   
function link_service_net_sale(){
    var date = $('#date_range').val();
    window.open(base_url + "reports/view_service_net_sales/" + date);
}
function link_service_taxable_sale(){
    var date = $('#date_range').val();
    window.open(base_url + "reports/view_service_taxable_sales/" + date);   
}
function link_non_taxable_service_sale(){
    var date = $('#date_range').val();
    window.open(base_url + "reports/view_service_non_taxable_sale/" + date);   
}
function link_service_tax(){
    var date = $('#date_range').val();
    window.open(base_url + "reports/view_service_tax/" + date);  
}
function link_service_discount(){
    var date = $('#date_range').val();
    window.open(base_url + "reports/view_service_discount/" + date);   
}