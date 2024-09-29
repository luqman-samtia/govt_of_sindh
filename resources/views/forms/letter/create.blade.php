@extends('layouts.app')
@section('title')
    {{ __('create form') }}
@endsection

@section('content')
{{-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> --}}
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

    <div class="container-fluid">
        <div class="d-flex flex-column ">
            @include('flash::message')
            @if(session('message'))
            <h6 class="alert alert-success">
                {{ session('message') }}
            </h6>
                    @endif
                    @if(session('error'))
            <h6 class="alert alert-danger">
                {{ session('error') }}
            </h6>
                    @endif
            @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
            <form action="{{ route('letters.store') }}" method="POST">
                @csrf
                <div class="row">
                    {{-- <div class="col-lg-2 col-md-2 col-sm-4">
                        <div class="mb-5">

                            <img src="{{asset('storage/qr-codes/download4.jpeg')}}" class="form-control form-control-solid" alt="GOVT OF SINDH" width="" style="width: 150px;background:none;border:none">
                        </div>
                    </div> --}}
                    <div class="col-lg-5 col-md-5 col-sm-4">
                        {{-- <div class="mb-5" style="text-align:left;justify-content:center;">

                            <h4 class="form-control form-control-solid" style="background:none;border:none">ANTI-CORRUPTION ESTABLISHMENT SINDH</h4>
                        </div> --}}
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4">
                        <div class="mb-5">
                            {{-- <label for="letter_no" class="form-label required mb-3">Letter No</label> --}}
                            <input type="text" id="letter_no" class="form-control form-control-solid" value="{{$newLetterNo}}" placeholder="Letter No" name="letter_no" readonly>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-4">
                        <div class="mb-5">
                            {{-- <label for="date" class="form-label required mb-3">Date</label> --}}
                            <input type="text" id="date" value="{{date(Auth::user()->date)}}"  class="form-control form-control-solid" placeholder="Date" name="date" readonly>
                        </div>
                    </div>
                </div>
            <div class="row">


                <div class="col-lg-6" style="display: none;">
                    <div class="mb-5">
                        {{-- <label for="head_title" class="form-label required mb-3">Head Title</label> --}}
                        <input type="text" id="head_title" value="{{Auth::user()->district}}" class="form-control form-control-solid" placeholder="Head Title" name="head_title" required>
                    </div>
                </div>
                <div class="col-lg-6" style="display:none;">
                    <div class="mb-5">
                        {{-- <label for="fix_address" class="form-label required mb-3">Address</label> --}}
                        <input type="text" id="fix_address" value="{{Auth::user()->address}}" class="form-control form-control-solid" placeholder="Address" name="fix_address" required>
                    </div>
                </div>

                {{-- <hr> --}}


                {{-- <div class="col-md-12" style="text-align: end;">
                    <button type="button" onclick="addRecipient()" id="add-field" class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0">
                    <span data-bs-toggle="tooltip" data-bs-placement="top" >
                        <i class="fas fa-plus"></i>
                    </span>
                    </button>
                </div> --}}
                <div class="col-lg-2 mb-5">
                    <div class="mb-5 ">
                        <h4 style="margin-top:10px">To,</h4>
                    </div>
                </div>
        <div id="dynamic-fields">
            <div class="row" id="field-0" style="display:flex;flex-direction:row;justify-content:center;align-items:center;">

                <div class="col-lg-2">
                    <div class="mb-5">
                        <input type="text" id="designation" class="form-control form-control-solid" placeholder="Designation" name="designation[0][designation]" required>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-5">
                        <input type="text" id="department"  class="form-control form-control-solid" placeholder="Department" name="designation[0][department]" required>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-5">
                        <input type="text" id="address" class="form-control form-control-solid" placeholder="Address" name="designation[0][address]" required>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-5">
                        <input type="text" id="contact" class="form-control form-control-solid" placeholder="Contact" name="designation[0][contact]" >
                    </div>
                </div>
                <div class="col-lg-2">
                   <div class="mb-5">
                    <button id="gos_bg_color" type="button" onclick="addRecipient()" id="add-field" class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0">
                        <span data-bs-toggle="tooltip" data-bs-placement="top" >
                            <i class="fas fa-plus"></i>
                        </span>
                 </button>
                   </div>
                </div>

            </div>
            </div>

                {{-- <hr>
                <h4>Subject:</h4> --}}
                <div class="row" style="display: flex:flex-direction:row;">
                    <div class="col-lg-10 mt-5">
                        <div class="mb-5 text-center">
                            {{-- <label for="subject" class="form-label required mb-3">Subject</label> --}}
                            <input type="text" id="subject" class="form-control form-control-solid" placeholder="Subject" name="subject" required>
                        </div>
                    </div>

                </div>

                <hr>
                <div class="col-lg-12">
                    <div class="mb-5">
                        {{-- <label for="draft_para" class="form-label required mb-3">Draft Section</label> --}}
                        <textarea id="draft_para" cols="70" rows="10" class="form-control form-control-solid ckeditor" placeholder="Draft Para" name="draft_para" required></textarea>

                    </div>
                </div>
                <hr>
                 {{-- <div class="col-md-12" style="text-align: end;">
                    <button type="button" onclick="signing_authority()" class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0" id="add-fieldd">
                <span data-bs-toggle="tooltip" data-bs-placement="top" >
                    <i class="fas fa-plus"></i>
                </span>
            </button>
                </div> --}}
                <div id="dynamic-fieldds">
                    <div class="row" id="fieldd-1">
                        <div class="col-lg-3">
                            <div class="mb-5">
                                {{-- <label for="s_a_name" class="form-label  mb-3">Signing Authority Name</label> --}}
                                <input type="text" id="s_a_name" class="form-control form-control-solid" placeholder="S A Name" name="signing_authorities[0][sa_name]" >
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-5">
                                {{-- <label for="s_a_designation" class="form-label  mb-3">Signing Authority Designation</label> --}}
                                <input type="text" id="s_a_designation" class="form-control form-control-solid" placeholder="S A Designation" name="signing_authorities[0][sa_designation]" >
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-5">
                                {{-- <label for="s_a_department" class="form-label  mb-3">Department</label> --}}
                                <input type="text" id="s_a_department" class="form-control form-control-solid" placeholder="Department" name="signing_authorities[0][sa_department]" >
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-5">
                                {{-- <label for="s_a_department" class="form-label  mb-3">Department</label> --}}
                                <input type="text" id="s_a_other" class="form-control form-control-solid" placeholder="Other" name="signing_authorities[0][sa_other]" >
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <script>

                </script> --}}

                <hr>

                 {{-- add more button --}}

                <div class="col-md-6 mb-5">
                     A copy is forwarded for similar compliance :-
                </div>
                <div class="col-md-6" style="text-align: end;">
                    <button id="gos_bg_color" type="button" id="add-fielddd" onclick="ForwardCopy()" class="btn btn btn-icon btn-primary text-white hide-arrow ps-2 pe-0">
                    <span >
                        <i class="fas fa-plus"></i>
                    </span>
                    </button>
                </div>
            <div id="dynamic-fielddds">
             <div class="row" id="fielddd-0">
                <div class="col-lg-6">
                       <div class="mb-5">
                        <input type="text" id="copy_forwarded" class="form-control form-control-solid" placeholder="Copy of Forwarded" name="forwarded_copies[0][copy_forwarded]" required>
                    </div>
                </div>

            </div>
        </div>

                <hr>
                <div class="col-md-12" style="text-align: center;">
                    <button id="gos_bg_color"  type="submit" name="save_as_draft"  class="btn btn-primary mx-1 ms-ms-3 mb-3 mb-sm-0">Save As Draft</button>
                    <button id="gos_bg_color" type="reset" name="reset"  class="btn btn-primary mx-1 ms-ms-3 mb-3 mb-sm-0">Reset</button>
                    {{-- <button  type="submit" name="submit"     class="btn btn-primary mx-1 ms-ms-3 mb-3 mb-sm-0">Save & Submit</button> --}}
                    {{-- <a href="{{ route('forms') }}"
                    class="btn btn-secondary btn-active-light-primary">{{ __('messages.common.cancel') }}
                </a> --}}
                </div>
            </div>

        </form>

        </div>
    </div>

