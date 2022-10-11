<?php

$db_conn = mysqli_connect("localhost", "root", "", "shop_db");
 
if($db_conn === false){
    echo "no connection";
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

