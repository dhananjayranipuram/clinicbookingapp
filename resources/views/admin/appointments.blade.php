@extends('layouts.admin')

@section('content')

<section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <div>
                <h5 class="card-title" style="display:inline-block">All Appointments</h5>
                <a type="button" style="display:inline-block;float: right;margin-top: 20px;" href ="{{url('/admin/calendar-view')}}" class="btn btn-primary" style="text-align:right;position: absolute; right: 25px;"><i class="bi bi-calendar"></i> Book Appointment</a>
                <a type="button" style="display:inline-block;float: right;margin-top: 20px;margin-right: 20px;" href ="{{url('/admin/appointments')}}" class="btn btn-primary" style="text-align:right;position: absolute; right: 25px;"><i class="bi bi-card-list"></i></a>
                <!-- <div class="row g-5"> -->
              </div>
                <form class="row g-5 needs-validation" method="post" action="{{ url('/admin/appointments') }}">
                @csrf <!-- {{ csrf_field() }} -->
                  <div class="col-md-5">
                    <label for="validationDefault02" class="form-label">Date</label>
                    <div id="reportrange" class="word-wrap-custom" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                        <input type="hidden" id="from" name="from">
                        <input type="hidden" id="to" name="to">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <label for="validationDefault02" class="form-label">Speciality</label>
                      <select name="speciality" class="form-select speciality-select">
                            <option value="">All Specialities</option>
                            @foreach($spec as $key => $value)
                                <option value="{{$value->id}}" @if(old('speciality') == $value->id) selected @endif>{{$value->name}}</option>
                            @endforeach
                      </select>
                  </div>
                  <div class="col-md-3">
                    <label for="validationDefault02" class="form-label">Doctor</label>
                    <select name="doctor" class="form-select doctor-select">
                        <option value="">All Doctors</option>
                        @foreach($docs as $key => $value)
                            <option value="{{$value->id}}" @if(old('doctor') == $value->id) selected @endif>{{$value->doctor_name}}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="col-md-1" style="align-content: end;">
                    <button class="btn btn-primary appt-search-button" type="submit">Search</i></button>
                  </div>
                  
                </form>
              <!-- </div> -->
              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>Appt ID</th>
                    <th>Patient Name</th>
                    <th>Patient Mobile</th>
                    <th>Doctor Name</th>
                    <th>Speciality</th>
                    <th data-type="date" data-format="YYYY/MM/DD">Appt Date</th>
                    <th>Appt Time</th>
                    <th style="min-width:110px;">Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($list as $key => $value)
                        <tr>
                            <td>{{$value->appointment_id}}</td>
                            <td>{{$value->patient_name}}</td>
                            <td>{{$value->patient_mobile}}</td>
                            <td>{{$value->doctor_name}}</td>
                            <td>{{$value->speciclity}}</td>
                            <td>{{$value->book_date}}</td>
                            <td>{{$value->book_time}}</td>
                            <td><div >
                              <a href="{{ url('/admin/edit-appointment') }}?id={{$value->appointment_id}}" class="btn btn-default"><i class="fa fa-edit"></i></a>
                              <a href="#" class="btn btn-default deleteAppt" data-id="{{$value->appointment_id}}"><i class="fa fa-trash"></i></a>
                            </div></td>
                        </tr>
                    @endforeach
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
    </section>
    
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript">
    $(function() {
    
        // var start = moment().subtract(29, 'days');
        var fromDate = "{{old('from')}}";
        var toDate = "{{old('to')}}";

        if(fromDate === '' ){
          var start = moment();
        }else{
          var start = moment(fromDate);
        }
        if(fromDate === ''){
          var end = moment();
        }else{
          var end = moment(toDate);
        }
        
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

    $(document).ready(function () { 
    

        $('.deleteAppt').click(function(){
            if(confirm("Do you want to delete this Appointment?")){
                $.ajax({
                    url: baseUrl + '/admin/delete-appt',
                    type: 'post',
                    data: {'id':$(this).attr("data-id")},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function( html ) {
                        if(html=='1'){
                            location.reload();
                        }
                    }
                });
            }
        });

        $('.speciality-select').change(function(){
            $(".needs-validation").submit();
        });

        $('.doctor-select').change(function(){
            $(".needs-validation").submit();
        });

        $('.ranges').click(function(){
            $(".needs-validation").submit();
        });
    });
    </script>    
@endsection