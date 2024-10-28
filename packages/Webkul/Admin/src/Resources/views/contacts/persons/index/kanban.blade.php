{!! view_render_event('admin.contacts.persons.index.kanban.before') !!}

<v-persons-kanban>
    <div class="flex flex-col gap-4">
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
                    <div
                        class="flex min-w-[275px] max-w-[275px] flex-col gap-1 rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900"
                        v-for="status in statusPersons"
                    >
                        <!-- Status Header -->
                        <div class="flex flex-col px-2 py-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium dark:text-white">
                                    @{{ status.name }} (@{{ status.persons.meta.total }})
                                </span>
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
                            <!-- Rest of your existing template -->
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
                    statusPersons: {},
                };
            },

            mounted() {
                this.getPersons();
            },

            methods: {
                getPersons() {
                    this.$axios.get("{{ route('admin.contacts.persons.get') }}")
                        .then(response => {
                            this.statusPersons = response.data;
                            this.isLoading = false;
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },

                // Add other methods similar to leads implementation
            }
        });
    </script>
@endPushOnce
