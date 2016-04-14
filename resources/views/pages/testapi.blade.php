@extends('app')

@section('content')
    <br>
    <div class='row'>
        <a class='input-field col s12 m4 btn waves-effect waves-light' data-cyclum='state' data-cyclum-value='register'>Registrarse</a>
    </div>
    <div class='row'>
        <button class='input-field col s12 m4 btn waves-effect waves-light' data-cyclum='state' data-cyclum-value='login'>Login</button>
    </div>
    <div class='row'>
        <button class='input-field col s12 m4 btn waves-effect waves-light' data-cyclum='state' data-cyclum-value='mashup'>Mashup</button>
    </div>
    <div class='row'>
        <button class='input-field col s12 m4 btn waves-effect waves-light' data-cyclum='variable' data-cyclum-value='questions' data-cyclum-amount='1'>Preguntar</button>
    </div>
@stop

@section('footer')
<script type='text/javascript' src="js/api.js"></script>
<script type='text/javascript'>
    cyclum.ini('4','test-testapi');
    // cyclum.ini('2','no-logeado');
</script>
@stop