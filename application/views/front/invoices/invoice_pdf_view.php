<?php
   $cur = (isset($currency) && !empty($currency)) ? $currency['symbol'] : '$'; 

   $image = "";
   if(isset($UserInfo) && !empty($UserInfo['profile_pic'])) {
      $image = base_url('uploads/profile') . '/' . $UserInfo['profile_pic'];
   } 
   
   if($estimate['parts'] != "")
   {
       $ind_part_tax = array_column($estimate['parts'],'individual_part_tax');
       $ind_part_id = array_column($estimate['parts'],'tax_id');
       $part_final_array = [];
       
       foreach($ind_part_id as $key=>$val){
           if($val != 0)
           {
               $val = explode(",",$val);
               $tax_val = explode("," , $ind_part_tax[$key]);
               foreach($val as $k1=>$v1){
                   if(array_key_exists($v1, $part_final_array)){
                   $part_final_array[$v1] = $part_final_array[$v1] + $tax_val[$k1];
                   } else {
                       $part_final_array[$v1] = $tax_val[$k1];
                   }
               }
           }
       }
   }
   
   if($estimate['services'] != "")
   {
       $ind_srv_tax = array_column($estimate['services'],'individual_service_tax');
       $ind_srv_id = array_column($estimate['services'],'tax_id');
       $srv_final_array = [];
       
       foreach($ind_srv_id as $key=>$val){
           if($val != 0)
           {
               $val = explode(",",$val);
               $tax_val = explode("," , $ind_srv_tax[$key]);
               foreach($val as $k1=>$v1){
                   if(array_key_exists($v1, $srv_final_array)){
                   $srv_final_array[$v1] = $srv_final_array[$v1] + $tax_val[$k1];
                   } else {
                       $srv_final_array[$v1] = $tax_val[$k1];
                   }
               }
           }
       }
   }

   $state = $company_info['state_name'];
   $state_name = implode(" ",$state);
   
   ?>
