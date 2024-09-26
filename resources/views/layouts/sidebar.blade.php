<div class="aside-menu-container" id="sidebar">
    <div class="aside-menu-container__aside-logo flex-column-auto">
        @php
            $settingValue = getSuperAdminSettingValue();
        @endphp
        @role('super_admin')
            <a data-turbo="false" href="" target="_blank"
                class="text-decoration-none sidebar-logo d-flex align-items-center" data-bs-toggle="tooltip"
                title="ACE Sindh">
                <div class="image image-mini me-3">
                    <img src="{{asset('assets/images/download.png')}}" class="img-fluid object-contain"
                        alt="profile image">
                </div>
                <span
                    class="text-gray-900 fs-4">ACE Sindh</span>
            </a>
        @endrole
        @role('admin|client')
            <a data-turbo="false" href="" target="_blank"
                class="text-decoration-none sidebar-logo d-flex align-items-center" data-bs-toggle="tooltip"
                title="ACE Sindh">
                <div class="image image-mini me-3">
                    {{-- {{ asset(getLogoUrl()) }} --}}
                    <img src="{{asset('assets/images/download.png')}}" class="img-fluid object-contain" alt="profile image">
                </div>
                <span
                    class="text-gray-900 fs-4">ACE Sindh</span>
            </a>
        @endrole
        <button type="button" class="btn px-0 aside-menu-container__aside-menubar d-lg-block d-none sidebar-btn">
            <i class="fa-solid fa-bars fs-1"></i>
        </button>
    </div>
    <div class="aside-menu-container__sidebar-scrolling overflow-auto">
        <ul class="aside-menu-container__aside-menu nav flex-column">
            @include('layouts.menu')
            <div class="no-record text-center d-none">No matching records found</div>
        </ul>
    </div>
</div>
<div class="bg-overlay" id="sidebar-overly"></div>
