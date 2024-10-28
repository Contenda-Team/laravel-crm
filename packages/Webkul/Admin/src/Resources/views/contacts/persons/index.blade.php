<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.contacts.persons.index.title')
    </x-slot>

    {!! view_render_event('admin.contacts.persons.index.header.before') !!}

    <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
        {!! view_render_event('admin.contacts.persons.index.header.left.before') !!}

        <div class="flex flex-col gap-2">
            <div class="flex cursor-pointer items-center">
                <!-- Breadcrumbs -->
                <x-admin::breadcrumbs name="contacts.persons" />
            </div>

            <div class="text-xl font-bold dark:text-white">
                @lang('admin::app.contacts.persons.index.title')
            </div>
        </div>

        {!! view_render_event('admin.contacts.persons.index.header.left.after') !!}

        {!! view_render_event('admin.contacts.persons.index.header.right.before') !!}

        <div class="flex items-center gap-x-2.5">
            <!-- Create button for Persons -->
            <div class="flex items-center gap-x-2.5">
                @if (bouncer()->hasPermission('admin.contacts.persons.create'))
                    <a
                        href="{{ route('admin.contacts.persons.create') }}"
                        class="primary-button"
                    >
                        @lang('admin::app.contacts.persons.index.create-btn')
                    </a>
                @endif
            </div>
        </div>

        {!! view_render_event('admin.contacts.persons.index.header.right.after') !!}
    </div>

    {!! view_render_event('admin.contacts.persons.index.header.after') !!}

    {!! view_render_event('admin.contacts.persons.index.content.before') !!}

    <!-- Content -->
    <div class="mt-3.5">
        @if ((request()->view_type ?? "kanban") == "table")
            @include('admin::contacts.persons.index.table')
        @else
            @include('admin::contacts.persons.index.kanban')
        @endif
    </div>

    {!! view_render_event('admin.contacts.persons.index.content.after') !!}
</x-admin::layouts>
