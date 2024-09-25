@if ($row->roles->first()->name === \App\Models\Role::ROLE_ADMIN)
    <span class="badge bg-light-success fs-7">{{ $row->roles->first()->display_name }}</span>
@elseif ($row->roles->first()->name === \App\Models\Role::ROLE_CLIENT)
    <span class="badge bg-light-primary fs-7">{{ $row->roles->first()->display_name }}</span>
@endif
