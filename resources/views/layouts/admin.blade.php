<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>{{ config('app.name', 'Laravel') }}</title>
  <meta content="{{ csrf_token() }}" name="csrf-token">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
  <link href="{{asset('admin_assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('admin_assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('admin_assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{asset('admin_assets/vendor/quill/quill.snow.css')}}" rel="stylesheet">
  <link href="{{asset('admin_assets/vendor/quill/quill.bubble.css')}}" rel="stylesheet">
  <link href="{{asset('admin_assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
  <link href="{{asset('admin_assets/vendor/simple-datatables/style.css')}}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{asset('admin_assets/css/style.css')}}" rel="stylesheet">
  
  <link href="{{asset('admin_assets/css/daterangepicker.css')}}" rel="stylesheet">
  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

  <style>
    .overlay {
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        position: fixed;
        background: #22222296;
        display:none;
    }

    .overlay__inner {
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        position: absolute;
    }

    .overlay__content {
        left: 50%;
        position: absolute;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .spinner {
        width: 75px;
        height: 75px;
        display: inline-block;
        border-width: 2px;
        border-color: rgba(255, 255, 255, 0.05);
        border-top-color: #fff;
        animation: spin 1s infinite linear;
        border-radius: 100%;
        border-style: solid;
    }

    @keyframes spin {
      100% {
        transform: rotate(360deg);
      }
    }
  </style>
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="{{ url('/admin/dashboard') }}" class="logo d-flex align-items-center">
        <span class="d-none d-lg-block"><i class="fas fa-user-md me-2"></i> Admin</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <!-- <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div>End Search Bar -->
    <div class="overlay">
        <div class="overlay__inner">
            <div class="overlay__content"><span class="spinner"></span></div>
        </div>
    </div>
    
    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number">4</span>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              You have 4 new notifications
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-exclamation-circle text-warning"></i>
              <div>
                <h4>Lorem Ipsum</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>30 min. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-x-circle text-danger"></i>
              <div>
                <h4>Atque rerum nesciunt</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>1 hr. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-check-circle text-success"></i>
              <div>
                <h4>Sit rerum fuga</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>2 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-info-circle text-primary"></i>
              <div>
                <h4>Dicta reprehenderit</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>4 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>
            <li class="dropdown-footer">
              <a href="#">Show all notifications</a>
            </li>

          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="{{asset('admin_assets/img/user-image.jpg')}}" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">{{ Session::get('userAdminData')->name}}</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header"><h6>{{ Session::get('userAdminData')->name}}</h6></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ url('/admin/profile') }}">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ url('/admin/pagenotfound') }}">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ url('/admin/logout') }}">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
              </a>
            </li>

          </ul>
        </li>
      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ url('/admin/dashboard') }}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ url('/admin/appointments') }}">
          <i class="bi bi-menu-button-wide"></i>
          <span>Appointments</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ url('/admin/doctor-list') }}">
          <i class="bi bi-menu-button-wide"></i>
          <span>Doctors</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ url('/admin/add-specialization') }}">
          <i class="bi bi-menu-button-wide"></i>
          <span>Specialities</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ url('/admin/add-language') }}">
          <i class="bi bi-menu-button-wide"></i>
          <span>Languages</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ url('/admin/profile') }}">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li>

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main" style="min-height:582px;">

    @yield('content')

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>GrowthArk Media</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      Designed by <a href="https://growtharkmedia.com">GrowthArk Media</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script>
    var baseUrl = "{{ url('/') }}";
  </script>
  <!-- Vendor JS Files -->
  <script src="{{asset('admin_assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
  <script src="{{asset('admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('admin_assets/vendor/chart.js/chart.umd.js')}}"></script>
  <script src="{{asset('admin_assets/vendor/echarts/echarts.min.js')}}"></script>
  <script src="{{asset('admin_assets/vendor/quill/quill.js')}}"></script>
  <script src="{{asset('admin_assets/vendor/simple-datatables/simple-datatables.js')}}"></script>
  <script src="{{asset('admin_assets/vendor/tinymce/tinymce.min.js')}}"></script>
  <script src="{{asset('admin_assets/vendor/php-email-form/validate.js')}}"></script>

  <!-- Template Main JS File -->
  <script src="{{asset('admin_assets/js/main.js')}}"></script>
  <script src="{{asset('assets/js/jquery-3.4.1.min.js')}}"></script>

  <script src="{{asset('admin_assets/js/moment.min.js')}}"></script>
  <script src="{{asset('admin_assets/js/daterangepicker.min.js')}}"></script>
</body>
<script>
$(document).ready(function () { 
    $(document).on("click", ".booking-count" , function(e) { 
        $(".overlay").show();
        $.ajax({
            url: baseUrl + '/admin/get-dashboard-booking-data',
            type: 'post',
            data: {'period':$(this).attr('data-value')},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function( html ) {
                if(html){
                  console.log(html)
                    $("#bookingCount").html(html.booking.today_cnt);
                    $(".booking-count-per").html(html.booking.increase + '%');
                    if(html.booking.increase>=0){
                        $(".booking-count-per").removeClass('text-success');
                        $(".booking-count-per").removeClass('text-danger');
                        $(".booking-count-per").addClass('text-success');
                        $(".booking-count-trend").html('Increase');
                    }else{
                        $(".booking-count-per").removeClass('text-success');
                        $(".booking-count-per").removeClass('text-danger');
                        $(".booking-count-per").addClass('text-danger');
                        $(".booking-count-trend").html('Decrease');
                    }                    
                }
                $(".overlay").hide();
            }
        });
    });

    $(document).on("click", ".customer-count" , function(e) { 
        $(".overlay").show();
        $.ajax({
            url: baseUrl + '/admin/get-dashboard-booking-data',
            type: 'post',
            data: {'period':$(this).attr('data-value')},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function( html ) {
                if(html){
                  console.log(html)
                    $("#customerCount").html(html.customer.today_cnt);
                    $(".customer-count-per").html(html.customer.increase + '%');
                    if(html.customer.increase>=0){
                        $(".customer-count-per").removeClass('text-success');
                        $(".customer-count-per").removeClass('text-danger');
                        $(".customer-count-per").addClass('text-success');
                        $(".customer-count-trend").html('Increase');
                    }else{
                        $(".customer-count-per").removeClass('text-success');
                        $(".customer-count-per").removeClass('text-danger');
                        $(".customer-count-per").addClass('text-danger');
                        $(".customer-count-trend").html('Decrease');
                    }
                }
                $(".overlay").hide();
            }
        });
    });
});
</script>
</html>