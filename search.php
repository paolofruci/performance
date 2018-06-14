<?php 

    include("data.php") ;
    $mydb = new db();
    $search = $_GET['search'];
    $filter = array('vms.ip like "%'.$search.'%"' , 'vms.vmname like "%'.$search.'%"');
    $result = $mydb->getAllVMs($filter);
    // echo "<pre>";
    // var_dump($result);
    $json = json_encode($result);
if ($json)
    echo $json;
else
    echo json_last_error_msg();
