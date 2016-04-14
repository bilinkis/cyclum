@if (Session::has('flash_message'))
    <script type="text/javascript">
        Materialize.toast("{{ session('flash_message') }}", 4000);
    </script>
@endif