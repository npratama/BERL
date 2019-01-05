<?php
class Payslips extends CI_Controller
{
  private static $page_ = 'payslip';

  public function index($id = 0)
  {
    // Check user login
    if(user_is_logged_in())
    {
      // Set data to pass to the view
      $data['page'] = self::$page_;
      $data['pageview'] = self::$page_.'/index';

      if($id != 0)
      {
        $data = $this->payslip_model->select($id);
        if(!empty($data))
        {
          header("Content-type: image/png");
          print base64_decode($data['data']);
          exit;
        }
      }
    }
  }
}
?>