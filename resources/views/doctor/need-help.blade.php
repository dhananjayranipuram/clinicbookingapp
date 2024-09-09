@extends('layouts.doctor')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-6">

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Post a complaint/Ticket</h5>

                <!-- No Labels Form -->
                <form class="row g-3">
                    <div class="col-md-12">
                        <input type="text" class="form-control" placeholder="Subject">
                    </div>
                    <div class="col-md-12">
                        <input class="form-control" type="file" id="formFile" placeholder="Subject">
                    </div>
                    <div class="col-12" style="height: fit-content;">
                        <div class="quill-editor-default"></div>
                    </div>
                    
                    <div class="text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </form><!-- End No Labels Form -->

                </div>
            </div>
        </div>

        <div class="col-lg-6">

            <div class="card">
            <div class="card-body">
                <h5 class="card-title">Status of tickets</h5>
                <p>Work in progress.</p>
            </div>
            </div>

        </div>
    </div>
</section>
@endsection