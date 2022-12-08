<!DOCTYPE html>
<html lang="en">


<!-- index.html  21 Nov 2019 03:44:50 GMT -->

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>@yield('titulo') - Sistema Diocese do Rio de janeiro</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ url('assets/css/app.min.css') }}">
  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ url('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ url('assets/css/components.css') }}">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="{{ url('assets/css/custom.css') }}">
  <!-- DataTables CSS-->
  <link rel="stylesheet" href="{{ url('assets/bundles/datatables/datatables.min.css') }}">
  <link rel="stylesheet"
    href="{{ url('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
  <!-- Baloo Paaji font -->
  <link href='https://fonts.googleapis.com/css?family=Baloo Paaji' rel='stylesheet'>
  <!-- CSS Select2 -->


  <link rel="stylesheet" href="{{ url('assets/bundles/select2/dist/css/select2.min.css') }}">


  <!-- Custom JS-->

  <!-- <link rel="text/javascript" href="{{ URL::asset('js/jquery.mask.min.js') }}">
  <link rel="text/javascript" href="{{ URL::asset('js/mask.js') }}"> -->

  <link rel='shortcut icon' type='image/x-icon' href="{{ url('assets/img/favicon.ico') }}" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body>
      <!-- conteudo das páginas -->
      @yield('content')
      <!--conteudo -->
      <footer>
        <!-- rodape-->
    </div>
  </div>
  <!-- General JS Scripts -->
  <script src="{{ url('assets/js/app.min.js') }}"></script>
  <!-- JS Libraies -->
  <script src="{{ url('assets/bundles/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ url('assets/bundles/owlcarousel2/dist/owl.carousel.min.js') }}"></script>
  <!-- Page Specific JS File -->
  <script src="{{ url('assets/js/page/index.js') }}"></script>
  <script src="{{ url('assets/js/page/owl-carousel.js') }}"></script>
  <!-- Template JS File -->
  <script src="{{ url('assets/js/scripts.js') }}"></script>
  <!-- Custom JS File -->
  <script src="{{ url('assets/js/custom.js') }}"></script>
  <!-- DataTables JS -->
  <script src="{{ url('assets/bundles/datatables/datatables.min.js') }}"></script>
  <script src="{{ url('assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ url('assets/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
  <script src="{{ url('assets/js/page/datatables.js') }}"></script>
  <!-- Select2 JS -->
  <script src="{{ url('assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>
  <!-- Sweetalerts JS -->
  <script src="{{url('assets/bundles/sweetalert/sweetalert.min.js')}}"></script>
</body>

</html>