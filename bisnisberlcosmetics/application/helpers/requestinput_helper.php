<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * RequestInput Helper Class
 * Handle POST request, validate inputs, and store values to model's properties.
 */
class RequestInput
{
  var $inputs;        // Array where POST request's key as key, and model properties as value. 
  var $req_body;      // Request body
  var $data;          // Array to store request body's value as model's properties
  var $field_name;    // Input field name
  var $invalid_input; // Invalid input message
  var $validity;      // Input validation flag
  var $req_type;

  /**
   * Constructor
   * @param var $inputs   Array where POST request's key as key, and model properties as value.
   */
  function __construct($inputs, $req_type = array())
  {
    // Initialize
    $this->inputs = $inputs;
    $this->req_type = $req_type;

    // Parse request body
    $this->collect_req_body();

    // Get data from request body
    $this->collect_data();
  }

  /**
   * Collect POST request body.
   */
  private function collect_req_body()
  {
    foreach($this->inputs as $key => $val)
    {
      $req_type = 'POST';

      // Check request type
      if(array_key_exists($key, $this->req_type))
      {
        if($this->req_type[$key] == 'FILE') $req_type = 'FILE';
      }

      // Set default
      $this->req_body[$key] = '';

      if($req_type == 'FILE')
      {
        // Check if request body key is exitst
        if(isset($_FILES[$key]))
        {
          // Get request body value from FILE
          $this->req_body[$key] = $_FILES[$key]['name'];
        }
      }
      else if($req_type == 'POST')
      {
        // Check if request body key is exitst
        if(array_key_exists($key, $_POST))
        {
          // Get request body value from POST
          $this->req_body[$key] = $_POST[$key];
        }
      }
      else
      {
        // Set blank
        $this->req_body[$key] = '';
      }

      // Initialize validation message and flag
      $this->invalid_input[$key] = '';
      $this->validity[$key] = TRUE;
    }
  }

  /**
   * Store request body's value as model's properties.
   */
  private function collect_data()
  {
    foreach($this->inputs as $key => $val)
    {
      $this->data[$val] = $this->req_body[$key];
    }
  }

  /**
   * Import request body from data.
   * @param var $data Data array.
   */
  function import_req_body($data)
  {
    foreach($this->inputs as $key => $val)
    {
      $this->body[$key] = '';
      if(!empty($data[$val])) $this->body[$key] = $data[$val];
    }
  }

  /**
   * Validate inputs.
   * @param string  $input_name Input name (request body's key).
   * @param string  $field_name Input field name.
   * @param var     $rules  Validation rules.
   * 
   * Rules:     Params:       Valid condition:
   * required   -             Not empty.
   * length     max length    Less than max length.
   * alphanum   -             Contains alpha numeric characters only.
   * email      -             Match with valid email format.
   * name       -             Contains alpha and space only.
   * url        -             Match with valid url format.
   * phone      -             Contains number or '+' or '-' only.
   */
  function check($input_name, $field_name, $rules)
  {
    $this->field_name[$input_name] =  $field_name;

    foreach($rules as $key => $val)
    {
      if(($this->validity[$input_name] == TRUE) && ($key == 'required')) $this->check_empty($input_name);
      if(($this->validity[$input_name] == TRUE) && ($key == 'length')) $this->check_length($input_name, $val);
      if(($this->validity[$input_name] == TRUE) && ($key == 'numeric')) $this->check_numeric($input_name);
      if(($this->validity[$input_name] == TRUE) && ($key == 'alphanum')) $this->check_alphanum($input_name);
      if(($this->validity[$input_name] == TRUE) && ($key == 'email')) $this->check_email($input_name);
      if(($this->validity[$input_name] == TRUE) && ($key == 'name')) $this->check_name($input_name);
      if(($this->validity[$input_name] == TRUE) && ($key == 'url')) $this->check_url($input_name);
      if(($this->validity[$input_name] == TRUE) && ($key == 'phone')) $this->check_phone($input_name);
    }
  }

  /**
   * Check if all validation results are TRUE.
   * @return  TRUE  All validations are TRUE.
   * @return  FALSE There is FALSE validation.
   */
  function is_valid()
  {
    $valid = TRUE;

    foreach($this->validity as $key => $val)
    {
      $valid &= $val;
    }

    return $valid;
  }

  /**
   * Set invalid input.
   * @param string  $input_name  Input name.
   * @param string  $msg  Invalid input message.
   */
  private function set_invalid($input_name, $msg)
  {
    $this->invalid_input[$input_name] = $this->field_name[$input_name].' '.$msg;
    $this->validity[$input_name] = FALSE;
  }

  /**
   * Check empty.
   */
  private function check_empty($input_name)
  {
    if(empty($this->req_body[$input_name]))
    {
      $this->set_invalid($input_name, 'tidak boleh kosong');
    }
  }

  /**
   * Check length.
   */
  private function check_length($input_name, $max_len)
  {
    if(strlen($this->req_body[$input_name]) > $max_len)
    {
      $this->set_invalid($input_name, 'tidak boleh lebih dari '.$max_len.' karakter');
    }
  }

  /**
   * Check name.
   */
  private function check_name($input_name)
  {
    preg_match('/^[A-z ]+$/', $this->req_body[$input_name], $match);
    if(empty($match))
    {
      $this->set_invalid($input_name, 'hanya boleh huruf atau spasi');
    }
  }

  /**
   * Check numeric.
   */
  private function check_numeric($input_name)
  {
    if(!preg_match('/^[0-9]+$/', $this->req_body[$input_name]))
    {
      $this->set_invalid($input_name, 'hanya boleh angka');
    }
  }

  /**
   * Check alpha numeric.
   */
  private function check_alphanum($input_name)
  {
    if(!preg_match('/^[A-z0-9]+$/', $this->req_body[$input_name]))
    {
      $this->set_invalid($input_name, 'hanya boleh alpha numerik karakter');
    }
  }

  /**
   * Check email.
   */
  private function check_email($input_name)
  {
    if(!filter_var($this->req_body[$input_name], FILTER_VALIDATE_EMAIL))
    {
      $this->set_invalid($input_name, 'format tidak vaild');
    }
  }

  /**
   * Check phone.
   */
  private function check_phone($input_name)
  {
    if(!preg_match("/^([0-9]|\-|\+)+$/", $this->req_body[$input_name]))
    {
      $this->set_invalid($input_name, 'format tidak vaild');
    }
  }
}

?>
