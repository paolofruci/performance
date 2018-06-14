<?php 
    include("data.php") ;
    $mydb = new db();
    session_start();

?>
<!-- <div class="d-flex align-items-start align-items-stretch">
    <h4>Lista Progetti</h4>
</div> -->
<!-- <hr/> -->
<div class="d-flex flex-wrap">
    
    <?php foreach ($mydb->getProgetti($_SESSION['user']['userid']) as $key => $value) :
            $nVm = count(explode(',',$mydb->getVM4Project($value['progetto_id']))) ;
    ?>
    <div class="p-2 item-container" style="position:relative">
        <a href="show_project.php?id_project=<?=$value['progetto_id']?>" 
            class="btn btn-light m-2 p-2 link2main
            <?php 
                    if($value['status'] == "1") 
                        echo "btn-outline-warning" ;
                    else if ($value['status'] == "2") 
                        echo "btn-outline-danger"  ;   
                ?> 
            ">
            <h5><?=$value['progetto_nome']?></h5>
            <small>Componenti <?=count($value['componenti'])?> </small> <br>
            <small>Virtual Machines <?=$nVm?> </small>
        </a>
        <a href="#" 
                title="rimuovi questo progetto"
                data-function="deleteProject"
                data-itemid = "<?=$value['progetto_id']?>"
                data-prjid  = "<?=$value['progetto_id']?>"
                data-itemname = "<?=$value['progetto_nome']?>"
                data-toggle="modal" 
                data-target="#delete-prj-comp-modal"  
            style="color:black;text-decoration:none;position:absolute;top:2;right:2" 
            class="p-1 d-none delete-btn-float">
            <span class="oi oi-circle-x"></span>
        </a>
    </div>


    <?php endforeach ?>

</div>

<hr>
    <div class="d-flex flex-row justify-content-between mb-4">
        <h6 class="text-muted">All Performance History</h6>
        <!-- <a href="new_request.php" class="btn btn-secondary btn-sm getPerformance" data-toggle="modal" data-target="#genericPerfModal">
            <span class="oi oi-bar-chart"></span> New performance request
        </a> -->
    </div>

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
    /* Request History table whit datatable */
    var table = $('#Requests-history-prj').DataTable( {

        "ajax": {
            url : 'historyRequests.php' 
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