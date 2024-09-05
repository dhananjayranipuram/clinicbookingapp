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
        <input type="hidden" id="appointmentDoctor" name="doctor">
    </form>
      <div class="container" style="margin-bottom: 50px;">
      <div class="section-title mb-4">
                    <h5 class="position-relative d-inline-block text-primary text-uppercase">Select the Doctor And Time-Slot</h5>
                    
                </div>
                <div class="booked-calendar-wrap large" id="calendar-body">
            
                {!!$calendarStr!!}
                
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
      <script src="{{asset('assets/js/calendar/actions_day.js')}}?v={{time()}}"></script>
@endsection