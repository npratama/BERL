<?php
class Resellers extends CI_Controller
{
  // Page data
  private $data = array(
    'page' => 'resellers'
  );

  // Inputs
  private static $inputs_ = array(
    'reseller_name' => 'name',
    'reseller_address' => 'address',
    'reseller_postcode' => 'postcode',
    'reseller_phone' => 'phone_wa',
    'reseller_email' => 'email',
    'reseller_courier' => 'courier',
    'reseller_payslip' => 'payslip_id'
  );

  // Email config
  private static $email_config = array(
    'protocol' => 'smtp',
    'smtp_host' => 'smtp.sendgrid.net',
    'smtp_user' => 'hai.stuw',
    'smtp_pass' => 'dummyp4ssw0rd',
    'smtp_port' => 587,
    'crlf' => "\r\n",
    'newline' => "\r\n",
    'mailtype' => 'html',
    'validate' => TRUE
  );

  /**
   * Route: /resellers/index
   */
  public function index()
  {
    // Check user login
    user_require_login();

    // Set data to pass to the view
    $this->data['pageview'] = $this->data['page'].'/index';
    $this->data['reseller_list'] = $this->reseller_model->select();

    // Load page view
    $this->load->view('page_view', $this->data);
  }

  /**
   * Route: /resellers/thanks
   */
  public function thanks()
  {
    // Set data to pass to the view
    $this->data['pageview'] = $this->data['page'] . '/thanks';

    // Load page view
    $this->load->view('page_view', $this->data);
  }

  /**
   * Route: /resellers/create
   */
  public function create()
  {
    $continue = FALSE;

    // Requests
    $req = new RequestInput(self::$inputs_, array(
      'reseller_payslip' => 'FILE'
    ));

    // Set data to pass to the view
    $this->data['pageview'] = $this->data['page'] . '/create';
    $this->data['input_value'] = $req->req_body;
    $this->data['invalid_input'] = $req->invalid_input;

    // Check POST request
    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
      // Validate inputs
      $req->check('reseller_name', 'Nama', array('required' => TRUE, 'length' => 100, 'name' => TRUE));
      $req->check('reseller_address', 'Alamat', array('required' => TRUE, 'length' => 500));
      $req->check('reseller_postcode', 'Kode Pos', array('required' => TRUE, 'length' => 5, 'numeric' => TRUE));
      $req->check('reseller_phone', 'No. Telp/Whatsapp', array('required' => TRUE, 'length' => 20, 'phone' => TRUE));
      $req->check('reseller_email', 'Email', array('required' => TRUE, 'length' => 50, 'email' => TRUE));
      $req->check('reseller_courier', 'Ekspedisi', array('required' => TRUE, 'length' => 50, 'name' => TRUE));
      $req->check('reseller_payslip', 'Bukti pembayaran', array('required' => TRUE));
      $this->data['invalid_input'] = $req->invalid_input;

      // Check validation
      if($req->is_valid())
      {
        // Create payslip data
        $payslip_id = $this->payslip_model->create($_FILES['reseller_payslip']);

        // Check payslip id
        if($payslip_id != 0)
        {
          // Set payslip id
          $req->data['payslip_id'] = $payslip_id;

          // Create reseller model
          $this->reseller_model->create($req->data);

          // Redirect
          if(user_is_logged_in())
          {
            // Redirect
            $continue = TRUE;
            redirect('/admin/resellers');
          }
          else
          {
            // Notify new reseller registration

            // Get reseller data
            $reseller_id = $this->db->insert_id();
            $reseller_data = $this->reseller_model->select($reseller_id);

            // Check reseller data
            if(!empty($reseller_data))
            {
              // Get payslip file for attachment
              $payslip_file = $this->payslip_model->get_temp_file($payslip_id);

              // Load library
              $this->load->library('email');
              $chtml = new CustomView();

              // Get admin data
              $user_data = $this->user_model->select(1);

              // Send notification to admin
              $this->email->initialize(self::$email_config);
              $this->email->from($user_data['email'], $user_data['surename']);
              $this->email->to($user_data['email']);
              $this->email->subject('New Reseller Registration - ' . $req->data['name']);
              if(!empty($payslip_file)) $this->email->attach($payslip_file);
              $msg = $chtml->read('resellers/html/admin_email', array(
                'reseller_name' => $reseller_data['name'],
                'reseller_phone' => $reseller_data['phone_wa'],
                'reseller_email' => $reseller_data['email'],
                'reseller_register_date' => $reseller_data['register_date']
              ));
              $this->email->message($msg);
              $this->email->send();

              // Send notification to reseller
              $this->email->initialize(self::$email_config);
              $this->email->from($user_data['email'], $user_data['surename']);
              $this->email->to($reseller_data['email']);
              $this->email->subject('New Reseller Registration - ' . $req->data['name']);
              if(!empty($payslip_file)) $this->email->attach($payslip_file);
              $msg = $chtml->read('resellers/html/reseller_email', array(
                'reseller_name' => $reseller_data['name'],
                'reseller_phone' => $reseller_data['phone_wa'],
                'reseller_email' => $reseller_data['email'],
                'reseller_register_date' => $reseller_data['register_date']
              ));
              $this->email->message($msg);
              $this->email->send();
            }
            
            // Redirect
            $continue = TRUE;
            redirect('/resellers/thanks');
          }
        }
        else
        {
          $this->data['invalid_input']['reseller_payslip'] = $this->payslip_model->get_error_message();
        }
      }
    }

    if(!$continue)
    {
      // Load page view
      $this->load->view('page_view', $this->data);
    }
  }

  /* Common functions */
  public static function url_create($id = 0) { return base_url().self::$page_.'/create'; }
  public static function url_update($id = 0) { return base_url().self::$page_.'/edit'.'/'.$id; }
  public static function url_delete($id = 0) { return base_url().self::$page_.'/delete'.'/'.$id; }
}
?>