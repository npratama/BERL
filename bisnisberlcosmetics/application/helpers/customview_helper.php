<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// require_once(APPPATH.'helpers/html/form.php');
function html($e, $s1 = '', $s2 = '', $s3 = '')
{
  $html = file_get_contents(APPPATH.'helpers/html/'.$e.'.html');

  $html = str_replace('{$1}', $s1, $html);
  $html = str_replace('{$2}', $s2, $html);
  $html = str_replace('{$3}', $s3, $html);

  echo $html;
}

/**
 * CustomView Helper Class
 * Build html components.
 */
class CustomView
{
  var $folder;
  var $data;

  /**
   * Constructor
   */
  function __construct()
  {
    $this->folder = '';
  }

  /**
   * Set components folder.
   * @param string  $folder Folder name.
   */
  function set_folder($folder)
  {
    $this->folder = $folder;
  }

  /**
   * Components data.
   * @param var  $data Data.
   */
  function set_data($data)
  {
    $this->data = $data;
  }

  /**
   * Read and print HTML components.
   * @param var $file View source file.
   * @param var $data Elements data.
   */
  function render($file, $data = '')
  {
    echo $this->read($file, $data);
  }

  /**
   * Read HTML components.
   * @param var $file View source file.
   * @param var $data Elements data.
   */
  function read($file, $data = '')
  {
    $folder = ($this->folder == '') ? '' : $this->folder.'/';

    $html = file_get_contents(VIEWPATH.$folder.$file.'.html');

    if(!empty($data))
    {
      foreach($data as $key => $val)
      {
        $html = str_replace('{'.$key.'}', $val, $html);
      }
    }

    if(!empty($this->data))
    {
      foreach($this->data as $key => $val)
      {
        $html = str_replace('{'.$key.'}', $val, $html);
      }
    }

    return $html;
  }
}

/**
 * CustomView global object.
 */
$cview = new CustomView();

/**
 * CustomForm Helper Class
 * Build html form components.
 */
class CustomForm
{
  var $cv;
  var $mode;
  var $input_value;
  var $invalid_input;

  /**
   * Constructor
   */
  function __construct()
  {
    $this->cv = new CustomView();
    $this->cv->set_folder('templates/form');
    $this->mode = 'render';
  }

  /**
   * Set render mode.
   * @param string  $mode  Render mode.
   */
  function set_mode($mode)
  {
    $this->mode = $mode;
  }

  function set_input($input_value, $invalid_input)
  {
    $this->input_value = $input_value;
    $this->invalid_input = $invalid_input;
  }

  function get_input_value($val)
  {
    $value = '';

    if(!empty($this->input_value))
    {
      if(array_key_exists($val, $this->input_value))
      {
        $value = $this->input_value[$val];
      }
    }

    return $value;
  }

  function get_input_msg($val)
  {
    $value = '';

    if(!empty($this->invalid_input))
    {
      if(array_key_exists($val, $this->invalid_input))
      {
        $value = $this->invalid_input[$val];
      }
    }

    return $value;
  }

  /**
   * Render HTML components.
   * @param var $file.
   */
  function render($file, $data = '')
  {
    $html = '';

    if($this->mode == 'render')
    {
      $this->cv->render($file, $data);
    }
    else
    {
      $html = $this->cv->read($file, $data);
    }

    return $html;
  }

  /**
   * Form open tag.
   */
  function open($action)
  {
    return $this->render('open', array('action' => $action));
  }

  /**
   * Form close tag.
   */
  function close()
  {
    return $this->render('close');
  }

  /**
   * Form message.
   */
  function msg($type, $msg = '', $name = '')
  {
    $html = '';
    
    if(empty($msg))
    {
      $msg = $this->get_input_msg($name);
    }
    if(!empty($msg))
    {
      $html .= $this->render('msg_'.$type, array('message' => $msg));
    }

    return $html;
  }

  /**
   * Message danger.
   */
  function msg_danger($msg = '', $name = '')
  {
    return $this->msg('danger', $msg, $name);
  }

  /**
   * Message primary.
   */
  function msg_primary($msg = '', $name = '')
  {
    return $this->msg('primary', $msg, $name);
  }
  
  /**
   * Form input.
   */
  function input($type, $name, $placeholder = '', $value = '', $invalid_msg = '')
  {
    $html = '';
    
    $html .= $this->msg_danger($invalid_msg, $name);
    if(empty($value))
    {
      $value = $this->get_input_value($name);
    }

    $html .= $this->render('input_' . $type, array(
      'name' => $name,
      'placeholder' => $placeholder,
      'value' => $value
    ));

    return $html;
  }

  /**
   * Input type text.
   */
  function input_text($name, $placeholder = '', $value = '', $invalid_msg = '')
  {
    return $this->input('text', $name, $placeholder, $value, $invalid_msg);
  }

  /**
   * Input type password.
   */
  function input_pswd($name, $placeholder = '', $value = '', $invalid_msg = '')
  {
    return $this->input('pswd', $name, $placeholder, $value, $invalid_msg);
  }

  /**
   * Input type fle.
   */
  function input_file($name, $placeholder = '', $value = '', $invalid_msg = '')
  {
    $html = '';
    
    $html .= $this->msg_danger($invalid_msg, $name);
    if(empty($value))
    {
      $value = $this->get_input_value($name);
    }

    $html .= $this->render('input_file', array(
      'name' => $name,
      'placeholder' => $placeholder,
      'value' => $value
    ));

    return $html;
  }

  /**
   * Input type submit.
   */
  function input_submit($value = 'Submit')
  {
    return $this->render('btn_submit', array('value' => $value));
  }
}

?>
