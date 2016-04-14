@section('navBarTabs')
    @if(isset($backButton))
        <a href="/home" class="left hide-on-med-and-down nav-margin"><i class="material-icons">arrow_back</i></a>
        <a href="#" class="brand-logo"><img class="nav-bar-logo" src="../img/logochico2.png"></a>
        <a href="/home" class="hide-on-med-and-down brand-logo center">Cyclum</a>
    @else
        <a href="#" class="brand-logo nav-margin"><img class="nav-bar-logo" src="img/logochico2.png"></a>
        <a href="/home" class="hide-on-med-and-down brand-logo center">Cyclum</a>
    @endif
    @if(isset($user))
        <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
        <ul class="right hide-on-med-and-down">
            <li><a href="/projectstats">{{ $user->teamName }}</a></li>
            <li><a href="/account">{{ $user->name }}</a></li>
            <li><a href="/auth/logout">Cerrar sesión</a></li>
        </ul>
        <ul class="side-nav" id="mobile-demo">
            @if(isset($backButton))
                <li><a href="/home"><i class="material-icons">arrow_back</i></a></li>
            @endif
            <li><a href="/projectstats">{{ $user->teamName }}</a></li>
            <li><a href="/account">{{ $user->name }}</a></li>
            <li><a href="/auth/logout">Cerrar sesión</a></li>
        </ul>
    @else
        <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
        <ul id="nav-bar" class="right hide-on-med-and-down">
            <li><a class="scroll-animation" href="#main-header">Inicio</a></li>
            <li><a class="scroll-animation" href="#know-more">Conoce Más</a></li>
            <li><a class="scroll-animation" href="#how-works">Como Funciona</a></li>
        </ul>
        <ul id="nav-bar" class="side-nav" id="mobile-demo">
            <li><a class="scroll-animation" href="#main-header" onclick="closeNav();">Inicio</a></li>
            <li><a class="scroll-animation" href="#know-more" onclick="closeNav();">Conoce Más</a></li>
            <li><a class="scroll-animation" href="#how-works" onclick="closeNav();">Cómo Funciona</a></li>
        </ul>
    @endif
@stop