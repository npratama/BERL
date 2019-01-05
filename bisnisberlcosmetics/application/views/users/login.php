<?php

// cform setting
$cform->set_mode('read');
$cform->set_input($input_value, $invalid_input);

$html = $cform->open(base_url().'admin/login');
$html .= $cform->input_text('user_name', 'Nama Anda');
$html .= $cform->input_pswd('user_password', 'Password Anda');
$html .= $cform->msg_danger($login_fail_msg);
$html .= $cform->input_submit('Log in');
$html .= $cform->close();

$cv->render('users/html/login', array('formBody' => $html));

?>