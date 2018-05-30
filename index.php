<?php 
    // include("db_connect.php");
    include("data.php") ;
    $mydb = new db();
?>
<html>
<head>

    <link href="bootstrap-4.1.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/fontawesome-all.css" rel="stylesheet">
    
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
    <link rel="stylesheet" href="open-iconic/font/css/open-iconic-bootstrap.css" >
    <link rel="stylesheet" href="dataTables/datatables.min.css" >
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>VMWare Performance</title>
</head>
<body>
<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0 link2main" href="homepage.php">
        <span class="oi oi-home"></span> LOTTOMATICA
    </a>
    <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
    <ul class="navbar-nav px-3">
    <li class="nav-item text-nowrap">
        <a class="nav-link" href="#">Sign out</a>
    </li>
    </ul>
</nav>


<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2  d-none d-md-block bg-light sidebar"></nav>

        <main id="main" role="main" class="col-md-10 ml-sm-auto"></main>
    </div>
</div>


<!-- VMs MODAL -->
<div class="modal fade" id="vm-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="width: 90%;max-width: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aggiungi VM</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- //VMs MODAL -->
<!-- EDIT PRJ FORM MODAL -->
<div class="modal fade" id="edit-prj-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Project Name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="save_project.php">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Project Name:</label>
                        <input type='hidden' name='project-id' value='' />
                        <input type="text" class="form-control" id="project-name" name="project-name">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="save-prj-btn btn btn-primary">Save</button>
            </div>        
        </div>
    </div>
</div>
<!-- //EDIT PRJ FORM MODAL -->


<!-- DELETE PROJECT/COMPONENT MODAL -->
<div class="modal fade" id="delete-prj-comp-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rimuovi <span class="item-name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="delete_item.php">
                    <div class="form-group">
                    <div class="alert alert-warning" role="alert">
                        Confermi l'eliminazione di <span class="item-name"></span>
                    </div>
                        <input type='hidden' name='item-id' value='' />
                        <input type='hidden' name='prj-id' value='' />
                        <input type='hidden' name='action-function' value='' />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="done-btn btn btn-secondary" data-dismiss="modal">Annulla</button>
                <button type="button" class="delete-prj-comp-btn btn btn-primary">Conferma</button>
            </div>        
        </div>
    </div>
</div>
<!-- //DELETE PROJECT/COMPONENT MODAL -->


<script src="js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="bootstrap-4.1.0/js/bootstrap.bundle.min.js"  crossorigin="anonymous"></script>
<script src="dataTables/datatables.min.js"></script>


<script type="text/javascript" src="DateRangePicker/moment.min.js"></script>
<script type="text/javascript" src="DateRangePicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="DateRangePicker/daterangepicker.css" />

<script type="text/javascript" src="js/app.js"></script>
</body>
</html>