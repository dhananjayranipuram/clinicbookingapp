@extends('layouts.app')

@section('content')
<!-- Contact Start -->
<link href="{{asset('assets/css/datatable/dataTables.dataTables.css')}}" rel="stylesheet">
<link href="{{asset('assets/css/datatable/searchBuilder.dataTables.css')}}" rel="stylesheet">
<link href="{{asset('assets/css/datatable/dataTables.dateTime.min.css')}}" rel="stylesheet">


<div class="container-fluid py-5">
    <div class="container" style="margin-bottom: 50px;">
        <div class="row g-5">
            <div class="col-lg-12">
                <div class="section-title mb-4">
                    <h5 class="position-relative d-inline-block text-primary text-uppercase">My Appointments</h5>
                </div>
            </div>
            <div class="col-xl-12 col-lg-6 wow slideInUp" data-wow-delay="0.3s" style="margin-top:5px !important;">
                <form>
                    <div class="row g-3">
                        
                    
                    <table id="appointments" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th style="text-align:center;">Appt ID</th>
                                <th>Doctor Name</th>
                                <th>Speciality</th>
                                <th>Appt Date</th>
                                <th>Appt Time</th>
                                <th style="text-align:center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $key => $value)
                            <tr>
                                <td style="text-align:center;">{{$value->appointment_id}}</td>
                                <td>{{$value->doctor_name}}</td>
                                <td>{{$value->speciclity}}</td>
                                <td>{{$value->book_date}}</td>
                                <td>{{$value->book_time}}</td>
                                <td style="text-align:center;"><div >
                                <a href="#" class="btn btn-default editAppt" data-id="{{$value->appointment_id}}"><i class="fa fa-edit"></i></a>
                                <a href="#" class="btn btn-default deleteAppt" data-id="{{$value->appointment_id}}"><i class="fa fa-trash"></i></a>
                                </div></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>



<div id="editAppt" class="overlay-container">
    <div class="popup-box">
        <h2>Edit Appointment</h2><br>
            <div class="row">
                <div class="col-md-6 mb-4">

                <div data-mdb-input-init class="form-outline">
                    <input type="text" id="firstName" class="form-control form-control-lg" placeholder="Speciality" />
                </div>

                </div>
                <div class="col-md-6 mb-4">

                <div data-mdb-input-init class="form-outline">
                    <input type="text" id="lastName" class="form-control form-control-lg" placeholder="Doctor" />
                </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4 pb-2">
                    <div data-mdb-input-init class="form-outline">
                        <input type="email" id="emailAddress" class="form-control form-control-lg" placeholder="Date" />
                    </div>
                </div>
                <div class="col-md-6 mb-4 pb-2">
                    <div data-mdb-input-init class="form-outline">
                        <input type="tel" id="phoneNumber" class="form-control form-control-lg" placeholder="Time slot" />
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-2">
                <input type="hidden" id="apptId">
                <input class="btn btn-primary btn-lg" type="button" onclick="registration();" value="Update" />
                <input class="btn-close-popup" type="button" onclick="closeAllPopup();" value="Close" />
            </div>
        
    </div>
</div>
<script src="{{asset('assets/js/datatable/jquery-3.7.1.js')}}"></script>
<script src="{{asset('assets/js/datatable/dataTables.js')}}"></script>
<script src="{{asset('assets/js/datatable/dataTables.searchBuilder.js')}}"></script>
<script src="{{asset('assets/js/datatable/searchBuilder.dataTables.js')}}"></script>
<script src="{{asset('assets/js/datatable/dataTables.dateTime.min.js')}}"></script>
<script>
new DataTable('#appointments');

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

    $('.editAppt').click(function(){
        $("#editAppt").addClass('show');
        $(this).attr("data-id");
    });

    function editAppointment(){
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
</script>
<!-- Contact End -->
@endsection