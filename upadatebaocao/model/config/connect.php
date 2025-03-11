<?php
    $server = "localhost";
    $user="root";
    $pass="";
    $database="NHLSPORTS";
    $conn= new mysqLi($server,$user,$pass,$database);
    if($conn){
        mysqLi_query($conn,"SET NAMES 'utf8'");
    }else{
        echo "Kết nối thất bại";
    }
?>