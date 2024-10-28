<x-admin::shimmer.person.view.kanban.toolbar />

<div class="flex gap-2.5 overflow-x-auto">
    <!-- Statuses -->
    @for ($i = 1; $i <= 4; $i++)
        <div class="flex min-w-[275px] max-w-[275px] flex-col gap-1 rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <!-- Status Header -->
            <div class="flex flex-col px-2 py-3">
                <div class="flex items-center justify-between">
                    <div class="shimmer h-4 w-20"></div>
                    <div class="shimmer h-[26px] w-[26px] rounded-md"></div>
                </div>

                <div class="flex items-center justify-between gap-2">
                    <div class="shimmer h-4 w-14"></div>
                    <div class="shimmer h-1 w-36"></div>
                </div>
            </div>

            <!-- Status Person Cards -->
            <div class="flex h-[calc(100vh-317px)] flex-col gap-2 overflow-y-auto p-2">
                @for ($j = 1; $j <= 3; $j++)
                    <!-- Card -->
                    <div class="flex w-full flex-col gap-5 rounded-md border border-gray-100 p-2 dark:border-gray-800">
                        <!-- Header -->
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-1">
                                <!-- Avatar -->
                                <div class="shimmer h-9 w-9 rounded-full"></div>

                                <!-- Name and Organization -->
                                <div class="flex flex-col gap-0.5">
                                    <div class="shimmer h-4 w-20"></div>
                                    <div class="shimmer h-[15px] w-12"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="flex flex-wrap gap-1">
                            <div class="shimmer h-6 w-24 rounded-xl"></div>
                            <div class="shimmer h-6 w-24 rounded-xl"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    @endfor
</div>
