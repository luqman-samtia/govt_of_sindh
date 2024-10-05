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

     @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
@endif
            <form id="draftForm" action="{{  route('forms.order.update', $letter) }}" method="POST" >
                @csrf
                @method('PUT')

                <div class="row " style="display:flex;flex-direction:row;align-items:center; justify-content:right;">

                    <div class="col-lg-3 col-md-3 col-sm-4">
                        <div class="mb-5">
                            {{-- <label for="letter_no" class="form-label required mb-3">Letter No</label> --}}
                            <input type="text" id="letter_no" class="form-control form-control-solid" value="{{$letter->letter_no}}" placeholder="Letter No" name="letter_no" readonly>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4">
                        <div class="mb-5">
                            {{-- <label for="date" class="form-label required mb-3">Date</label> --}}
                            <input type="text" id="date" value="{{now()->format('F d, Y')}}"  class="form-control form-control-solid" placeholder="Date" name="date" readonly>
                        </div>
                    </div>
                </div>

            <div class="row">


                <hr>
                <div class="col-lg-12">
                    <div class="mb-5" >
                        {{-- <label for="draft_para" class="form-label required mb-3">Draft Section</label> --}}
                        <textarea id="editor" cols="70" rows="10" class="form-control form-control-solid " placeholder="Draft Para" name="draft_para" required>{{$letter->draft_para}}</textarea>
                    </div>
                </div>
                <hr>

                <div id="dynamic-fieldds">
                    @foreach ($letter->signingAuthorities as $index => $signing_authority)


                    <div class="row" id="fieldd-1">
                        <div class="col-lg-3">
                            <div class="mb-5">
                                {{-- <label for="s_a_name" class="form-label  mb-3">Signing Authority Name</label> --}}
                                <input type="text" id="s_a_name" class="form-control form-control-solid" placeholder="S A Name" value="{{$signing_authority->name}}" name="signing_authorities[{{$index}}][sa_name]" >
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-5">
                                {{-- <label for="s_a_designation" class="form-label  mb-3">Signing Authority Designation</label> --}}
                                <input type="text" id="s_a_designation" class="form-control form-control-solid" placeholder="S A Designation" value="{{$signing_authority->designation}}" name="signing_authorities[{{$index}}][sa_designation]" >
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-5">
                                {{-- <label for="s_a_department" class="form-label  mb-3">Department</label> --}}
                                <input type="text" id="s_a_department" class="form-control form-control-solid" placeholder="Department" value="{{$signing_authority->department}}" name="signing_authorities[{{$index}}][sa_department]" >
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-5">
                                {{-- <label for="s_a_department" class="form-label  mb-3">Department</label> --}}
                                <input type="text" id="s_a_other" class="form-control form-control-solid" placeholder="Others" value="{{$signing_authority->other}}" name="signing_authorities[{{$index}}][sa_other]" >
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>


                <hr>

                 {{-- add more button --}}

                 <div class="col-md-6 mb-5">
                    A copy is forwarded for similar compliance :-
               </div>
                <div class="col-md-6" style="text-align: end;">
                    <button id="gos_bg_color" type="button" id="add-fielddd" onclick="ForwardCopy()" class="btn btn btn-icon btn-primary text-white hide-arrow ps-2 pe-0">
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

                <div id="dynamic-fields">
                    {{-- @if(isset($letter) && $letter->toLetters->count() > 0) --}}
                    @foreach($letter->designations as $index => $toLetter)
                    <div class="row" id="field-{{$index}}" style="display:flex;flex-direction:row;justify-content:center;align-items:center;">

                    <div class="col-lg-3">
                        <div class="mb-5">
                            <input type="text" id="designation-{{$index}}" class="form-control form-control-solid" placeholder="S A Name" value="{{ $toLetter->designation }}" name="designation[{{$index}}][designation]">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-5">
                            <input type="text" id="department-{{$index}}"  class="form-control form-control-solid" placeholder="S A Designation" value="{{ $toLetter->department }}" name="designation[{{$index}}][department]" required>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-5">
                            <input type="text" id="address-{{$index}}" class="form-control form-control-solid" placeholder="Department" value="{{ $toLetter->address }}" name="designation[{{$index}}][address]" required>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-5">
                            <input type="text" id="contact-{{$index}}" class="form-control form-control-solid" placeholder="Other" value="{{ $toLetter->contact }}" name="designation[{{$index}}][contact]" >
                        </div>
                    </div>

                </div>
                    @endforeach


                </div>

                <div class="col-md-12" style="text-align: center;">
                    <a id="gos_bg_color"  onclick="downloadPdf('{{ route('Order.download.pdf', $letter->id) }}')" class="btn btn-primary mx-1 ms-ms-3 mb-3 mb-sm-0" data-bs-original-title="Pdf file Download" title="Pdf File Download" data-bs-toggle="tooltip" id="download-btn">PDF Download</a>
                    <a   id="gos_bg_color" onclick="downloadDOC('{{route('order.download.doc',$letter->id)}}')"  class="btn btn-primary mx-1 ms-ms-3 mb-3 mb-sm-0">DOC Download</a>
                    <button id="gos_bg_color" onclick="updateLetter(event)" type="submit" name="action" value="save_as_draft"  class="btn btn-primary mx-1 ms-ms-3 mb-3 mb-sm-0">Update Draft</button>
                    <button id="gos_bg_color"  type="button"  data-toggle="modal" data-target="#letterPreviewModal" onclick="loadLetterPreview({{ $letter->id }})" class="btn btn-primary mx-1 ms-ms-3 mb-3 mb-sm-0">Print Preview</button>

                </div>
            </div>
                                       <!-- Bootstrap Modal -->
