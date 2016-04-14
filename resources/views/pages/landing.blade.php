@extends('app')

@include('../partials/nav_bar_tabs')

@section('content')
    <div id="main-header">
        <div class="section white topsection">
            <div class="container">
                @if ($userType == "user")
                    <h1 class="Header center small-screens">Organizá tus tareas y medí los resultados online</h1>
                @else
                    <!--<img class="responsive-img" src="img/logomedio2.png">-->
                    <h1 class="Header center small-screens">Build, Measure, Learn. Online</h1>
                @endif
                <br>
                <br>
                <div class="row loginForm">
                    <div class="col s12 medium8">
                        <div class="row">
                            <div class="input-field col s12 m8">
                                <input placeholder="Ingrese nombre del emprendimiento" id="first-name" type="text" class="validate active">
                                <label for="first-name">Registrá tu emprendimiento</label>
                            </div>
                            <button class="input-field col s12 m4 btn waves-effect waves-light" type="submit" name="action" onclick="openMod('register-modal');">Registrar<i class="material-icons right">send</i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div align="center">
                <a href="#" onclick="openMod('login-modal')">o Iniciá Sesion</a>
            </div>
            <br>
            <br>
            <div class="row bottomsection" align="center">
                <a href="#know-more" class="waves-effect waves-light btn-large scroll-animation">Conoce más<i class="material-icons right">search</i></a>
            </div>
        </div>
    </div>
    <div id="know-more">
        <div class="parallax-container">
            <div class="parallax">
                <img src="img/teamwork1.jpg">
            </div>
        </div>
        <div class="section white"> 
            <div class="container">
                @if ($userType == "user")
                    <h3 class="header"><b>Organizá tu vida de forma inteliente</b></h3>
                    <blockquote>
                            <p class="p-more-info">Organizá tus tareas y medí sus impactos.</p>
                            <p class="p-more-info">Utilizalo para mejorar las ventas en tu negocio o simplemente en tu vida cotidiana.</p>
                            <p class="p-more-info">Todo este proceso online en Cyclum!</p>
                    </blockquote>
                @else
                    <h3 class="header"><b>Menos Desperdicios, Más Exitos</b></h3>
                    <blockquote>
                            <p class="p-more-info">Basá tus decisiones en estadísticas.</p>
                            <p class="p-more-info">Optimizá tu producto evaluando distintas versiones, y mejoralo según la reacción de tus clientes.</p>
                            <p class="p-more-info">Todo este proceso online en Cyclum!</p>
                    </blockquote>
                @endif
            </div>
        </div>
    </div>
    <div id="how-it-works">
        <div class="parallax-container">
            <div class="parallax">
                <img src="img/lean_startup.jpg">
            </div>
        </div>
        <div id="how-works" class="section white">
            <div class="container">
                <h3 class="header"><b>Cómo Funciona</b></h3>
                <div class="row">
                    @if ($userType == "user")
                        <div class="slider">
                            <ul class="slides">
                              <li>
                                <img class="slider-img-blur" src="img/organize.png">
                                <div class="caption center-align">
                                  <h3 class="black-text">Organizá tus tareas</h3>
                                  <h5 class="light black-text text-lighten-3">Te ayudamos a hacer una lista online de los cambios y tareas que tenés para hacer.</h5>
                                </div>
                              </li>
                              <li>
                                <img class="slider-img-blur" src="img/organize.png"> <!-- random image -->
                                <div class="caption center-align">
                                  <h3 class="black-text">Evaluá los cambios</h3>
                                  <h5 class="light black-text text-lighten-3">A partir de estadísticas, decidí si tus cambios fueron positivos o negativos.</h5>
                                </div>
                              </li>
                              <li>
                                <img class="slider-img-blur" src="img/measure.png"> <!-- random image -->
                                <div class="caption center-align">
                                  <h3 class="black-text">Analizá tu progreso</h3>
                                  <h5 class="light black-text text-lighten-3">Mira, por ejemplo, como variaron tus ganancias a lo largo del año, y qué cambios influyeron.</h5>
                                </div>
                              </li>
                            </ul>
                        </div>
                    @else
                        <div class="slider">
                            <ul class="slides">
                              <li>
                                <img class="slider-img-blur" src="img/organize.png">
                                <div class="caption center-align">
                                  <h3 class="black-text">Organizá tus tareas</h3>
                                  <h5 class="light black-text text-lighten-3">Asigná tareas de forma estratégica basándote en recomendaciones.</h5>
                                </div>
                              </li>
                              <li>
                                <img class="slider-img-blur" src="img/measure.png"> <!-- random image -->
                                <div class="caption center-align">
                                  <h3 class="black-text">Evaluá los cambios</h3>
                                  <h5 class="light black-text text-lighten-3">Obtené estadísticas para saber cuál es el impacto de un cambio en tus usuarios.</h5>
                                </div>
                              </li>
                              <li>
                                <img class="slider-img-blur" src="img/restart.jpg"> <!-- random image -->
                                <div class="caption center-align">
                                  <h3 class="black-text">Volvé a empezar</h3>
                                  <h5 class="light black-text text-lighten-3">Utilizá esta información para volver a comenzar el desarrollo y mejorar tu producto</h5>
                                </div>
                              </li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <footer class="page-footer">
        <div class="footer-copyright">
            <div class="container">
                © 2016 Cyclum.
                <a class="grey-text text-lighten-3" target="_blank" href="http://www.twitter.com/Cyclum.IO">Twitter</a>
                <a class="grey-text text-lighten-3" target="_blank" href="http://www.facebook.com/Cyclum.IO">Facebook</a>
                <a class="grey-text text-lighten-4 right scroll-animation" href="#nav-bar">Volver a arriba</a>
            </div>
        </div>
    </footer>
    <div id="login-modal" class="modal">
        <form role="form" method="POST" action="{{ url('/auth/login') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal-content">
                <h4>Login</h4>
                <div class="row">
                    <div class="input-field col s12">
                        <input placeholder="" id="login-email" name="email" type="email" value="{{ old('email') }}" class="validate">
                        <label for="login-email" data-error="incorrecto" data-success="correcto">E-mail</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input placeholder="" id="login-pass" name="password" type="password" class="validate">
                        <label for="login-pass">Contraseña</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input type="checkbox" name="remember" class="validate" checked="checked" id="login-remember">
                        <label for="login-remember">No cerrar sesion</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer row col s12">
                <a class="modal-action modal-close waves-effect waves-red btn-flat center col s6">Cancelar</a>
                <input onclick="return loginCheck();" type="submit" class="waves-effect waves-green btn-flat center col s6" value="Iniciar Sesion">
            </div>
        </form>
        <ul id="login-error" class="collection" style="display:none;"></ul>
        @include ('errors/list', ['errorType' => 'login'])
    </div>
    <div id="register-modal" class="modal">
        {!! Form::open(['url' => '/auth/register']) !!}
            <div class="modal-content">
                    <h4>Registrarse</h4>
                    @include('partials/register_form')
            </div>
                <div class="modal-footer row col s12">
                    <a class="modal-action modal-close waves-effect waves-red btn-flat center col s6">Cancelar</a>
                    <input onclick="return registerCheck();" type="submit" class="waves-effect waves-green btn-flat center col s6" value="Registrar">
                </div>
            <ul id="register-error" class="collection" style="display:none;"></ul>
            @include ('errors/list', ['errorType' => 'register'])
        {!! Form::close() !!}
    </div>
@stop