@extends('layouts.doctor')

@section('content')
<section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">My Appointments</h5>

              <form class="row g-5 needs-validation" method="post" action="{{ url('/doctor/appointments') }}">
                @csrf <!-- {{ csrf_field() }} -->
                  <div class="col-md-3">
                    <label for="validationDefault02" class="form-label">Data</label>
                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                        <input type="hidden" id="from" name="from">
                        <input type="hidden" id="to" name="to">
                    </div>
                  </div>
                  
                  <div class="col-md-3" style="align-content: end;">
                    <button class="btn btn-primary" type="submit">Search</button>
                  </div>
                </form>
              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>Appointment ID</th>
                    <th>Patient Name</th>
                    <th>Patient Mobile</th>
                    <th data-type="date" data-format="YYYY/MM/DD">Appointment Date</th>
                    <th>Appointment Time</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($list as $key => $value)
                        <tr>
                            <td>{{$value->appointment_id}}</td>
                            <td>{{$value->patient_name}}</td>
                            <td>{{$value->patient_mobile}}</td>
                            <td>{{$value->book_date}}</td>
                            <td>{{$value->book_time}}</td>
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
        $('.ranges').click(function(){
            $(".needs-validation").submit();
        });
    });
    </script>      
@endsection