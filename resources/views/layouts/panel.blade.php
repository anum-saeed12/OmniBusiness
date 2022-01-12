<!DOCTYPE html>
<html>
<head>
    @include('includes.head')
</head>

<body class="hold-transition sidebar-mini text-sm">
    <div class="wrapper">

        @include('includes.nav')

        @include('includes.sidebar')

        {{-- Content Wrapper. Contains page content --}}
        <div class="content-wrapper">

            @yield('breadcrumbs')

            @yield('content')

        </div>

        @include('includes.footer')
    </div>

    @include('includes.js')

    @yield('extras')
    @yield('filters.js')
</body>

</html>
