@extends('layouts.app')

@section('content')


      <link rel="stylesheet" href="{{asset('assets/css/calendar/calendar-style-one.css')}}">
 
      <link rel='stylesheet' id='booked-icons-css' href="{{asset('assets/css/calendar/icons.css')}}" media='all' />
      <link rel='stylesheet' id='booked-styles-css' href="{{asset('assets/css/calendar/styles.css')}}" media='all' />
      <style rel='stylesheet' href="{{asset('assets/css/calendar/calendar-style.css')}}"></style>
      <link rel='stylesheet' id='brivona-servicebox-animation-css' href="{{asset('assets/css/calendar/servicebox-animation.min.css')}}" media='all' />
      <link rel='stylesheet' id='brivona-responsive-style-css' href="{{asset('assets/css/calendar/responsive.min.css')}}" media='all' />
      <link rel='stylesheet' id='brivona-last-checkpoint-css' href="{{asset('assets/css/calendar/brivona-last-checkpoint.min.css')}}" media='all' />
      <script  src="{{asset('assets/js/calendar/jquery.min.js')}}"></script>
      <!-- <script  src="{{asset('assets/js/calendar/jquery-migrate.min.js')}}" id="jquery-migrate-js"></script> -->
      <script  src="{{asset('assets/js/calendar/jquery.themepunch.tools.min.js')}}"></script>
      <script  src="{{asset('assets/js/calendar/jquery.themepunch.revolution.min.js')}}"></script>
      <script  src="{{asset('assets/js/calendar/jquery-resize.min.js')}}"></script>
      <script  src="{{asset('assets/js/calendar/jquery.blockUI.min.js')}}"></script>
      
      
      <style>
      .vc_custom_1547708474661{padding-top:65px !important;padding-bottom:15px !important}.vc_custom_1547708487513{padding-bottom:50px !important}
         .booked-calendar{
            background-color: #06A3DA !important;
         }
      </style>
      <div class="container-fluid py-5 ">
      <form action="{{ url('/make-appointment') }}" method="post" id="appointmentForm">
        @csrf <!-- {{ csrf_field() }} -->
        <input type="hidden" id="appointmentTime" name="appointmenttime">
        <input type="hidden" id="appointmentDate" name="appointmentdate">
        <input type="hidden" id="appointmentDoctor" name="doctor" value="{{$docId}}">
    </form>
      <div class="container" style="margin-bottom: 50px;">
         <div class="row">
            <div class="col-lg-6">
               <div class="section-title mb-4">
                  <h5 class="position-relative d-inline-block text-primary text-uppercase">Doctor Details</h5>  
                  <!-- <div class="section-title mb-4">
                     <h5 class="position-relative d-inline-block text-primary text-uppercase">About Us</h5>
                     <h1 class="display-5 mb-0">The World's Best Dental Clinic That You Can Trust</h1>
                  </div> -->
                  <div class="position-relative rounded-top" style="z-index: 1;">
                     <img class="img-fluid rounded-top" style="width: 50%;" src="{{asset($docData[0]->profile_pic)}}" alt="">
                  </div>
                  <h1 class="display-5 mb-0"> {{$docData[0]->name}} </h1>
                  <p class="mb-4">Tempor erat elitr rebum at clita. Diam dolor diam ipsum et tempor sit. Aliqu diam amet diam et eos labore. Clita erat ipsum et lorem et sit, sed stet no labore lorem sit. Sanctus clita duo justo et tempor eirmod magna dolore erat amet</p>
                  <div class="row g-3">
                     <div class="col-sm-6 wow zoomIn" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: zoomIn;">
                           <h5 class="mb-3"><i class="fa fa-check-circle text-primary me-3"></i>Award Winning</h5>
                           <h5 class="mb-3"><i class="fa fa-check-circle text-primary me-3"></i>Professional Staff</h5>
                     </div>
                     <div class="col-sm-6 wow zoomIn" data-wow-delay="0.6s" style="visibility: visible; animation-delay: 0.6s; animation-name: zoomIn;">
                           <h5 class="mb-3"><i class="fa fa-check-circle text-primary me-3"></i>24/7 Opened</h5>
                           <h5 class="mb-3"><i class="fa fa-check-circle text-primary me-3"></i>Fair Prices</h5>
                     </div>
                  </div>
                  <a class="btn btn-primary py-3 px-5 mt-4 wow zoomIn" data-wow-delay="0.6s" style="visibility: visible; animation-delay: 0.6s; animation-name: zoomIn;">Select a Date &nbsp;<i class="booked-icon booked-icon-arrow-right"></i></a>         
               </div>
            </div>
            <div class="col-lg-6">
               <div class="section-title mb-4">
                  <h5 class="position-relative d-inline-block text-primary text-uppercase">Select the Time-slot</h5>           
               </div>
               <div class="booked-calendar-wrap large" id="calendar-body">
                  {!!$calendarStr!!} 
               </div>
            </div>
         </div>
      </div>

         
      </div>
      <div id="yith-quick-view-modal">
         <div class="yith-quick-view-overlay"></div>
         <div class="yith-wcqv-wrapper">
            <div class="yith-wcqv-main">
               <div class="yith-wcqv-head"><a href="#" id="yith-quick-view-close" class="yith-wcqv-close">X</a></div>
               <div id="yith-quick-view-content" class="woocommerce single-product"></div>
            </div>
         </div>
      </div>
     
  
      <script src="{{asset('assets/js/calendar/spin.min.js')}}"></script>
      <script src="{{asset('assets/js/calendar/spin.jquery.js')}}"></script>
      <script src="{{asset('assets/js/calendar/jquery.tooltipster.min.js')}}"></script>
      <script src="{{asset('assets/js/calendar/functions.js')}}"></script>
      <script src="{{asset('assets/js/calendar/perfect-scrollbar.jquery.min.js')}}"></script>
      <script src="{{asset('assets/js/calendar/actions.js')}}?v={{time()}}"></script>
@endsection