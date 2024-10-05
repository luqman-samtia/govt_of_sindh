@extends('layouts.app')
@section('title')
    {{-- {{ __('messages.dashboard') }} --}}
@endsection
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <div class="container-fluid">
        <div class="d-flex flex-column">
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
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        {{-- Clients Widget --}}
                        <div class="col-xxl-3 col-xl-3 col-sm-6 col-md-3 col-md-3 widget">
                            <a href="{{route('total_letter')}}" class="mb-xl-8 text-decoration-none">
                                <div
                                    class="bg-success shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                    <div
                                        class="bg-green-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-file-invoice card-icon text-white"></i>
                                    </div>
                                    <div class="text-end text-white">
                                        <h2 class="fs-1-xxl fw-bolder text-white">{{ $total_letters }}
                                        </h2>
                                        <h3 class="mb-0 fs-4 fw-light"> {{ __('Total Letters') }}
                                        </h3>
                                    </div>
                                </div>
                            </a>
                        </div>

                        {{-- Total Invoices Widget --}}
                        <div class="col-xxl-3 col-xl-3 col-sm-6 col-md-3 col-md-3 widget">
                            <a href="{{route('total_order')}}" class="mb-xl-8 text-decoration-none">
                                <div
                                    class="bg-info shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                    <div
                                        class="bg-blue-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-file-invoice card-icon text-white"></i>
                                    </div>
                                    <div class="text-end text-white">
                                        <h2 class="fs-1-xxl fw-bolder text-white">{{ count($users_order) }}
                                        </h2>
                                        <h3 class="mb-0 fs-4 fw-light">{{ __('Total Orders') }}
                                        </h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xxl-3 col-xl-3 col-sm-6 col-md-3 col-md-3 widget">
                            <a href="" class="mb-xl-8 text-decoration-none">
                                <div
                                    class="bg-danger shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                    <div
                                        class="bg-red-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-file-invoice card-icon text-white"></i>
                                    </div>
                                    <div class="text-end text-white">
                                        <h2 class="fs-1-xxl fw-bolder text-white">{{ $total_drafts + count($users_draft_order)}}
                                        </h2>
                                        <h3 class="mb-0 fs-4 fw-light">{{ __('Total Drafts') }}
                                        </h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xxl-3 col-xl-3 col-sm-6 col-md-3 col-md-3 widget">
                            <a href="{{route('admin.dashboard')}}" class="mb-xl-8 text-decoration-none">
                                <div
                                    class="bg-primary shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                    <div
                                        class="bg-cyan-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-file-invoice card-icon text-white"></i>
                                    </div>
                                    <div class="text-center text-white">
                                        <h2 class="fs-1-xxl fw-bolder text-white">
                                        </h2>
                                        <h3 class="mb-0 fs-4 fw-light">{{ __('Create Letter | Order') }}
                                        </h3>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>

                <div class="col-12 col-md-12 col-lg-12 mb-5 mb-xl-0">
                    <div class="card">
                        <div style="display: flex;justify-content:space-between;">
                            <div class="card-header pb-0 px-10">
                                <h3 class="mb-0">{{ __('Total Draft Orders') }}</h3>
                            </div>
                            <div class="card-header pb-0 px-10">
                                <a href="{{route('total_draft_order')}}" class="btn btn-danger mb-0">{{ __('Draft Orders') }}-{{count($draft_order)}}</a>

                                <a style="margin-left:5px;" href="{{route('total_draft_letter')}}" class="btn btn-danger mb-0">{{ __('Draft Letters') }}-{{$total_drafts}}</a>
                            </div>
                        </div>

                        <div class="card-body pt-7">



                            <div class="table-responsive mt-5">
                                <table class="table table-striped">
                                   <thead class="">
                                      <tr>
                                         <th scope="col" class="text-center" wire:key="header-col-0-4Of9aF3orQYiqBL2xp3j">
                                            <div class="d-flex align-items-center" style="cursor:pointer;">
                                               <span>Full Name</span>
                                               <span class="relative d-flex align-items-center">
                                                  <svg xmlns="http://www.w3.org/2000/svg" class="ml-1" style="width:1em;height:1em;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                                  </svg>
                                               </span>
                                            </div>
                                         </th>
                                         <th scope="col" class="" style="width:9%;text-align:center;" wire:key="header-col-2-4Of9aF3orQYiqBL2xp3j">
                                            <div class="d-flex align-items-center" wire:click="sortBy('id')" style="cursor:pointer;">
                                               <span>Order No</span>
                                               <span class="relative d-flex align-items-center">
                                                  <svg xmlns="http://www.w3.org/2000/svg" class="ml-1" style="width:1em;height:1em;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                                  </svg>
                                               </span>
                                            </div>
                                         </th>
                                         <th scope="col" class="text-center" wire:key="header-col-3-4Of9aF3orQYiqBL2xp3j">
                                            <div class="d-flex align-items-center" wire:click="sortBy('designation')" style="cursor:pointer;">
                                               <span>Date</span>
                                               <span class="relative d-flex align-items-center">
                                                  <svg xmlns="http://www.w3.org/2000/svg" class="ml-1" style="width:1em;height:1em;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                                  </svg>
                                               </span>
                                            </div>
                                         </th>



                                         <th scope="col" class="text-center" wire:key="header-col-3-4Of9aF3orQYiqBL2xp3j">
                                            <div class="d-flex align-items-center" wire:click="sortBy('designation')" style="cursor:pointer;">
                                               <span>Print Preview</span>
                                               <span class="relative d-flex align-items-center">
                                                  <svg xmlns="http://www.w3.org/2000/svg" class="ml-1" style="width:1em;height:1em;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                                  </svg>
                                               </span>
                                            </div>
                                         </th>
                                         <th scope="col" class="" style="width:9%;text-align:center;" wire:key="header-col-9-4Of9aF3orQYiqBL2xp3j">
                                            Download
                                         </th>
                                         <th scope="col" class="" style="width:9%;text-align:center;" wire:key="header-col-9-4Of9aF3orQYiqBL2xp3j">
                                            Action
                                         </th>
                                      </tr>
                                   </thead>
                                   <tbody class="">
                                    @if ($letters->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">No data available</td>
                                    </tr>

                                <script>
                                    toastr.error("No records found for the given search criteria.");
                                </script>

                                    @else

                                    @foreach ($letters as $letter)
                                      <tr wire:loading.class.delay="" class="" wire:key="row-0-4Of9aF3orQYiqBL2xp3j">
                                         <td class="" wire:key="cell-0-0-4Of9aF3orQYiqBL2xp3j">
                                            <div class="d-flex align-items-center">
                                               {{-- <a href="#">
                                                  <div class="image image-circle image-mini me-3">
                                                     <img src="{{getLogInUser()->profile_image}}" alt="user" class="user-img">
                                                  </div>
                                               </a> --}}
                                               <div class="d-flex flex-column">
                                                  <a href="" class="mb-1 text-decoration-none fs-6">
                                                    {{ getLogInUser()->full_name }}
                                                  </a>
                                                  {{-- <span class="fs-6">admin@infy-invoices.com</span> --}}
                                               </div>
                                            </div>
                                         </td>
                                         <td class="" wire:key="cell-0-2-4Of9aF3orQYiqBL2xp3j">
                                            <span class="badge bg-light-success fs-7">{{$letter->letter_no}}</span>
                                         </td>

                                         <td class="" wire:key="cell-0-7-4Of9aF3orQYiqBL2xp3j">
                                           {{date('dS M, Y',strtotime($letter->date))}}
                                         </td>
                                         <td class="" wire:key="cell-0-8-4Of9aF3orQYiqBL2xp3j">

                                            <button style="border:none;outline:none" class="badge bg-light-success fs-7" type="button"  data-toggle="modal" data-target="#letterPreviewModal" onclick="loadLetterPreview({{ $letter->id }})"> Print Preview
                                            </button>

                                         </td>
                                         {{-- @if($letter->is_submitted==0) --}}
                                         <td class="" wire:key="cell-0-9-4Of9aF3orQYiqBL2xp3j">
                                            <div class="width-90px text-center d-flex justify-content-center align-content-center">
                                               {{-- <a href="" onclick="downloadPdf('{{ route('Form.download.pdf', $letter->id) }}')" class="btn btn-sm px-2 text-primary fs-3 py-2" data-bs-original-title="Pdf file Download" title="Pdf File Download" data-bs-toggle="tooltip" id="download-btn"> <span class="badge bg-light-success fs-7 px-2">unsigned</span></a> --}}

                                               <div class="dropdown">
                                                <button id="dropdown-toggle" class="btn btn-sm px-2 text-primary fs-3 py-2 dropdown-toggle"
                                                        type="button" id="downloadDropdown" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                    <span class="badge bg-light-success fs-7 px-2">select</span>

                                                </button>
                                                <ul id="dropdown-menu"  class="dropdown-menu" aria-labelledby="downloadDropdown">
                                                    <li><a class="dropdown-item badge bg-light-success fs-7 px-2"  onclick="downloadFile('{{ $letter->id }}', 'pdf')">PDF</a></li>
                                                    <li><a class="dropdown-item badge bg-light-success fs-7 px-2"  onclick="downloadFile('{{ $letter->id }}', 'doc')">DOC</a></li>
                                                </ul>
                                            </div>


                                               {{-- <a href="{{ route('letters.download_signed', $letter->id) }}" class="btn btn-sm px-2 text-primary fs-3 py-2" data-bs-original-title="Uploaded file Download" title="Uploaded File Download" data-bs-toggle="tooltip"> <span class="badge bg-light-primary fs-7 px-2">uploaded</span></a> --}}
                                               {{-- <a href="{{route('letter.download.doc', $letter->id)}}" class="btn btn-sm px-2 text-primary fs-3 py-2" data-bs-original-title="Doc File Download" title="Doc File Download" data-bs-toggle="tooltip"><span class="badge bg-light-info fs-7 px-2"> doc</span></a> --}}

                                               {{-- <form action="{{ route('forms.letter.destroy', $letter) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                               <button type="submit" onclick="return confirm('Are you sure you want to delete this letter?')"   data-bs-original-title="Delete" title="Delete" data-bs-toggle="tooltip" class="user-delete-btn btn px-2 text-danger fs-3 py-2"  data-turbolinks="false" >
                                                  <svg class="svg-inline--fa fa-trash" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="trash" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                                     <path fill="currentColor" d="M135.2 17.69C140.6 6.848 151.7 0 163.8 0H284.2C296.3 0 307.4 6.848 312.8 17.69L320 32H416C433.7 32 448 46.33 448 64C448 81.67 433.7 96 416 96H32C14.33 96 0 81.67 0 64C0 46.33 14.33 32 32 32H128L135.2 17.69zM394.8 466.1C393.2 492.3 372.3 512 346.9 512H101.1C75.75 512 54.77 492.3 53.19 466.1L31.1 128H416L394.8 466.1z"></path>
                                                  </svg>
                                                  <!-- <i class="fa-solid fa-trash"></i> Font Awesome fontawesome.com -->
                                               </button>
                                            </form> --}}
                                            </div>
                                         </td>
                                         <td>

                                            <a href="{{ route('forms.order.edit', $letter) }}" class="btn px-2 text-primary fs-3 py-2" title="Edit" data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                               <svg class="svg-inline--fa fa-pen-to-square" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="pen-to-square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                                  <path fill="currentColor" d="M490.3 40.4C512.2 62.27 512.2 97.73 490.3 119.6L460.3 149.7L362.3 51.72L392.4 21.66C414.3-.2135 449.7-.2135 471.6 21.66L490.3 40.4zM172.4 241.7L339.7 74.34L437.7 172.3L270.3 339.6C264.2 345.8 256.7 350.4 248.4 353.2L159.6 382.8C150.1 385.6 141.5 383.4 135 376.1C128.6 370.5 126.4 361 129.2 352.4L158.8 263.6C161.6 255.3 166.2 247.8 172.4 241.7V241.7zM192 63.1C209.7 63.1 224 78.33 224 95.1C224 113.7 209.7 127.1 192 127.1H96C78.33 127.1 64 142.3 64 159.1V416C64 433.7 78.33 448 96 448H352C369.7 448 384 433.7 384 416V319.1C384 302.3 398.3 287.1 416 287.1C433.7 287.1 448 302.3 448 319.1V416C448 469 405 512 352 512H96C42.98 512 0 469 0 416V159.1C0 106.1 42.98 63.1 96 63.1H192z"></path>
                                               </svg>
                                               <!-- <i class="fa-solid fa-pen-to-square"></i> Font Awesome fontawesome.com -->
                                            </a>


                                         </td>
                                         {{-- @endif --}}
                                      </tr>
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
                                                      fetch('/forms')
                                                      .then(response => response.text())
                                                      .then(formUrl => {
                                                          window.location.href = formUrl;
                                                      });
                                                      // window.location.href = '{{ URL::previous() }}'; // Redirect back to previous page
                                                      window.location.href = "{{ route('forms') }}";
                                                  }
                                              };
                                              xhr.send();
                                          }

                                          function downloadFile(letterId, fileType) {
                                                let url = '';

                                                if (fileType === 'pdf') {
                                                    // Set the URL for the PDF route
                                                    url = "{{ route('Order.download.pdf', ':id') }}".replace(':id', letterId);
                                                } else if (fileType === 'doc') {
                                                    // Set the URL for the DOC route
                                                    url = "{{ route('order.download.doc', ':id') }}".replace(':id', letterId);
                                                }

                                                // Redirect to the appropriate URL for the download
                                                window.location.href = url;
                                            }

                                            @if(Session::has('success'))
                                            toastr.success("{{ Session::get('success') }}");
                                        @endif

                                        @if(Session::has('error'))
                                            toastr.error("{{ Session::get('error') }}");
                                        @endif

                                        // print preview
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
                window.location.href = '{{ route('total_draft_order', ':id') }}'.replace(':id', letterId);
            });
        })
        .catch(error => {
            console.error('Error fetching letter preview:', error);
            document.getElementById('letterPreviewContent').innerHTML = '<p>Unable to load the letter preview.</p>';
        });
}

// end print preview

                                      </script>

                                      @endforeach
                                      @endif
                                   </tbody>
                                </table>
                                 {{-- print preview --}}
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
                                {{-- end print preview --}}
                             </div>
                            <div id="payment-overview-container" class="justify-align-center">
                                <canvas id="payment_overview"></canvas>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
    {{-- {{ Form::hidden('currency', getCurrencySymbol(), ['id' => 'currency']) }} --}}
    {{-- {{ Form::hidden('currency_position', currencyPosition(), ['id' => 'currency_position']) }} --}}
@endsection
