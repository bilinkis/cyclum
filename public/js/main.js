// Landing page
/**
 * Start the materialize functions for different things to work
 */ 
$('.parallax').parallax();
$('.button-collapse').sideNav();
$('.modal-trigger').leanModal();
$('.scroll-animation').click(function(){
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname){
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
        if (target.length){
            $('html,body').animate({
                scrollTop: target.offset().top
            }, 1000);
            return false;
        }
    }
});
$(document).ready(function(){
    $('.tooltipped').tooltip({delay: 50});
    $('.slider').slider({full_width: true});

});
/**
 * Closes the side navbar
 */ 
function closeNav(){
    $('.button-collapse').sideNav('hide');
}
/**
 * Opens the modal passed by parameter and depending on its name
 * it does another things
 * 
 * @param {string} modal
 */
function openMod(modal){
    $('#' + modal).openModal();
    if(modal == 'login-modal'){
        $('#login-email').focus();
    }
    else if(modal == 'register-modal'){
        if($('#first-name').val() != ''){
            $('#register-teamname').val($('#first-name').val());
        }
        $('#register-teamname').focus();
    }
    else if(modal == 'add-task-modal'){
        $('#create-task-text').focus();
    }
    else if(modal == 'add-worker-modal'){
        $('#register-worker-name').focus();
    }
    else if(modal == 'edit-task-text-modal'){
        // $('#edit-task-text').focus();
    }
    else if(modal == 'add-team-modal'){
        $('#register-team-name').focus();
    }
}
/**
 * Closes the modal passed by parameter
 * 
 * @param {string} modal
 */
function closeMod(modal){
    $('#' + modal).closeModal();
}
/**
 * Checks for typo or no-values errors on inputs
 * 
 * @return {boolean}
 */
function loginCheck(){
    var email = $('#login-email').val(),
        pass = $('#login-pass').val();
    
    $('#error-list').remove();
    $('#login-error').empty();
    $('#login-error').css('display', 'none');
    
    if(email == "" || pass == ""){
        if(email == ""){
            $('#login-error').append(''
                + '<li class="collection-item error">'
                    + 'El email es un campo requerido.'
                + '</li>');
        }
        if(pass == ""){
            $('#login-error').append(''
                + '<li class="collection-item error">'
                    + 'La contraseña es un campo requerido.'
                + '</li>');
        }
        $('#login-error').css('display', 'block');
        return false;
    }
    return true;
}
function registerCheck(){
    var teamName = $('#register-teamname').val(),
        name = $('#register-leadername').val(),
        email = $('#register-email').val(),
        passFirst = $('#register-pass').val(),
        passSecond = $('#register-pass-second').val();
        
    $('#error-list').remove();
    $('#register-error').empty();
    $('#register-error').css('display', 'none');
    
    if(teamName == "" || name == "" || email == "" || passFirst == "" || passSecond == ""){
        if(teamName == ""){
            $('#register-error').append(''
                + '<li class="collection-item error">'
                    + 'El nombre del equipo es un campo requerido.'
                + '</li>');
        }
        if(name == ""){
            $('#register-error').append(''
                + '<li class="collection-item error">'
                    + 'El nombre del líder es un campo requerido.'
                + '</li>');
        }
        if(email == ""){
            $('#register-error').append(''
                + '<li class="collection-item error">'
                    + 'El email es un campo requerido.'
                + '</li>');
        }
        if(passFirst == ""){
            $('#register-error').append(''
                + '<li class="collection-item error">'
                    + 'La contraseña es un campo requerido.'
                + '</li>');
        }
        if(passSecond == ""){
            $('#register-error').append(''
                + '<li class="collection-item error">'
                    + 'La segunda contraseña es un campo requerido.'
                + '</li>');
        }
        $('#register-error').css('display', 'block');
        return false;
    }
    if(passFirst != passSecond){
        $('#register-error').append(''
            + '<li class="collection-item error">'
                + 'Las contraseñas no coinciden'
            + '</li>');
        $('#register-error').css('display', 'block');
        return false;
    }
    return true;
}

