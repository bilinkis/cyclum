@if ($errors->any() && ($errorType == session('type') || $errorType == 'any'))
    <ul id="error-list" class="collection">
        @foreach ($errors->all() as $error)
            <li class="collection-item error">{{ $error }}</li>
        @endforeach
    </ul>
@endif