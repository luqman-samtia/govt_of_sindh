@extends('layouts.app')
@section('title')
    {{ __('edit letter') }}
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
            <form action="{{  route('forms.letter.update', $letter) }}" method="POST" >
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-4">
                        <div class="mb-5">
                            {{-- <label for="letter_no" class="form-label"></label> --}}
                            {{-- <input type="text" id="letter_no" class="form-control form-control-solid" value="{{$newLetterNo}}" placeholder="Letter No" name="letter_no" readonly> --}}
                            <img src="{{asset('storage/qr-codes/download4.jpeg')}}" class="form-control form-control-solid" alt="GOVT OF SINDH" width="" style="width: 150px;background:none;border:none">
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-4">
                        <div class="mb-5" style="text-align:left;justify-content:center;">
                            {{-- <label for="letter_no" class="form-label mt-3"></label> --}}
                            {{-- <input type="text" id="letter_no" class="form-control form-control-solid" value="{{$newLetterNo}}" placeholder="Letter No" name="letter_no" readonly> --}}
                            <h4 class="form-control form-control-solid" style="background:none;border:none">ANTI-CORRUPTION ESTABLISHMENT SINDH</h4>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4">
                        <div class="mb-5">
                            {{-- <label for="letter_no" class="form-label required mb-3">Letter No</label> --}}
                            <input type="text" id="letter_no" class="form-control form-control-solid" value="{{$letter->letter_no}}" placeholder="Letter No" name="letter_no" readonly>
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
                {{-- <hr>
                <h4>To,</h4> --}}

                {{-- <div class="col-md-12" style="text-align: end;">
                    <button type="button" onclick="addRecipient()" id="add-field" class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0" name="designation">
                    <span data-bs-toggle="tooltip" data-bs-placement="top"  >
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
                {{-- @if(isset($letter) && $letter->toLetters->count() > 0) --}}
                @foreach($letter->designations as $index => $toLetter)

            <div class="row" id="field-1" style="display:flex;flex-direction:row;justify-content:center;align-items:center;">
                <div class="col-lg-2">
                    <div class="mb-5">
                        <input type="text" id="designation" class="form-control form-control-solid" placeholder="Designation" value="{{ $toLetter->designation }}" name="designation[{{$index}}][designation]" required>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-5">
                        <input type="text" id="department"  class="form-control form-control-solid" placeholder="Department" value="{{ $toLetter->department }}" name="designation[{{$index}}][department]" required>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-5">
                        <input type="text" id="address" class="form-control form-control-solid" placeholder="Address" value="{{ $toLetter->address }}" name="designation[{{$index}}][address]" required>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-5">
                        <input type="text" id="contact" class="form-control form-control-solid" placeholder="Contact" value="{{ $toLetter->contact }}" name="designation[{{$index}}][contact]" >
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-5">
                     <button type="button" onclick="addRecipient()" id="add-field" class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0">
                         <span data-bs-toggle="tooltip" data-bs-placement="top" >
                             <i class="fas fa-plus"></i>
                         </span>
                  </button>
                    </div>
                 </div>
            </div>
            @endforeach
            </div>

                {{-- <hr>
                <h4>Subject:</h4> --}}
               <div class="row" style="display:flex:flex-direction:row">
                <div class="col-lg-10 mt-5">
                    <div class="mb-5">
                        <label for="subject" class="form-label required mb-3">Subject</label>
                        <input type="text" id="subject" class="form-control form-control-solid" placeholder="Subject" value="{{$letter->subject}}" name="subject" required>
                    </div>
                </div>
                <div class="col-lg-3">
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
                </div>
               </div>
                <hr>
                <div class="col-lg-12">
                    <div class="mb-5">
                        <label for="draft_para" class="form-label required mb-3">Draft Para</label>
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
                    <button type="button" id="add-fielddd" onclick="ForwardCopy()" class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0" name="copy">
                    <span data-bs-toggle="tooltip" data-bs-placement="top"  >
                        <i class="fas fa-plus"></i>
                    </span>
                    </button>
                </div>
                <div id="dynamic-fielddds">
                    @foreach ($letter->forwardedCopies as $index => $copy)
             <div class="row" id="fielddd-1">
                <div class="col-lg-6">
                       <div class="mb-5">
                        <input type="text" id="copy_forwarded" class="form-control form-control-solid" placeholder="Copy of Forwarded" value="{{$copy->copy_forwarded}}" name="forwarded_copies[0][copy_forwarded]" required>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

                <hr>

                {{-- <div id="signedLetterUpload" style="display: block;">
                    <label for="signed_letter">Upload Signed Letter:</label>
                    <input type="file" name="signed_letter" id="signed_letter" accept=".pdf">
                </div> --}}
                <div class="col-md-12" style="text-align: center;">
                    <a href="" onclick="downloadPdf('{{ route('Form.download.pdf', $letter->id) }}')" class="btn btn-primary mx-1 ms-ms-3 mb-3 mb-sm-0" data-bs-original-title="Pdf file Download" title="Pdf File Download" data-bs-toggle="tooltip" id="download-btn">PDF Download</a>
                    <a  type="button" name="" value=""  class="btn btn-primary mx-1 ms-ms-3 mb-3 mb-sm-0">DOC Download</a>
                    <button  type="submit" name="action" value="save_as_draft"  class="btn btn-primary mx-1 ms-ms-3 mb-3 mb-sm-0">Update Draft</button>
                    <button  type="button" name="" value=""    class="btn btn-primary mx-1 ms-ms-3 mb-3 mb-sm-0">Print Preview</button>
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
        <div class="col-lg-12">
            <div class="mb-5">
                <label for="signed_letter" class="form-label  mb-3">Upload Signed Letter:</label>
                <input type="file" id="signed_letter" name="signed_letter" class="form-control form-control-solid" accept=".pdf">
            </div>

        </div>
        <button type="submit" class="btn btn-primary text-center">Upload Signed Letter</button>

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
 CKEDITOR.replace('draft_para');


    function addRecipient(){
        let fieldCounter = 0;
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
                    <div class="mb-5">
                        <input type="hidden" id="designation" class="form-control form-control-solid">
                    </div>
                </div>
                </div>`;
            document.getElementById('dynamic-fields').insertAdjacentHTML('beforeend', newField);
        // });
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
        let fieldCounterss = 0;
            // document.getElementById('add-fielddd').addEventListener('click', function() {
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
            // });

            if (document.getElementById('signed_letter')) {
    document.getElementById('signedLetterUpload').style.display = 'block';
}

     }
</script>
