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

    .word-wrap-custom{
      overflow: hidden;
      white-space: nowrap; /* Don't forget this one */
      text-overflow: ellipsis;
    }
    
  @media (min-width:641px)  {

    .appt-search-button{
      width: initial;
    }
  }
  @media (min-width:961px){

    .appt-search-button{
      width: initial;
    }
  }  
  @media (min-width:1025px) and (max-width:1488px) { 

    .appt-search-button{
      width: initial;
    }
  }
  @media only screen and (min-width: 1489px) {

    .appt-search-button{
      width: initial;
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

        <!-- <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li>End Search Icon -->

        <li class="nav-item dropdown">

          <!-- <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number">4</span>
          </a> -->
          <!-- End Notification Icon -->

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
            <span class="d-none d-md-block dropdown-toggle ps-2">
           
              @if(isset(Session::get('userAdminData')->name))         
                {{Session::get('userAdminData')->name}}
              @else
                  
              @endif
            </span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header"><h6>
              @if(isset(Session::get('userAdminData')->name))         
                {{Session::get('userAdminData')->name}}
              @else
                  
              @endif  
            </h6></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ url('/admin/profile') }}">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <!-- <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ url('/admin/pagenotfound') }}">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li> -->
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
    <div class="modal fade" id="registrationModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Registration</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6 mb-4">

              <div data-mdb-input-init class="form-outline">
                  <input type="text" id="firstName" class="form-control form-control-lg" placeholder="First Name" />
                  <!-- <label class="form-label" for="firstName">First Name</label> -->
              </div>

              </div>
              <div class="col-md-6 mb-4">

              <div data-mdb-input-init class="form-outline">
                  <input type="text" id="lastName" class="form-control form-control-lg" placeholder="Last Name" />
                  <!-- <label class="form-label" for="lastName">Last Name</label> -->
              </div>

              </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-4 pb-2">

                <div data-mdb-input-init class="form-outline">
                    <input type="email" id="emailAddress" class="form-control form-control-lg" placeholder="Email" />
                    <!-- <label class="form-label" for="emailAddress">Email</label> -->
                </div>

                </div>
                <div class="col-md-6 mb-4 pb-2">

                <div data-mdb-input-init class="form-outline">
                    <input type="tel" id="phoneNumber" class="form-control form-control-lg" placeholder="Phone Number" />
                    <!-- <label class="form-label" for="phoneNumber">Phone Number</label> -->
                </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4 d-flex align-items-center">

                <div data-mdb-input-init class="form-outline datepicker w-100">
                    <input type="date" class="form-control form-control-lg" id="dob" />
                    <label for="birthdayDate" class="form-label">Birthday</label>
                </div>

                </div>
                <div class="col-md-6 mb-4">

                <h6 class="mb-2 pb-1">Gender: </h6>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" value="Female" checked />
                    <label class="form-check-label" for="femaleGender">Female</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" value="Male" />
                    <label class="form-check-label" for="maleGender">Male</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" value="other" />
                    <label class="form-check-label" for="otherGender">Other</label>
                </div>

                </div>
            </div>
            <div class="row">
              <div class="col-md-12 mb-12">
                <span id="errors"></span>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="close-modal-reg" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary book_appointment" >Book Appointment</button>
          </div>
        </div>
      </div>
    </div>
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
  <script src="{{asset('admin_assets/js/dashboard.js')}}?v={{time()}}"></script>
</body>
</html>