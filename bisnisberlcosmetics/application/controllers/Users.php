<?php
class Users extends CI_Controller
{
  // Page data
  private $data = array(
    'page' => 'users',
    'login_fail_msg' => '',
    'update_ok_msg' => '',
    'input_value' => array(),
    'invalid_input' => array()
  );

  /**
   * Route: /users/login
   */
  public function login()
  {
    $continue = FALSE;

    // Request inputs
    $req = new RequestInput(array(
      'user_name' => 'user_name',
      'user_password' => 'password'
    ));

    // Set data to pass to the view
    $this->data['pageview'] = $this->data['page'] . '/login';
    $this->data['input_value'] = $req->req_body;
    $this->data['invalid_input'] = $req->invalid_input;

    // Check POST request
    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
      // Validate inputs
      $req->check('user_name', 'User name', array('required' => TRUE, 'length' => 50, 'alphanum' => TRUE));
      $req->check('user_password', 'Password', array('required' => TRUE, 'length' => 50));
      $this->data['invalid_input'] = $req->invalid_input;

      // Check validation
      if($req->is_valid())
      {
        // Get user data
        $req->data['password'] = md5($req->data['password']);
        $user_data = $this->user_model->select_user($req->data['user_name'], $req->data['password']);

        // Check user data
        if(!empty($user_data))
        {
          // User login
          user_login($user_data);

          // Continue to next route
          $continue = TRUE;

          // Redirect to admin home page
          redirect('/admin');
        }
        else
        {
          // Login failed
          $this->data['login_fail_msg'] = 'User name atau password tidak ditemukan';
        }
      }
    }

    if(!$continue)
    {
      // Load page view
      $this->load->view('page_view', $this->data);
    }
  }

  /**
   * Route: /users/logout
   */
  public function logout()
  {
    // User logout
    user_logout();

    // Redirect
    redirect('/admin/login');
  }

  /**
   * Route: /users/update
   */
  public function update()
  {
    // Check user login
    user_require_login();

    $continue = FALSE;

    // Request inputs
    $req = new RequestInput(array(
      'user_surename' => 'surename',
      'user_email' => 'email'
    ));

    // Set data to pass to the view
    $this->data['pageview'] = $this->data['page'] . '/update';
    $this->data['input_value'] = $req->req_body;
    $this->data['invalid_input'] = $req->invalid_input;

    // Check POST request
    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
      // Validate inputs
      $req->check('user_surename', 'Nama Anda', array('required' => TRUE, 'length' => 50, 'name' => TRUE));
      $req->check('user_email', 'Email Anda', array('required' => TRUE, 'length' => 50, 'email' => TRUE));
      $this->data['invalid_input'] = $req->invalid_input;

      // Check validation
      if($req->is_valid())
      {
        // Get user data
        $user_data = user_get_data();
        $user_data['surename'] = $req->data['surename'];
        $user_data['email'] = $req->data['email'];
        
        // Update user data
        $this->user_model->update($user_data['user_id'], $user_data);

        // Set user data
        $this->data['input_value'] = array(
          'user_surename' => $user_data['surename'],
          'user_email' => $user_data['email']
        );

        // Set update ok message
        $this->data['update_ok_msg'] = 'Data telah diupdae.';
      }
    }
    else // GET request
    {
      // Get user data
      $user_data = user_get_data();

      $this->data['input_value'] = array(
        'user_surename' => $user_data['surename'],
        'user_email' => $user_data['email']
      );
    }

    if(!$continue)
    {
      // Load page view
      $this->load->view('page_view', $this->data);
    }
  }

  /* Common functions */
  public static function url_create($id = 0) { return base_url().$this->data['page'].'/create'; }
  public static function url_update($id = 0) { return base_url().$this->data['page'].'/edit'.'/'.$id; }
  public static function url_delete($id = 0) { return base_url().$this->data['page'].'/delete'.'/'.$id; }
}
?>