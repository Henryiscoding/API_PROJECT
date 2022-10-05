<?php

$link = mysqli_connect("localhost", "root", "", "shop_db");
 
if($link === false){
    echo "no connection";
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

