<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', '') - HiBuy</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" type="text/css" href="{{ asset('DataTables/DataTables-1.13.8/css/jquery.dataTables.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('asset/Favicon.png') }}" type="image/x-icon">
    <!-- Add this meta tag in the <head> section of your layout -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Dropzone CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.css" />

    <!-- Dropzone JS -->

</head>
@yield('main-content')

<script src="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.js"></script>
<script src="{{ asset('javascript/jquery.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('javascript/canvas.js') }}"></script>
<script type="text/javascript" src="{{ asset('DataTables/DataTables-1.13.8/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('javascript/script.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(window).on('load', function() {
        $('#loading').hide();
    })
    $(document).ready(function() {
        $('#datatable').DataTable();
        $('select').select2({
            width: '100%'
        });
        $('#Items_dropdown').select2({
            minimumResultsForSearch: Infinity
        });
    });
</script>
@yield('js')
</body>

</html>