<div style="display: block; border: solid 1px #dddddd; padding: 15px">
   <table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
      <td>
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
          <tr>
            <td>
              <a href="#" style="display: block;">
                <?php if($image != "" && !empty($image) && $image != null) { ?>
                  <img src="<?php echo $image ?>" width="200" height="100">
                <?php } ?>
              </a>
            </td>
            <td align="right" style="vertical-align: bottom;padding-bottom: 15px">
              <?php if($company_info['business_name'] != '') { echo $company_info['business_name']; } ?><br/>
              <?php if($company_info['address'] != '') { echo $company_info['address']; } ?><br/>
              <?php if($company_info['city'] != '') { echo $company_info['city'].', '; } ?>
              <?php if($state_name != '') { echo $state_name.' '; } ?>
              <?php if($company_info['zip_code'] != '') { echo $company_info['zip_code']; } ?><br/>
              <?php if($company_info['contact_number'] != '') { echo $company_info['contact_number']; } ?><br/>
              <?php if($company_info['email_id'] != '') { echo $company_info['email_id']; } ?><br/>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td style="padding: 2px;background: #000;"></td>
    </tr>
    <!-- <tr>
      <td>
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
          <tr>
            <td align="right" style="padding-top: 10px;">
              <h3  style="font-size: 30px; color: #333333;">Invoice</h3>
              <p style="font-size: 15px; padding-bottom: 33px; color: #333333;"># <?php echo $estimate['estimate_id']; ?></p>
            </td>
          </tr>
        </table>
      </td>
    </tr> -->
    <tr>
      <td>
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
          <tr>
            <td align="left" style="width:50%;vertical-align: bottom;padding-top: 15px;padding-bottom: 10px;">
              <h4 style="font-size: 14px; color: #a7a7aa;">Invoice To:</h4>
              <p style="color: #333334; display: block; font-weight: bold;"><?php if($estimate['cust_name'] != '') { echo $estimate['cust_name']; } ?></p>
              <p style="font-size: 13px; color: #555956; line-height: 1.5;"><?php if($estimate['address'] != '') { echo $estimate['address']; } ?></p>
              <p style="font-size: 13px; color: #555956; line-height: 1.5;padding-bottom: 15px;margin-bottom: 15px;"><?php if($estimate['email'] != '') { echo str_replace(',', ',<br>', $estimate['email']); } ?></p>
              <p style="font-size: 13px; color: #555956; line-height: 1.5;padding-bottom: 15px;margin-bottom: 15px;"><?php if($estimate['phone_number'] != '') { echo $estimate['phone_number']; } ?></p>
            </td>
            <td align="right" style="width:50%;vertical-align: text-top;padding-top: 10px;padding-bottom: 10px;">
              <table cellpadding="0" cellspacing="0" border="0" style="width:80%;">
                <tr>
                   <td align="left" style="font-size: 25px; color: #333333;">Invoice</td>
                   <td align="right" style="font-size: 20px; color: #666;"># <?php echo $estimate['estimate_id']; ?></td>
                </tr>
                <tr>
                   <td align="left" style="font-size: 14px; color: #333333;">Invoice Date</td>
                   <td align="right" style="font-size: 13px; color: #666;"><?php echo date($format['format'], strtotime($estimate['estimate_date'])) ?></td>
                </tr>
                <tr>
                   <td align="left" style="font-size: 14px; color: #333333;">Due Date</td>
                   <td align="right" style="font-size: 13px; color: #666;"><?php echo date($format['format'], strtotime($estimate['expiry_date'])) ?></td>
                </tr>
                <tr>
                   <td align="left" style="font-size: 14px; color: #333333; ">Representative</td>
                   <td align="right" style="font-size: 13px; color: #666;"><?php echo $estimate['full_name'] ?></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

   <table cellspacing="0" cellpadding="0" width="100%" style="border: solid 1px #dddddd; margin-top: 5px;">
    <thead>
       <tr>
          <?php if($estimate['make_name'] != '') { ?>
            <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; border-top: solid 1px #dddddd;">Make</th>
          <?php } ?>
          <?php if($estimate['modal_name'] != '') { ?>
            <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; border-top: solid 1px #dddddd;">Model</th>
          <?php } ?>
          <?php if($estimate['year_name'] != '') { ?>
            <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; border-top: solid 1px #dddddd;">Year</th>
          <?php } ?>

          <?php
          $est_checked_field = '';
          if($vehicleArr['invoice_field'] != "")
          {
            $est_checked_field = explode(',', $vehicleArr['invoice_field']);
          }
          if($est_checked_field != "") { 
            if(isset($fieldArr) && !empty($fieldArr)){
              foreach ($fieldArr as $key => $fieldvalue) {
                if(in_array($fieldvalue['id'],$est_checked_field)){
                ?>
                
                  <?php if($fieldvalue['field_name'] == "Color") { if($estimate['color_name'] != '') { ?>
                    <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; border-top: solid 1px #dddddd;">Color</th>
                  <?php } } ?>
                  <?php if($fieldvalue['field_name'] == "VIN#") { if($estimate['vin_id'] != '') { ?>
                    <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; border-top: solid 1px #dddddd;">VIN#</th>
                  <?php } } ?>
                  <?php if($fieldvalue['field_name'] == "License plate#") { if($estimate['lic_plate_id'] != '') { ?>
                    <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; border-top: solid 1px #dddddd;">License plate#</th>
                  <?php } } ?>
                  <?php if($fieldvalue['field_name'] == "PO#") { if($estimate['po_number'] != '') { ?>
                    <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; border-top: solid 1px #dddddd;">PO#</th>
                  <?php } } ?>
                  <?php if($fieldvalue['field_name'] == "Stock#") { if($estimate['stock'] != '') { ?>
                    <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; border-top: solid 1px #dddddd;">Stock#</th>
                  <?php } } ?>
                  <?php if($fieldvalue['field_name'] == "Work Order#") { if($estimate['work_order'] != '') { ?>
                    <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; border-top: solid 1px #dddddd;">Work Order#</th>
                  <?php } } ?>
                  <?php if($fieldvalue['field_name'] == "Reference#") { if($estimate['reference'] != '') { ?>
                    <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; border-top: solid 1px #dddddd;">Reference#</th>
                  <?php } } ?>
                  <?php if($fieldvalue['field_name'] == "Tracking#") { if($estimate['tracking'] != '') { ?>
                    <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; border-top: solid 1px #dddddd;">Tracking#</th>
                  <?php } } ?>
                    
                  <?php 
                  } 
                } 
              } 
            } else { 
              ?>
            <?php if($estimate['color_name'] != '') { ?>
                  <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; border-top: solid 1px #dddddd;">Color</th>
                <?php } ?>
                <?php if($estimate['vin_id'] != '') { ?>
                  <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; border-top: solid 1px #dddddd;">VIN#</th>
                <?php } ?>
                <?php if($estimate['lic_plate_id'] != '') { ?>
                  <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; border-top: solid 1px #dddddd;">License plate#</th>
                <?php } ?>
                <?php if($estimate['po_number'] != '') { ?>
                  <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; border-top: solid 1px #dddddd;">PO#</th>
                <?php } ?>
                <?php if($estimate['stock'] != '') { ?>
                  <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; border-top: solid 1px #dddddd;">Stock#</th>
                <?php } ?>
                <?php if($estimate['work_order'] != '') { ?>
                  <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; border-top: solid 1px #dddddd;">Work Order#</th>
                <?php } ?>
                <?php if($estimate['reference'] != '') { ?>
                  <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; border-top: solid 1px #dddddd;">Reference#</th>
                <?php } ?>
                <?php if($estimate['tracking'] != '') { ?>
                  <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; border-top: solid 1px #dddddd;">Tracking#</th>
                <?php } ?>
          <?php } ?>
        </tr>
    </thead>
    <tbody>
       <tr>
          <?php if($estimate['make_name'] != '') { ?>
            <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; padding: 10px;"><?php echo $estimate['make_name']; ?></td>
          <?php } ?>
          <?php if($estimate['modal_name'] != '') { ?>
            <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; padding: 10px;"><?php echo $estimate['modal_name']; ?></td>
          <?php } ?>
          <?php if($estimate['year_name'] != '') { ?>
            <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; padding: 10px;"><?php echo $estimate['year_name']; ?></td>
          <?php } ?>

          <?php
          $est_checked_field = '';
          if($vehicleArr['invoice_field'] != "")
          {
            $est_checked_field = explode(',', $vehicleArr['invoice_field']);
          }
          if($est_checked_field != "") { 
            if(isset($fieldArr) && !empty($fieldArr)){
              foreach ($fieldArr as $key => $fieldvalue) {
                if(in_array($fieldvalue['id'],$est_checked_field)){
                ?>
                  <?php if($fieldvalue['field_name'] == "Color") { if($estimate['color_name'] != '') { ?>
                    <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; padding: 10px;"><?php echo $estimate['color_name']; ?></td>
                  <?php } } ?>
                  <?php if($fieldvalue['field_name'] == "VIN#") { if($estimate['vin_id'] != '') { ?>
                    <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; padding: 10px;"><?php echo $estimate['vin_id']; ?></td>
                  <?php } } ?>
                  <?php if($fieldvalue['field_name'] == "License plate#") { if($estimate['lic_plate_id'] != '') { ?>
                    <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; padding: 10px;"><?php echo $estimate['lic_plate_id']; ?></td>
                  <?php } } ?>
                  <?php if($fieldvalue['field_name'] == "PO#") { if($estimate['po_number'] != '') { ?>
                    <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; padding: 10px;"><?php echo $estimate['po_number']; ?></td>
                  <?php } } ?>
                  <?php if($fieldvalue['field_name'] == "Stock#") { if($estimate['stock'] != '') { ?>
                    <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; padding: 10px;"><?php echo $estimate['stock']; ?></td>
                  <?php } } ?>
                  <?php if($fieldvalue['field_name'] == "Work Order#") { if($estimate['work_order'] != '') { ?>
                    <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; padding: 10px;"><?php echo $estimate['work_order']; ?></td>
                  <?php } } ?>
                  <?php if($fieldvalue['field_name'] == "Reference#") { if($estimate['reference'] != '') { ?>
                    <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; padding: 10px;"><?php echo $estimate['reference']; ?></td>
                  <?php } } ?>
                  <?php if($fieldvalue['field_name'] == "Tracking#") { if($estimate['tracking'] != '') { ?>
                    <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; padding: 10px;"><?php echo $estimate['tracking']; ?></td>
                  <?php } } ?>
                  <?php 
                  } 
                } 
              } 
            } else { 
              ?>
              <?php if($estimate['color_name'] != '') { ?>
                <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; padding: 10px;"><?php echo $estimate['color_name']; ?></td>
              <?php } ?>
              <?php if($estimate['vin_id'] != '') { ?>
                <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; padding: 10px;"><?php echo $estimate['vin_id']; ?></td>
              <?php } ?>
              <?php if($estimate['lic_plate_id'] != '') { ?>
                <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; padding: 10px;"><?php echo $estimate['lic_plate_id']; ?></td>
              <?php } ?>
              <?php if($estimate['po_number'] != '') { ?>
                <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; padding: 10px;"><?php echo $estimate['po_number']; ?></td>
              <?php } ?>
              <?php if($estimate['stock'] != '') { ?>
                <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; padding: 10px;"><?php echo $estimate['stock']; ?></td>
              <?php } ?>
              <?php if($estimate['work_order'] != '') { ?>
                <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; padding: 10px;"><?php echo $estimate['work_order']; ?></td>
              <?php } ?>
              <?php if($estimate['reference'] != '') { ?>
                <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; padding: 10px;"><?php echo $estimate['reference']; ?></td>
              <?php } ?>
              <?php if($estimate['tracking'] != '') { ?>
                <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; padding: 10px;"><?php echo $estimate['tracking']; ?></td>
              <?php } ?>
          <?php } ?> 
        </tr>
    </tbody>
   </table>
   
   <?php 
      if (isset($estimate['parts']) && !empty($estimate['parts'])) 
      {
        $dis_part_colspan = 2;
        foreach ($estimate['parts'] as $k => $p):
          if($p['discount_rate'] !=  0 && $p['discount_rate'] !=  "" && $p['discount_rate'] !=  null)
          { 
            $dis_part_colspan = 0;
          } 
        endforeach;
      
        $tax_part_colspan = 3;
        foreach ($estimate['parts'] as $k => $p):
            if($p['individual_part_tax'] !=  "" && $p['individual_part_tax'] !=  null) 
            { 
                $tax_part_colspan = 0;
            }
        endforeach;
        $tax = 0.00;
      ?>
   <table cellspacing="0" cellpadding="0" width="100%" style="border: solid 1px #dddddd; margin-top: 5px;">
      <thead>
         <tr>
            <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; border-top: solid 1px #dddddd; width: 5%;">#</th>
            <th style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-top: solid 1px #dddddd; text-align: left;">Location</th>
            <th width="230" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-top: solid 1px #dddddd; text-align: left;">Parts</th>
            <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-top: solid 1px #dddddd;">Quantity</th>
            <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-top: solid 1px #dddddd;">Rate</th>
            <?php if($dis_part_colspan == 0) { ?>
            <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-top: solid 1px #dddddd;">Discount</th>
            <?php } ?>
            <?php if($tax_part_colspan == 0) { ?>
            <th width="130" align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-top: solid 1px #dddddd;">Tax</th>
            <?php } ?>
            <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; border-top: solid 1px #dddddd; padding: 5px 10px; border-bottom: solid 1px #dddddd;">Amount</th>
         </tr>
      </thead>
      <tbody>
         <?php foreach ($estimate['parts'] as $k => $p) { ?>
         <tr>
            <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; padding: 10px;"><?php echo ($k + 1) ?></td>
            <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; border-left: solid 1px #dddddd; padding: 10px;"><?php echo $p['location_name']; ?></td>
            <td style="font-size:11px; text-align: left; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; padding: 10px;">
               <table cellpadding="0" cellspacing="0" width="100%" border="0">
                  <tr>
                     <td width="50" style="text-align: center;">
                        <?php if($p['global_part_image'] != '' && $p['global_part_image'] != null) { ?>
                        <img src="<?php echo ($p['global_part_image'] != '') ? base_url() . ITEMS_IMAGE_PATH . '/' . $p['global_part_image'] : base_url() . ITEMS_IMAGE_PATH . "/no_image.jpg" ?>" height="30"/>
                        <?php } else if($p['image'] != '' && $p['image'] != null) {  ?>
                        <img src="<?php echo ($p['image'] != '') ? base_url() . ITEMS_IMAGE_PATH . '/' . $p['image'] : base_url() . ITEMS_IMAGE_PATH . "/no_image.jpg" ?>" height="30"/>
                        <?php } else { ?>
                        <img src="<?php echo base_url() . ITEMS_IMAGE_PATH . "/no_image.jpg" ?>" height="30"/>
                        <?php } ?> 
                     </td>
                     <td style="font-size: 11px; color: #888;">
                        <span style="width: 100%; display: block; font-weight: bold; color: #333;"><?php echo $p['part_no'] ?></span>
                        <br><?php echo $p['description'] ?>
                        <?php if($p['item_note'] != "") { ?><br><?php echo '("'.$p['item_note'].'")'; ?><?php } ?>
                     </td>
                  </tr>
               </table>
            </td>
            <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; padding: 10px;"><?php echo $p['quantity']; ?></td>
            <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; padding: 10px;"><?php echo number_format((float) $p['rate'], 2, '.', ''); ?></td>
            <?php if($p['discount_rate'] > 0) { ?>
            <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; padding: 10px;"><?php echo ($p['discount_type_id'] == 'p') ? $p['discount'] . '%' : $cur . '' . $p['discount']; ?></td>
            <?php } else if($dis_part_colspan == 0) { ?>
            <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; padding: 10px;">---</td>
            <?php } else { } ?>
            <?php if($p['individual_part_tax'] !=  "" && $p['individual_part_tax'] !=  null) { ?>
            <td align="center" style="font-size:11px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; padding: 10px;"><?php if ($p['tax_id'] != 0) { echo str_replace(')', ')<br>', $p['tax_list'] ); } ?>
               <span style="display: block; color: #3c97e8;">
               <?php if ($p['tax_id'] != 0) { $tax = ($tax + number_format((float) $p['tax_rate'], 2, '.', '')); echo number_format((float) $p['tax_rate'], 2, '.', ''); } else { echo '0.00'; } ?>
               </span>
            </td>
            <?php } else if($tax_part_colspan == 3) { ?>
            <?php } else { ?>
            <td align="center" style="font-size:11px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; padding: 10px;">---</td>
            <?php } ?>
            <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; padding: 10px;"><?php echo number_format((float) $p['amount'], 2, '.', ''); ?></td>
         </tr>
         <?php } ?>
      </tbody>
   </table>
   <?php } ?>
   <?php 
      if (isset($estimate['services']) && !empty($estimate['services'])) {
          $dis_srv_colspan = 2;
          foreach ($estimate['services'] as $k => $p):
              if($p['discount_rate'] !=  0 && $p['discount_rate'] !=  "" && $p['discount_rate'] !=  null)
              { 
                  $dis_srv_colspan = 0;
              } 
          endforeach;
      
          $tax_srv_colspan = 3;
          foreach ($estimate['services'] as $k => $p):
              if($p['individual_service_tax'] !=  "" && $p['individual_service_tax'] !=  null) 
              { 
                  $tax_srv_colspan = 0;
              }
          endforeach; 
      $s_tax = 0.00;
      ?> 
   <table cellspacing="0" cellpadding="0" width="100%" style="border: solid 1px #dddddd; margin-top: 10px;">
      <thead>
         <tr>
            <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; width: 5%;">#</th>
            <th width="320" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; text-align: left;">Services</th>
            <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd;">Quantity</th>
            <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd;">Rate</th>
            <?php if($dis_srv_colspan == 0) { ?>
            <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd;">Discount</th>
            <?php } ?>
            <?php if($tax_srv_colspan == 0) { ?>
            <th align="center"  width="135" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd;">Tax</th>
            <?php } ?>
            <th align="center" style="font-size: 12px; color: #fff; font-weight: normal; background: #313335; padding: 5px 10px; border-bottom: solid 1px #dddddd;">Amount</th>
         </tr>
      </thead>
      <tbody>
         <?php foreach ($estimate['services'] as $k => $p) { ?>  
         <tr>
            <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; padding: 10px;"><?php echo ($k + 1) ?></td>
            <td style="font-size:11px; text-align: left; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; padding: 10px;">
              <?php echo $p['service_name']; ?>
              <?php if($p['service_note'] != "" && $p['service_note'] != null) { ?><br><?php echo '("'.$p['service_note'].'")'; ?><?php } ?>    
            </td>
            <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; padding: 10px;"><?php echo $p['qty']; ?></td>
            <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; padding: 10px;"><?php echo number_format((float) $p['rate'], 2, '.', ''); ?></td>
            <?php if($p['discount_rate'] > 0) { ?>
            <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; padding: 10px;"><?php echo ($p['discount_type_id'] == 'p') ? $p['discount']. '%' : $cur . '' . $p['discount']; ?></td>
            <?php } else if($dis_srv_colspan == 0) { ?>
            <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; padding: 10px;">---</td>
            <?php } else { } ?>
            <?php if($p['individual_service_tax'] !=  "" && $p['individual_service_tax'] !=  null) { ?>
            <td align="center" style="font-size:11px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; padding: 10px;"><?php if ($p['tax_id'] != 0) { echo str_replace(')', ')<br>',$p['tax_list']); } ?>
               <span style="display: block; color: #3c97e8;">
               <?php if ($p['tax_id'] != 0) { $tax = ($tax + number_format((float) $p['tax_rate'], 2, '.', '')); echo number_format((float) $p['tax_rate'], 2, '.', ''); } else { echo '0.00'; } ?>
               </span>
            </td>
            <?php } else if($tax_srv_colspan == 3) { } else { ?>
            <td align="center" style="font-size:11px; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; padding: 10px;">
               <span style="display: block;">---</span>
            </td>
            <?php } ?>
            <td style="font-size:11px; text-align: center; border-bottom: solid 1px #dddddd; border-right: solid 1px #dddddd; padding: 10px;"><?php echo number_format((float) $p['amount'], 2, '.', ''); ?></td>
         </tr>
         <?php } ?>
      </tbody>
   </table>
   <?php } ?>
   <div style="width: 100%; display: block; margin: 10px 0;">
      <div style="width: 44%; float: left;">
         <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <?php if ($estimate['notes'] != '') { ?>
            <tr>
               <td style="padding-bottom: 10px">
                  <h4 style="font-size: 12px; color: #333; font-weight: bold; margin: 0;">Notes</h4>
                  <p style="font-size: 11px; color: #888; line-height: 1.6; margin: 0px;"><?php echo $estimate['notes']; ?></p>
               </td>
            </tr>
            <?php } ?>
        </table>
      </div>
      <div style="width: 50%; float: right;">
         <?php 
            if($part_final_array != "" && !empty($part_final_array) && $srv_final_array != "" && !empty($srv_final_array)) {
              $tax_colspan = '';
            } else if($part_final_array != "" && !empty($part_final_array)) {
              $tax_colspan = 2;
            } else if($srv_final_array != "" && !empty($srv_final_array)) {
              $tax_colspan = 2;
            } else {
              $tax_colspan = '';
            }
            ?>
         <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
               <td colspan="2" style="border-bottom: solid 1px #e7e7e7; padding: 0px; font-size: 16px; color: #333; font-weight: bold;"></td>
            </tr>
            <tr>
               <td style="border-bottom: solid 1px #e7e7e7; padding: 10px; font-size: 11px; color: #333; font-weight: bold;">Subtotal:</td>
               <td style="border-bottom: solid 1px #e7e7e7; padding: 10px; font-size: 11px; color: #333; font-weight:normal;" align="right"><?php echo number_format((float) $estimate['sub_total'], 2, '.', ''); ?></td>
            </tr>
            <tr>
               <?php if($part_final_array != "" && !empty($part_final_array)) { ?>
               <td valign="top" colspan="<?php echo $tax_colspan; ?>" style="border-bottom: solid 1px #e7e7e7; padding: 10px; font-size: 11px; color: #333; font-weight: bold;">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%">
                     <tr>
                        <td colspan="2" style="font-size: 11px; color: #333; font-weight: bold;">Part Tax:</td>
                     </tr>
                     <?php 
                        foreach ($part_final_array as $key => $value) {
                            foreach ($taxes as $k => $v) {
                                if($key == $v['id']) {
                        ?>
                     <tr>
                        <td width="70%" style="font-weight: normal; font-size: 10px; padding: 4px 0;"><?php echo $v['name'] . ' (' . $v["rate"] . '%)'; ?></td>
                        <td align="right" style="font-weight: normal; font-size: 10px; padding: 4px 0; color: #666;"><?php echo $value;?></td>
                     </tr>
                     <?php
                        }
                        }
                        }
                        ?>
                  </table>
               </td>
               <?php } ?>
               <?php if($srv_final_array != "" && !empty($srv_final_array)) { ?>
               <td valign="top" colspan="<?php echo $tax_colspan; ?>" style="border-bottom: solid 1px #e7e7e7; padding: 10px; font-size: 11px; color: #333; font-weight: bold;" width="50%">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%">
                     <tr>
                        <td colspan="2" style="font-size: 11px; color: #333; font-weight: bold;">Labor And Services tax:</td>
                     </tr>
                     <?php
                        foreach ($srv_final_array as $key => $value) {
                          foreach ($taxes as $k => $v) {
                            if($key == $v['id']) {
                        ?>
                     <tr>
                        <td width="70%" style="font-weight: normal; font-size: 10px; padding: 4px 0;"><?php echo $v['name'] . ' (' . $v["rate"] . '%)'; ?></td>
                        <td align="right" style="font-weight: normal; font-size: 10px; padding: 4px 0; color: #666;"><?php echo $value;?></td>
                     </tr>
                     <?php
                        }
                        }
                        }
                        ?>
                  </table>
               </td>
               <?php } ?>
            </tr>
            <tr>
               <td style="border-bottom: solid 1px #e7e7e7; padding: 10px; font-size: 11px; color: #333; font-weight: bold;">Total Tax:</td>
               <td style="border-bottom: solid 1px #e7e7e7; padding: 10px; font-size: 11px; color: #333; font-weight:normal;" align="right"><?php echo number_format((float) ($tax + $s_tax), 2, '.', ''); ?></td>
            </tr>
            <?php if($estimate['shipping_display_status'] == 1) { ?>
            <tr>
               <td style="border-bottom: solid 1px #e7e7e7; padding: 10px; font-size: 11px; color: #333; font-weight: bold;">Shipping Charge:</td>
               <td style="border-bottom: solid 1px #e7e7e7; padding: 10px; font-size: 11px; color: #333; font-weight:normal;" align="right"><?php echo number_format((float) $estimate['shipping_charge'], 2,'.',''); ?></td>
            </tr>
            <?php } ?>
            <tr>
               <td style="padding: 10px; font-size: 11px; color: #333; font-weight: bold;">Total (<?php echo $cur ?>):</td>
               <td style="padding: 10px; font-size: 11px; color: #3c97e8; font-weight:normal;" align="right"><b><?php echo $cur . "" . number_format((float) $estimate['total'], 2, '.', ''); ?></b></td>
            </tr>
            <tr>
              <td colspan="2">
                <table cellspacing="0" cellpadding="0" width="100%" border="0">
                  <tr>
                    <td width="3%">&nbsp;</td>
                    <td width="44%">
                      <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                           <td colspan="2" style="padding-bottom: 5px; font-size: 13px; font-weight: bold">Payment Details</td>
                        </tr>
                        <?php if($estimate['payment_name'] != "") { ?>
                        <tr>
                           <td style="font-size: 11px; color: #333; padding: 5px 0;">Method</td>
                           <td style="font-size: 11px; color: #666; padding: 5px 0;" align="right"><?php echo $estimate['payment_name']; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if($estimate['payment_reference'] != "") { ?>
                        <tr>
                           <td style="font-size: 11px; color: #333; padding: 5px 0;">Payment Reference</td>
                           <td style="font-size: 11px; color: #666; padding: 5px 0;" align="right"><?php echo $estimate['payment_reference']; ?></td>
                        </tr>
                        <?php } ?>
                     </table>
                    </td>
                    <td width="6%">&nbsp;</td>
                    <?php 
                      if(!empty($estimate) && !empty($estimate['signature_attachment']) && file_exists(FCPATH . 'uploads/signatures/' . $estimate['signature_attachment'])) 
                          {
                          if(getimagesize(base_url('uploads/signatures/' . $estimate['signature_attachment'])))
                            {
                          ?>
                          <td width="47%" align="right">
                              <img src="<?= site_url('uploads/signatures/' . $estimate['signature_attachment'] . '?=' . time()) ?>" width="150" style="border: 1px solid #e2e2d6">
                          </td>
                      <?php 
                          } 
                        } 
                      ?>
                  </tr>
                </table>
              </td>
            </tr>
         </table>
      </div>
   </div>
   <table cellpadding="0" cellspacing="0" border="0" width="100%">
      <tr>
         <td style="font-size: 9px; color: #000; font-weight: 400; padding: 0 0 7px;">Terms & Conditions</td>
      </tr>
      <tr>
         <td style="font-size: 9px; color: #aaa8a8; line-height: 1.4;">
            <?php echo (isset($terms_condition) && $terms_condition != null) ? $terms_condition : "Your company's Terms and Conditions will be displayed here. You can add it in the Estimation Preference under Settings." ?>
         </td>
      </tr>
   </table>
</div>