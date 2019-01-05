<?php

// Custom View
$cv = new CustomView();
$cv->set_data(array(
	'registerUrl' => base_url().'resellers/register',
	'baseUrl' => base_url()
));

// Custom form
$cform = new CustomForm();

// Template view option
$show_topmenu = TRUE;
$show_footer = TRUE;
switch($pageview)
{
  case 'resellers/create':
  case 'resellers/thanks':
  case 'users/login':
    $show_topmenu = FALSE;
    $show_footer = FALSE;
    break;
  case 'users/index':
  case 'users/update':
    $show_footer = FALSE;
    break;
  default :
    break;
}


// Load head
$cv->render('templates/head');

// Load topmenu
if($show_topmenu) {
  $nav_active = ' highlight select_nav active';
  if (user_is_logged_in())
  {
    $cv->render('templates/topmenu_admin');
  }
  else
  {
    $cv->render('templates/topmenu', array(
      'navBtnHome' => ($page == 'home' ? $nav_active : ''),
      'navBtnProducts' => ($page == 'products' ? $nav_active : ''),
      'navBtnAbout' => ($page == 'about' ? $nav_active : ''),
    ));
  }
}

// Load page
require_once(VIEWPATH.$pageview.'.php');

// Load footer
if($show_footer)
{
  $cv->render('templates/footer');
}

// Load foot
$cv->render('templates/foot');

?>