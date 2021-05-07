<?php
    //getting all files
    include_once('headers.php');
    include_once('Data.php');
    include_once('Products.php');

    //Running my main method and passing all data that is gonna be used in Api
    Products::main($data);