<div class="w-full m-3 py-4 px-4 space-y-4">
    <div>
        <nav class="sm:hidden" aria-label="Back">
            <a href="#" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                <!-- Heroicon name: solid/chevron-left -->
                <svg class="flex-shrink-0 -ml-1 mr-1 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Back
            </a>
        </nav>
        <nav class="hidden sm:flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <div>
                        <a href="/staff/workflow" class="text-sm font-medium text-gray-500 hover:text-gray-700">Portal</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <!-- Heroicon name: solid/chevron-right -->
                        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <a href="#" aria-current="page" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Staff Rehires</a>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    <div class="mt-2 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Staff Rehires
            </h2>
        </div>
        <div class="mt-4 flex-shrink-0 flex md:mt-0 md:ml-4">
            <livewire:rehire-counter :sites="$sites"/>
            <button type="button" onclick="window.location.href='/staff/workflow'" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Back to Portal
            </button>
        </div>
    </div>
    @foreach($staff as $key => $s)
            @switch($key)
            @case('Goes to Regular Contract if Rehired')
                @php($class = 'bg-blue-600')
                @break
            @case('Regular Contract Continuing')
                @php($class = 'bg-green-600')
                @break
            @case('Not Eligible')
                @php($class = 'bg-red-600')
                @break
            @case('Remains Duration of Need if Rehired')
                @php($class = 'bg-indigo-600')
                @break
            @case('Support')
                @php($class = 'bg-yellow-600')
                @break
            @case('Emergency Certified')
                @php($class = 'bg-purple-600')
                @break
            @default
                @php($class = 'bg-gray-600')
        @endswitch

        <div class="{{$class}} text-lg p-4 mx-4 text-center text-white leading-6">{{(strlen($key) === 0)?"No Type Provided":$key}}</div>
        <livewire:rehire-table
            :staff="$s"
        />
    @endforeach
</div>
