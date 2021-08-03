<!DOCTYPE html>
<html>
   @include('layouts.admin.header')
  <body class="hold-transition sidebar-mini">
    <div class="wrapper">
      @include('layouts.admin.navbar')
      @include('layouts.admin.sidebar')
      @yield('content')
      @include('layouts.admin.footer')
    <script>
      $(function() {
        $("#example1").DataTable({
          "responsive": true,
          "autoWidth": false,
        });
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": true,
        });
      });
    </script>
  </body>
</html>