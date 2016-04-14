@extends('app')

@section('content')
    <div class="container">
        <h4 class="center">Bienvenido {{ $worker->name }}</h4>
        <h5 class="center" style="color: grey;">Elija su contaseña.</h5>
        <form role="form" method="POST" action="{{ url('/passconfirmation') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id_hash" value="{{ $id }}">
            <div>
                <div class="row">
                    <div class="input-field col s12">
                        <input placeholder="" id="worker-passconfirmation" type="password" class="validate" name="password">
                        <label for="worker-passconfirmation">Contraseña</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input placeholder="" id="worker-passconfirmation-second" type="password" class="validate" name="password_confirmation">
                        <label for="worker-passconfirmation-second">Repetí tu contraseña</label>
                    </div>
                </div>
            </div>
            <div class="center">
                <input onclick="return workerConfirmationCheck();" type="submit" class="btn waves-effect waves-light" value="Confirmar">
            </div>
        </form>
        <ul id="pass-confirmation-worker-error" class="collection" style="display:none;"></ul>
    </div>
@stop