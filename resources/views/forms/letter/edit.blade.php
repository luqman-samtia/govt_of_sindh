@extends('layouts.app')
@section('title')
    {{ __('edit letter') }}
@endsection

@section('content')
<!-- Bootstrap CSS -->


<!-- Bootstrap 4 CSS -->



    <div class="container-fluid">
        <div class="d-flex flex-column ">
            @include('flash::message')
            {{-- @if(session('message'))
            <h6 class="alert alert-success">
                {{ session('message') }}
            </h6>
                    @endif
                    @if(session('error'))
            <h6 class="alert alert-danger">
                {{ session('error') }}
            </h6>
                    @endif --}}
     @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
@endif
            <form action="{{  route('forms.letter.update', $letter) }}" method="POST" >
                @csrf
                @method('PUT')

                <div class="row " style="display:flex;flex-direction:row;align-items:center; justify-content:right;">
                    {{-- <div class="col-lg-2 col-md-2 col-sm-4">
                        <div class="mb-5">

                            <img src="{{asset('storage/qr-codes/download4.jpeg')}}" class="form-control form-control-solid" alt="GOVT OF SINDH" width="" style="width: 150px;background:none;border:none">
                        </div>
                    </div> --}}
                    {{-- <div class="col-lg-5 col-md-5 col-sm-4">
                        <div class="mb-5" style="text-align:left;justify-content:center;">

                            <h4 class="form-control form-control-solid" style="background:none;border:none">ANTI-CORRUPTION ESTABLISHMENT SINDH</h4>
                        </div>
                    </div> --}}
                    <div class="col-lg-3 col-md-3 col-sm-4">
                        <div class="mb-5">
                            {{-- <label for="letter_no" class="form-label required mb-3">Letter No</label> --}}
                            <input type="text" id="letter_no" class="form-control form-control-solid" value="{{$letter->letter_no}}" placeholder="Letter No" name="letter_no" readonly>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4">
                        <div class="mb-5">
                            {{-- <label for="date" class="form-label required mb-3">Date</label> --}}
                            <input type="text" id="date" value="{{date(Auth::user()->date)}}"  class="form-control form-control-solid" placeholder="Date" name="date" readonly>
                        </div>
                    </div>
                </div>

            <div class="row">
                {{-- <hr>
                <h4>To,</h4> --}}

                {{-- <div class="col-md-12" style="text-align: end;">
                    <button type="button" onclick="addRecipient()" id="add-field" class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0" name="designation">
                    <span data-bs-toggle="tooltip" data-bs-placement="top"  >
                        <i class="fas fa-plus"></i>
                    </span>
                    </button>
                </div> --}}
                <div class="col-lg-2">
                    <div>
                        <h4>To,</h4>
                    </div>
                </div>
                <div style="text-align:right;">
                    <div class="">
                     <button type="button" onclick="addRecipient()" id="add-recipient-btn" class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0">
                         <span data-bs-toggle="tooltip" data-bs-placement="top" >
                             <i class="fas fa-plus"></i>
                         </span>
                  </button>
                    </div>
                </div>
            <div id="dynamic-fields">
                {{-- @if(isset($letter) && $letter->toLetters->count() > 0) --}}
                @foreach($letter->designations as $index => $toLetter)
                <div class="row" id="field-{{$index}}" style="display:flex;flex-direction:row;justify-content:center;align-items:center;">

                <div class="col-lg-2">
                    <div class="mb-5">
                        <input type="text" id="designation-{{$index}}" class="form-control form-control-solid" placeholder="Designation" value="{{ $toLetter->designation }}" name="designation[{{$index}}][designation]" required autofocus="off"  autocomplete="off">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-5">
                        <input type="text" id="department-{{$index}}"  class="form-control form-control-solid" placeholder="Department" value="{{ $toLetter->department }}" name="designation[{{$index}}][department]" required>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-5">
                        <input type="text" id="address-{{$index}}" class="form-control form-control-solid" placeholder="Address" value="{{ $toLetter->address }}" name="designation[{{$index}}][address]" required>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-5">
                        <input type="text" id="contact-{{$index}}" class="form-control form-control-solid" placeholder="Contact" value="{{ $toLetter->contact }}" name="designation[{{$index}}][contact]" >
                    </div>
                </div>

            </div>
                @endforeach


            </div>

                {{-- <hr>
                <h4>Subject:</h4> --}}
               <div class="row" style="display:flex:flex-direction:row">
                <div class="col-lg-10 ">
                    <div class="mb-5">
                        {{-- <label for="subject" class="form-label required mb-3">Subject</label> --}}
                        <input type="text" id="subject" class="form-control form-control-solid" placeholder="Subject" value="{{$letter->subject}}" name="subject" required>
                    </div>
                </div>
                {{-- <div class="col-lg-3">
                    <div class="mb-5">
                        <label for="dear" class="form-label  mb-3">Dear</label>
                        <select name="dear" class="form-control form-control-solid" id="dear">
                            <option value="">Select option</option>
                            <option value="Respected Mam."{{($letter->dear=='Respected Mam.' ? 'selected': '')}}>Respected Mam</option>
                            <option value="Respected Sir."{{($letter->dear=='Respected Sir.' ? 'selected': '')}}>Respected Sir</option>
                            <option value="Mr."{{($letter->dear=='Respected Mam.' ? 'selected': '')}}>Mr.</option>
                            <option value="Mrs."{{($letter->dear=='Mrs.' ? 'selected': '')}}>Mrs.</option>
                            <option value="Ms."{{($letter->dear=='Ms.' ? 'selected': '')}}>Ms.</option>

                        </select>
                    </div>
                </div> --}}
               </div>
                <hr>
                <div class="col-lg-12">
                    <div class="mb-5">
                        {{-- <label for="draft_para" class="form-label required mb-3">Draft Section</label> --}}
                        <textarea id="draft_para" cols="70" rows="10" class="form-control form-control-solid ckeditor" placeholder="Draft Para" name="draft_para" required>{{$letter->draft_para}}</textarea>

                    </div>
                </div>
                <hr>
                 {{-- <div class="col-md-12" style="text-align: end;">
                    <button type="button" onclick="signing_authority()" class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0" id="add-fieldd" name="signing">
                <span data-bs-toggle="tooltip" data-bs-placement="top"  >
                    <i class="fas fa-plus"></i>
                </span>
            </button>
                </div> --}}
                <div id="dynamic-fieldds">
                    @foreach ($letter->signingAuthorities as $index => $signing_authority)


                    <div class="row" id="fieldd-1">
                        <div class="col-lg-4">
                            <div class="mb-5">
                                {{-- <label for="s_a_name" class="form-label  mb-3">Signing Authority Name</label> --}}
                                <input type="text" id="s_a_name" class="form-control form-control-solid" placeholder="S A Name" value="{{$signing_authority->name}}" name="signing_authorities[{{$index}}][sa_name]" >
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-5">
                                {{-- <label for="s_a_designation" class="form-label  mb-3">Signing Authority Designation</label> --}}
                                <input type="text" id="s_a_designation" class="form-control form-control-solid" placeholder="S A Designation" value="{{$signing_authority->designation}}" name="signing_authorities[{{$index}}][sa_designation]" >
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-5">
                                {{-- <label for="s_a_department" class="form-label  mb-3">Department</label> --}}
                                <input type="text" id="s_a_department" class="form-control form-control-solid" placeholder="Department" value="{{$signing_authority->department}}" name="signing_authorities[{{$index}}][sa_department]" >
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                {{-- <script>

                </script> --}}

                <hr>

                 {{-- add more button --}}

                 <div class="col-md-6 mb-5">
                    A copy is forwarded for similar compliance :-
               </div>
                <div class="col-md-6" style="text-align: end;">
                    <button type="button" id="add-fielddd" onclick="ForwardCopy()" class="btn btn btn-icon btn-primary text-white hide-arrow ps-2 pe-0">
                    <span data-bs-toggle="tooltip">
                        <i class="fas fa-plus"></i>
                    </span>
                    </button>
                </div>
                <div id="dynamic-fielddds">
                    @foreach ($letter->forwardedCopies as $index => $copy)
             <div class="row" id="fielddd-{{$index}}">
                <div class="col-lg-6">
                       <div class="mb-5">
                        <input type="text" id="copy_forwarded-{{$index}}" class="form-control form-control-solid" placeholder="Copy of Forwarded" value="{{$copy->copy_forwarded}}" name="forwarded_copies[{{$index}}][copy_forwarded]" required>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

                <hr>

                           <!-- Bootstrap Modal -->
