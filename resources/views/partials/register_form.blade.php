<input type="hidden" name="_token" value="{{{ csrf_token() }}}">
@if (!isset($user) || $user->isALeader())
    <div class="row">
        <div class="input-field col s12">
            {!! Form::text('teamName', null, ['class' => 'validate', 'id' => 'register-teamname']) !!}
            {!! Form::label('register-teamname', 'Nombre del emprendimiento') !!}
        </div>
    </div>
@endif
<div class="row">
    <div class="input-field col s12">
        {!! Form::text('name', null, ['class' => 'validate', 'id' => 'register-leadername']) !!}
        @unless (isset($user))
            {!! Form::label('register-leadername', 'Nombre del líder') !!}
        @else
            {!! Form::label('register-leadername', 'Nombre') !!}
        @endunless
    </div>
</div>
<div class="row">
    <div class="input-field col s12">
        {!! Form::email('email', null, ['class' => 'validate', 'id' => 'register-email']) !!}
        {!! Form::label('register-email', 'E-mail') !!}
    </div>
</div>
<div class="row">
    <div class="input-field col s12">
        {!! Form::password('password', null, ['class' => 'validate', 'id' => 'register-pass']) !!}
        @if (isset($user))
            {!! Form::label('register-pass', 'Ingresá tu contraseña por seguridad') !!}
        @else
            {!! Form::label('register-pass', 'Contraseña') !!}
        @endif
    </div>
</div>
@unless (isset($user))
<div class="row">
    <div class="input-field col s12">
        {!! Form::password('password_confirmation', null, ['class' => 'validate', 'id' => 'register-pass-second']) !!}
        {!! Form::label('register-pass-second', 'Repetí tu contraseña') !!}
    </div>
</div>
@endunless