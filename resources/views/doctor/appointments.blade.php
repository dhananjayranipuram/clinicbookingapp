@extends('layouts.doctor')

@section('content')
<section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">My Appointments</h5>

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
@endsection