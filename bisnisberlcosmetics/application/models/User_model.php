<?php
class User_model extends CI_Model
{
  private static $dbtable_ = 'tbl_user';
  private static $dbpkey_ = 'user_id';

  public function __construct()
  {
    $this->load->database();
  }

  public function select_user($name, $password)
  {
    $query = $this->db->get_where(self::$dbtable_, array(
      'user_name' => $name,
      'password' => $password
    ));
    return $query->row_array();
  }

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

  public function create($data)
  {
    return $this->db->insert(self::$dbtable_, $data);
  }

  public function update($id, $data)
  {
    $this->db->where(self::$dbpkey_, $id);
    return $this->db->update(self::$dbtable_, $data);
  }

  public function delete($id)
  {
    $this->db->where(self::$dbpkey_, $id);
    return $this->db->delete(self::$dbtable_);
  }
}
?>