// Task Organizer
var startParentId;
var deleteTask = true;
var taskText = '';
var user_is_leader = '';
var team_task_tabs = "task";
/**
 * Updates the functions for the buttons on the 'tasks-cards' whenever they are 
 * being created dinamically
 */ 
function updateTaskFuncs(){
    $('.tasks-add-people').each(function(){
        $(this).unbind('click').click(function(){
            var cont = $($(this).parent().parent().parent())[0];
            var task_id = $(cont).data('task-id');
            
            openMod('task-add-people-modal');
            getTaskWorkers(task_id);
        });
    });
    $('.tasks-validating').each(function(){
        $(this).unbind('click').click(function(){
            var cont = $($(this).parent().parent().parent())[0];
            var task_id = $(cont).data('task-id');
            location.href = '/stats/' + task_id;
        });
    });
    $('.tasks-edit-task-text').each(function(){
        $(this).unbind('click').click(function(){
            var cont = $($(this).parent().parent())[0];
            var task_id = $(cont).data('task-id');
            var text = $($(cont).find('span'))[0].childNodes[0].nodeValue;
            
            openMod('edit-task-text-modal');
            setEditTask(task_id, text);
        });
    });
}
/**
 * Calls 'changeTeamTasks' or 'changeTeams' depending on 
 * the parameter passed to the function
 * 
 * @param {string} id_team
 */ 
function changeTeamOrTasks(id_team){
    if(team_task_tabs == "task") {
        changeTeamTasks(id_team);
    } else if(team_task_tabs == "team") {
        changeTeams(id_team);
    }
}
/**
 * Changes the display from the tabs depending on the parameter
 * passed to the function
 * 
 * @param {string} tab
 */
function changeTabs(tab){
    if(tab == "task"){
        team_task_tabs = "task";
        $('#tasks').css('display','block');
        $('#team').css('display','none');
        $('#all').addClass("active");
        
        /*
            $('#tasks-tab').addClass("active");
            $('#team-tab').removeClass("active");
        */
    } else if(tab == "team"){
        team_task_tabs = "team";
        $('#tasks').css('display','none');
        $('#team').css('display','block');
        $('#all').addClass("active");
        
        /*
            $('#team-tab').addClass("active");
            $('#tasks-tab').removeClass("active");
        */
    } else if(tab == "general-stats"){
        $('#user-analysis').css('display','none');
        $('#general-stats').css('display','block');
        $('#task-stats').css('display','none');
        $('#general-stats-tab').addClass("active");
        $('#user-analysis-tab').removeClass("active");
        $('#task-stats-tab').removeClass("active");
    } else if(tab == "user-analysis") {
        $('#general-stats').css('display','none');
        $('#user-analysis').css('display','block');
        $('#task-stats').css('display','none');
        $('#user-analysis-tab').addClass("active");
        $('#general-stats-tab').removeClass("active");
        $('#task-stats-tab').removeClass("active");
    } else if(tab == "task-stats"){
        $('#task-stats').css('display','none');
        $('#task-stats').css('display','block');
        $('#task-stats').css('display','none');
        $('#task-stats-tab').addClass("active");
        $('#task-stats-tab').removeClass("active");
        $('#task-stats-tab').removeClass("active");
    } else if(tab == 'stats-configuration'){
        $('#stats-configuration').css('display','block');
        $('#stats-changes').css('display', 'none');
        $('#stats-team').css('display', 'none');
    } else if(tab == 'stats-changes'){
        $('#stats-configuration').css('display','none');
        $('#stats-changes').css('display', 'block');
        $('#stats-team').css('display', 'none'); 
    } else if(tab == 'stats-team'){
        $('#stats-configuration').css('display','none');
        $('#stats-changes').css('display', 'none');
        $('#stats-team').css('display', 'block'); 
    } else if(tab == 'stats-configuration-project'){
        $('#stats-configuration-project').css('display', 'block');
        $('#project-stats').css('display', 'none');
    } else if(tab == 'project-stats'){
        $('#stats-configuration-project').css('display', 'none');
        $('#project-stats').css('display', 'block');
    }
}
/**
 * Changes the task position, AJAX 'GET' request
 * 
 * @param {string} data
 */ 
