<?php
class Payslip_model extends CI_Model
{
  private static $dbtable_ = 'tbl_payslip';
  private static $dbpkey_ = 'payslip_id';
  private $error_message;

  public function __construct()
  {
    $this->load->database();
    $this->error_message = '';
  }

  public function get_error_message()
  {
    return $this->error_message;
  }

  /**
   * Select data.
   */
  public function select($id = 0)
  {
    if($id === 0)
    {
        $query = $this->db->get(self::$dbtable_);
        return $query->result_array();
    }

    $query = $this->db->get_where(self::$dbtable_, array(self::$dbpkey_ => $id));
    return $query->row_array();
  }

  /**
   * Create data.
   * @return  $payslip_id Inserted payslip_id.
   */
  public function create($file)
  {
    $payslip_id = 0;
    $upload_ok = TRUE;

    // Set target path
    $target_dir = __DIR__ . "/../../assets/";
    $target_file = $target_dir . basename($file["name"]);

    // Get image file type
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file already exists
    if($upload_ok)
    {
      if(file_exists($target_file))
      {
        // $this->error_message = "Sorry, file already exists.";
        // $upload_ok = FALSE;
      }
    }

    // Check file size
    if($upload_ok)
    {  
      if($file["size"] > 500000)
      {
        $this->error_message = "Sorry, your file is too large.";
        $upload_ok = FALSE;
      }
    }

    // Allow certain file formats
    if($upload_ok)
    { 
      if($imageFileType != "jpg"
      && $imageFileType != "png"
      && $imageFileType != "jpeg"
      && $imageFileType != "gif")
      {
        $this->error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $upload_ok = FALSE;
      }
    }

    // Check if $uploadOk is set to 0 by an error
    if ($upload_ok)
    {
      // Gather all required data
      $name = $this->db->escape($file['name']);
      $mime = $this->db->escape($file['type']);
      $data = base64_encode(file_get_contents($file['tmp_name']));
      $size = intval($file['size']);

      // Set query data
      $query_data = array(
        'name' => $name,
        'mime' => $mime,
        'data' => $data,
        'size' => $size
      );

      // Insert into table
      $result = $this->db->insert(self::$dbtable_, $query_data);
      if($result)
      {
        // Get inserted id
        $payslip_id = $this->db->insert_id();
      }

      // Upload file
      // $upload_ok = move_uploaded_file($file["tmp_name"], $target_file);
      if(!$upload_ok)
      {
        $this->error_message = "Sorry, there was an error uploading your file.";
        $upload_ok = FALSE;
      }
    }

    return $payslip_id;
  }

  /**
   * Update data.
   *
   * public function update($id, $data)
   * {
   *   $this->db->where(self::$dbpkey_, $id);
   *   return $this->db->update(self::$dbtable_, $data);
   * }
   */

  /**
   * Delete data.
   */
  public function delete($id)
  {
    $this->db->where(self::$dbpkey_, $id);
    return $this->db->delete(self::$dbtable_);
  }

  /**
   * Get payslip temporary file.
   * It is formated as jpeg file.
   * @return  string  $payslip_file Payslip temporary file.
   */
  public function get_temp_file($id)
  {
    $payslip_file = '';

    // Check id
    if($id >= 0)
    {
      // Get payslip data
      $data = $this->payslip_model->select($id);
      $extension = array(
        "'image/png'" => '.png',
        "'image/jpg'" => '.jpg',
        "'image/bmp'" => '.bmp'
      );
      if(!empty($data))
      {
        // Get file content
        $file_content = base64_decode($data['data']);

        // Set file path
        $ext = array_key_exists($data['mime'], $extension) ? $extension[$data['mime']] : '.bin';
        $path = __DIR__ . '\\..\\data\\payslips\\payslip'.$ext;
        $path_temp = __DIR__ . '\\..\\data\\payslips\\payslip.jpeg';

        // Open file
        $myfile = fopen($path, "w");
        if(!empty($myfile))
        {
          // Write file
          fwrite($myfile, $file_content);

          // Close file
          fclose($myfile);

          // Convert to jpeg
          $jpeg_ok = img_to_jpeg($path, $path_temp, 90);

          // Set payslip file
          if($jpeg_ok)
          {
            $payslip_file = $path_temp;
          }
        }
      }
    }

    return $payslip_file;
  }
}
?>