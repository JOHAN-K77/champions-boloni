<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel App')</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div class="wrapper">

    @include('partials.sidebar')

    <div class="content-wrapper">

        @include('partials.header')

        <div class="page-content">
            @yield('content')
        </div>

        @include('partials.footer')

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

    // Sidebar collapse
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');

    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
    });

    // Dropdown sidebar menu
    document.querySelectorAll('.menu-dropdown').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            this.parentElement.classList.toggle('menu-open');
        });
    });

</script>
<script src="{{ asset('js/finance/finance.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

</body>
</html>