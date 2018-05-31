<?php 
    include("data.php");
    $mydb = new db();    
    if(isset($_GET['component_id'])){
        $component = $mydb->getComponent($_GET['component_id']);
    }
?>
<style>
    #vmsTable th, #vmsTable td{
        font-size:11px;
    }
    #vmselected td , #vmselected th{
        font-size:11px;
    }
    .dt-buttons{
        float:left;
    }
</style>

<form class="form" id="add_task" action="save_item.php">
    <div class="row">
        <div class="col-sm-12">
            <div class="d-inline-flex p-2">
                <?php if(isset($_GET['project_id']) && $_GET['project_id'] != NULL ) : ?>

                    <input type="hidden" name="project_id" value="<?=$_GET['project_id']?>" />
                    <h4><?=$mydb->getProgettoName($_GET['project_id'])?> / </h4>

                <?php endif ?>
            </div>
            <div class="d-inline-flex p-2 col-sm-4">
                <?php if( isset($_GET['component_id']) && $_GET['component_id'] != NULL ) : ?>

                    <input type="hidden" name="component_id" value="<?=$_GET['component_id']?>" />

                <?php endif; ?>

                <input type="text" 
                       name="componentName" 
                       class="form-control form-control-sm" 
                       id="inputComponentName" 
                       placeholder="Component Name"
                       value="<?=(isset($_GET['component_id']))? $component->componente_nome : ''  ?>"
                />
            </div>
            <div class="d-inline-flex p-2">
                <button type="submit" id="buttonSend" class="btn btn-primary btn-sm">Salva</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <!-- <button type="button" class="btn btn-light btn-sm" >
                Aggiungi VM
            </button> -->
            <table id="vmselected" class="table table-bordered table-sm table-striped">
                <thead>
                    <tr>
                        <th style="font-size: 11px">
                            VM Selezionate  
                        </th>
                        <th>
                            <a href="#" class="hasTooltip btn btn-light btn-sm" data-toggle="modal" data-target="#vm-modal" data-placement="top" title="Aggiungi VMs">
                                <span class="oi oi-plus"></span>
                            </a>
                            <a href="#" class="hasTooltip btn btn-light btn-sm" id="removeAllSeleted" data-placement="top" title="rimuovi tutto">
                                <span class="oi oi-x"></span>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($component->vms) && count($component->vms) > 0) : 
                            foreach ($component->vms as $key => $oVM) : ?>
                            <tr>
                                <td>
                                    <input type='hidden' value='<?=$oVM->vm_id?>' name='vm_id[]' />
                                    <?=$oVM->vmname?>
                                </td>
                                <td>
                                    <a href='#' class='restore'><span class='oi oi-trash'></span></a>
                                </td>
                            </tr>
                    <?php 
                            endforeach; 
                        endif;?>
                </tbody>
            </table>
        </div>     
    </div>
</form>
<script>
  
        var interval;
        clearInterval(interval);

        $(".hasTooltip").tooltip();

        $(".restore").click(function(e){
            var vm_id =     $(this).closest("tr").find("input").val();
            $(this).closest("tr").remove();
        })
        
        $("#removeAllSeleted").click(function(){
            $("#vmselected tbody tr").remove();
        })

        // SUBMIT form
        $("#add_task").submit(function(e) {

            var action = $(this).attr("action"); // the script where you handle the form input.
            var postData = $(this).serialize()
            $.post( action , postData , function( data ) {
                $( "#main" ).html( data );
            });

            e.preventDefault(); // avoid to execute the actual submit of the form.
        });
        

</script> 