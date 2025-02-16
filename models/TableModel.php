<?php

require_once '../conf/conf.php';


class TableModel
{
  private $conn;

  public function __construct()
  {
    global $conn;
    $this->conn = $conn;
  }

  public function getAllTables()
  {
    $sql = "SELECT * FROM tables";
    $result = $this->conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
  }
}
