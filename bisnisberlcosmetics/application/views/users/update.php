<?php

// cform setting
$cform->set_mode('read');
$cform->set_input($input_value, $invalid_input);

$html = $cform->open(base_url().'admin/edit');
$html .= $cform->msg_primary($update_ok_msg);
$html .= $cform->input_text('user_surename', 'Nama Anda');
$html .= $cform->input_text('user_email', 'Email Anda');
$html .= $cform->input_submit('Simpan');
$html .= $cform->close();

$cv->render('users/html/user_data', array('formBody' => $html));

?>