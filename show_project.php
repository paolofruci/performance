<?php 

include("data.php") ;
$mydb = new db();
$id = $_GET['id_project']; 
$projectName = $mydb->getProgettoName($id);
$components = $mydb->getProjectComponents($id);
$aComponentsIDs = array();
foreach ($components as $key => $value) {
    $aComponentsIDs[] = $value['componente_id'];
}

?>
<style>
    table th, table td{
        font-size:11px;
    }
</style>

<div class="d-flex align-items-start align-items-stretch">
    <h4><?=$projectName?></h4>
    <div class="dropdown border-left  ml-2 pl-2 pt-1">
        <a class="dropdown-toggle" href="#" id="dropdownMenuLink" data-toggle="dropdown" >
            Actions
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a  href="#" 
                data-toggle="modal" 
                data-target="#edit-prj-modal"
                data-prjname="<?=$projectName?>"
                data-prjid="<?=$id?>"
                class="dropdown-item" >
                    <span class="oi oi-pencil"></span> Rename
            </a>
            <a href="#" 
                title="rimuovi questo progetto"
                data-function="deleteProject"
                data-itemid="<?=$id?>"
                data-prjid="<?=$id?>"
                data-itemname="<?=$projectName?>"
                data-toggle="modal" 
                data-target="#delete-prj-comp-modal" 
                class="dropdown-item">
                    <span class="oi oi-circle-x"></span> Delete
            </a>
            <div class="dropdown-divider"></div>
            <a href="edit_component.php?project_id=<?=$id?>" class="link2main dropdown-item">
                Nuovo Componente
            </a>
        </div>
    </div>
</div>
<hr/>
<div class="d-flex flex-wrap">
    <?php foreach($components as $key => $component) : ?>
    <div class="p-2 item-container" style="position:relative">
        <a href="show_component.php?id=<?=$component['componente_id']?>" 
            class="btn btn-light 
                <?php 
                    if($component['status'] == "warning") 
                        echo "btn-outline-warning" ;
                    else if ($component['status'] == "critical") 
                        echo "btn-outline-danger"  ;   
                ?> 
            m-2 p-2 link2main">
            <h5><?=$component['componente_nome']?></h5>
            <small><?=count(explode(',',$component['vms_id']))?> Virtual Machines</small>
        </a>
        <a href="#" 
            title="rimuovi componente"
            data-function="deleteComponent"
            data-itemid="<?=$component['componente_id']?>"
            data-itemname="<?=$component['componente_nome']?>"
            data-prjid="<?=$id?>"
            data-toggle="modal" 
            data-target="#delete-prj-comp-modal" 
            style="color:black;text-decoration:none;position:absolute;top:2;right:2" 
            class="p-1 d-none delete-btn-float">
            <span class="oi oi-circle-x"></span>
        </a>
    </div>
    <?php endforeach; ?>
</div>
<hr>
<h6 class="text-muted">Performance History</h6>
<div class="row">
    <div class="col-sm-12" id="history-request">
        <table id="Requests-history-prj" class="display" style="width:100%">
            <thead class="">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Task</th>
                    <th scope="col">Start Time</th>
                    <th scope="col">End Time</th>
                    <th scope="col">Interval</th>
                    <th scope="col">type</th>
                    <th scope="col">status</th>
                    <th scope="col">Report</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
 
<script>

    var table = $('#Requests-history-prj').DataTable( {

        "ajax": {
            url : 'historyRequests.php' ,
            data : {  "project_id" : <?=$id?> }
        }, 
        "columns": [
            { "data": "request_id" },
            { "data": "request_name" },
            { "data": "start_time" },
            { "data": "end_time" },
            { "data": "interval_time" },
            { "data": "type" },
            { "data": "status" },
            { "data": "request_id"}
        ],
        "order": [[ 0, "desc" ]],
        "columnDefs": [
            { 
                "targets": 7,
                "render": function ( data, type, row ) {
                            return "<a target='_blank' href='/performance_report/"+data+"/stats_"+data+".html' >HTML</a> &nbsp; \
                                    <a target='_blank' href='/performance_report/"+data+"/stats_"+data+".zip' >ZIP</a>";
                        }
            }
        ]
    } );
    var interval;
    clearInterval(interval);
    interval = setInterval( function () {
        table.ajax.reload(null, false);
    }, 10000 );


</script>