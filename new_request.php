<?php
    include("data.php");
    $mydb = new db();

    // STO RICHIEDENDO PERFORMANCE PER UN COMPONENTE
    if( isset($_POST['action']) && $_POST['action'] == "newRequestperComponent" ){
        if( isset($_POST['parentid']) ){
            $component_id   = $_POST['parentid'] ;
            $action = $_POST['action'];
            $componentData  = $mydb->getComponent($component_id) ;
            $component_name = $componentData->componente_nome ;
            $project_id     = $componentData->progetto_id ;
            $project_name   = $componentData->progetto_nome ;
            $select_list    = array() ;
            foreach ($componentData->vms as $key => $value) {
                $select_list[$value->vm_id] = $value->vmname;
            }
        }
    }
    // STO RICHIEDENDO PERFORMANCE PER UN PROGETTO
    elseif (isset($_POST['action']) && $_POST['action'] == "newRequestperProject") {
        if( isset($_POST['parentid']) ){
            $project_id = $_POST['parentid'] ;
            $action = $_POST['action'];
            $component_name = "COMPLETE" ;
            $project_name   = $mydb->getProgettoName($project_id) ;
            $component_id = null;
            $select_list    = array() ;
            foreach ($mydb->getProjectComponents($project_id) as $key => $value) {
                $select_list[$value['componente_id']] = $value['componente_nome'] . " <small>(".count(explode(',',$value['vms_id'])) . " vms)</small>";
            }
        }
    }
?>


<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">New Performance Request</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="requestBody">
        <form id="add_request" action="save_request.php">
            <input type="hidden" name="action" value="<?=$action?>" />
            <input type="hidden" name="componentID" value="<?=$component_id?>" />
            <input type="hidden" name="componentName" value="<?=$component_name?>" />
            <input type="hidden" name="projectName" value="<?=$project_name?>" />
            <input type="hidden" name="projectID" value="<?=$project_id?>" />
            <div class="form-group">
                <label for="">Request Name</label>
                <input type="text"  name="requestName" class="form-control" value="<?=$project_name." - ".$component_name?>" />
            </div>
            <div class="form-group">
                <label for="">Interval</label>
                <input type="text" readonly name="IntervalTime" class="datetime form-control"  required/>
            </div>
            <div class="form-group">
                <?php foreach ($select_list as $key => $value) : ?>                
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="select_list[]" value="<?=$key?>" id="Check<?=$key?>" checked />
                    <label class="form-check-label" for="Check<?=$key?>">
                        <?=$value?>
                    </label>
                </div>
                <?php endforeach ?>
            </div>
            <!-- <pre>PERFORMANCE TEMPORANEAMENTE NON DISPONIBILI</pre> -->
        </form>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="button" id="requestSubmitButton" class="btn btn-primary">Send Request  </button>
</div>

<script>
   
    var daterangepicker = $('input.datetime').daterangepicker({
        timePicker: true,
        timePicker24Hour:true,
        autoUpdateInput: false,
        showCustomRangeLabel : true,
        maxDate:  moment(),
        minDate: moment().subtract(30, 'days'),
        ranges: {
            'Last Hour': [moment().subtract(1, 'Hours'), moment()],
            'Last 24 Hours': [moment().subtract(24, 'Hours'), moment()],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()]
        },
        // startDate: moment().startOf('hour'),
        // endDate: moment().startOf('hour').add(32, 'hour'),
        locale: {
            format: 'DD/MM/YYYY HH:mm',
            cancelLabel: 'Clear'
        }
    });
    $('input.datetime').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm') + ' - ' + picker.endDate.format('DD/MM/YYYY HH:mm'));
    });

    $('input.datetime').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });


        
// SUBMIT Request Form
    $("#requestSubmitButton").click(function(){
        console.log($("input[required]").val().length)
        if( $("input[required]").val().length == 0 ){
            $("input[required]").focus();
            alert("Interval required!");
        }else{
            $("#add_request").submit();
        } 
    });
    $("#add_request").submit(function(e) {
        e.preventDefault();
        var action = $(this).attr("action") ;
        var postData = $(this).serialize();
        $.post( action , postData , function( data ) {
            $( ".requestBody" ).html( data );
            $("#requestSubmitButton").hide();
        });
    });
</script>
