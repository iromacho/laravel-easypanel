@php use EasyPanel\Models\CRUD; @endphp
<li class="list-divider"></li>
<li class="nav-small-cap"><span class="hide-menu">{{ __('CRUD Menu') }}</span></li>
@foreach(CRUD::active() as $crud)
    <x-easypanel::crud-menu-item :crud="$crud"/>
@endforeach
