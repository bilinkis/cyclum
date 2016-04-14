@extends('app')
@section('head')
    <link rel="stylesheet" href="../css/src/prism.css"/>
    <script type='text/javascript' src='../js/src/prism.js'></script>
@stop

@include('../partials/nav_bar_tabs')

@section('content')
    <div class="col s12">
        <ul class="tabs">
            <li class="tab col s3"><a onclick="changeTabs('stats-configuration')">Configuracion</a></li>
            <li id='stats-changes-tab' class="tab col s3 disabled"><a onclick="changeTabs('stats-changes')">Estadisticas del cambio</a></li>
        </ul>
    </div>
    <div class="container">
        <div class="row">
            <div id="stats-configuration" style="display:block;">
            @if ($userType != "user")
                <div class="col s6">
                    <h5 class="center">Grupo A</h5>
                    <p class="center">ID del grupo: {{ $task->groups[0]->id }}</p>
                </div>
                    
                <div class="col s6">
                    <h5 class="center">Grupo B</h5>
                    <p class="center">ID del grupo: {{ $task->groups[1]->id }}</p>
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
            <div id="stats-changes" style="display:none;"> <!-- Estadisticas del cambio -->
                <div id="user-data-change-group-a"></div>
                <div id="user-data-change-group-b"></div>
                <div id="change-values"></div>
                <div id="change-time-values"></div>
                @if($user->rank == 'leader')
                    <div class="row col s12" style="position: fixed; bottom: 0px;">
                        <div class="col s2 offset-s2"><a onclick="delayValidation('accepted', '{{ Crypt::encrypt($task->id) }}');" class="btn-floating btn-large green tooltipped" data-position="top" data-delay="0" data-tooltip="Aceptar el cambio"><i class="material-icons large">thumb_up</i></a></div>                        <div class="col s2 offset-s2"><a onclick="delayValidation('rejected', {{ $task->id }});" class="btn-floating btn-large red tooltipped" data-position="top" data-delay="0" data-tooltip="Rechazar el cambio"><i class="material-icons large">thumb_down</i></a></div>
                    </div>
                @endif
            </div>
            <div id="stats-team" style="display:none;">
                <div id=""></div>
            </div>
        </div>
    </div>
@stop

