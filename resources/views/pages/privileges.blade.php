@extends('boilerplate')

@section('title', 'Manage Privileges')

@section('main-content')
    <div class="p-4 ">
        <nav class="relative bg-white border-gray-200">
            <div class="w-full flex items-center justify-between mx-auto px-4">
                <div>
                    <h3 class="font-semibold text-2xl">Manage Privileges</h3>
                </div>
            </div>
        </nav>

        <div class="mt-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('menus.update') }}" method="POST" class="bg-white p-6 rounded-lg shadow">
                @csrf
                <input type="hidden" name="user_id" value="{{ request()->route('user_id') }}">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-2 px-4 border-b text-left">Menu Name</th>
                            <th class="py-2 px-4 border-b text-center">Can View</th>
                            <th class="py-2 px-4 border-b text-center">Can Add</th>
                            <th class="py-2 px-4 border-b text-center">Can Edit</th>
                            <th class="py-2 px-4 border-b text-center">Can Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($menus as $menu)
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-4 border-b">
                                    {{-- <input type="checkbox" name="menu[{{ $menu->menu_id }}][selected]" value="1"
                                        {{ $menu->permissions ? 'checked' : '' }}> --}}
                                    {{ $menu->menu_name }}
                                </td>
                                <td class="py-2 px-4 border-b text-center">
                                    @if ($menu->can_view == 1)
                                        <input type="checkbox" name="menu[{{ $menu->menu_id }}][can_view]" value="1"
                                            {{ $menu->permissions && $menu->permissions->can_view ? 'checked' : '' }}>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b text-center">
                                    @if ($menu->can_add == 1)
                                        <input type="checkbox" name="menu[{{ $menu->menu_id }}][can_add]" value="1"
                                            {{ $menu->permissions && $menu->permissions->can_add ? 'checked' : '' }}>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b text-center">
                                    @if ($menu->can_edit == 1)
                                        <input type="checkbox" name="menu[{{ $menu->menu_id }}][can_edit]" value="1"
                                            {{ $menu->permissions && $menu->permissions->can_edit ? 'checked' : '' }}>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b text-center">
                                    @if ($menu->can_delete == 1)
                                        <input type="checkbox" name="menu[{{ $menu->menu_id }}][can_delete]" value="1"
                                            {{ $menu->permissions && $menu->permissions->can_delete ? 'checked' : '' }}>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Save Changes
                </button>
            </form>
        </div>
    </div>
@endsection
