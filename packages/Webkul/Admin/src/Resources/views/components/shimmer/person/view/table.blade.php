<div class="flex flex-col gap-4">
    <!-- Toolbar Shimmer -->
    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <!-- Left Toolbar -->
        <div class="flex gap-x-1">
            <div class="shimmer w-[100px] h-[39px]"></div>
        </div>

        <!-- Right Toolbar -->
        <div class="flex gap-x-1">
            <div class="shimmer w-[100px] h-[39px]"></div>
            <div class="shimmer w-[100px] h-[39px]"></div>
        </div>
    </div>

    <!-- Datagrid Shimmer -->
    <div class="grid">
        <!-- Datagrid Head -->
        <div class="grid grid-cols-8 gap-2.5 border-b border-gray-300 dark:border-gray-800 px-4 py-2.5">
            @foreach (range(1, 8) as $i)
                <div class="shimmer w-[100px] h-[21px]"></div>
            @endforeach
        </div>

        <!-- Datagrid Body -->
        @foreach (range(1, 10) as $i)
            <div class="grid grid-cols-8 gap-2.5 px-4 py-4 border-b border-gray-300 dark:border-gray-800">
                @foreach (range(1, 8) as $j)
                    <div class="shimmer w-[100px] h-[21px]"></div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>