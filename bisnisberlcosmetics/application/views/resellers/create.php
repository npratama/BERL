<?php

$cform->set_mode('read');
$cform->set_input($input_value, $invalid_input);

$html = $cform->open(base_url().'resellers/register');
$html .= $cform->input_text('reseller_name', 'Nama Anda');
$html .= $cform->input_text('reseller_address', 'Alamat lengkap Anda');
$html .= $cform->input_text('reseller_postcode', 'Kode Pos');
$html .= $cform->input_text('reseller_phone', 'No. Telp/Whatsapp Anda');
$html .= $cform->input_text('reseller_email', 'Email Anda');
$html .= $cform->input_text('reseller_courier', 'Ekspedisi');
$html .= $cform->input_file('reseller_payslip', 'Bukti pembayaran');
$html .= $cform->input_submit('Daftar Sekarang');
$html .= $cform->close();

$cv->render('resellers/html/register', array('formBody' => $html));

?>