@extends('app')

@include('../partials/nav_bar_tabs')

@section('content')
    <div id="actions-container" class="row">
        <div id="loader">
            <div class="progress">
                <div class="indeterminate"></div>
            </div>
        </div>
        <div id="user-tab" class="col s12">
            <ul class="tabs">
                <li onclick="changeTabs('task')" class="tab col s6"><a href="" id="tasks-tab" class="active">Cambios</a></li>
                @unless ($userType == "user")
                    <li onclick="changeTabs('team')" class="tab col s6"><a href="" id="team-tab" class="">Equipo</a></li>
                @endunless
            </ul>
        </div>
        <div class="row">
            <div class="col s12">
                <ul class="tabs">
                    <!--<li class="tab col s3" id="all" onclick="changeTeamOrTasks('all')">
                        <a data-team-id="all">Todo</a>
                    </li>-->
                    @foreach($teams as $team)
                        <li class="tab col s3" onclick="changeTeamOrTasks('{{ $team->id }}')">
                            <a data-team-id='{{ $team->id }}'>{{ $team->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div> 
        </div>
        <div id="tasks" class="row" style="display:block;">
            <div class="col s12 m12 l3 task-droppable">
                <div id="tasks-things-todo" class="card-panel teal tasks-container grey lighten-2 hoverable">
                    <div class="tasks-header black-text bold">Cosas a hacer</div>
                    <div class="tasks-content">
                    @foreach($tasks as $task)
                        @if($task->status == 'todo')
                            <div class="card-panel grey lighten-5 tasks-card hoverable" 
                                 data-task-status="{{ $task->status }}"
                                 data-task-id="{{ Crypt::encrypt($task->id) }}">
                                <span class="black-text truncate">{{ $task->text }}
                                    <i class="material-icons right tasks-edit-task-text">edit</i>
                                    <strong>
                                        @if ($userType != "user" && $user->isALeader())
                                        <i class="material-icons right tasks-add-people">person_add</i>
                                        @endif
                                    </strong>
                                </span>
                            </div>
                        @endif
                    @endforeach
                    </div>
                </div>
            </div>
            <div class="col s12 m12 l3 task-droppable">
                <div id="tasks-doing" class="card-panel teal tasks-container grey lighten-2 hoverable">
                    <div class="tasks-header black-text bold">Haciendo</div>
                    <div class="tasks-content">
                    @foreach($tasks as $task)
                        @if($task->status == 'doing')
                            <div class="card-panel grey lighten-5 tasks-card hoverable"
                                 data-task-status="{{ $task->status }}"
                                 data-task-id="{{ Crypt::encrypt($task->id) }}"
                             >
                                <span class="black-text truncate">{{ $task->text }}
                                    <i class="material-icons right tasks-edit-task-text">edit</i>
                                    <strong></strong>
                                </span>
                            </div>
                        @endif
                    @endforeach
                    </div>
                </div>
            </div>
            <div class="col s12 m12 l3 task-droppable">
                <div id="tasks-finished" class="card-panel grey lighten-4 tasks-container grey lighten-2 hoverable">
                    <div class="tasks-header black-text bold">Terminado</div>
                    <div class="tasks-content">
                    @foreach($tasks as $task)
                        @if($task->status == 'done')
                            <div class="card-panel grey lighten-5 tasks-card hoverable"
                                 data-task-status="{{ $task->status }}"
                                 data-task-id="{{ Crypt::encrypt($task->id) }}"
                             >
                                <span class="black-text truncate">{{ $task->text }}
                                    <i class="material-icons right tasks-edit-task-text">edit</i>
                                    <strong></strong>
                                </span>
                            </div>
                        @endif
                    @endforeach
                    </div>
                </div>
            </div>
            <div class="col s12 m12 l3 task-droppable">
                <div id="tasks-validating" class="card-panel teal tasks-container grey lighten-2 hoverable">
                    <div class="tasks-header black-text bold">Validando</div>
                    <div class="tasks-content">
                        @foreach($tasks as $task)
                            @if($task->status == 'validating')
                                <div class="card-panel grey lighten-5 tasks-card hoverable"
                                     data-task-status="{{ $task->status }}"
                                     data-task-id="{{ Crypt::encrypt($task->id) }}"
                                >
                                    <span class="black-text truncate">{{ $task->text }}
                                        <i class="material-icons right tasks-edit-task-text">edit</i>
                                        <strong>
                                            <i class="material-icons right tasks-validating">trending_up</i>
                                        </strong>
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @if ($userType != "user")
            <div id="team" style="display:none;">
                <div class="row container">
                    <div class="col s12">
                        @if (count($workers) > 0)
                            <ul class="workers-list collection" style="margin-top: 1em;">
                                @foreach($workers as $worker)
                                    <a class="collection-item">
                                        @if (($worker->rank == 'worker' && $user->rank == 'leader') || ($worker->rank == 'leader' && $user->workers->first()->leader->id == $user->id))
                                            <i class="tooltipped material-icons right" data-position="top" data-delay="50" data-tooltip="Eliminar trabajador" style="cursor: pointer;" onclick="window.location = '/deleteworker/{{ Crypt::encrypt($worker->id) }}'">delete</i>
                                        @endif
                                        @if ($user->rank == 'leader')
                                            @if ($user->workers->first()->leader->id == $user->id && $worker->rank == 'leader')
                                                <i class="tooltipped material-icons right" data-position="bottom" data-delay="50" data-tooltip="Degradar a trabajador" style="cursor: pointer;" onclick="window.location = '/downgrade/{{ Crypt::encrypt($worker->id) }}'">arrow_downward</i>
                                            @endif
                                        @endif
                                        @if ($user->rank == 'leader' && $worker->rank == 'worker')
                                            <i class="tooltipped material-icons right" data-position="top" data-delay="50" data-tooltip="Ascender a líder" style="cursor: pointer;" onclick="window.location = '/upgrade/{{ Crypt::encrypt($worker->id) }}'">arrow_upward</i>
                                        @endif
                                        {{ $worker->name }}
                                    </a>
                                @endforeach
                            </ul>
                        @else
                            <div class="center">
                                <br/><i class="material-icons" style="font-size: 56px;">mood_bad</i>
                                <p style="margin-top: 5px !important;">No tenés miembros en tu equipo.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
    @if ($user->isALeader())
        <div id="delete-task" class="center tasks-delete" style="display:none;">
            <i class="medium material-icons">delete</i>
        </div>
        <div id="fixed-action-btn" class="fixed-action-btn" style="bottom: 45px; right: 24px;">
            <a class="btn-floating btn-large red"><i class="large material-icons">mode_edit</i></a>
            <ul>
                <li onclick="openMod('add-task-modal')"><a class="btn-floating blue btn tooltipped" data-position="left" data-delay="50" data-tooltip="Agregar tarea"><i class="material-icons">add</i></a></li>
                @if ($userType != "user")
                    <li onclick="openMod('add-worker-modal')"><a class="btn-floating yellow darken-1 btn tooltipped" data-position="left" data-delay="150" data-tooltip="Agregar trabajador"><i class="material-icons">person_add</i></a></li>
                    <li onclick="openMod('add-team-modal')"><a class="btn-floating green darken-1 btn tooltipped" data-position="left" data-delay="150" data-tooltip="Agregar equipo"><i class="material-icons">create_new_folder</i></a></li>
                @endif
            </ul>
        </div>
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
                        <input id="subteam_id_for_task_creation" type="hidden" name="subteam_id" value="{{ $teams[0]->id }}">
                    </div>
                </div>
                <div class="modal-footer row col s12">
                    <a class="modal-action modal-close waves-effect waves-red btn-flat center col s6" onclick="">Cancelar</a>
                    <input onclick="return createTaskCheck();" type="submit" class="waves-effect waves-green btn-flat center col s6" value="Crear tarea">
                </div>
            </form>
            <ul id="create-task-error" class="collection" style="display:none;"></ul>
            @include ('errors/list', ['errorType' => 'add_task'])
        </div>
    @endif
    <div id="edit-task-text-modal" class="modal">
        <form role="form" method="POST" action="{{ url('/edittask') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="task_id" value="">
            <div class="modal-content">
                <h4>Editar una tarea</h4>
                <div class="row">
                    <div class="input-field col s12">
                        <textarea id="edit-task-text" class="materialize-textarea" name="edited_text"></textarea>
                        <label for="edit-task-text">Texto de la tarea</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer row col s12">
                <a class="modal-action modal-close waves-effect waves-red btn-flat center col s6" onclick="">Cancelar</a>
                <input onclick="return editTaskCheck();" type="submit" class="waves-effect waves-green btn-flat center col s6" value="Editar tarea">
            </div>
        </form>
        <ul id="edit-task-text-error" class="collection" style="display:none;"></ul>
        <!-- include ('errors/list', ['errorType' => 'edit_task']) -->
    </div>
    @if ($user->isALeader())
        <div id="task-add-people-modal" class="modal">
            <form role="form" method="POST" action="{{ url('/addworkertask') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="task_id" value="">
                <div class="modal-content">
                    <h4>Asignar tarea</h4>
                    <div class="row">
                        <div class='input-field col s12'>
                            <select name='workers_list[]' multiple>
                                <option class="all-options" value="" disabled selected>Elegi los trabajadores</option>
                                @foreach($user->workers as $worker)
                                    <option class="all-options" value='{{$worker->id}}' data-worker-id='{{$worker->id}}' data-worker-name='{{$worker->name}}'>{{$worker->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer row col s12">
                    <a class="modal-action modal-close waves-effect waves-red btn-flat center col s6">Cancelar</a>
                    <input type="submit" class="waves-effect waves-green btn-flat center col s6" value="Editar trabajadores">
                </div>
            </form>
            @include ('errors/list', ['errorType' => 'add_task'])
        </div>
        <div id="add-worker-modal" class="modal">
            <form role="form" method="POST" action="{{ url('/addworker') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-content">
                    <h4>Agregar trabajador</h4>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="register-worker-name" type="text" class="validate" name="name">
                            <label for="register-worker-name">Nombre y apellido</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="register-worker-email" type="email" class="validate" name="email">
                            <label for="register-worker-email" data-error="wrong" data-success="right">E-mail</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <select name="subteam_id">
                                <option value="" disabled selected>Elegir equipo</option>
                                <option value="0">Sin Equipo</option>
                                @foreach ($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                                @endforeach
                            </select>
                            <label>Elegir equipo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer row col s12">
                    <a class="modal-action modal-close waves-effect waves-red btn-flat center col s6">Cancelar</a>
                    <input onclick="return addWorkerCheck();" type="submit" class="waves-effect waves-green btn-flat center col s6" value="Agregar">
                </div>
            </form>
            <ul id="add-worker-error" class="collection" style="display:none;"></ul>
            @include ('errors/list', ['errorType' => 'add_worker'])
        </div>
        <div id="add-team-modal" class="modal">
            <form role="form" method="POST" action="{{ url('/addteam') }}"> <!-- Revisen la parte de {{ url('/addteam') }} ya cambie el addworker por addteam-->
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-content">
                    <h4>Agregar Equipo</h4>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="register-team-name" type="text" class="validate" name="name">
                            <label for="register-team-name">Nombre equipo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer row col s12">
                    <a class="modal-action modal-close waves-effect waves-red btn-flat center col s6">Cancelar</a>
                    <input onclick="return addTeamCheck();" type="submit" class="waves-effect waves-green btn-flat center col s6" value="Agregar">
<!-- ACA ALGUIEN TIENE QUE METER CODIGO PARA SUBIER EL EQUIPO Y HACER LAS FUNCIONES QUE FALTAN, EL MODEL SE ABRE Y SE CIERRA LO DEMAS FALTA -->
                </div>
            </form>
        </div>
    @endif
@stop

@section('footer')
    <script type="text/javascript">
        $(document).ready(function() {
            $('select').material_select();
            $('ul.tabs').tabs();
        });
        updateTaskFuncs();
        user_is_leader = '{{$user->isALeader()}}';
    </script>
    @if (Session::has('selected_tab'))
        <script type="text/javascript">
            //changeTabs("{{ session('selected_tab') }}");
            closeMod('add-worker-modal');
        </script>
    @endif
@stop