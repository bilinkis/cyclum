<!DOCTYPE html>
<html>
    <head>
        <!--Import Google Icon Font-->
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/css/materialize.min.css">
        <!-- Ryan CSS -->
        <link type="text/css" rel="stylesheet" href="../css/main.css"  media="screen,projection"/>
        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!--Roboto font-->
        <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
        {!! Html::style('css/main.css') !!}
        
        @yield('head')
    </head>
    <body>
        <nav id="main-navbar" class="main-navbar">
            <div class="nav-wrapper">
                @yield('navBarTabs')
            </div>
        </nav>
        @yield('content')
        
        <!-- Import jQuery before materialize.js -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
        <!-- Import jQuery UI -->
        <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
        <!-- Compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/js/materialize.min.js"></script>
        <!-- Migue JS -->
        <script type="text/javascript" src="../js/main.js"></script>
        <!-- JQuery Touch Punch -->
        <script type="text/javascript" src="../js/src/jquery.ui.touch-punch.min.js"></script>
        <!-- Google column chart -->
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <!-- Google pie chart // change version in future -->
        <script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>
        
        @include('partials.toast')
        @include('partials.show_modals')
        
        @yield('footer')
    </body>
</html>