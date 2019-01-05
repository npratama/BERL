<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User login.
 * Save user data into session variable.
 * @param var $data User data.
 */
function user_login($data)
{
  // Set session
  $_SESSION['user_logged_in'] = $data;
}

/**
 * Get logged in user data.
 * Get data from user data session.
 * @return  int $data User data.
 */
function user_get_data()
{
  $data = '';

  // Check user session data
  if(isset($_SESSION['user_logged_in']))
  {
    // Get user data
    $data = $_SESSION['user_logged_in'];
  }

  return $data;
}

/**
 * Get logged in user id.
 * Get data from user data session.
 * @return  int $id User id.
 */
function user_get_id()
{
  $id = 0;

  // Get user data
  $data = user_get_data();

  // Check user id
  if(!empty($data['user_id']))
  {
    // Get user id
    $id = $data['user_id'];
  }

  return $id;
}

/**
 * Check is there user that logged id.
 * @return  TRUE There is logged in user.
 * @return  FALSE There is no logged in user.
 */
function user_is_logged_in()
{
  $id = user_get_id();
  if($id != 0)
  {
    return TRUE;
  }
  else
  {
    return FALSE;
  }
}

/**
 * User logout.
 * Delete user data in session variable.
 */
function user_logout()
{
  // Delete session data
  if(isset($_SESSION['user_logged_in']))
  {
    unset($_SESSION['user_logged_in']);
  }
}

/**
 * Check is user logged in.
 * If not, redirect to login page.
 */
function user_require_login()
{
  // Check is user logged in
  if (!user_is_logged_in())
  {
    // If not, redirect to login page
    redirect('admin/login');
  }
}

?>