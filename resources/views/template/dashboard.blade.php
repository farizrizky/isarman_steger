<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>Isaraman Steger - Dashboard</title>
        <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" >
        <link rel="icon" href="/assets/img/logo.png" type="image/x-icon">
        <script src="/assets/js/plugin/webfont/webfont.min.js"></script>
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
                urls: ["/assets/css/fonts.min.css"],
                },
                active: function () {
                sessionStorage.fonts = true;
                },
        });
        </script>
        <script src="/assets/js/core/jquery-3.7.1.min.js"></script>
        <link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="/assets/css/plugins.min.css" />
        <link rel="stylesheet" href="/assets/css/kaiadmin.min.css" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
        <link rel="stylesheet" href="/assets/css/demo.css" />
     </head>
  <body>
    <div class="wrapper">
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <div class="logo-header" data-background-color="dark">
                    <a href="/dashboard" class="logo">
                        <img src="/assets/img/logo-name.png" alt="navbar brand" class="navbar-brand" height="50">
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-left"></i>
                        </button>
                    </div>
                    <button class="topbar-toggler more">
                        <i class="gg-more-vertical-alt"></i>
                    </button>
                </div>
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    @include('template.navigation')
                </div>
            </div>
        </div>

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <div class="logo-header" data-background-color="dark">
                    <a href="index.html" class="logo">
                        <img src="/assets/img/logo.png" alt="navbar brand" class="navbar-brand" height="20">
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-left"></i>
                        </button>
                    </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                </div>
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                            {{-- <li class="nav-item topbar-icon dropdown hidden-caret">
                                <a
                                    class="nav-link dropdown-toggle"
                                    href="#"
                                    id="notifDropdown"
                                    role="button"
                                    data-bs-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                >
                                    <i class="fa fa-bell"></i>
                                    <span class="notification">4</span>
                                </a>
                                <ul
                                    class="dropdown-menu notif-box animated fadeIn"
                                    aria-labelledby="notifDropdown"
                                >
                                    <li>
                                        <div class="dropdown-title">
                                            You have 4 new notification
                                        </div>
                                    </li>
                                    <li>
                                        <div class="notif-scroll scrollbar-outer">
                                            <div class="notif-center">
                                            <a href="#">
                                                <div class="notif-icon notif-primary">
                                                <i class="fa fa-user-plus"></i>
                                                </div>
                                                <div class="notif-content">
                                                <span class="block"> New user registered </span>
                                                <span class="time">5 minutes ago</span>
                                                </div>
                                            </a>
                                            <a href="#">
                                                <div class="notif-icon notif-success">
                                                <i class="fa fa-comment"></i>
                                                </div>
                                                <div class="notif-content">
                                                <span class="block">
                                                    Rahmad commented on Admin
                                                </span>
                                                <span class="time">12 minutes ago</span>
                                                </div>
                                            </a>
                                            <a href="#">
                                                <div class="notif-img">
                                                <img
                                                    src="/assets/img/profile2.jpg"
                                                    alt="Img Profile"
                                                />
                                                </div>
                                                <div class="notif-content">
                                                <span class="block">
                                                    Reza send messages to you
                                                </span>
                                                <span class="time">12 minutes ago</span>
                                                </div>
                                            </a>
                                            <a href="#">
                                                <div class="notif-icon notif-danger">
                                                <i class="fa fa-heart"></i>
                                                </div>
                                                <div class="notif-content">
                                                <span class="block"> Farrah liked Admin </span>
                                                <span class="time">17 minutes ago</span>
                                                </div>
                                            </a>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="see-all" href="javascript:void(0);"
                                            >See all notifications<i class="fa fa-angle-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </li> --}}
                            {{-- <li class="nav-item topbar-icon dropdown hidden-caret">
                                <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                    <i class="fas fa-layer-group"></i>
                                </a>
                                <div class="dropdown-menu quick-actions animated fadeIn">
                                    <div class="quick-actions-header">
                                        <span class="title mb-1">Quick Actions</span>
                                        <span class="subtitle op-7">Shortcuts</span>
                                    </div>
                                    <div class="quick-actions-scroll scrollbar-outer">
                                        <div class="quick-actions-items">
                                            <div class="row m-0">
                                                <a class="col-6 col-md-4 p-0" href="#">
                                                    <div class="quick-actions-item">
                                                        <div class="avatar-item bg-danger rounded-circle">
                                                            <i class="far fa-calendar-alt"></i>
                                                        </div>
                                                        <span class="text">Calendar</span>
                                                    </div>
                                                </a>
                                                <a class="col-6 col-md-4 p-0" href="#">
                                                    <div class="quick-actions-item">
                                                        <div class="avatar-item bg-warning rounded-circle" >
                                                            <i class="fas fa-map"></i>
                                                        </div>
                                                        <span class="text">Maps</span>
                                                    </div>
                                                </a>
                                                <a class="col-6 col-md-4 p-0" href="#">
                                                    <div class="quick-actions-item">
                                                        <div class="avatar-item bg-info rounded-circle">
                                                            <i class="fas fa-file-excel"></i>
                                                        </div>
                                                        <span class="text">Reports</span>
                                                    </div>
                                                </a>
                                                <a class="col-6 col-md-4 p-0" href="#">
                                                    <div class="quick-actions-item">
                                                        <div class="avatar-item bg-success rounded-circle">
                                                            <i class="fas fa-envelope"></i>
                                                        </div>
                                                        <span class="text">Emails</span>
                                                    </div>
                                                </a>
                                                <a class="col-6 col-md-4 p-0" href="#">
                                                    <div class="quick-actions-item">
                                                        <div class="avatar-item bg-primary rounded-circle">
                                                            <i class="fas fa-file-invoice-dollar"></i>
                                                        </div>
                                                        <span class="text">Invoice</span>
                                                    </div>
                                                </a>
                                                <a class="col-6 col-md-4 p-0" href="#">
                                                    <div class="quick-actions-item">
                                                        <div class="avatar-item bg-secondary rounded-circle">
                                                            <i class="fas fa-credit-card"></i>
                                                        </div>
                                                        <span class="text">Payments</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li> --}}

                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                    <span class="profile-username">
                                        <span class="op-7">Hi,</span>
                                        <span class="fw-bold">{{ HUser::getUser()['fullname']}}</span><br>
                                        <small class="text-muted">({{ HUser::getUser()['role'] }})</small>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="u-text">
                                                    <h4>{{ HUser::getUser()['fullname']}}</h4>
                                                    <p class="text-muted">{{ HUser::getUser()['role'] }}</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            {{-- <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">My Profile</a>
                                            <a class="dropdown-item" href="#">My Balance</a>
                                            <a class="dropdown-item" href="#">Inbox</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Account Setting</a>
                                            <div class="dropdown-divider"></div> --}}
                                            <a class="dropdown-item" href="/logout">Logout</a>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>

            <div class="container">
                @yield('content')
            </div>

            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="copyright">
                        2025, CV Isaraman Steger Bengkulu. All rights reserved<br>
                        <i><small>Developed by Fariz Rizky Tanjung</small></i>
                    </div>
                    <div>
                        Theme by <a href="http://www.themekita.com">ThemeKita</a>. Distributed by
                        <a target="_blank" href="https://themewagon.com/">ThemeWagon</a>.
                    </div>
                </div>
            </footer>
        </div>
        <!-- End Navbar -->

        <!-- Custom template | don't include it in your project! -->
        <div class="custom-template">
            <div class="title">Settings</div>
            <div class="custom-content">
                <div class="switcher">
                    <div class="switch-block">
                        <h4>Logo Header</h4>
                        <div class="btnSwitch">
                            <button type="button" class="selected changeLogoHeaderColor" data-color="dark"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="blue"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="purple"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="light-blue"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="green" ></button>
                            <button type="button" class="changeLogoHeaderColor"  data-color="orange" ></button>
                            <button type="button" class="changeLogoHeaderColor"  data-color="red" ></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="white" ></button>
                            <br />
                            <button type="button"  class="changeLogoHeaderColor" data-color="dark2" ></button>
                            <button type="button" class="changeLogoHeaderColor"  data-color="blue2" ></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="purple2" ></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="light-blue2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="green2" ></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="orange2" ></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="red2" ></button>
                        </div>
                    </div>
                    <div class="switch-block">
                        <h4>Navbar Header</h4>
                        <div class="btnSwitch">
                            <button type="button"  class="changeTopBarColor" data-color="dark" ></button>
                            <button type="button" class="changeTopBarColor" data-color="blue" ></button>
                            <button  type="button" class="changeTopBarColor" data-color="purple" ></button>
                            <button type="button" class="changeTopBarColor" data-color="light-blue" ></button>
                            <button type="button" class="changeTopBarColor" data-color="green" ></button>
                            <button type="button" class="changeTopBarColor" data-color="orange" ></button>
                            <button type="button" class="changeTopBarColor" data-color="red" ></button>
                            <button type="button" class="selected changeTopBarColor" data-color="white" ></button>
                            <br />
                            <button type="button" class="changeTopBarColor" data-color="dark2" ></button>
                            <button type="button" class="changeTopBarColor" data-color="blue2" ></button>
                            <button type="button" class="changeTopBarColor" data-color="purple2" ></button>
                            <button type="button" class="changeTopBarColor" data-color="light-blue2" ></button>
                            <button type="button"  class="changeTopBarColor"  data-color="green2" ></button>
                            <button type="button" class="changeTopBarColor" data-color="orange2" ></button>
                            <button  type="button" class="changeTopBarColor" data-color="red2" ></button>
                        </div>
                    </div>
                    <div class="switch-block">
                        <h4>Sidebar</h4>
                        <div class="btnSwitch">
                            <button type="button" class="changeSideBarColor" data-color="white" ></button>
                            <button  type="button"  class="selected changeSideBarColor" data-color="dark" ></button>
                            <button type="button" class="changeSideBarColor" data-color="dark2" ></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="/assets/js/core/popper.min.js"></script>
        <script src="/assets/js/core/bootstrap.min.js"></script>
        <script src="/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.8.1"></script>
        <script src="/assets/js/plugin/datatables/datatables.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
        <script src="/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="/assets/js/kaiadmin.min.js"></script>
        <script src="/assets/js/setting-demo.js"></script>
        <script>
            
            (() => {
                'use strict'

                const forms = document.querySelectorAll('.needs-validation')

                Array.from(forms).forEach(form => {
                    form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                    }, false)
                })
            })()

            function formatingNumber(className){
                new AutoNumeric.multiple('.'+className, { 
                    digitGroupSeparator : '.',
                    decimalCharacter : ',',
                    decimalPlaces : 0,
                    minimumValue : 0,
                    unformatOnSubmit : true
                });
            }

            function unformatingNumber(number){
                var unformat = number.replace(/\./g, '');
                unformat = unformat.replace(/,/g, '.');
                return unformat;
            }

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

            function confirmAlert(action, text){
                Swal.fire({
                    title: "Peringatan!",
                    text: text,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Lanjutkan",
                    cancelButtonText: "Batalkan",        
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = action
                    } else {
                        swal.close();
                    }
                });
            }

            $(document).ready(function(){
                formatingNumber('currency');
            });

        </script>

        @yield('script')
        
        @if(Session::get('notify'))
        @include('template.notify')
        @endif

        @if(Session::get('sweetalert'))
        @include('template.sweetalert')
        @endif
        
     </body>
</html>
