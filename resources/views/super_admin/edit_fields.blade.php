<div class="row gx-10 mb-5">
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('first_name', __('messages.super_admin.first_name') . ':', ['class' => 'form-label required mb-3']) }}
            {{ Form::text('first_name', isset($superAdmin) ? $superAdmin->first_name : null, ['class' => 'form-control form-control-solid', 'placeholder' => __('messages.client.first_name'), 'required']) }}
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('last_name', __('messages.super_admin.last_name') . ':', ['class' => 'form-label required mb-3']) }}
            {{ Form::text('last_name', isset($superAdmin) ? $superAdmin->last_name : null, ['class' => 'form-control form-control-solid', 'placeholder' => __('messages.client.last_name'), 'required']) }}
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('email', __('messages.super_admin.email') . ':', ['class' => 'form-label required mb-3']) }}
            {{ Form::email('email', isset($superAdmin) ? $superAdmin->email : null, ['class' => 'form-control form-control-solid', 'placeholder' => __('messages.client.email'), 'required']) }}
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('contact', __('messages.client.contact_no') . ':', ['class' => 'form-label required mb-3']) }}
            {{ Form::tel('contact', isset($superAdmin) ? $superAdmin->contact : null, ['class' => 'form-control form-control-solid', 'placeholder' => __('messages.client.contact_no'),'required']) }}
            {{-- {{ Form::hidden('region_code', isset($superAdmin) ? $superAdmin->region_code : null, ['id' => 'prefix_code']) }}
            <span id="valid-msg" class="hide text-success fw-400 fs-small mt-2">✓ &nbsp;
                {{ __('messages.placeholder.valid_number') }}</span>
            <span id="error-msg" class="hide text-danger fw-400 fs-small mt-2"></span> --}}
        </div>
    </div>
    <div class="col-md-6 mb-5">
        <div class="fv-row">
            <div>
                {{ Form::label('password', __('messages.super_admin.password') . ':', ['class' => 'form-label mb-3']) }}
                <div class="position-relative">
                    <input class="form-control form-control-solid" type="password"
                        placeholder="{{ __('messages.super_admin.password') }}" name="password" autocomplete="off"
                        aria-label="Password" data-toggle="password">
                    <span
                        class="position-absolute d-flex align-items-center top-0 bottom-0 end-0 me-4 input-icon input-password-hide cursor-pointer text-gray-600">
                        <i class="bi bi-eye-slash-fill"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-5">
        <div class="fv-row">
            <div>
                {{ Form::label('confirmPassword', __('messages.super_admin.confirm_password') . ':', ['class' => 'form-label mb-3']) }}
                <div class="position-relative">
                    <input class="form-control form-control-solid" type="password"
                        placeholder="{{ __('messages.super_admin.confirm_password') }}" name="password_confirmation"
                        autocomplete="off" aria-label="Password" data-toggle="password">
                    <span
                        class="position-absolute d-flex align-items-center top-0 bottom-0 end-0 me-4 input-icon input-password-hide cursor-pointer text-gray-600">
                        <i class="bi bi-eye-slash-fill"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="class="mb-5">
            <label class="form-label required mb-3" for="designation">Designation:</label>
            <select class="form-control form-control-solid" name="designation" id="designation" required>
                <option value="">Select Designation</option>
                <option value="Chairman" {{ $superAdmin->designation == 'Chairman' ? 'selected' : '' }}>Chairman</option>
                <option value="Director"{{ $superAdmin->designation == 'Director' ? 'selected' : '' }}>Director</option>
                <option value="Deputy Director"{{ $superAdmin->designation == 'Deputy Director' ? 'selected' : '' }}>Deputy Director</option>
                <option value="Assistant Director"{{ $superAdmin->designation == 'Assistant Director' ? 'selected' : '' }}>Assistant Director</option>
                <option value="Circle Officer"{{ $superAdmin->designation == 'Circle Officer' ? 'selected' : '' }}>Circle Officer</option>
                <option value="Inspector"{{ $superAdmin->designation == 'Inspector' ? 'selected' : '' }}>Inspector</option>
                <option value="Sub-inspector"{{ $superAdmin->designation == 'Sub-inspector' ? 'selected' : '' }}>Sub-inspector</option>
                <!-- Add more options as needed -->
            </select>

        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            <label for="zone" class="form-label required mb-3">Zone:</label>
            <select class="form-control form-control-solid" name="zone" id="zone" required onchange="updateDistricts()" >
                <option value="">Select Zone</option>
                <option value="HYDERABAD ZONE" {{ $superAdmin->zone == 'HYDERABAD ZONE' ? 'selected' : '' }}>HYDERABAD ZONE</option>
                <option value="JAMSHORO ZONE" {{ $superAdmin->zone == 'JAMSHORO ZONE' ? 'selected' : '' }}>JAMSHORO ZONE</option>
                <option value="LARKANA ZONE" {{ $superAdmin->zone == 'LARKANA ZONE' ? 'selected' : '' }}>LARKANA ZONE</option>
                <option value="MIRPURKHAS ZONE" {{ $superAdmin->zone == 'MIRPURKHAS ZONE' ? 'selected' : '' }}>MIRPURKHAS ZONE</option>
                <option value="SBA ZONE" {{ $superAdmin->zone == 'SBA ZONE' ? 'selected' : '' }}>SBA ZONE</option>
                <option value="SUKKUR ZONE" {{ $superAdmin->zone == 'SUKKUR ZONE' ? 'selected' : '' }}>SUKKUR ZONE</option>
                <option value="SOUTH ZONE" {{ $superAdmin->zone == 'SOUTH ZONE' ? 'selected' : '' }}>SOUTH ZONE</option>
                <option value="WEST ZONE" {{ $superAdmin->zone == 'WEST ZONE' ? 'selected' : '' }}>WEST ZONE</option>
                <option value="EAST ZONE" {{ $superAdmin->zone == 'EAST ZONE' ? 'selected' : '' }}>EAST ZONE</option>
            </select>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            <label for="district" class="form-label required mb-3">District:</label>
            <select class="form-control form-control-solid" name="district" id="district" required>
                <!-- District options will be populated by JavaScript based on the selected zone -->
            </select>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="class="mb-5">
            <label class="form-label required mb-3" for="grade">Grade:</label>
            <select class="form-control form-control-solid" name="grade" id="grade" required>
                <option value="">Select Grade</option>
                <option value="16" {{$superAdmin->grade=='16' ? 'selected': ''}}>16</option>
                <option value="17" {{$superAdmin->grade=='17' ? 'selected': ''}}>17</option>
                <option value="18" {{$superAdmin->grade=='18' ? 'selected': ''}}>18</option>
                <option value="19" {{$superAdmin->grade=='19' ? 'selected': ''}}>19</option>
                <option value="20" {{$superAdmin->grade=='20' ? 'selected': ''}}>20</option>
                <option value="21" {{$superAdmin->grade=='21' ? 'selected': ''}}>21</option>
                <option value="22" {{$superAdmin->grade=='22' ? 'selected': ''}}>22</option>
                <!-- Add more options as needed -->
            </select>

        </div>
    </div>
    <div class="mb-3" io-image-input="true">
        <label for="exampleInputImage" class="form-label">{{ __('messages.super_admin.profile') }}:</label>
        <div class="d-block">
            <div class="image-picker">
                <div class="image previewImage" id="exampleInputImage"
                    {{ $styleCss }}="background-image: url('{{ !empty($superAdmin->profile_image) ? $superAdmin->profile_image : asset('assets/images/avatar.png') }}')">
                </div>
                <span class="picker-edit rounded-circle text-gray-500 fs-small" data-bs-toggle="tooltip"
                    title="{{ __('messages.user.change_profile') }}">
                    <label>
                        <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                        <input type="file" id="profile_image" name="profile" class="image-upload d-none"
                            accept=".png, .jpg, .jpeg" />
                    </label>
                </span>
            </div>
        </div>
        <div class="form-text">{{ __('messages.user.allowed_file_types') }}</div>
    </div>
</div>
<div class="float-end d-flex">
    {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2']) }}
    <a href="{{ route('super-admins.index') }}" type="reset"
        class="btn btn-secondary btn-active-light-primary">{{ __('messages.common.cancel') }}</a>
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
                if (district === '{{ $superAdmin->district }}') {
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
</script>
