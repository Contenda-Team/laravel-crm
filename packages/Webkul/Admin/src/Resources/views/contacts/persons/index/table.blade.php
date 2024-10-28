{!! view_render_event('admin.contacts.persons.index.table.before') !!}

<x-admin::datagrid :src="route('admin.contacts.persons.index')">
    <!-- DataGrid Shimmer -->
    <x-admin::shimmer.datagrid />

    <x-slot:toolbar-right-after>
        @include('admin::contacts.persons.index.view-switcher')
    </x-slot>
</x-admin::datagrid>

{!! view_render_event('admin.contacts.persons.index.table.after') !!}