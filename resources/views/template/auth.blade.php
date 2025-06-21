<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Isarman Steger - Login</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport">
    <link rel="icon" href="/assets/img/logo.png" type="image/x-icon">

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="assets/css/demo.css" />
  </head>
  <body>
    <div class="wrapper">
      <div class="container">
        <div class="page-inner">
          <div class="row d-flex justify-content-center">
              <div class="col-md-6 d-flex justify-content-center">
                <div class="container text-center mx-auto p-0">
                  <div>
                      <img class="img-fluid" src="assets/img/logo.png" width="100px">
                  </div>
                  <h1>ISARMAN STEGER</h1>
                  <p>Jl. RE Martadinata, Pagar Dewa, Kec. Selebar, Kota Bengkulu</p>
                </div>
              </div>
          </div>
          <div class="row d-flex justify-content-center">
            <div class="col-md-4">
              @yield('content')
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/kaiadmin.min.js"></script>
    <script src="assets/js/setting-demo.js"></script>
    <script>
      function showAlert(title, message, state){
        var button = "";
        if(state == "success"){
            button = "btn btn-success";
        }else if(state == "error"){
            button = "btn btn-danger";
        }else if(state == "info"){
            button = "btn btn-info";
        }

        Swal.fire({
            title: title,
            text: message,
            icon: state,
            buttons: {
                confirm: {
                    className: button,
                },
            },
        });
      }
    </script>
    
    @if(Session::get('notify'))
    @include('template.notify')
    @endif

    @if(Session::get('sweetalert'))
    @include('template.sweetalert')
    @endif
        
  </body>
</html>
