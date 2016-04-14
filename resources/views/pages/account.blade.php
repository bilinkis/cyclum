@extends('app')

@include('../partials/nav_bar_tabs')

@section('content')
    <div id="configuration-modal">
        {!! Form::model($user, ['method' => 'post', 'url' => '/edituser']) !!}
            <div class="container">
                <h4>Editar datos de cuenta</h5>
                @include('partials/register_form')
                @include ('errors/list', ['errorType' => 'any'])
                <div class="row">
                    <div class="center col s12"><input onclick="" type="submit" class="btn green darken-1 waves-effect waves-green" value="Guardar"></div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@stop