<!-- Modal Structure -->
<div class="modal fade" id="letterPreviewModal" tabindex="-1" role="dialog" aria-labelledby="letterPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="letterPreviewModalLabel">Letter Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="letterPreviewContent" style="overflow: hidden;">
                <!-- Dynamic content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- end letter preview --}}
                <div class="col-md-12" style="text-align: center;">
                    <a href="" onclick="downloadPdf('{{ route('Form.download.pdf', $letter->id) }}')" class="btn btn-primary mx-1 ms-ms-3 mb-3 mb-sm-0" data-bs-original-title="Pdf file Download" title="Pdf File Download" data-bs-toggle="tooltip" id="download-btn">PDF Download</a>
                    <a  type="button" name="" value=""  class="btn btn-primary mx-1 ms-ms-3 mb-3 mb-sm-0">DOC Download</a>
                    <button type="submit" name="action" value="save_as_draft"  class="btn btn-primary mx-1 ms-ms-3 mb-3 mb-sm-0">Update Draft</button>
                    <button  type="button"  data-toggle="modal" data-target="#letterPreviewModal" onclick="loadLetterPreview({{ $letter->id }})" class="btn btn-primary mx-1 ms-ms-3 mb-3 mb-sm-0">Print Preview</button>
                    {{-- <a href="{{ route('forms') }}"
                    class="btn btn-secondary btn-active-light-primary">{{ __('messages.common.cancel') }}
                    </a> --}}
                </div>
            </div>
            <script>
                function downloadPdf(url) {
                              var xhr = new XMLHttpRequest();
                              xhr.open('GET', url, true);
                              xhr.responseType = 'blob';
                              xhr.onload = function() {
                                  if (xhr.status === 200) {
                                      var blob = new Blob([xhr.response], {type: 'application/pdf'});
                                      var link = document.createElement('a');
                                      link.href = window.URL.createObjectURL(blob);
                                      link.download = 'letter-' + '{{ $letter->letter_no }}' + '.pdf';
                                      link.click();
                                    //   fetch('/forms/letter-form/{{$letter->id}}/edit')
                                    //   .then(response => response.text())
                                    //   .then(formUrl => {
                                    //       window.location.href = formUrl;
                                    //   });
                                    //   window.location.href = '{{ URL::previous() }}'; // Redirect back to previous page
                                    //   window.location.href = "{{ route('forms.letter.edit',$letter->id) }}";
                                    window.location.href = "{{ route('forms.letter.edit', $letter->id) }}";
                                  }
                              };
                              xhr.send();
                          }
            </script>
        </form>
        <hr>
        <form action="{{ route('letter.upload', $letter->id) }}" method="POST" enctype="multipart/form-data" style="margin-top: 10px;">
            @csrf

        <div class="col-md-12 col-lg-12">

            <label style="color:white;" for="signed_letter" class="form-label  mb-3">NOTE: Download the letter, have it signed, then scan and upload it as a PDF. Ensure the file number matches the letter number for a successful upload.</label>
        </div>
        <div style="display:flex;align-items:center;justify-content:center">
        <div class="col-lg-4">
            <div class="">

                <input type="file" id="signed_letter" name="signed_letter" class="form-control form-control-solid" accept=".pdf">
            </div>

        </div>
        {{-- <div class="col-md-3"> --}}
            <button style="width:150px; margin-left:5px;" type="submit" class="btn btn-primary text-center">Submit Letter</button>
        {{-- </div> --}}
    </div>
    </form>
        </div>
    </div>

            {{-- letter preview modal --}}



