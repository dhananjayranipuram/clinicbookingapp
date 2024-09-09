<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <!-- Favicon -->
    <link href="{{asset('assets/img/favicon.png')}}" rel="icon">

    <!-- Google Web Fonts -->
    <link href="{{asset('assets/css/css2.css')}}" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{asset('assets/lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/lib/animate/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/lib/twentytwenty/twentytwenty.css')}}" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/filter-style.css')}}" rel="stylesheet">
    <style>

        .btn-open-popup {
            padding: 12px 24px;
            font-size: 18px;
            background-color: green;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-open-popup:hover {
            background-color: #4caf50;
        }

        .overlay-container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 10000;
        }

        .popup-box {
            background: #fff;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
            width: 60%;
            text-align: center;
            opacity: 0;
            transform: scale(0.8);
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .login-box {
            background: #fff;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
            width: 25%;
            text-align: center;
            opacity: 0;
            transform: scale(0.8);
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .form-container {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            margin-bottom: 10px;
            font-size: 16px;
            color: #444;
            text-align: left;
        }

        .form-input {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
        }

        .btn-submit,
        .btn-close-popup {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-submit {
            background-color: #06A3DA;
            color: #fff;
        }

        .btn-close-popup {
            margin-top: 12px;
            background-color: #e74c3c;
            color: #fff;
        }

        .btn-submit:hover,
        .btn-close-popup:hover {
            background-color: #4caf50;
        }

        /* Keyframes for fadeInUp animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animation for popup */
        .overlay-container.show {
            display: flex;
            opacity: 1;
        }

        .gradient-custom {
        /* fallback for old browsers */
        background: #f093fb;

        /* Chrome 10-25, Safari 5.1-6 */
        background: -webkit-linear-gradient(to bottom right, rgba(240, 147, 251, 1), rgba(245, 87, 108, 1));

        /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        background: linear-gradient(to bottom right, rgba(240, 147, 251, 1), rgba(245, 87, 108, 1))
        }

        .card-registration .select-input.form-control[readonly]:not([disabled]) {
        font-size: 1rem;
        line-height: 2.15;
        padding-left: .75em;
        padding-right: .75em;
        }
        .card-registration .select-arrow {
        top: 13px;
        }

    </style>
    <style>

.action {
  /* position: fixed; */
  /* top: 20px; */
  right: 30px;
}

.action .profile {
  position: relative;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  overflow: hidden;
  cursor: pointer;
  text-align: center;
}

.action .profile img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.action .menu {
  position: absolute;
  top: 120px;
  right: 20px;
  padding: 10px 20px;
  background: #e5f0f6;
  width: 200px;
  box-sizing: 0 5px 25px rgba(0, 0, 0, 0.1);
  border-radius: 15px;
  transition: 0.5s;
  visibility: hidden;
  opacity: 0;
}

.action .menu.active {
  top: 80px;
  visibility: visible;
  opacity: 1;
  z-index:10;
}

.action .menu::before {
  content: "";
  position: absolute;
  top: -5px;
  right: 28px;
  width: 20px;
  height: 20px;
  background: #e5f0f6;
  transform: rotate(45deg);
}

.action .menu h3 {
  width: 100%;
  text-align: center;
  font-size: 18px;
  padding: 20px 0;
  font-weight: 500;
  color: #555;
  line-height: 1.5em;
}

.action .menu h3 span {
  font-size: 14px;
  color: #cecece;
  font-weight: 300;
}

.action .menu ul li {
  list-style: none;
  padding: 16px 0;
  border-top: 1px solid rgba(0, 0, 0, 0.05);
  display: flex;
  align-items: center;
}

.action .menu ul li img {
  max-width: 20px;
  margin-right: 10px;
  opacity: 0.5;
  transition: 0.5s;
}

.action .menu ul li:hover img {
  opacity: 1;
}

.action .menu ul li a {
  display: inline-block;
  text-decoration: none;
  color: #555;
  font-weight: 500;
  transition: 0.5s;
}

.action .menu ul li:hover a {
  color: #ff5d94;
}

@media screen and (min-width: 320px) and (max-width: 480px) { 
/* smartphones, iPhone, portrait 480x320 phones */ 
    .popup-box{
        width:90% !important;
    }

    .login-box{
        width:90% !important;
    }
}
@media screen and (min-width: 481px) and (max-width: 640px) { 
/* portrait e-readers (Nook/Kindle), smaller tablets @ 600 or @ 640 wide. */
    .popup-box{
        width:75% !important;
    }

    .login-box{
        width:75% !important;
    }
}
@media screen and (min-width: 641px) and (max-width: 960px) { 
/* portrait tablets, portrait iPad, landscape e-readers, landscape 800x480 or 854x480 phones */
    .popup-box{
        width:50% !important;
    }

    .login-box{
        width:50% !important;
    }
}
@media (min-width:961px)  { /* tablet, landscape iPad, lo-res laptops ands desktops */ }
@media (min-width:1025px) { /* big landscape tablets, laptops, and desktops */ }
@media (min-width:1281px) { /* hi-res laptops and desktops */ }
    </style>

<style>
    .overlay {
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        position: fixed;
        background: #22222296;
        display:none;
        z-index: 10000000;
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
    <!-- Spinner Start -->
    <!-- <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary m-1" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <div class="spinner-grow text-dark m-1" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <div class="spinner-grow text-secondary m-1" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div> -->

    <div class="overlay">
        <div class="overlay__inner">
            <div class="overlay__content"><span class="spinner"></span></div>
        </div>
    </div>
    <!-- Spinner End -->


    <!-- Topbar Start -->
    <div class="container-fluid bg-light ps-5 pe-0 d-none d-lg-block">
        <div class="row gx-0">
            <div class="col-md-6 text-center text-lg-start mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center">
                    <small class="py-2"><i class="far fa-clock text-primary me-2"></i>Opening Hours: Mon - Tues : 6.00 am - 10.00 pm, Sunday Closed </small>
                </div>
            </div>
            <div class="col-md-6 text-center text-lg-end">
                <div class="position-relative d-inline-flex align-items-center bg-primary text-white top-shape px-5">
                    <div class="me-3 pe-3 border-end py-2">
                        <p class="m-0"><i class="fa fa-envelope-open me-2"></i>info@example.com</p>
                    </div>
                    <div class="py-2">
                        <p class="m-0"><i class="fa fa-phone-alt me-2"></i>+012 345 6789</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow-sm px-5 py-3 py-lg-0">
        <a href="{{ url('/home') }}" class="navbar-brand p-0">
            <h1 class="m-0 text-primary"><i class="fas fa-user-md me-2"></i>Clinic Booking</h1>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0">
                <a href="{{ url('/home') }}" class="nav-item nav-link @if(Request::path() === 'home') active @endif">Home</a>
                <a href="{{ url('/home') }}?#aboutus-section" class="nav-item nav-link @if(Request::path() === 'home') active @endif">About</a>
                <a href="{{ url('/speciality') }}" class="nav-item nav-link @if(Request::path() === 'speciality') active @endif">Book Appointment</a>
                <!-- <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Book Appointment</a>
                    <div class="dropdown-menu m-0">
                        <a href="{{ url('/doctors') }}" class="dropdown-item">Search by Doctor</a>
                        <a href="{{ url('/calendar') }}" class="dropdown-item">Search by Day</a>
                    </div>
                </div> -->
                <a href="{{ url('/contact-us') }}" class="nav-item nav-link @if(Request::path() === 'contact-us') active @endif">Contact</a>
                
                
            </div>
            <!-- <button type="button" class="btn text-dark" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fa fa-search"></i></button>
            <a href="appointment.html" class="btn btn-primary py-2 px-4 ms-3">Appointment</a> -->
        </div>
        @if(session()->has('userName'))
                <div class="action">
                <div class="profile" onclick="menuToggle();">
                    <i class="fa fa-user" style="font-size:36px"></i>
                </div>
                <div class="menu">
                    <h3>{{ Session::get('userName')}}<br /></h3>
                    <ul>
                    <li>
                        <a href="#">My profile</a>
                    </li>
                    <li>
                        <a href="{{ url('/logout') }}">Logout</a>
                    </li>
                    </ul>
                </div>
                </div>
                @else 
                    <a onclick="toggleRegistration();" class="btn btn-primary py-2 px-4 ms-3">Register</a>
                    <a onclick="toggleLogin();" class="btn btn-primary py-2 px-4 ms-3">Login</a>
                @endif
    </nav>
    <!-- Navbar End -->

    <main class="py-4">
        @yield('content')
    </main>
    <div id="popupRegistration" class="overlay-container">
        <div class="popup-box">
            <h2>Registration</h2>
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
                    <div class="col-md-6 mb-4 pb-2">

                    <div data-mdb-input-init class="form-outline">
                        <input type="password" id="password" class="form-control form-control-lg" placeholder="Password" />
                        <!-- <label class="form-label" for="emailAddress">Password</label> -->
                    </div>

                    </div>
                    <div class="col-md-6 mb-4 pb-2">

                    <div data-mdb-input-init class="form-outline">
                        <input type="password" id="confirmPassword" class="form-control form-control-lg" placeholder="Confirm  Password" />
                        <!-- <label class="form-label" for="phoneNumber">Confirm  Password</label> -->
                    </div>

                    </div>
                </div>

                <div class="mt-4 pt-2">
                    <label >Already registered? <a onclick="toggleLogin();" style="cursor:pointer; color:blue;">Login</a></label><br>
                    <input class="btn btn-primary btn-lg" type="button" onclick="registration();" value="Submit" />
                    <input class="btn-close-popup" type="button" onclick="closeAllPopup();" value="Close" />
                    <!-- <button class="btn-close-popup" onclick="closeAllPopup();">Close</button> -->
                </div>
            
        </div>
    </div>

    <div id="popupLogin" class="overlay-container">
        <div class="login-box">
            <h2>Login</h2>
            <div class="row">
                <div class="col-md-12 mb-4 pb-2">

                    <div data-mdb-input-init class="form-outline">
                        <input type="email" id="loginEmailAddress" class="form-control form-control-lg" placeholder="Email" />
                        <!-- <label class="form-label" for="loginEmailAddress">Email</label> -->
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-4 pb-2">

                    <div data-mdb-input-init class="form-outline">
                        <input type="password" id="loginPassword" class="form-control form-control-lg" placeholder="Password" />
                        <!-- <label class="form-label" for="emailAddress">Password</label> -->
                    </div>

                </div>
            </div>

            <div class="mt-4 pt-2">
                <label >Not registered? <a onclick="toggleRegistration();" style="cursor:pointer; color:blue;">Register here</a></label><br>
                <input class="btn btn-primary btn-lg" type="button" onclick="login();" value="Login" />
                <input class="btn-close-popup" type="button" onclick="closeAllPopup();" value="Close" />
                <div id="loginMessage" style="color:red;"></div>
            </div>            
        </div>
    </div>

    <div id="popupMessage" class="overlay-container">
        <div class="login-box">
            <h2>Message</h2>
            <div id="message"></div>
            <input class="btn-close-popup" type="button" onclick="closeMessagePopup();" value="Close" />
        </div>
    </div>
    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light py-5 wow fadeInUp" data-wow-delay="0.3s" style="margin-top: -75px;">
        <div class="container pt-5">
            <div class="row g-5 pt-4">
                <div class="col-lg-3 col-md-6">
                    <h3 class="text-white mb-4">Quick Links</h3>
                    <div class="d-flex flex-column justify-content-start">
                        <a class="text-light mb-2" href="{{ url('/home') }}"><i class="bi bi-arrow-right text-primary me-2"></i>Home</a>
                        <a class="text-light mb-2" href="{{ url('/home') }}?#aboutus-section"><i class="bi bi-arrow-right text-primary me-2"></i>About Us</a>
                        <a class="text-light mb-2" href="{{ url('/doctors') }}"><i class="bi bi-arrow-right text-primary me-2"></i>Search by Doctor</a>
                        <a class="text-light mb-2" href="{{ url('/calendar') }}"><i class="bi bi-arrow-right text-primary me-2"></i>Search by Day</a>
                        <a class="text-light" href="{{ url('/contact-us') }}"><i class="bi bi-arrow-right text-primary me-2"></i>Contact Us</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h3 class="text-white mb-4">Popular Links</h3>
                    <div class="d-flex flex-column justify-content-start">
                        <a class="text-light mb-2" href="{{ url('/home') }}"><i class="bi bi-arrow-right text-primary me-2"></i>Home</a>
                        <a class="text-light mb-2" href="{{ url('/home') }}?#aboutus-section"><i class="bi bi-arrow-right text-primary me-2"></i>About Us</a>
                        <a class="text-light mb-2" href="{{ url('/doctors') }}"><i class="bi bi-arrow-right text-primary me-2"></i>Search by Doctor</a>
                        <a class="text-light mb-2" href="{{ url('/calendar') }}"><i class="bi bi-arrow-right text-primary me-2"></i>Search by Day</a>
                        <a class="text-light" href="{{ url('/contact-us') }}"><i class="bi bi-arrow-right text-primary me-2"></i>Contact Us</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h3 class="text-white mb-4">Get In Touch</h3>
                    <p class="mb-2"><i class="bi bi-geo-alt text-primary me-2"></i>123 Street, New York, USA</p>
                    <p class="mb-2"><i class="bi bi-envelope-open text-primary me-2"></i>info@example.com</p>
                    <p class="mb-0"><i class="bi bi-telephone text-primary me-2"></i>+012 345 67890</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h3 class="text-white mb-4">Follow Us</h3>
                    <div class="d-flex">
                        <a class="btn btn-lg btn-primary btn-lg-square rounded me-2" href="#"><i class="fab fa-twitter fw-normal"></i></a>
                        <a class="btn btn-lg btn-primary btn-lg-square rounded me-2" href="#"><i class="fab fa-facebook-f fw-normal"></i></a>
                        <a class="btn btn-lg btn-primary btn-lg-square rounded me-2" href="#"><i class="fab fa-linkedin-in fw-normal"></i></a>
                        <a class="btn btn-lg btn-primary btn-lg-square rounded" href="#"><i class="fab fa-instagram fw-normal"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid text-light py-4" style="background: #051225;">
        <div class="container">
            <div class="row g-0">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-md-0">&copy; <a class="text-white border-bottom" href="https://growtharkmedia.com">GrowthArk Media</a>. All Rights Reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">Designed by <a class="text-white border-bottom" href="https://growtharkmedia.com">GrowthArk Media</a></p>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="{{asset('assets/js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/lib/wow/wow.min.js')}}"></script>
    <script src="{{asset('assets/lib/easing/easing.min.js')}}"></script>
    <script src="{{asset('assets/lib/waypoints/waypoints.min.js')}}"></script>
    <script src="{{asset('assets/lib/owlcarousel/owl.carousel.min.js')}}"></script>
    <script src="{{asset('assets/lib/tempusdominus/js/moment.min.js')}}"></script>
    <script src="{{asset('assets/lib/tempusdominus/js/moment-timezone.min.js')}}"></script>
    <script src="{{asset('assets/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js')}}"></script>
    <script src="{{asset('assets/lib/twentytwenty/jquery.event.move.js')}}"></script>
    <script src="{{asset('assets/lib/twentytwenty/jquery.twentytwenty.js')}}"></script>
    
    <!-- Template Javascript -->
    <script src="{{asset('assets/js/main.js')}}"></script>
    <script>
        var baseUrl = "{{ url('/') }}";
    </script>
    <script>
        function toggleRegistration() {
            $('#popupRegistration').addClass('show');
            $('#popupLogin').removeClass('show');
            $('#popupMessage').removeClass('show');
        }

        function toggleLogin() {
            $('#popupLogin').addClass('show');
            $('#popupRegistration').removeClass('show');
            $('#popupMessage').removeClass('show');
        }

        function closeAllPopup(){
            $('#popupRegistration').removeClass('show');
            $('#popupLogin').removeClass('show');
        }
        
        function registration(){
            var datas = {
                'firstName': $("#firstName").val(),
                'lastName': $("#lastName").val(),
                'emailAddress': $("#emailAddress").val(),
                'phoneNumber': $("#phoneNumber").val(),
                'dob': $("#dob").val(),
                'gender':$('input[name="gender"]:checked').val(),
                'password': $("#password").val(),
                'confirmPassword': $("#confirmPassword").val(),
            };
            if(datas.password == datas.confirmPassword){
                $.ajax({
                    url: baseUrl + '/registration',
                    type: 'post',
                    data: datas,
                    dataType: "json",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(res) {
                        $('#popupRegistration').removeClass('show');
                        if(res.status==200){
                            $("#message").html('<span style="color:green;">'+res.message+'</span><br><span onclick="toggleLogin();">Click here to Login</span>');
                        }else{
                            $("#message").html('<span style="color:red;">'+res.message+' User not registered.</span>');
                        }
                        $('#popupMessage').addClass('show');
                    }
                });
            }else{
            }
        }

        function closeMessagePopup(){
            $('#popupMessage').removeClass('show');
        }

        function login(){
            
            var datas = {
                'email': $("#loginEmailAddress").val(),
                'password': $("#loginPassword").val()
            };
            $.ajax({
                url: baseUrl + '/user-login',
                type: 'post',
                data: datas,
                dataType: "json",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(res) {
                    if(res.status==200){
                        $('#popupLogin').removeClass('show');
                        chk = 0;
                        if($("#appointmentDate").val() == undefined || $("#appointmentDate").val() == ''){
                            chk = 1;
                        }
                        if($("#appointmentTime").val() == undefined || $("#appointmentTime").val() == ''){
                            chk = 1;
                        }
                        if($("#appointmentDoctor").val() == undefined || $("#appointmentDoctor").val() == ''){
                            chk = 1;
                        }
                        if(chk == 0){
                            $(".overlay").show();
                            $("#appointmentForm").submit();
                        }else{
                            location.reload();
                        }
                    }else{
                        $("#loginMessage").html(res.message);
                    }
                }
            });
        }

        function logout(){
            $.ajax({
                url: baseUrl + '/logout',
                type: 'post',
                data: [],
                dataType: "json",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(res) {
                    // console.log(res)
                    if(res==true){
                        location.reload();
                    }
                }
            });
        }

        function menuToggle() {
            const toggleMenu = document.querySelector(".menu");
            toggleMenu.classList.toggle("active");
        }
    </script>
</body>

</html>