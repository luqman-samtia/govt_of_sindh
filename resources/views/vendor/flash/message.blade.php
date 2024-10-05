
@foreach (session('flash_notification', collect())->toArray() as $message)
    @if ($message['overlay'])
        @include('flash::modal', [
            'modalClass' => 'flash-modal',
            'title'      => $message['title'],
            'body'       => $message['message']
        ])
    @else
        <div class="alert alert-{{ $message['level'] }} {{ $message['important'] ? 'alert-important' : '' }} custom-message">
            <div class="d-flex text-white align-items-center">
                <i class="fa-solid {{ $message['level'] == 'success' ? 'fa-face-smile' : 'fa-face-frown' }}  me-5"></i>
                <div>
                    <span class="text-white">{{ $message['message'] }}</span>
                </div>
            </div>
        </div>
    @endif
@endforeach
<script>
    $(document).ready(function() {
        // Toastr options (Optional)
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
        };

        // Check for success message
        @if (session('message'))
            toastr.success("{{ session('message') }}");
        @endif

        // Check for error message
        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    });

    // @if ($errors->any())
    //     @foreach ($errors->all() as $error)
    //         toastr.error("{{ $error }}", "Error", {
    //             closeButton: true,
    //             progressBar: true,
    //             positionClass: "toast-top-right",
    //             timeOut: 5000,
    //         });
    //     @endforeach
    // @endif



    @if ($errors->any())
        let errorMessages = '';
        @foreach ($errors->all() as $error)
            errorMessages += '<p>{{ $error }}</p>';
        @endforeach

        Swal.fire({
            title: 'Validation Error!',
            html: errorMessages,
            icon: 'error',
            confirmButtonText: 'Ok',
            customClass: {
                popup: 'swal-wide' // Optional: You can define custom styles
            }
        });
    @endif


</script>
{{-- {{ session()->forget('flash_notification') }} --}}