@endsection

{{-- @if($letter->is_submitted)
    <script>
        document.querySelectorAll('input, textarea, select').forEach(function(el) {
            el.setAttribute('disabled', true);
        });
    </script>
@endif --}}
<script>
    // var fieldCounter =  0;
    var fieldCounter = {{ count($letter->designations) ? count($letter->designations) - 1 : -1 }};
    var fieldCounterss = {{ count($letter->forwardedCopies) ? count($letter->forwardedCopies) - 1 : -1 }};


 CKEDITOR.replace('draft_para');

    // letter preview
//     function loadLetterPreview(letterId) {
//     // Correct the URL to the preview route using Laravel's route helper in Blade
//     var previewUrl = '{{ route('letter.preview', ':id') }}';
//     previewUrl = previewUrl.replace(':id', letterId);

//     // Fetch the letter content via AJAX
//     fetch(previewUrl)
//         .then(response => response.text())
//         .then(htmlContent => {
//             // Insert the content into the modal
//             document.getElementById('letterPreviewContent').innerHTML = htmlContent;
//             // Show the modal
//             $('#letterPreviewModal').modal('show');
//         })
//         .catch(error => {
//             console.error('Error fetching letter preview:', error);
//             // Fallback if there is an error
//             document.getElementById('letterPreviewContent').innerHTML = '<p>Unable to load the letter preview.</p>';
//         });
// }

