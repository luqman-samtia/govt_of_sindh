@extends('layouts.app')
@section('title')
    {{ __('messages.dashboard') }}


@endsection
@section('content')

<div class="container-fluid">
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
    <div class="d-flex flex-column">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="row">
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                        <a href="{{ route('users.index') }}"
                           class="mb-xl-8 text-decoration-none">

                            <div
                                class="bg-primary shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                <div
                                    class="bg-cyan-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-user display-4 card-icon text-white"></i>
                                </div>
                                <div class="text-end text-white">
                                    <h2 class="fs-1-xxl fw-bolder text-white"> {{ formatTotalAmount($data['users']) }}</h2>
                                    <h3 class="mb-0 fs-4 fw-light">{{ __('messages.total_users') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                        <a href="{{route('super.admin.total.letters')}}"
                        class="mb-xl-8 text-decoration-none">

                        <div
                        class="bg-success shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">

                                <div
                                    class="bg-green-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                    {{-- <i class="fas fa-rupee-sign display-4 card-icon text-white"></i> --}}
                                    <i class="fas fa-file-invoice card-icon text-white"></i>
                                </div>
                                {{-- <div class="text-center">

                                    <h2 style="text-align: center;">jjjjjj</h2>
                                </div> --}}
                                <div class="text-end text-white">
                                     {{-- <h3 class="fs-1-xxl text-white text-center">LETTERS</h3> --}}

                                    <h2 class="fs-1-xxl fw-bolder text-white">{{count($users_form)}}</h2>
                                    <h3 class="mb-0 fs-4 fw-light">{{ __('Total Letters Issued') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                        <a href=""
                           class="mb-xl-8 text-decoration-none">

                            <div
                                class="bg-info shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                <div
                                    class="bg-blue-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-toggle-on display-4 card-icon text-white"></i>

                                </div>
                                <div class="text-end text-white">
                                    <h2 class="fs-1-xxl fw-bolder text-white">0</h2>
                                    <h3 class="mb-0 fs-4 fw-light">{{ __('Total Order Issued') }}</h3>
                                </div>

                            </div>
                        </a>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                        <a href=""
                           class="mb-xl-8 text-decoration-none">

                            <div
                                class="bg-danger shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                <div
                                    class="bg-red-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-file-invoice card-icon text-white"></i>
                                </div>
                                <div class="text-end text-white">
                                    <h2 class="fs-1-xxl fw-bolder text-white">{{count($letters)}}</h2>
                                    <h3 class="mb-0 fs-4 fw-light">{{ __('Total Drafts') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12 mb-4">
                <div class="">
                    <div class="card mt-3">
                        <div class="card-body p-5">
                            <div class="card-header border-0 pt-5">
                                <h3 class="mb-0">{{  __('Total Drafts') }}</h3>
                                <div class="ms-auto">
                                    <div id="rightData" class="date-picker-space">
                                        {{-- <input class="form-control removeFocus" id="super_admin_time_range"> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-lg-6 p-0">


                                 {{-- table --}}

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
                               <span>Letter No</span>
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
                               <span>Status</span>
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
                    @else

                    @foreach ($letters as $letter)
                      <tr wire:loading.class.delay="" class="" wire:key="row-0-4Of9aF3orQYiqBL2xp3j">
                         <td class="" wire:key="cell-0-0-4Of9aF3orQYiqBL2xp3j">
                            <div class="d-flex align-items-center">
                               {{-- <a href="#">
                                  <div class="image image-circle image-mini me-3">
                                     <img src="{{$letter->user->profile_image}}" alt="user" class="user-img">
                                  </div>
                               </a> --}}
                               <div class="d-flex flex-column">
                                  <a href="" class="mb-1 text-decoration-none fs-6">
                                    {{ $letter->user->full_name }}
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
                            @if ($letter->is_submitted==0)
                            <span class="badge bg-light-success fs-7"> Draft


                            @else
                            <span class="badge bg-light-danger fs-7"> Issued
                            @endif
                            </span>
                         </td>
                         {{-- @if($letter->is_submitted==0) --}}
                         <td class="" wire:key="cell-0-9-4Of9aF3orQYiqBL2xp3j">
                            <div class="width-90px text-center d-flex justify-content-center align-content-center">
                               {{-- <a href="" onclick="downloadPdf('{{ route('Form.download.pdf', $letter->id) }}')" class="btn btn-sm px-2 text-primary fs-3 py-2" data-bs-original-title="Pdf file Download" title="Pdf File Download" data-bs-toggle="tooltip" id="download-btn"> <span class="badge bg-light-success fs-7 px-2">unsigned</span></a> --}}

                               <div class="dropdown">
                                <button id="dropdown-toggle" class="btn btn-sm px-2 text-primary fs-3 py-2 dropdown-toggle"
                                        type="button" id="downloadDropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    <span class="badge bg-light-success fs-7 px-2">unsigned</span>

                                </button>
                                <ul id="dropdown-menu" class="dropdown-menu" aria-labelledby="downloadDropdown">
                                    <li><a class="dropdown-item badge bg-light-success fs-7 px-2" href="#" onclick="downloadFile('{{ $letter->id }}', 'pdf')">PDF</a></li>
                                    <li><a class="dropdown-item badge bg-light-success fs-7 px-2" href="#" onclick="downloadFile('{{ $letter->id }}', 'doc')">DOC</a></li>
                                </ul>
                            </div>

                               {{-- <a href="{{ route('letters.download_signed', $letter->id) }}" class="btn btn-sm px-2 text-primary fs-3 py-2" data-bs-original-title="Uploaded file Download" title="Uploaded File Download" data-bs-toggle="tooltip"> <span class="badge bg-light-primary fs-7 px-2">signed</span></a> --}}
                               {{-- <a href="{{route('letter.download.doc', $letter->id)}}" class="btn btn-sm px-2 text-primary fs-3 py-2" data-bs-original-title="Doc File Download" title="Doc File Download" data-bs-toggle="tooltip"><span class="badge bg-light-info fs-7 px-2"> doc</span></a> --}}
                               <!--<a href="{{ route('forms.letter.edit', $letter) }}" class="btn px-2 text-primary fs-3 py-2" title="Edit" data-bs-toggle="tooltip" data-bs-original-title="Edit">-->
                               <!--   <svg class="svg-inline--fa fa-pen-to-square" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="pen-to-square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">-->
                               <!--      <path fill="currentColor" d="M490.3 40.4C512.2 62.27 512.2 97.73 490.3 119.6L460.3 149.7L362.3 51.72L392.4 21.66C414.3-.2135 449.7-.2135 471.6 21.66L490.3 40.4zM172.4 241.7L339.7 74.34L437.7 172.3L270.3 339.6C264.2 345.8 256.7 350.4 248.4 353.2L159.6 382.8C150.1 385.6 141.5 383.4 135 376.1C128.6 370.5 126.4 361 129.2 352.4L158.8 263.6C161.6 255.3 166.2 247.8 172.4 241.7V241.7zM192 63.1C209.7 63.1 224 78.33 224 95.1C224 113.7 209.7 127.1 192 127.1H96C78.33 127.1 64 142.3 64 159.1V416C64 433.7 78.33 448 96 448H352C369.7 448 384 433.7 384 416V319.1C384 302.3 398.3 287.1 416 287.1C433.7 287.1 448 302.3 448 319.1V416C448 469 405 512 352 512H96C42.98 512 0 469 0 416V159.1C0 106.1 42.98 63.1 96 63.1H192z"></path>-->
                               <!--   </svg>-->
                                  <!-- <i class="fa-solid fa-pen-to-square"></i> Font Awesome fontawesome.com -->
                               <!--</a>-->
                            </td>
                               <td>
                               <form action="{{ route('forms.letter.destroy', $letter) }}" method="POST">
                                @csrf
                                @method('DELETE')
                               <button type="submit" onclick="return confirm('Are you sure you want to delete this letter?')"   data-bs-original-title="Delete" title="Delete" data-bs-toggle="tooltip" class="user-delete-btn btn px-2 text-danger fs-3 py-2"  data-turbolinks="false" >
                                  <svg class="svg-inline--fa fa-trash" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="trash" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                     <path fill="currentColor" d="M135.2 17.69C140.6 6.848 151.7 0 163.8 0H284.2C296.3 0 307.4 6.848 312.8 17.69L320 32H416C433.7 32 448 46.33 448 64C448 81.67 433.7 96 416 96H32C14.33 96 0 81.67 0 64C0 46.33 14.33 32 32 32H128L135.2 17.69zM394.8 466.1C393.2 492.3 372.3 512 346.9 512H101.1C75.75 512 54.77 492.3 53.19 466.1L31.1 128H416L394.8 466.1z"></path>
                                  </svg>
                                  <!-- <i class="fa-solid fa-trash"></i> Font Awesome fontawesome.com -->
                               </button>
                            </form>
                            </div>
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
                                      fetch('/users-forms')
                                      .then(response => response.text())
                                      .then(formUrl => {
                                          window.location.href = formUrl;
                                      });
                                      // window.location.href = '{{ URL::previous() }}'; // Redirect back to previous page
                                      window.location.href = "{{ route('users.forms') }}";
                                  }
                              };
                              xhr.send();
                          }
                      </script>

                      @endforeach
                      @endif
                   </tbody>
                </table>
             </div>

            {{-- end table --}}


                                <div class="">
                                    <div id="revenue_overview-container" class="pt-2">
                                        {{-- <canvas id="revenue_chart_canvas" height="200" width="905"></canvas> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
    {{-- {{ Form::hidden('currency',  getCurrencySymbol(),['id' => 'currency']) }} --}}
    {{-- {{ Form::hidden('currency_position',  superAdminCurrencyPosition(),['id' => 'currency_position']) }} --}}
@endsection
