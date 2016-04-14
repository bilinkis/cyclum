@extends('app')

@section('content')
    <nav id="tasks-navbar">
        <div class="nav-wrapper">
            <a href="#!" class="brand-logo">Logo Nuestro Proyecto</a>
            <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
                <li><a href="">teamname</a></li>
                <li><a href="">name</a></li>
                <li><a href="/auth/logout">Cerrar sesión</a></li>
            </ul>
            <ul class="side-nav" id="mobile-demo">
                <li><a href="">asdasd</a></li>
                <li><a href="">asdasd</a></li>
                <li><a href="/auth/logout">Cerrar sesión</a></li>
            </ul>
        </div>
    </nav>
    <br>
    <br>
    <br>
    <div class="row">
        <div id="user-tab" class="col s12">
            <ul class="tabs">
                <li onclick="changeTabs('task')" class="tab col s6"><a href="" id="tasks-tab" class="active">Tareas</a></li>
                <li onclick="changeTabs('team')" class="tab col s6"><a href="" id="team-tab" class="">Equipo</a></li>
            </ul>
        </div>
        <div id="tasks" class="row" style="display:block;">
            <div class="col s12 m12 l3 tasks-droppable">
                <div id="tasks-things-todo" class="card-panel teal tasks-container grey lighten-2 hoverable">
                    <div class="tasks-header black-text bold">Cosas a hacer</div>
                    <div class="tasks-content">
                        <div class="card-panel teal tasks-card hoverable" data-task-id="1">
                            <span class="white-text truncate tooltipped" data-position="top" data-delay="50" data-tooltip="textotextotextotextotextotextotextotextotextotexto">textotextotextotextotextotextotextotextotextotexto<strong><i class="material-icons right tasks-add-people" onclick="openMod('task-add-people-modal');">person_add</i></strong></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12 m12 l3 tasks-droppable">
                <div id="tasks-doing" class="card-panel teal tasks-container grey lighten-2 hoverable">
                    <div class="tasks-header black-text bold">Haciendo</div>
                    <div class="tasks-content">
                        <div class="card-panel teal tasks-card hoverable" data-task-id="2">
                            <span class="white-text">tesxt<strong></strong></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12 m12 l3 tasks-droppable">
                <div id="tasks-finished" class="card-panel teal tasks-container grey lighten-2 hoverable">
                    <div class="tasks-header black-text bold">Terminado</div>
                    <div class="tasks-content">
                        <div class="card-panel teal tasks-card hoverable" data-task-id="3">
                            <span class="white-text">asdasdasdd<strong></strong></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12 m12 l3 tasks-droppable">
                <div id="tasks-validating" class="card-panel teal tasks-container grey lighten-2 hoverable">
                    <div class="tasks-header black-text bold">Validando</div>
                    <div class="tasks-content">
                        <div class="card-panel teal tasks-card hoverable" data-task-id="4">
                            <span class="white-text">textt<strong><i class="material-icons right" onclick="">trending_up</i></strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="team" class="row container" style="display:none;">
            <ul class="collection">
                <a href="#!" class="collection-item">1</a>
                <a href="#!" class="collection-item">2</a>
                <a href="#!" class="collection-item">3</a>
                <a href="#!" class="collection-item">4</a>
            </ul>
        </div>
    </div>
    <div class="fixed-action-btn" style="bottom: 45px; right: 24px;">
        <a class="btn-floating btn-large red"><i class="large material-icons">mode_edit</i></a>
        <ul>
            <li onclick="openMod('add-task-modal')"><a class="btn-floating blue btn tooltipped" data-position="left" data-delay="50" data-tooltip="Agregar tarea"><i class="material-icons">add</i></a></li>
            <li onclick="openMod('add-worker-modal')"><a class="btn-floating yellow darken-1 btn tooltipped" data-position="left" data-delay="150" data-tooltip="Agregar trabajador"><i class="material-icons">person_add</i></a></li>
            <!--<li><a class="btn-floating green btn tooltipped" data-position="left" data-delay="150" data-tooltip="Perfil"><i class="material-icons">person</i></a></li>
            <li><a class="btn-floating blue btn tooltipped" data-position="left" data-delay="150" data-tooltip="Ajustes"><i class="material-icons">settings</i></a></li>-->
        </ul>
    </div>
    <div id="delete-task" class="center tasks-delete" style="display:none;"><i class="medium material-icons">delete</i></div>
    <div id="add-task-modal" class="modal">
        <form role="form" method="POST" action="{{ url('/addtask') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal-content">
                <h4>Crear una tarea</h4>
                <div class="row">
                    <div class="input-field col s12">
                        <textarea id="create-task-text" class="materialize-textarea" name="text"></textarea>
                        <label for="create-task-text">Título de la tarea</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a class="modal-action modal-close waves-effect waves-green btn-flat" onclick="closeMod('add-task-modal');">Cancelar</a>
                <input type="submit" class="waves-effect waves-green btn-flat" value="Crear tarea">
            </div>
        </form>
    </div>
    <div id="task-add-people-modal" class="modal">
        <form role="form" method="POST" action="{{ url('/addworkertask') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal-content">
                <h4>Asignar tarea</h4>
                <div class="row">
                    <div class="input-field col s12">
                        <p><input type="checkbox" id="123"/><label for="123">asdasdasd</label></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a class="modal-action modal-close waves-effect waves-green btn-flat" onclick="">Cancelar</a>
                <a class="modal-action modal-close waves-effect waves-green btn-flat" onclick="">Aceptar</a>
            </div>
        </form>
    </div>
    <div id="add-worker-modal" class="modal">
        <form role="form" method="POST" action="{{ url('/addworker') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal-content">
                <h4>Agregar trabajador</h4>
                <div class="row">
                    <div class="input-field col s12">
                        <input placeholder="" id="register-worker-name" type="text" class="validate" name="name">
                        <label for="register-worker-name">Nombre y apellido</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input placeholder="" id="register-worker-email" type="email" class="validate" name="email">
                        <label for="register-worker-email" data-error="wrong" data-success="right">E-mail</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a class="modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <input type="submit" class="waves-effect waves-green btn-flat" value="Agregar">
            </div>
        </form>
    </div>
@stop

@section('footer')
    @if (Session::has('selected_tab'))
        <script type="text/javascript">
            //changeTabs("{{ session('selected_tab') }}");
            closeMod('add-worker-modal');
        </script>
    @endif
@stop