<!-- Modal Structure -->
<div class="modal fade" id="letterPreviewModal" tabindex="-1" role="dialog" aria-labelledby="letterPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="letterPreviewModalLabel">Order Preview</h5>
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
{{--  --}}
            <script>
                CKEDITOR.replace('editor')


                function downloadPdf(url) {
                               // Show custom modal popup
                        Swal.fire({
                            title: 'Download PDF?',
                            text: 'Are you sure you want to download the PDF?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, download!',
                            cancelButtonText: 'No, cancel!'
                        }).then((result) => {
                            if (result.value) {
                                var xhr = new XMLHttpRequest();
                                xhr.open('GET', url, true);
                                xhr.responseType = 'blob';
                                xhr.onload = function() {
                                    if (xhr.status === 200) {
                                        var blob = new Blob([xhr.response], {type: 'application/pdf'});
                                        var link = document.createElement('a');
                                        link.href = window.URL.createObjectURL(blob);
                                        link.download = 'order_' + '{{ $letter->letter_no }}' + '.pdf';
                                        link.click();
                                        Swal.fire({
                                        title: 'Download Successful!',
                                        text: 'Your PDF file has been downloaded successfully.',
                                        icon: 'success',
                                        confirmButtonText: 'Ok'
                                    });
                                    }
                                };
                                xhr.send();
                            }
                        });
                    }
                          function downloadDOC(url) {
                              // Show custom modal popup
                                Swal.fire({
                                    title: 'Download DOCX?',
                                    text: 'Are you sure you want to download the DOCX file?',
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: 'Yes, download!',
                                    cancelButtonText: 'No, cancel!'
                                }).then((result) => {
                                    if (result.value) {
                                        var xhr = new XMLHttpRequest();
                                        xhr.open('GET', url, true);
                                        xhr.responseType = 'blob';
                                        xhr.onload = function() {
                                            if (xhr.status === 200) {
                                                var blob = new Blob([xhr.response], {type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'});
                                                var link = document.createElement('a');
                                                link.href = window.URL.createObjectURL(blob);
                                                link.download = 'order_' + '{{ $letter->letter_no }}' + '.docx';
                                                link.click();
                                                Swal.fire({
                                        title: 'Download Successful!',
                                        text: 'Your DOC file has been downloaded successfully.',
                                        icon: 'success',
                                        confirmButtonText: 'Ok'
                                    });
                                            }
                                        };
                                        xhr.send();
                                    }
                                });
                            }
            </script>
        </form>
        <hr>
        <form id="UploadLetter" action="{{ route('order.upload', $letter->id) }}" method="POST" enctype="multipart/form-data" style="margin-top: 10px;">
            @csrf

        <div class="col-md-12 col-lg-12">

            <label style="color:red;" for="signed_letter" class="form-label  mb-3">NOTE: Download the order, have it signed, then scan and upload it as a PDF. Ensure the file number matches the order number for a successful upload.</label>
        </div>
        <div style="display:flex;align-items:center;justify-content:center">
        <div class="col-lg-4">
            <div class="">

                <input type="file" id="signed_letter" name="signed_order" class="form-control form-control-solid" accept=".pdf">
            </div>

        </div>
        {{-- <div class="col-md-3"> --}}
            <button id="gos_bg_color" onclick="createLetter(event)" style="width:150px; margin-left:5px;" type="submit" class="btn btn-primary text-center">Submit Order</button>
        {{-- </div> --}}
    </div>
    </form>
        </div>
    </div>

            {{-- letter preview modal --}}

            <script>
                function createLetter(event) {
                   event.preventDefault(); // Prevent default form submission

                   let formData = new FormData($('#UploadLetter')[0]); // Create a new FormData object

                   // Perform AJAX request to submit the form data
                   $.ajax({
                       url: $('#UploadLetter').attr('action'), // Use the form's action attribute
                       type: 'POST',
                       data: formData, // Send the FormData object
                       processData: false, // Prevent jQuery from processing the data
                       contentType: false, // Prevent jQuery from setting the content type
                       success: function(response) {
                           // Redirect with the letter ID
                            // Display success message
                   Swal.fire({
                       title: 'Success!',
                       text: 'Signed Order uploaded & submitted successfully',
                       icon: 'success',
                       confirmButtonText: 'Ok'
                   });
                           let letterId = response.letter_id;
                           window.location.href = "/admin/dashboard?error=true"; // Construct URL with letter ID
                       },
                       error: function(xhr, status, error) {
                   // Handle error
                   let errorMsg = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred.';
                   if (xhr.responseJSON && xhr.responseJSON.errors) {
                       // Display validation errors
                       let errors = xhr.responseJSON.errors;
                       let errorMessages = [];
                       for (let field in errors) {
                           errorMessages.push(errors[field][0]);
                       }
                       Swal.fire({
                           title: 'Error!',
                           text: errorMessages.join('<br>'),
                           icon: 'error',
                           confirmButtonText: 'Ok'
                       });
                   } else if (xhr.status === 400) {
                       // Display custom error message
                       Swal.fire({
                           title: 'Error!',
                           text: 'Uploaded Order does not match the original order',
                           icon: 'error',
                           confirmButtonText: 'Ok'
                       });
                   } else {
                       Swal.fire({
                           title: 'Error!',
                           text: errorMsg,
                           icon: 'error',
                           confirmButtonText: 'Ok'
                       });
                   }
               }
                   });
               }

                          </script>

@endsection




<!-- A friendly reminder to run on a server, remove this during the integration. -->

<script>

    // CKEDITOR.replace( 'draft_para' );

    function updateLetter(event) {
        event.preventDefault();
    var formData = new FormData($('#draftForm')[0]);
    $.ajax({
        url: $('#draftForm').attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            Swal.fire({
                title: 'Success!',
                text: response.message,
                icon: 'success',
                confirmButtonText: 'Ok'
            });
            // window.location.href = window.location.href;
            return false;
        }
    });
}


    var fieldCounter = {{ count($letter->designations) ? count($letter->designations) - 1 : -1 }};
    var fieldCounterss = {{ count($letter->forwardedCopies) ? count($letter->forwardedCopies) - 1 : -1 }};

//  CKEDITOR.replace('draft_para');

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
    var previewUrl = '{{ route('order.preview', ':id') }}';
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
                window.location.href = '{{ route('forms.order.edit', ':id') }}'.replace(':id', letterId);
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
                        <input type="hidden"  class="form-control form-control-solid" placeholder="Designation">
                    </div>
                </div>
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
                <div class="col-lg-2">
                <button type="button" class="btn btn-danger mb-5" onclick="removeRecipient(${fieldCounter})">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
            </div>`;

        document.getElementById('dynamic-fields').insertAdjacentHTML('beforeend', newField);


    }
    // Function to remove the corresponding row
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

                fieldCounterss++;
                const newFielddd = `
                    <div class="row" id="fieldd-${fieldCounterss}">
                        <div class="col-lg-6">
                       <div class="mb-5">
                        <input type="text" id="copy_forwarded" class="form-control form-control-solid" placeholder="Copy of Forwarded" name="forwarded_copies[${fieldCounterss}][copy_forwarded]" required>
                    </div>
                </div>
                 <div class="col-lg-2">
                <button type="button" class="btn btn-danger mb-5" onclick="removeForwardCopy(${fieldCounterss})">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
                    </div>`;
                document.getElementById('dynamic-fielddds').insertAdjacentHTML('beforeend', newFielddd);


        }
        function removeForwardCopy(id) {
    const field = document.getElementById(`fieldd-${id}`);
    if (field) {
        field.remove(); // Remove the row
    }
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






</script>
