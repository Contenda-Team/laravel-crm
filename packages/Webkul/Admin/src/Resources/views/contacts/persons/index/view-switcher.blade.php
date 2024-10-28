{!! view_render_event('admin.contacts.persons.index.view_switcher.before') !!}

<div class="flex gap-4">
    <!-- Status Filter -->
    <x-admin::dropdown>
        <x-slot:toggle>
            {!! view_render_event('admin.contacts.persons.index.view_switcher.status.button.before') !!}

            <button
                type="button"
                class="flex cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-[7px] text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
            >
                <span class="whitespace-nowrap">
                    {{ $currentStatus?->name ?? __('admin::app.contacts.persons.index.all-persons') }}
                </span>
                
                <span class="icon-down-arrow text-2xl"></span>
            </button>

            {!! view_render_event('admin.contacts.persons.index.view_switcher.status.button.after') !!}
        </x-slot>

        <x-slot:content class="!p-0">
            {!! view_render_event('admin.contacts.persons.index.view_switcher.status.content.header.before') !!}

            <!-- Header -->
            <div class="flex items-center justify-between px-3 py-2.5">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-300">
                    @lang('admin::app.contacts.persons.index.all-statuses')
                </span>
            </div>

            {!! view_render_event('admin.contacts.persons.index.view_switcher.status.content.header.after') !!}

            <div class="grid gap-1 pb-2">
                <!-- All Persons -->
                <a
                    href="{{ route('admin.contacts.persons.index', ['view_type' => request('view_type')]) }}"
                    class="px-4 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 {{ ! request('status') ? 'bg-gray-100 dark:bg-gray-800' : '' }}"
                >
                    @lang('admin::app.contacts.persons.index.all-persons')
                </a>

                @foreach ($statuses as $status)
                    <a
                        href="{{ route('admin.contacts.persons.index', [
                            'status'    => $status->id,
                            'view_type' => request('view_type')
                        ]) }}"
                        class="px-4 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 {{ request('status') == $status->id ? 'bg-gray-100 dark:bg-gray-800' : '' }}"
                    >
                        {{ $status->name }}
                    </a>
                @endforeach
            </div>
        </x-slot>
    </x-admin::dropdown>

    <!-- View Type Switcher -->
    <div class="flex items-center gap-0.5">
        @if (request('view_type') === 'table')
            <a
                class="flex"
                href="{{ route('admin.contacts.persons.index', array_merge(request()->except('view_type'), [])) }}"
            >
                <span class="icon-kanban p-2 text-2xl"></span>
            </a>

            <span class="icon-list rounded-md bg-gray-100 p-2 text-2xl dark:bg-gray-950"></span>
        @else
            <span class="icon-kanban rounded-md bg-white p-2 text-2xl dark:bg-gray-900"></span>

            <a
                class="flex"
                href="{{ route('admin.contacts.persons.index', array_merge(request()->except('view_type'), ['view_type' => 'table'])) }}"
            >
                <span class="icon-list p-2 text-2xl"></span>
            </a>
        @endif
    </div>
</div>

{!! view_render_event('admin.contacts.persons.index.view_switcher.after') !!}
