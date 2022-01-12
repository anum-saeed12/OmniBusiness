<!DOCTYPE html>
<html>
<head>
    @include('includes.head')
</head>
<body class="hold-transition{{ isset($class['body']) ? " {$class['body']}" : '' }}">
    @yield('content')

    @include('includes.js')
</body>
</html>
