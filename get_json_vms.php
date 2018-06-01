<?php
 
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
// DB table to use
$table = 'vms';
 
// Table's primary key
$primaryKey = 'vm_id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple

$wherecondition = '';
if( isset($_GET['exclude']) && is_array($_GET['exclude']) && count($_GET['exclude']) > 0 ){
    $vms_id_to_exclude = implode(",",$_GET['exclude']);
    $wherecondition =  "vm_id NOT IN ($vms_id_to_exclude)";
}



$columns = array(
    array( 'db' => 'vm_id', 'dt' => 'vm_id' ),
    array( 'db' => 'vmname', 'dt' => 'vmname' ),
    array( 'db' => 'vcenter',  'dt' => 'vcenter' ),
    array( 'db' => 'ip',  'dt' => 'ip' ),
    array( 'db' => 'project',   'dt' => 'project' ),
    array( 'db' => 'functionality',     'dt' =>'functionality' ),
    array( 'db' => 'env',     'dt' =>'env' )
    // array(
    //     'db'        => 'start_date',
    //     'dt'        => 4,
    //     'formatter' => function( $d, $row ) {
    //         return date( 'jS M y', strtotime($d));
    //     }
    // ),
    // array(
    //     'db'        => 'salary',
    //     'dt'        => 5,
    //     'formatter' => function( $d, $row ) {
    //         return '$'.number_format($d);
    //     }
    // )
);
 
// SQL server connection information
$sql_details = array(
    'user' => 'vmware',
    'pass' => 'Password1',
    'db'   => 'vmware',
    'host' => 'localhost'
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( 'DataTables/ssp.class.php' );
 
echo json_encode(
    // SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
    SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns,$wherecondition )
);