function changeTask(data){
    $.ajax({
        type: "GET",
        url: "/changetask",
        data: data,
        cache: false,
        success: function(response) {
            if (response == '200') {
                
            } else {
                
            }
        }
    });
}
/**
 * Prepares the modal to edit a task text
 * 
 * @param {string} task_id
 * @param {string} text
 */
function setEditTask(task_id, text){
    $('input[name=task_id]').val(task_id);
    $('#edit-task-text').val(text);
    taskText = text;
}
/**
 * Return correct task status text
 * 
 * @param  {string} status
 * @return {string} vals[x]
 */ 
function getTaskStatus(status){
    var vars = ['todo', 'doing', 'finished', 'validating'];
    var vals = ['todo', 'doing', 'done', 'validating'];
    
    for(var i = 0; i < vars.length; i++){
        if(status.indexOf(vars[i]) > -1){
            return vals[i];
        }
    }
}
/**
 * Performs a 'GET' AJAX request to '/gettaskworkers' with id_task as parameter
 * AJAX response = {}
 * Selects the options of the workers whose IDs are on the JSON on the
 * <select> tag
 * 
 * @param {string} id_task 
 */
function getTaskWorkers(id_task){
    $('input[name=task_id]').val(id_task);
    $.ajax({
        type: 'GET',
        url: '/gettaskworkers',
        data: 'id_task=' + id_task,
        cache: false,
        success: function(response){
            console.log(response);
            if(response != 'no_workers'){
                var data = $.parseJSON(response);
                $('select option').first().attr('selected', false);
                
                for(var worker in data){
                    var val = data[worker];
                    $('option[data-worker-id="' + val + '"]').attr('selected', true);
                }
            } else{
                
            }
        }
    });
}

/**
 * Checks for typo or no-values errors on inputs
 * 
 * @return {boolean}
 */
function createTaskCheck(){
    var length = $('#tasks-things-todo').children()[1].children.length;
    var text = $('#create-task-text').val();
    
    $('#error-list').remove();
    $('#create-task-error').empty();
    $('#create-task-error').css('display', 'none');
    
    if(text == '' || length == 3){
        if(text == ''){
            $('#create-task-error').append(''
                + '<li class="collection-item error">'
                    + 'El campo de texto es requerido.'
                + '</li>');
        }
        if(length == 3){
            $('#create-task-error').append(''
                + '<li class="collection-item error">'
                    + 'Ya hay 3 tareas para hacer!'
                + '</li>');
        }
        $('#create-task-error').css('display', 'block');
        return false;
    }
    return true;
}
function addWorkerCheck(){
    var email = $('#register-worker-email').val(),
        name = $('#register-worker-name').val();
        
    $('#error-list').remove();
    $('#add-worker-error').empty();
    $('#add-worker-error').css('display', 'none');
    
    if(email == '' || name == ''){
        if(name == ''){
            $('#add-worker-error').append(''
                + '<li class="collection-item error">'
                    + 'El nombre es un campo requerido.'
                + '</li>');
        }
        if(email == ''){
            $('#add-worker-error').append(''
                + '<li class="collection-item error">'
                    + 'El email es un campo requerido.'
                + '</li>');
        }
        $('#add-worker-error').css('display', 'block');
        return false;
    }
    return true;
}
function editTaskCheck(){
    var text = $('#edit-task-text').val();
    
    $('#error-list').remove();
    $('#edit-task-text-error').empty();
    $('#edit-task-text-error').css('display', 'none');
    
    if(text == taskText || text == '' || text.length > 64){
        if(text == ''){
            $('#edit-task-text-error').append(''
                + '<li class="collection-item error">'
                    + 'El texto de la tarea es requerido.'
                + '</li>');
        }
        if(text == taskText){
            $('#edit-task-text-error').append(''
                + '<li class="collection-item error">'
                    + 'El texto de la tarea es igual al anterior.'
                + '</li>');
        }
        if(text.length > 64){
            $('#edit-task-text-error').append(''
                + '<li class="collection-item error">'
                    + 'El texto de la tarea debe ser menor a 64 caracteres.'
                + '</li>');
        }
        $('#edit-task-text-error').css('display', 'block');
        return false;
    }
    return true;
}
function workerConfirmationCheck(){
    var pass = $('#worker-passconfirmation').val(),
        pass_second = $('#worker-passconfirmation-second').val();
        
    $('#error-list').remove();
    $('#pass-confirmation-worker-error').empty();
    $('#pass-confirmation-worker-error').css('display', 'none');

    if(pass == '' || pass_second == ''){
        if(pass == ''){
            $('#pass-confirmation-worker-error').append(''
                + '<li class="collection-item error">'
                    + 'La contraseña es requerida.'
                + '</li>');
        }
        if (pass_second == ''){
            $('#pass-confirmation-worker-error').append(''
                + '<li class="collection-item error">'
                    + 'Tienes que repetir la contraseña.'
                + '</li>');
        }
        $('#pass-confirmation-worker-error').css('display', 'block');
        return false;
    }
    if(pass.length < 6){
        $('#pass-confirmation-worker-error').append(''
            + '<li class="collection-item error">'
                + 'Las contraseña ser mayor a 6 caracteres.'
            + '</li>'); 
        $('#pass-confirmation-worker-error').css('display', 'block');
        return false;
    }
    if(pass != pass_second){
        $('#pass-confirmation-worker-error').append(''
            + '<li class="collection-item error">'
                + 'Las contraseñas no coinciden.'
            + '</li>'); 
        $('#pass-confirmation-worker-error').css('display', 'block');
        return false;   
    }
    return true;
}

