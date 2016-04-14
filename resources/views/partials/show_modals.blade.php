@if (Session::has('show_modal'))
    <script type="text/javascript">
        $("#{{ session('show_modal') }}").openModal();
    </script>
@else
    
@endif