function loadLetterPreview(letterId) {
    var previewUrl = '{{ route('letter.preview', ':id') }}';
    previewUrl = previewUrl.replace(':id', letterId);

    fetch(previewUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(htmlContent => {
            document.getElementById('letterPreviewContent').innerHTML = htmlContent;

            var modal = new bootstrap.Modal(document.getElementById('letterPreviewModal'));
            modal.show();

            // Set up the redirect after a timeout (or other event)
            modal._element.addEventListener('hidden.bs.modal', function () {
                window.location.href = '{{ route('forms.letter.edit', ':id') }}'.replace(':id', letterId);
            });
        })
        .catch(error => {
            console.error('Error fetching letter preview:', error);
            document.getElementById('letterPreviewContent').innerHTML = '<p>Unable to load the letter preview.</p>';
        });
}



    // end letter preview
    //     document.getElementById('add-field').addEventListener('click', (function(counter) {
        //     return function() {
            //         addRecipient(counter);
            //     };
            // })(fieldCounter);
            // })

            // Set the initial fieldCounter to the number of existing designations


            function addRecipient() {
        // Increment fieldCounter on each call
        fieldCounter++;
        event.preventDefault();
        const newField = `
            <div class="row" id="field-${fieldCounter}" style="display:flex;flex-direction:row;justify-content:center;align-items:center;">
                <div class="col-lg-2">
                    <div class="mb-5">
                        <input type="text" id="designation-${fieldCounter}" class="form-control form-control-solid" placeholder="Designation" name="designation[${fieldCounter}][designation]" required>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-5">
                        <input type="text" id="department-${fieldCounter}" class="form-control form-control-solid" placeholder="Department" name="designation[${fieldCounter}][department]" required>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-5">
                        <input type="text" id="address-${fieldCounter}" class="form-control form-control-solid" placeholder="Address" name="designation[${fieldCounter}][address]" required>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-5">
                        <input type="text" id="contact-${fieldCounter}" class="form-control form-control-solid" placeholder="Contact" name="designation[${fieldCounter}][contact]" >
                    </div>
                </div>
            </div>`;

        document.getElementById('dynamic-fields').insertAdjacentHTML('beforeend', newField);


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

                fieldCounterss++;
                const newFielddd = `
                    <div class="row" id="fieldd-${fieldCounterss}">
                        <div class="col-lg-6">
                       <div class="mb-5">
                        <input type="text" id="copy_forwarded" class="form-control form-control-solid" placeholder="Copy of Forwarded" name="forwarded_copies[${fieldCounterss}][copy_forwarded]" required>
                    </div>
                </div>
                    </div>`;
                document.getElementById('dynamic-fielddds').insertAdjacentHTML('beforeend', newFielddd);


        }

        if (document.getElementById('signed_letter')) {
    document.getElementById('signedLetterUpload').style.display = 'block';
    }

    // Check for success message
    @if (session('message'))
            toastr.success("{{ session('message') }}");
        @endif

        // Check for error message
        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif


        document.addEventListener("DOMContentLoaded", function (event) {
        var scrollpos = sessionStorage.getItem('scrollpos');
        if (scrollpos) {
            window.scrollTo(0, scrollpos);
            sessionStorage.removeItem('scrollpos');
        }
    });

    window.addEventListener("beforeunload", function (e) {
        sessionStorage.setItem('scrollpos', window.scrollY);
    });

    window.onload = function() {
    // Remove auto-focus on the first input by setting the focus elsewhere
    document.activeElement.blur();
};

</script>
