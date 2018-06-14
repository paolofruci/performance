<?php 

    include("data.php") ;
    $mydb = new db();
    $array = array(
        "data" => array()
    );


    if(isset($_GET['component_id'])){
        foreach( $mydb->getVM4Component($_GET['component_id']) as $key => $value){
            $array['data'][] = array(
                "vm_id"         => $value->vm_id,
                "vmname"        => $value->vmname,
                "powerstate"    => $value->powerstate,
                "badge"         => $value->badge,
                "ip"            => $value->ip,
                "ncpu"          => $value->ncpu,
                "memorymb"      => $value->memorymb,
                "mem_max_perc"  => array("value" => $value->mem_max_perc, "className" => $mydb->getStatusClassColor("mem",$value->mem_max_perc)),
                "cpu_max_perc"  => array("value" => $value->cpu_max_perc, "className" => $mydb->getStatusClassColor("cpu",$value->cpu_max_perc)),
                "disk_max_io"   => array("value" => $value->disk_max_io,  "className" => $mydb->getStatusClassColor("disk",$value->disk_max_io)),
                "net_max_io"    => array("value" => $value->net_max_io,   "className" => $mydb->getStatusClassColor("net",$value->net_max_io))
            );
        }        
    }
    echo json_encode($array);
?>