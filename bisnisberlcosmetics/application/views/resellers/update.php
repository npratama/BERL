<div class="container">
<?php
  cv_form_open('admin/resellers/edit/'.$input_value['id']);
  cv_form_input_text('reseller_name', 'Nama Anda', $input_value['reseller_name'], $invalid_input['reseller_name']);
  cv_form_input_text('reseller_phone', 'No. Telp/Whatsapp', $input_value['reseller_phone'], $invalid_input['reseller_phone']);
  cv_form_input_text('reseller_email', 'Email Anda', $input_value['reseller_email'], $invalid_input['reseller_email']);
  cv_form_input_text('reseller_address', 'Alamat Anda', $input_value['reseller_address'], $invalid_input['reseller_address']);
  cv_form_input_submit('Simpan');
  cv_form_close();
?>
</div>
