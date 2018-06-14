<?php 
    include("data.php");
    $mydb = new db();    
    if(isset($_GET['component_id'])){
        $component_id=$_GET['component_id'];
        $component = $mydb->getComponent($_GET['component_id']);
    }else{
        $component_id='null';
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

<form class="form" id="form_edit_component" action="save_component.php">

    <input type="hidden" name="project_id" value="<?=$_GET['project_id']?>" />
    <?php if( isset($_GET['component_id']) && $_GET['component_id'] != NULL ) : ?>
    <input type="hidden" name="component_id" value="<?=$_GET['component_id']?>" />
    <?php endif; ?>

    <div class="d-flex flex-md-nowrap align-items-start align-items-stretch">
        
        <h4><?=$mydb->getProgettoName($_GET['project_id'])?></h4>
        
        <div class="border-left ml-2 pl-2 w-50">
            <input type="text" 
                    name="componentName" 
                    class="form-control" 
                    id="inputComponentName" 
                    placeholder="Component Name"
                    value="<?=(isset($_GET['component_id']))? $component->componente_nome : ''  ?>"
            />
        </div>
        
        <div class="border-left  pl-2 ml-auto">
            <button type="submit" id="buttonSend" class="btn btn-primary">Salva</button>
            <a href="<?=(isset($_GET['component_id']))? "show_component.php?id=".$_GET['component_id'] : 'show_project.php?id_project='.$_GET['project_id']  ?>" id="buttonAnnulla" class="link2main btn btn-light">
                Annulla
            </a>
        </div>
    
    </div>

    <hr>

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
        $(document).trigger("loadpage_edit_component" , [ <?=$_GET['project_id']?> , <?=$component_id?>] )   
</script> 