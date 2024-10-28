<div class="flex items-center justify-between gap-4 flex-wrap">
    <!-- Left Side -->
    <div class="flex gap-x-1">
        <!-- View Switcher -->
        @include('admin::contacts.persons.index.view-switcher')
    </div>

    <!-- Right Side -->
    <div class="flex items-center gap-x-2">
        <!-- Search -->
        <div class="relative">
            <input 
                type="text"
                class="bg-white border border-gray-300 rounded-md px-3 py-2 w-[280px] text-sm text-gray-600 dark:bg-gray-900 dark:border-gray-800"
                placeholder="@lang('admin::app.contacts.persons.index.search')"
                v-model="searchTerm"
                @keyup.enter="searchPersons"
            >

            <span 
                class="icon-search text-2xl absolute right-2 top-1.5 flex cursor-pointer"
                @click="searchPersons"
            >
            </span>
        </div>

        @if (bouncer()->hasPermission('admin.contacts.persons.create'))
            <a 
                href="{{ route('admin.contacts.persons.create') }}"
                class="primary-button"
            >
                @lang('admin::app.contacts.persons.index.create.title')
            </a>
        @endif
    </div>
</div>