$('.tasks-card').draggable({
    /**
     * containment: Where the element is contained, document 
     * zIndex: For the element to appear over others and not 'behind'
     * connectToSortable: Can get inside elements with '.tasks-content' class
     * (gets connected to it)
     * revert: If it doesn't get connected it goes back to its original
     * position
     */
    containment: "document",
    zIndex: 1501,
    connectToSortable: '.tasks-content',
    revert: true,
    /**
     * Shows the delete-task icon and hides the fixed button
     * Gets the startParentId for further information and adds the element
     * the class 'tasks-card-dragging' to make the 'moving' effect
     */ 
    start: function(event, ui){
        $(ui.helper[0]).addClass('tasks-card-dragging');
        startParentId = $(ui.helper[0].parentElement.parentElement).attr('id');
        $('#delete-task').css('display', 'block');
        $('#fixed-action-btn').css('display', 'none');
    },
    stop: function(event, ui){
        $(ui.helper[0]).removeClass('tasks-card-dragging');
        $(ui.helper[0]).removeClass('tasks-over-delete');
        $('#delete-task').css('display', 'none');
        $('#fixed-action-btn').css('display', 'block');
    },
    drag: function(event, ui){
        
    }
});
$('.tasks-container').droppable({
    accept: '.tasks-card',
    /**
     * Changes the information (html) from the draggable '<div>'
     * Creates the data information to send to the server
     * once the task is dropped
     * Removes the shadow from the task-container
     */ 
    drop: function(event, ui){
        var containerId = $(this).attr('id');
        
        if(containerId != startParentId){
            if($('#' + containerId + ' span').length < 3){
                $(ui.draggable[0]).removeClass('tasks-card-dragging');
                $(ui.draggable[0]).removeClass('tasks-over-delete');
                
                var drag = $(ui.draggable[0]),
                    html = $(drag[0]).children('span').children('strong')[0],
                    task_id = $(drag).attr('data-task-id'),
                    status = getTaskStatus(containerId),
                    data = "status=" + status + "&task_id=" + task_id;
                
                $(drag).css('left', 0);
                $(drag).css('top', 0); 
                
                if(containerId == 'tasks-things-todo'){
                    html.innerHTML = (user_is_leader == '1') ? "<i class='material-icons right tasks-add-people'>person_add</i>" : '';
                } else if(containerId == 'tasks-validating'){
                    html.innerHTML = "<i class='material-icons right tasks-validating'>trending_up</i>";
                } else {
                    html.innerHTML = '';
                }
                
                $(this).children('.tasks-content').append(drag[0]);
                updateTaskFuncs();
                changeTask(data);
            }
        }
        $('.tasks-shadow').remove();
    },
    /**
     * Creates a shadow-like pattern depending on the drag width and height
     * and appends it to the element in which the task-card is over
     */ 
    over: function(event, ui){
        var drag = $(ui.draggable[0]),
            h = $(drag[0]).height(),
            w = $(drag[0]).width(),
            container = $(this).children('.tasks-content').context.children[1],
            containerId = $(this).attr('id');
            
        if(containerId != startParentId){
            if($('#' + containerId + ' span').length < 3){
                $(container).append('<div class="tasks-shadow card-panel" style="width:' + w + 'px;height:' + h + 'px;"></div>');
            }
        }
    },
    /**
     * Once the task-card leaves the container, the shadow is removed
     */ 
    out: function(event, ui){
        $('.tasks-shadow').remove();
    }
});
$('.tasks-delete').droppable({
    accept: '.tasks-card',
    /**
     * Deletes the task from the database once the task-card is dropped on
     * the delete icon
     */ 
    drop: function(event, ui){
        var drag = $(ui.draggable[0]),
            task_id = $(drag).data('task-id');
        
        deleteTask = true;
        $(drag).css('display', 'none');
        $(drag).removeClass('tasks-over-delete');
        
        Materialize.toast(
            "Tarea eliminada <a class='btn-flat waves-effect waves-light yellow-text' onclick='deleteTask = false;'>Deshacer</a>", 2000, '',
            function(){
                if(deleteTask){
                    $.ajax({
                        type: "GET",
                        url: "/deletetask",
                        data: "task_id=" + task_id,
                        cache: false,
                        success: function(response) {
                            if (response == '200') {
                                $(drag).remove();
                            } else {
                                
                            }
                        }
                    });
                } else{
                    $(drag).css('display', 'block');
                }
            }
        );
    },
    /**
     * Changes colors to show the user that it's about to delete a task-card
     */ 
    over: function(event, ui){
        var drag = $(ui.draggable[0]);
        $(drag).removeClass('grey lighten-5');delayValidation
        $(drag).addClass('tasks-over-delete');
    },
    out: function(event, ui){
        var drag = $(ui.draggable[0]);
        $(drag).addClass('grey lighten-5');
        $(drag).removeClass('tasks-over-delete');
    },
});

