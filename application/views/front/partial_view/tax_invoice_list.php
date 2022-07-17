<div class="row">
    <?php 
        $invoice_arr = array();
        foreach ($invoice_list as $key => $value) {
            $invoice_arr[] = $value['estimate_id'];
        }

        $final_invoic_list= array_unique($invoice_arr);
        foreach($final_invoic_list as $invoice_no) {
            ?>
                <a class="btn custom_dt_action_button"><?php echo $invoice_no; ?></a>
        <?php
        }
    ?>    
</div>

