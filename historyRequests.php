<?php 

    include("data.php") ;
    $mydb = new db();
    $HistoryRequests = array(
        "data" => array()
    );
    if(isset($_GET['component_id']))
    {
        $HistoryRequests['data'] = $mydb->getHistoryRequest( $_GET['component_id'] );
    }
    elseif (isset($_GET['project_id'])) 
    {
        $components = $mydb->getProjectComponents($_GET['project_id']);
        foreach ($components as $key => $value) {
            $componentRequest = $mydb->getHistoryRequest($value['componente_id']) ;
            foreach ( $componentRequest as $k => $v) {
                $HistoryRequests['data'][] = $v;
            }
        }
    }
    else
    {
        $HistoryRequests['data'] = $mydb->getHistoryRequest();
    }
    $HistoryRequests['draw'] = 1;
    $HistoryRequests['recordsTotal'] = count( $HistoryRequests['data'] );
    $HistoryRequests['recordsFiltered'] = count( $HistoryRequests['data'] );

    echo json_encode($HistoryRequests);
?>