// Stats
var validate_confirmed = true;
/**
 * Delays the validation and validates the event from the task 
 * if 'validate_confirmed' is true else, the validation is 
 * canceled
 * 
 * @param  {string}  event
 * @param  {string}  task_id
 * @return {boolean}
 */ 
function delayValidation(event, task_id){
    var event_response = event == 'accepted' ? 'aceptado' : 'rechazado';

    Materialize.toast(
        "Cambio " + event_response + "<a class='btn-flat waves-effect waves-light yellow-text' onclick='validate_confirmed = false;'>Deshacer</a>", 4000, '',
        function(){
            if(validate_confirmed){
                location.href = '/stats/' + task_id + '/validate/' + event; // De dnd sacás el task_id acá? es una variable global
            } else{
                return false;
            }
        }
    );
}

// Subteams
/**
 * Empties the tasks content from each section
 */ 
function clearTasks(){
    $('#tasks-things-todo .tasks-content').empty();
    $('#tasks-doing .tasks-content').empty();
    $('#tasks-finished .tasks-content').empty();
    $('#tasks-validating .tasks-content').empty();
}
/**
 * Empties the workers-list collection
 */ 
function clearUsers(){
    $('.workers-list').empty();
}
/**
 * Performs a 'GET' AJAX request to '/changeteamtasks' with the team_id as parameter
 * AJAX response = [{},{}...]
 * Creates a 'task-card' for each task object that there is in the response and 
 * places it on its respective section (done, doing, validating, finished)
 * Changes '#subteam_id_for_task_creation' to the param team_id 
 * 
 * @param {string} team_id
 */ 
