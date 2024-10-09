<div class="row">
    <div class="col-lg-6">
        <div class="mb-5">
            {{-- {{ Form::label('officer_name', __('messages.client.officer_name').':', ['class' => 'form-label required mb-3']) }} --}}
            <label for="first_name" class="form-label required mb-3">Oficer First Name</label>
            {{-- {{ Form::text('first_name', isset($user) ? $user->first_name : null, ['class' => 'form-control form-control-solid', 'placeholder' => __('messages.client.first_name'), 'required']) }} --}}
            <input type="text" value="@if (isset($user)){{ $user->first_name }} @endif" id="first_name" class="form-control form-control-solid" placeholder="Officer Name" name="first_name" required>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            <label for="" class="form-label required mb-3">Oficer Last Name</label>
            {{ Form::text('last_name', isset($user) ? $user->last_name : null, ['class' => 'form-control form-control-solid', 'placeholder' => __('messages.client.last_name'), 'required']) }}
        </div>
    </div>
    <div class="col-lg-6">
        <div class="class="mb-5">
            <label class="form-label required mb-3" for="designation">Designation:</label>
            <input type="text" id="designation" class="form-control form-control-solid" value="{{$user->designation}}" placeholder="Enter Designation" name="designation" required>

        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            <label for="zone" class="form-label required mb-3">Place Of Posting:</label>
            <input type="text" id="zone" class="form-control form-control-solid" value="{{$user->zone}}" placeholder="Enter Place Of Posting" name="zone" required>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            <label for="district" class="form-label required mb-3">District:</label>
            <input type="text" id="district" class="form-control form-control-solid" value="{{$user->district}}" placeholder="Enter District" name="district" required>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            <label for="letter_no" class="form-label required mb-3">Letter No:</label>
            <input type="text" id="letter_no" class="form-control form-control-solid" value="{{$user->letter_no}}" placeholder="Edit Letter No" name="letter_no" required>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            <label for="order_no" class="form-label required mb-3">Order No:</label>
            <input type="text" id="order_no" class="form-control form-control-solid" value="{{$user->order_no}}" placeholder="Edit Order No" name="order_no" required>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="class="mb-5">
            <label class="form-label required mb-3" for="grade">Grade:</label>
            <input type="text" id="grade" class="form-control form-control-solid" value="{{$user->grade}}" placeholder="Enter District" name="grade" required>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('email', __('messages.client.email').':', ['class' => 'form-label mb-3 required']) }}
            {{ Form::email('email', isset($user) ? $user->email : null, ['class' => 'form-control form-control-solid', 'placeholder' => __('messages.client.email'),'required']) }}
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('contact', __('messages.client.contact_no').':', ['class' => 'form-label mb-3 required']) }}
            {{ Form::tel('contact', isset($user) ? $user->contact : null, ['class' => 'form-control form-control-solid', 'placeholder' => __('messages.client.contact_no'),'required']) }}
        </div>
    </div>
    {{-- <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('contact', __('messages.client.contact_no').':', ['class' => 'form-label required mb-3']) }}
            {{ Form::tel('contact', null, ['class' => 'form-control form-control-solid', 'placeholder' => __('messages.client.contact_no'), 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")','id'=>'phoneNumber']) }}
            {{ Form::hidden('region_code', null,['id'=>'prefix_code']) }}
            <span id="valid-msg" class="hide text-success fw-400 fs-small mt-2">✓ &nbsp; {{ __('messages.placeholder.valid_number') }}</span>
            <span id="error-msg" class="hide text-danger fw-400 fs-small mt-2"></span>
        </div>
    </div> --}}
    <div class="col-md-6 mb-5">
        <div class="fv-row">
            <div>
                {{ Form::label('password',__('messages.client.password').':' ,['class' => 'form-label mb-3 required']) }}
                <div class="position-relative">
                    <input class="form-control form-control-solid"
                           type="password"  placeholder="{{__('messages.client.password')}}" name="password"
                           autocomplete="off"
                           id="password"
                           aria-label="Password" data-toggle="password" >
                    <span class="position-absolute d-flex align-items-center top-0 bottom-0 end-0 me-4 input-icon input-password-hide cursor-pointer text-gray-600" id="togglePasswordTwo" >
                        <i class="bi bi-eye-slash-fill" id="toggleIconTwo"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-5">
        <div class="fv-row">
            <div>
                {{ Form::label('password_confirmation', __('messages.client.confirm_password') . ':', ['class' => 'form-label mb-3 required']) }}
                <div class="position-relative">
                    <input class="form-control form-control-solid"
                           type="password"
                           id="password_confirmation"
                           placeholder="{{ __('messages.client.confirm_password') }}"
                           name="password_confirmation"
                           autocomplete="off" aria-label="Password"
                           >
                    <span class="position-absolute d-flex align-items-center top-0 bottom-0 end-0 me-4 input-icon input-password-hide cursor-pointer text-gray-600" id="togglePassword">
                        <i class="bi bi-eye-slash-fill" id="toggleIcon"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-5" io-image-input="true">
        <label for="exampleInputImage" class="form-label">{{ __('messages.client.profile') }}:</label>
        <div class="d-block">
            <div class="image-picker">
                <div class="image previewImage" id="exampleInputImage"
                {{$styleCss}}="
                background-image:url('{{ !empty($user->profile_image) ? $user->profile_image :
                    asset('assets/images/avatar.png') }}')">
            </div>
            <span class="picker-edit rounded-circle text-gray-500 fs-small" data-bs-toggle="tooltip"
                  title="{{ __('messages.user.change_profile') }}">
                    <label>
                        <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                            <input type="file" id="profile_image" name="profile" class="image-upload d-none"
                                   accept="image/*"/>
                    </label>
                </span>
        </div>
    </div>
    <div class="form-text">{{ __('messages.user.allowed_file_types') }}</div>
</div>
</div>
<div class="float-end d-flex mb-5" style="background: #48B7A3;">
    {{ Form::submit(__('messages.common.save'),['class' => 'btn btn-primary me-2']) }}
    <a href="{{ route('users.index') }}" type="reset"
       class="btn btn-secondary btn-active-light-primary">{{__('messages.common.cancel')}}</a>
</div>
<script>
    // JavaScript function to update districts based on selected zone
    function updateDistricts() {
        // Define the districts for each zone
        const zoneDistricts = {
            "HYDERABAD ZONE": ["Hyderabad", "Badin", "Tando Allahyar", "Matiari", "Tando Muhammad Khan"],
            "JAMSHORO ZONE": ["Jamshoro", "Thatta", "Sujawal", "Dadu"],
            "LARKANA ZONE": ["Larkana", "Shikarpur", "Kashmore", "Kambar Shahdadkot", "Jacobabad"],
            "MIRPURKHAS ZONE": ["Mirpur Khas", "Tharparkar", "Umerkot"],
            "SBA ZONE": ["Shaheed Benazir Abad", "Sanghar", "Naushahro Firoze"],
            "SUKKUR ZONE": ["Sukkur", "Khairpur", "Ghotki"],
            "SOUTH ZONE": ["Karachi Korangi", "Karachi South"],
            "WEST ZONE": ["Karachi West", "Karachi Central", "Karachi Keemari"],
            "EAST ZONE": ["Karachi East", "Karachi Malir"],
        };

        // Get the selected zone
        const selectedZone = document.getElementById('zone').value;

        // Get the district dropdown
        const districtDropdown = document.getElementById('district');

        // Clear the current options in the district dropdown
        districtDropdown.innerHTML = '<option value="">Select District</option>';

        // Check if the selected zone has any districts
        if (zoneDistricts[selectedZone]) {
            // Loop through the districts of the selected zone and add them as options
            zoneDistricts[selectedZone].forEach(function(district) {
                const option = document.createElement('option');
                option.value = district;
                option.text = district;

                // Preselect the saved district (for edit functionality)
                if (district === '{{ $user->district }}') {
                    option.selected = true;
                }

                districtDropdown.add(option);
            });
        }
    }

    // Call updateDistricts when the page loads to prepopulate the districts dropdown
    // document.addEventListener('DOMContentLoaded', function() {
    //     updateDistricts();
    // });

    if (window.location.pathname.includes('/edit')) {
    updateDistricts(); // This will prepopulate the district dropdown with the saved value
}


document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordField = document.getElementById('password_confirmation');
        const toggleIcon = document.getElementById('toggleIcon');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        toggleIcon.classList.toggle('bi-eye');
        toggleIcon.classList.toggle('bi-eye-slash-fill');
    });

    document.getElementById('togglePasswordTwo').addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIconTwo');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        toggleIcon.classList.toggle('bi-eye');
        toggleIcon.classList.toggle('bi-eye-slash-fill');
    });
</script>
