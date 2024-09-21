@extends('layouts.admin')

@section('content')
<section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Edit Appointment</h5>

              <!-- General Form Elements -->
              <form method="POST" action="{{url('/admin/update-appointment')}}">
              @csrf <!-- {{ csrf_field() }} -->  
                <input type="hidden" value="{{$det[0]->appointment_id}}" name="appId">
                <div class="row mb-3">
                  <div class="col-sm-6">
                    <label for="inputDate" class="col-sm-12 col-form-label">Patient Name</label>
                    <div class="col-sm-12">
                      <input type="text" class="form-control" value="{{$det[0]->patient_name}}" disabled>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <label for="inputDate" class="col-sm-12 col-form-label">Mobile Number</label>
                    <div class="col-sm-12">
                      <input type="text" class="form-control" value="{{$det[0]->patient_mobile}}" disabled>
                    </div>
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-sm-6">
                    <label class="col-sm-12 col-form-label">Speciality</label>
                    <div class="col-sm-12">
                      <select class="form-select" aria-label="Default select example" disabled>
                      @foreach($spec as $key => $value)
                          <option value="{{$value->id}}" @if($value->id == $det[0]->spec_id) selected @endif>{{$value->name}}</option>
                      @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-sm-6">
                    <label class="col-sm-12 col-form-label">Doctor</label>
                    <div class="col-sm-12">
                      <select class="form-select inputs" id="doctorId" aria-label="Default select example" name="docId">
                      @foreach($docs as $key => $value)
                          <option value="{{$value->id}}" @if($value->id == $det[0]->doc_id) selected @endif>{{$value->doctor_name}}</option>
                      @endforeach
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-sm-6">
                    <label for="inputDate" class="col-sm-12 col-form-label">Date</label>
                    <div class="col-sm-12">
                      <input type="date" class="form-control inputs" id="appDate" value="{{$det[0]->book_date}}"  min="{{ date('Y-m-d') }}" name="appDate">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <label class="col-sm-12 col-form-label">Time Slot</label>
                    <div class="col-sm-12" id="timeSlotContainer">
                      {!!$timeslotselect!!}
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary">Update</button>
                  </div>
                </div>

              </form><!-- End General Form Elements -->

            </div>
          </div>

        </div>

        
      </div>
    </section>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script>
$(document).ready(function () { 
    

    $('.inputs').change(function(){
            $.ajax({
                url: baseUrl + '/admin/get-time-slot',
                type: 'post',
                data: {'docId':$("#doctorId").val() , 'appDate':$("#appDate").val()},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function( html ) {
                    $("#timeSlotContainer").html(html);
                }
            });
    });

    
});
</script>
@endsection