@endsection

{{-- @if($letter->is_submitted)
    <script>
        document.querySelectorAll('input, textarea, select').forEach(function(el) {
            el.setAttribute('disabled', true);
        });
    </script>
@endif --}}
<script>
    var fieldCounter = 0;
    var fieldCounterss = 0;
 CKEDITOR.replace('draft_para');




    function addRecipient(){
        // document.getElementById('add-field').addEventListener('click', function() {
            fieldCounter++;
            const newField = `
                <div class="row" id="field-${fieldCounter}" style="display:flex;flex-direction:row;justify-content:center;align-items:center;">

                <div class="col-lg-2">
                    <div class="mb-5">
                        <input type="text" id="designation" class="form-control form-control-solid" placeholder="Designation" name="designation[${fieldCounter}][designation]" required>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-5">
                        <input type="text" id="department"  class="form-control form-control-solid" placeholder="Department" name="designation[${fieldCounter}][department]" required>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-5">
                        <input type="text" id="address" class="form-control form-control-solid" placeholder="Address" name="designation[${fieldCounter}][address]" required>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-5">
                        <input type="text" id="contact" class="form-control form-control-solid" placeholder="Contact" name="designation[${fieldCounter}][contact]" >
                    </div>
                </div>

                <div class="col-lg-2">
                <button type="button" class="btn btn-danger mb-5" onclick="removeRecipient(${fieldCounter})">
                    <i class="fa fa-minus"></i>
                </button>
                 </div>
                </div>`;
            document.getElementById('dynamic-fields').insertAdjacentHTML('beforeend', newField);
        // });
    }
    function removeRecipient(id) {
    const field = document.getElementById(`field-${id}`);
    if (field) {
        field.remove(); // Remove the row
    }
}
    function signing_authority(){
        let fieldCounters = 0;
                    // document.getElementById('add-fieldd').addEventListener('click', function() {
                        fieldCounters++;
                        const newFieldd = `
                            <div class="row" id="fieldd-${fieldCounters}">
                                <div class="col-lg-4">
                                                <div class="mb-5">
                                                    <label for="s_a_name" class="form-label  mb-3">Signing Authority Name</label>
                                                    <input type="text" id="s_a_name" class="form-control form-control-solid" placeholder="S A Name" name="signing_authorities[${fieldCounters}][sa_name]" >
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-5">
                                                    <label for="s_a_designation" class="form-label  mb-3">Signing Authority Designation</label>
                                                    <input type="text" id="s_a_designation" class="form-control form-control-solid" placeholder="S A Designation" name="signing_authorities[${fieldCounters}][sa_designation]" >
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-5">
                                                    <label for="s_a_department" class="form-label  mb-3">Department</label>
                                                    <input type="text" id="s_a_department" class="form-control form-control-solid" placeholder="Department" name="signing_authorities[${fieldCounters}][sa_department]" >
                                                </div>
                                        </div>

                            </div>`;
                        document.getElementById('dynamic-fieldds').insertAdjacentHTML('beforeend', newFieldd);
                    // });
    }
     function ForwardCopy(){

            // document.getElementById('add-fielddd').addEventListener('click', function() {
                fieldCounterss++;
                const newFielddd = `
                    <div class="row" id="fielddd-${fieldCounterss}">
                        <div class="col-lg-6">
                       <div class="mb-5">
                        <input type="text" id="copy_forwarded" class="form-control form-control-solid" placeholder="Copy of Forwarded" name="forwarded_copies[${fieldCounterss}][copy_forwarded]" required>
                    </div>
                </div>
                <div class="col-lg-2">
                <button type="button" class="btn btn-danger mb-5" onclick="removeForwarddCopy(${fieldCounterss})">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
                    </div>`;
                document.getElementById('dynamic-fielddds').insertAdjacentHTML('beforeend', newFielddd);
            // });

     }
     function removeForwarddCopy(id) {
    const field = document.getElementById(`fielddd-${id}`);
    if (field) {
        field.remove(); // Remove the row
    }
}
</script>
