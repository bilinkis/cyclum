@extends('app')

@section('head')
    <link rel="stylesheet" href="../css/src/prism.css"/>
    <script type='text/javascript' src='../js/src/prism.js'></script>
@stop

@include('../partials/nav_bar_tabs')

@section('content')
    <div class="col s12">
        <ul class="tabs">
            <li class="tab col s3"><a onclick="changeTabs('stats-configuration-project')">Configuracion</a></li>
            <li id='project-stats-tab' class="tab col s3 disabled"><a onclick="changeTabs('project-stats')">Estadisticas del proyecto</a></li>
        </ul>
    </div>
    <div class="container">
        <div class="row">
            <div id="stats-configuration-project" style="display:block;">
            @if ($userType != "user")
                <div class="col s12">
                    <h5 class="center">Proyecto</h5>
                    <p class="center">ID del proyecto: {{ $user->group_id }}</p> <!-- Missing Project ID -->
                </div>
                @include('partials/configuration')
            @else
                <div id="api-intro" class="section scrollspy">
                    <h5>Introducción</h5>
                    <p>Cyclum te brinda la posibilidad de:</p>
                    <p>Además, damos la opción de sacar estadísticas del producto sin cambios a lo largo del tiempo, para que se pueda generar una historia de cómo cada cambio fue variando el comportamiento de los usuarios.</p>
                </div>
                <div class="divider"></div>
            @endif
            </div>
            <div id="project-stats" style="display:none;">
                <div id="project-variable-change"></div>
                <div id="project-states-changes"></div>
                <div id="project-times-change"></div>
            </div>
        </div>
    </div>
@stop

@section('footer')
<script type='text/javascript'>
    String.prototype.toJSONString = function(){ 
        return this.toString().replace(/&quot;/g,'"').replace('"{', '{').replace('}"', '}').replace(/&#039;/g, '"');
    };

    $(document).ready(function(){
        $('#right-navbar').pushpin({
            top: 80,
            left: $('#right-navbar').offset().left
        });
        // $('.scrollspy').scrollSpy();
        $('#project-stats').css('display', 'block');
        
        project_variables_time_line();
        project_states_over_time_area();
        project_times_line();
        
        $('#project-stats').css('display', 'none');
        $('#project-stats-tab').removeClass('disabled');
        $('ul.tabs').tabs();
    });
    $(window).resize(function(){
        project_variables_time_line();
        project_states_over_time_area();
        project_times_line();
    });
    
    google.load('visualization', '1', {packages: ['corechart', 'line']});

    var variables_time_info = {
        options: {
            title: 'Variables',
            interpolateNulls: true,
            hAxis: {
                title: 'Tiempo',
            },
            vAxis: {
                title: 'Valor'
            },
            legend: {
                position: 'bottom',
                alignment: 'start'
            },
            lineWidth: 3
        }
    };
    var states_over_time_info = {
        options: {
            title: 'Estados de los clientes',
            annotation: {
                
            },
            vAxis: {
                title: 'Porcentaje de clientes'
            },
            hAxis: {
                title: 'Tiempo'
            },
            legend: {
                position: 'bottom',
                alignment: 'start'
            },
            lineWidth: 3,
            isStacked: 'relative',
        }
    };
    var times_info  = {
        options: {
            title: 'Tiempo por sesión en cada página',
            interpolateNulls: true,
            hAxis: {
                title: 'Tiempo',
            },
            vAxis: {
                title: 'Segundos'
            },
            legend: {
                position: 'bottom',
                alignment: 'start'
            },
            lineWidth: 3
        }
    };

    function project_variables_time_line(){
        var variables_time_json = "{{ $jsonVariables }}";
        var variables_time = $.parseJSON(variables_time_json.toJSONString());
        
        var data = new google.visualization.arrayToDataTable(variables_time);
        var options = variables_time_info.options;

        var chart = new google.visualization.LineChart(document.getElementById('project-variable-change'));
        chart.draw(data, options);
    }
    function project_states_over_time_area(){
        var states_over_time_json = "{{ $jsonStates }}";
        var states_over_time = $.parseJSON(states_over_time_json.toJSONString());
        
        var data = new google.visualization.arrayToDataTable(states_over_time);
        var options = states_over_time_info.options;

        var chart = new google.visualization.AreaChart(document.getElementById('project-states-changes'));
        chart.draw(data, options);
    }
    function project_times_line(){
        var times_json = "{{ $jsonTimes }}";
        var times = $.parseJSON(times_json.toJSONString());
        
        var data = google.visualization.arrayToDataTable(times);
        var options = times_info.options;
        
        var chart = new google.visualization.LineChart(document.getElementById('project-times-change'));
        chart.draw(data, options);
    }
</script>
@stop