@section('footer')
<script type='text/javascript'>
    $(document).ready(function(){
        $('#right-navbar').pushpin({
            top: 80,
            left: $('#right-navbar').offset().left
        });
        
        // $('.scrollspy').scrollSpy();
        $('#stats-changes').css('display', 'block');
        
        group_a_pie();
        group_b_pie();
        change_in_time_column();
        change_values_column();
        
        $('#stats-changes').css('display', 'none');
        $('#stats-changes-tab').removeClass('disabled');
        $('ul.tabs').tabs();
    });
    $(window).resize(function(){
        group_a_pie();
        group_b_pie();
        change_in_time_column();
        change_values_column();
    });
    google.load('visualization', '1',{ packages: ['corechart', 'bar'] });
    
    var change_values_info = {
        columns: {
            first: 'Grupo A',
            second: 'Grupo B'
        },
        options: {
            height: 400,
            title: 'Variables',
            titleTextStyle: {
                fontSize: '15'
            },
            series: {
                0: { axis: 'length' },
                1: { axis: 'length' }
            },
            hAxis: {
                title: "Variables"
            },
            vAxis: {
                title: "Clicks",
                viewWindow: {
                    max: 0,
                    min: 0
                }
            },
            annotations: {
                boxStyle: {
                    stroke: '#ffffff'
                },
                textStyle: {
                    fontSize: 15,
                    color: '#000000'
                }
            }
        }
    };
    var changes_time_info = {
        columns: {
            first: 'Grupo A',
            second: 'Grupo B'
        },
        options: {
            height: 400,
            title: 'Tiempo promedio',
            titleTextStyle: {
                fontSize: '15'
            },
            series: {
                0: { axis: 'length' },
                1: { axis: 'length' }
            },
            hAxis: {
                title: "Páginas"
            },
            vAxis: {
                title: "Segundos",
                viewWindow: {
                    max: 0,
                    min: 0
                }
            },
            annotations: {
                boxStyle: {
                    stroke: '#ffffff'
                },
                textStyle: {
                    fontSize: 15,
                    color: '#000000'
                }
            }
        }
    };
    
    function change_values_column() {
        var change_values_json = '{{ $varsData }}';
        var change_values = $.parseJSON(change_values_json.replace(/&quot;/g,'"'));
        var results = get_changevalues(change_values);

        var options = change_values_info.options;
        var chart = new google.visualization.ColumnChart(document.getElementById('change-values'));
        
        var data = new google.visualization.arrayToDataTable(results);
        var view = new google.visualization.DataView(data);
        if(results[1].indexOf('No hay datos.') == -1){
            view.setColumns([0, 1, 
                {
                    calc: 'stringify', 
                    sourceColumn: 1,
                    type: 'string',
                    role: 'annotation'
                }, 2, {
                    calc: 'stringify',
                    sourceColumn: 2,
                    type: 'string',
                    role: 'annotation'
            }]);
            chart.draw(view, options);
        } else{
            chart.draw(data, options);
        }
    }
    function change_in_time_column(){
        var change_time_values_json = '{{ $pagesData }}';
        var changes_time = $.parseJSON(change_time_values_json.replace(/&quot;/g,'"'));
        var results = get_changestime(changes_time);
        
        var data = new google.visualization.arrayToDataTable(results);
        var options = changes_time_info.options;
        var chart = new google.visualization.ColumnChart(document.getElementById('change-time-values'));
        
        var view = new google.visualization.DataView(data);
        if(results[1].indexOf('No hay datos.') == -1){
            view.setColumns([0, 1, 
                {
                    calc: 'stringify', 
                    sourceColumn: 1,
                    type: 'string',
                    role: 'annotation'
                }, 2, {
                    calc: 'stringify',
                    sourceColumn: 2,
                    type: 'string',
                    role: 'annotation'
            }]);
            chart.draw(view, options);
        } else{
            chart.draw(data, options);
        }
    }
    function group_a_pie(){
        var a_pie_json = '{{ $clientsStatusData }}';
        var a_pie = $.parseJSON(a_pie_json.replace(/&quot;/g,'"'));

        var results = get_piechart(a_pie, 0);
        var data = google.visualization.arrayToDataTable(results);
        var options = { 
            is3D: true,
            height: 300,
            title: 'Estado de usuarios del grupo A' /* group name */,
            titleTextStyle: { 
                fontSize: '15'
            }
        };

        var chart = new google.visualization.PieChart(document.getElementById('user-data-change-group-a'));
        chart.draw(data, options);
    }
    function group_b_pie(){
        var b_pie_json = '{{ $clientsStatusData }}';
        var b_pie = $.parseJSON(b_pie_json.replace(/&quot;/g,'"'));
        
        var results = get_piechart(b_pie, 1);
        var data = google.visualization.arrayToDataTable(results);
        var options = { 
            is3D: true,
            height: 300,
            title: 'Estado de usuarios del grupo B' /* group name */,
            titleTextStyle: { 
                fontSize: '15'
            }
        };

        var chart = new google.visualization.PieChart(document.getElementById('user-data-change-group-b'));
        chart.draw(data, options);
    }
    
    function get_piechart(pie, i){
        var res = [['Analisis de usuarios', 'Porcentaje de personas']];
        var len = Object.keys(pie[i]).length;
        
        for(var j = 0; j < len; j++){
            var obj = Object.keys(pie[i]);
            var arr = [obj[j], pie[i][obj[j]]];
            res.push(arr);
        }
        return res;
    }
    function get_changevalues(chart){
        var res = [['Nombre', change_values_info.columns.first, change_values_info.columns.second]];
        var obj = Object.keys(chart);
        var len = obj.length;
        var max = 0;
        
        if(len <= 0) {
            delete change_values_info.options.hAxis;
            change_values_info.options.height = 150;
            return [['', { role: 'annotation' }], ['', 'No hay datos.']];
        }
        for(var i = 0; i < len; i++){
            var name = obj[i];
            var vals = chart[name];
            var res_aux = [name];
            
            for(var j = 0; j < vals.length; j++){
                res_aux.push(vals[j]);
                if(vals[j] > max) max = vals[j] + 1;
                if(j + 1 == vals.length){
                    res.push(res_aux);        
                }
            }
        }
        change_values_info.options.vAxis.viewWindow.max = max;
        return res;
    }
    function get_changestime(chart){
        var res = [['Nombre', changes_time_info.columns.first, changes_time_info.columns.second]];
        var obj = Object.keys(chart);
        var len = obj.length;
        var max = 0;

        if(len <= 0) {
            delete changes_time_info.options.hAxis;
            changes_time_info.options.height = 150;
            return [['', { role: 'annotation' }], ['', 'No hay datos.']];
        }
        for(var i = 0; i < len; i++){
            var name = obj[i];
            var vals = chart[name];
            var res_aux = [name];
            
            for(var j = 0; j < vals.length; j++){
                res_aux.push(vals[j]);
                if(vals[j] > max) max = vals[j] + 1;
                if(j + 1 == vals.length){
                    res.push(res_aux);        
                }
            }
        }
        changes_time_info.options.vAxis.viewWindow.max = max;
        return res;
    }
</script>
@stop