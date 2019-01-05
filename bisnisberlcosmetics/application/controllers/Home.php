<?php
class Home extends CI_Controller
{
  // Page data
  private $data = array(
    'page' => 'home'
  );

  public function index()
  {
    // Set data to pass to the view
    $this->data['pageview'] = $this->data['page'] . '/index';

    // Load view
    $this->load->view('page_view', $this->data);
  }
}
?>