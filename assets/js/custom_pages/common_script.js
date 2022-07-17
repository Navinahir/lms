//Serach Make, Model and Year details based on VIN Number.
$(document).on('click', '#search-vin-for-make-model', function () {
    var vin_no = $("input[name=txt_vin_no]").val();
    var upper_vin_no = vin_no.toUpperCase();

    if (vin_no == '') {
        $("#txt_vin_no_error").removeClass('hide');
        $("#txt_vin_no_error").text('Please enter VIN number');
        $("input[name=txt_vin_no]").focus();
        return false;
    } else if (vin_no.length != 17) {
        $("#txt_vin_no_error").removeClass('hide');
        $("#txt_vin_no_error").text('VIN number must be in 17 digits');
        $("input[name=txt_vin_no]").focus();
        return false;
    } else if (vin_no !== '') {
        var is_find_letter = false;
        var not_allowed_character = ['I', 'O', 'Q'];
        let vin_no_arr = [...upper_vin_no];

        $.each(not_allowed_character, function (key, value) {
            if (jQuery.inArray(value, vin_no_arr) !== -1) {
                is_find_letter = true;
            }
        });

        if (is_find_letter == true) {
            $("#txt_vin_no_error").removeClass('hide');
            $("#txt_vin_no_error").text('VIN should not contain any of these letters (I, O, Q)');
            $("input[name=txt_vin_no]").focus();
            return false;
        }

        $("#txt_vin_no_error").empty().addClass('hide');
        $('#custom_loading').removeClass('hide');
        $('#custom_loading').css('display', 'block');

        $.ajax({
            url: api_url,
            type: 'POST',
            data: {vin_no: vin_no},
            dataType: 'HTML',
            success: function (response) {
                $("#div_vehical_details").removeClass('hide');
                $(".view_vehical_info").html(response);
                $("#custom_loading").fadeOut(2500);
            }
        });
    }
});