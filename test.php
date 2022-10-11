<?php
require_once 'db.php';

$query = "DELETE FROM `cart` WHERE 'pid' = '1' AND 'user_id' = '1'";
    if ($link->query($query) === TRUE) {
      return "Record deleted successfully";
    } else {
      return "Error deleting record: " . $link->error;
    }