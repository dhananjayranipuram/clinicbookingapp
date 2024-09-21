@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="{{asset('calendar_assets/css/calendar/calendar-style-one.css')}}">
 
<link rel='stylesheet' id='booked-icons-css' href="{{asset('calendar_assets/css/calendar/icons.css')}}" media='all' />
<link rel='stylesheet' id='booked-styles-css' href="{{asset('calendar_assets/css/calendar/styles.css')}}" media='all' />
<style rel='stylesheet' href="{{asset('calendar_assets/css/calendar/calendar-style.css')}}"></style>
<link rel='stylesheet' id='brivona-servicebox-animation-css' href="{{asset('calendar_assets/css/calendar/servicebox-animation.min.css')}}" media='all' />
<link rel='stylesheet' id='brivona-responsive-style-css' href="{{asset('calendar_assets/css/calendar/responsive.min.css')}}" media='all' />
<link rel='stylesheet' id='brivona-last-checkpoint-css' href="{{asset('calendar_assets/css/calendar/brivona-last-checkpoint.min.css')}}" media='all' />
<script  src="{{asset('calendar_assets/js/calendar/jquery.min.js')}}"></script>
<!-- <script  src="{{asset('calendar_assets/js/calendar/jquery-migrate.min.js')}}" id="jquery-migrate-js"></script> -->
<script  src="{{asset('calendar_assets/js/calendar/jquery.themepunch.tools.min.js')}}"></script>
<script  src="{{asset('calendar_assets/js/calendar/jquery.themepunch.revolution.min.js')}}"></script>
<script  src="{{asset('calendar_assets/js/calendar/jquery-resize.min.js')}}"></script>
<script  src="{{asset('calendar_assets/js/calendar/jquery.blockUI.min.js')}}"></script>

<style>
tbody, td, tfoot, th, thead, tr {
    height: 40px !important;
}
.doctor-image-calendar{
  width:100%;
}
.dropdown-divider {
    height: 0;
    margin: var(--bs-dropdown-divider-margin-y) 0;
    overflow: hidden;
    border-top: 1px solid #012970;
    opacity: 1;
}
</style>
<style>
.vc_custom_1547708474661{padding-top:65px !important;padding-bottom:15px !important}.vc_custom_1547708487513{padding-bottom:50px !important}
.booked-calendar{
background-color: #012970 !important;
}
</style>
<section class="section">
      <div class="row">
        <div class="col-lg-12">

        <input type="hidden" id="appointmentTime" name="appointmenttime">
        <input type="hidden" id="appointmentDate" name="appointmentdate">
        <input type="hidden" id="appointmentDoctor" name="doctor">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">All Appointments</h5>
              <!-- <div class="row g-5"> -->
                <div class="row g-5 needs-validation">
                  <div class="col-md-3">
                    <label for="validationDefault02" class="form-label">Speciality</label>
                      <select name="speciality" class="form-select" id="specId">
                            <option value="">All Specialities</option>
                            @foreach($spec as $key => $value)
                                <option value="{{$value->id}}" @if(old('speciality') == $value->id) selected @endif>{{$value->name}}</option>
                            @endforeach
                      </select>
                  </div>
                  <div class="col-md-3">
                    <label for="validationDefault02" class="form-label">Doctor</label>
                    <select name="doctor" class="form-select" id="docId">
                        <option value="">All Doctors</option>
                        @foreach($docs as $key => $value)
                            <option value="{{$value->id}}" @if(old('doctor') == $value->id) selected @endif>{{$value->doctor_name}}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="col-md-3">
                      <a type="button" href ="{{url('/admin/appointments')}}" class="btn btn-primary" style="text-align:right;position: absolute; right: 25px; top:100px;"><i class="bi bi-card-list me-1"></i> List View</a>
                  </div>
                 </div>
                <div class="booked-calendar-wrap large" id="calendar-body">
                  {!!$calendarStr!!} 
                </div>

            </div>
          </div>

        </div>
      </div>
    </section>
    
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript">
    $(function() {
    
        // var start = moment().subtract(29, 'days');
        var start = moment();
        var end = moment();
    
        function cb(start, end) {
            $('#reportrange span').html(start.format('DD-MM-Y') + ' - ' + end.format('DD-MM-Y'));
            $("#from").val(start.format('Y-MM-DD'));
            $("#to").val(end.format('Y-MM-DD'));
        }
    
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
              //  'Last 7 Days': [moment().subtract(6, 'days'), moment()],
              //  'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
              //  'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
              'This Year': [moment().startOf('year'), moment().endOf('year')],
            }
        }, cb);
    
        cb(start, end);
    
    });
    </script> 
    
    <script src="{{asset('calendar_assets/js/calendar/spin.min.js')}}"></script>
      <script src="{{asset('calendar_assets/js/calendar/spin.jquery.js')}}"></script>
      <script src="{{asset('calendar_assets/js/calendar/jquery.tooltipster.min.js')}}"></script>
      <script src="{{asset('calendar_assets/js/calendar/functions.js')}}"></script>
      <script src="{{asset('calendar_assets/js/calendar/perfect-scrollbar.jquery.min.js')}}"></script>
      <script src="{{asset('calendar_assets/js/calendar/actions_day.js')}}?v={{time()}}"></script>    
@endsection