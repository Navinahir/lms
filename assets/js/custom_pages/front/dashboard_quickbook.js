//Insert customer 
$(function() {
    $('#quickbook_customer').click(function(e){
        e.preventDefault();
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            url: site_url + 'quickbook/customer/add',
            type: 'POST',
            dataType: 'json',
            success: function(data)
            {
                console.log(data);
                l.stop();
                $("#quickbook_customer").hide(1000);
                location.reload(true);
            }
        });
    });
});
$("#config_submit").click(function(e) {
    var income_account = $("#income_account").val();
    var expense_account = $("#expense_account").val();
    var inventory_asset_account = $("#inventory_asset_account").val();
    var income_account_service = $("#income_account_service").val();
    var deposite_to = $("#deposite_to").val();
    var customer_id = $("#customer_id").val();
    var realmId = $("#realmId").val();
   
    $.ajax({
        url: site_url + 'quickbook/config',
        type: 'POST',
        dataType: 'json',
        data: {
            income_account: income_account,
            expense_account: expense_account,
            inventory_asset_account: inventory_asset_account,    
            income_account_service: income_account_service,    
            deposite_to: deposite_to,    
            customer_id: customer_id,    
            realmId: realmId,    
        },
        success:function(data)
        {
            if(data.status == 1)
            {
                $("#config_submit").text('Update');
                if(data.operation == 'add')
                {
                    var x = document.getElementById("snackbar_add");
                }
                else
                {
                    var x = document.getElementById("snackbar_update");
                }
                x.className = "show";
                setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
            }
            else
            {
                var x = document.getElementById("snackbar_failed");
                x.className = "show";
                setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
            }
        }
    });
});