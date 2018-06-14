<?php 
    // include("db_connect.php");
    include("data.php") ;
    $mydb = new db();
    session_start();
?>

<div class="sidebar-sticky">
    <!-- <div class="d-flex flex-column"> -->
    <div>
        <!-- <h5 class="text-muted pl-2" style="line-height:1;margin-bottom:0">Saved Project </h5> -->
        <a  href="#" class="ml-2  my-1 btn btn-info btn-round btn-sm"  title="Nuovo Progetto" data-toggle="modal" data-target="#edit-prj-modal" style="line-height:1;font-family:verdana;font-style: oblique;">
        <span class="oi oi-plus"></span> &nbsp;Nuovo Progetto 
        </a>
        <!-- <a  href="#" class="m-2 callpsajax">test ps ajax</a> -->
    </div>

    <ul class="nav flex-column mt-2">
        <?php foreach ($mydb->getProgetti($_SESSION['user']['userid']) as $key => $value) { ?>
            <li class="nav-item">
                <div class="d-flex">
                    <a href="#" class="p-1 pl-2 caret" data-toggle="collapse" data-target="#prjCollapse<?=$key?>">
                        <span class="oi oi-caret-right text-muted" style="line-height:1.5"></span>
                    </a>
                    <a class="p-1 nav-link link2main" id="project<?=$value['progetto_id']?>" href="show_project.php?id_project=<?=$value['progetto_id']?>"  >
                        <?=$value['progetto_nome']?>
                        <?php
                            if($value['status'] == '2'){
                                echo '<span class="text-danger oi oi-warning"></span>';
                            }else if ($value['status'] == '1'){
                                echo '<span class="text-warning oi oi-warning"></span>';
                            }
                        ?>
                    </a>
                </div>
                <div id="prjCollapse<?=$key?>" class="submenuCollapsible collapse pl-5">
                    <ul  class="nav flex-column sub-menu">
                    <?php foreach ($value['componenti'] as $k => $v) { ?>
                        <li class="nav-item">
                            <a id="component<?=$v['componente_id']?>" class="nav-link p-1 link2main" href="show_component.php?id=<?=$v['componente_id']?>">
                                <?=$v['componente_nome']?>
                                <?php
                                    if($v['status'] == '2'){
                                        echo '<span class="text-danger oi oi-warning"></span>';
                                    }else if ($v['status'] == '1'){
                                        echo '<span class="text-warning oi oi-warning"></span>';
                                    }
                                ?>
                            </a>
                        </li>
                    <?php }?>
                    </ul>
                </div>
            </li>
        <?php } ?>
    </ul>
</div>
<script>
$(document).ready(function(){
    $('.submenuCollapsible').on('show.bs.collapse', function () {
        $(this).closest(".nav-item").find(".oi-caret-right").removeClass().addClass("oi oi-caret-bottom")
    })
    $('.submenuCollapsible').on('hide.bs.collapse', function () {
        $(this).closest(".nav-item").find(".oi-caret-bottom").removeClass().addClass("oi oi-caret-right text-muted")
    })
})
</script>