{!! view_render_event('admin.contacts.persons.index.kanban.before') !!}

<!-- Kanban Vue Component -->
<v-persons-kanban>
    <div class="flex flex-col gap-4">
        <!-- Shimmer -->
        <x-admin::shimmer.person.view.kanban />
    </div>
</v-persons-kanban>

{!! view_render_event('admin.contacts.persons.index.kanban.after') !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-persons-kanban-template">
        <template v-if="isLoading">
            <div class="flex flex-col gap-4">
                <x-admin::shimmer.person.view.kanban />
            </div>
        </template>

        <template v-else>
            <div class="flex flex-col gap-4">
                @include('admin::contacts.persons.index.toolbar')

                <div class="flex gap-2.5 overflow-x-auto">
                    <!-- Status Cards -->
                    <div
                        v-for="status in statuses"
                        :key="status.id"
                        class="flex min-w-[275px] max-w-[275px] flex-col gap-1 rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900"
                    >
                        <!-- Status Header -->
                        <div class="flex flex-col px-2 py-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium dark:text-white">
                                    @{{ status.name }} (@{{ status.persons.meta.total }})
                                </span>

                                @if (bouncer()->hasPermission('admin.contacts.persons.create'))
                                    <a
                                        :href="'{{ route('admin.contacts.persons.create') }}' + '?status_id=' + status.id"
                                        class="icon-add cursor-pointer rounded p-1 text-lg text-gray-600 transition-all hover:bg-gray-200 hover:text-gray-800 dark:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-white"
                                    >
                                    </a>
                                @endif
                            </div>

                            <div class="flex items-center justify-between gap-2">
                                <span class="text-xs font-medium dark:text-white">
                                    @{{ status.persons.meta.total }} @lang('admin::app.contacts.persons.index.persons')
                                </span>

                                <div class="h-1 w-36 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-800">
                                    <div
                                        class="h-1 bg-blue-500"
                                        :style="{ width: getProgressWidth(status) + '%' }"
                                    ></div>
                                </div>
                            </div>
                        </div>

                        <!-- Draggable Person Cards -->
                        <draggable
                            class="flex h-[calc(100vh-317px)] flex-col gap-2 overflow-y-auto p-2"
                            :class="{ 'justify-center': !status.persons.data.length }"
                            ghost-class="draggable-ghost"
                            handle=".person-item"
                            v-bind="{animation: 200}"
                            :list="status.persons.data"
                            item-key="id"
                            group="persons"
                            @scroll="handleScroll(status, $event)"
                            @change="updatePersonStatus($event, status.id)"
                        >
                            <!-- Empty State -->
                            <template #header>
                                <div 
                                    class="flex flex-col items-center justify-center"
                                    v-if="!status.persons.data.length"
                                >
                                    <img
                                        class="dark:mix-blend-exclusion dark:invert"
                                        src="{{ asset('images/empty-placeholders/contacts.svg') }}"
                                    >

                                    <div class="flex flex-col items-center gap-4">
                                        <div class="flex flex-col items-center gap-2">
                                            <p class="text-xl font-semibold dark:text-white">
                                                @lang('admin::app.contacts.persons.index.empty-title')
                                            </p>

                                            <p class="text-base text-gray-500 dark:text-gray-300">
                                                @lang('admin::app.contacts.persons.index.empty-description')
                                            </p>
                                        </div>

                                        @if (bouncer()->hasPermission('admin.contacts.persons.create'))
                                            <a
                                                :href="'{{ route('admin.contacts.persons.create') }}' + '?status_id=' + status.id"
                                                class="secondary-button"
                                            >
                                                @lang('admin::app.contacts.persons.index.create-btn')
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </template>

                            <!-- Person Card Template -->
                            <template #item="{ element }">
                                <a
                                    class="person-item flex cursor-pointer flex-col gap-2.5 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900"
                                    :href="'{{ route('admin.contacts.persons.view', 'replaceId') }}'.replace('replaceId', element.id)"
                                >
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2.5">
                                            <div class="avatar-round">
                                                @{{ element.name.charAt(0).toUpperCase() }}
                                            </div>

                                            <div class="flex flex-col gap-1">
                                                <p class="text-base text-gray-800 dark:text-white">
                                                    @{{ element.name }}
                                                </p>

                                                <p class="text-gray-600 dark:text-gray-300">
                                                    @{{ element.email }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-1">
                                        <div
                                            class="flex items-center gap-1 rounded-xl bg-gray-200 px-2 py-1 text-xs font-medium dark:bg-gray-800 dark:text-white"
                                            v-if="element.organization"
                                        >
                                            @{{ element.organization.name }}
                                        </div>

                                        <template v-for="tag in element.tags">
                                            <div
                                                class="rounded-xl px-2 py-1 text-xs font-medium"
                                                :style="{
                                                    backgroundColor: tag.color,
                                                    color: tagTextColor[tag.color]
                                                }"
                                            >
                                                @{{ tag.name }}
                                            </div>
                                        </template>
                                    </div>
                                </a>
                            </template>
                        </draggable>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-persons-kanban', {
            template: '#v-persons-kanban-template',

            data() {
                return {
                    isLoading: true,
                    statuses: {},
                    tagTextColor: {
                        '#FEE2E2': '#DC2626',
                        '#FFEDD5': '#EA580C',
                        '#FEF3C7': '#D97706',
                        '#FEF9C3': '#CA8A04',
                        '#ECFCCB': '#65A30D',
                        '#DCFCE7': '#16A34A',
                    },
                };
            },

            mounted() {
                this.getPersons();
            },

            methods: {
                getPersons() {
                    this.$axios.get("{{ route('admin.contacts.persons.get') }}")
                        .then(response => {
                            this.statuses = response.data;
                            this.isLoading = false;
                        })
                        .catch(error => {
                            this.isLoading = false;
                            console.log('Error fetching persons:', error);
                        });
                },

                getProgressWidth(status) {
                    if (!status.persons.meta.total) return 0;
                    const total = Object.values(this.statuses).reduce((sum, s) => 
                        sum + (s.persons.meta.total || 0), 0);
                    return total ? (status.persons.meta.total / total) * 100 : 0;
                },

                updatePersonStatus(event, statusId) {
                    if (!event.added) return;

                    this.$axios.put(
                        "{{ route('admin.contacts.persons.status.update', '_id_') }}"
                            .replace('_id_', event.added.element.id),
                        { status_id: statusId }
                    )
                    .then(response => {
                        this.$emitter.emit('add-flash', { 
                            type: 'success', 
                            message: response.data.message 
                        });
                    })
                    .catch(error => {
                        this.$emitter.emit('add-flash', { 
                            type: 'error', 
                            message: error.response.data.message 
                        });
                    });
                },

                handleScroll(status, event) {
                    const bottom = event.target.scrollHeight - event.target.scrollTop === event.target.clientHeight;

                    if (!bottom) return;

                    if (status.persons.meta.current_page >= status.persons.meta.last_page) return;

                    this.$axios.get("{{ route('admin.contacts.persons.get') }}", {
                        params: {
                            status_id: status.id,
                            page: status.persons.meta.current_page + 1,
                            limit: 10,
                        }
                    })
                    .then(response => {
                        const updatedStatus = response.data[status.id];
                        status.persons.data = status.persons.data.concat(updatedStatus.persons.data);
                        status.persons.meta = updatedStatus.persons.meta;
                    });
                },
            }
        });
    </script>
@endPushOnce