function changeTeamTasks(team_id){
    $.ajax({
        type: 'GET',
        data: 'team_id=' + team_id,
        url: '/changeteamtasks',
        cache: false,
        success: function(response){
            if(response != '404'){
                clearTasks();
                var s = { start: '<strong><i class="material-icons right ', end: '</i></strong>'};
                
                for(var tasks in response){
                    var task = response[tasks];
                    var div = '<div class="card-panel grey lighten-5 tasks-card hoverable ui-draggable ui-draggable-handle" data-task-status="' + task['status'] + '" data-task-id="' + task['id'] + '" style="position: relative;"><span class="black-text truncate">' + task['text'] + '<i class="material-icons right tasks-edit-task-text">edit</i>';
                
                    if(task['status'] == 'todo'){
                        div += s.start + 'tasks-add-people">person_add' + s.end;
                    } else if(task['status'] == 'validating'){
                        div += s.start + 'tasks-validating">trending_up' + s.end;
                    }
                    div += '</span></div>';
                    
                    // Corregir esto a futuro
                    if(task['status'] == 'todo') $('#tasks-things-todo .tasks-content').append(div);
                    else if(task['status'] == 'doing') $('#tasks-doing .tasks-content').append(div);
                    else if(task['status'] == 'done') $('#tasks-finished .tasks-content').append(div);
                    else if(task['status'] == 'validating') $('#tasks-validating .tasks-content').append(div);
                    
                    updateTaskFuncs();
                }
            } else {
                window.location.href = window.location.href;
            }
        }
    });
    $('#subteam_id_for_task_creation').val(team_id);
}
/**
 * Performs a 'GET' AJAX request to '/changeteams' with the team_id as parameter
 * AJAX response = [
 *                     [{},{}...], 
 *                     [string]
 *                 ]
 * Creates a '.collection-item' for each worker in the response, and appends the
 * created 'div' to the '.workers-list' <ul>
 * Changes '#subteam_id_for_task_creation' to the param team_id 
 * 
 * @ param {string} team_id
 */ 
function changeTeams(team_id){
    $.ajax({
        type: 'GET',
        data: 'team_id=' + team_id,
        url: '/changeteams',
        cache: false,
        success: function(response){
            if(response != '404'){
                clearUsers();
                var rank = response[1];
                
                for(var workers in response[0]){
                    var worker = response[0][workers];
                    var div = '<a class="collection-item">';
                    var cont = '<i class="tooltipped material-icons right" style="cursor: pointer;" data-position="top" data-delay="50" data-tooltip=';
                    
                    if (worker['rank'] == 'worker' && rank == 'leader'){
                        div += cont + '"Eliminar trabajador" onclick="window.location = \'/deleteworker/' + worker['id'] + '\'">delete</i>';
                    }
                    if(rank == 'leader'){
                        if(worker['rank'] == 'leader'){
                            div += cont + '"Degradar a trabajador" onclick="window.location = \'/downgrade/' + worker['id'] + '\'">arrow_downward</i>';
                        }
                    }
                    if(rank == 'leader' && worker['rank'] == 'worker'){
                        div += cont + '"Ascender a líder" onclick="window.location = \'/upgrade/' + worker['id'] + '\'">arrow_upward</i>';
                    }
                    
                    div += worker['name'] + '</a>';
                    $('.workers-list').append(div);
                }
                
                /**
                 * Tooltips that have no [data-tooltip-id] attribute (which means that
                 * have no asigned tooltip) get a tooltip
                 */ 
                $('.tooltipped:not([data-tooltip-id])').tooltip({delay: 50});
            } else {
                window.location.href = window.location.href;
            }
        }
    });
    $('#subteam_id_for_task_creation').val(team_id);
}