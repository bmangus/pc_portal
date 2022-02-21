<main class="flex-1 relative overflow-y-auto focus:outline-none">
    <div class="p-4">

        <div class="py-4 space-y-4">
            <!-- Top Bar -->
            <div class="flex justify-between">
                <div class="w-2/4 flex space-x-4">
                    <x-input.text wire:model="filters.search" placeholder="Search Issues..." />

                    <x-button.link wire:click="toggleShowFilters">@if ($showFilters) Hide @endif Advanced Search...</x-button.link>
                </div>

                <div class="space-x-2 flex items-center">
                    <x-input.group borderless paddingless for="perPage" label="Per Page">
                        <x-input.select wire:model="perPage" id="perPage">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </x-input.select>
                    </x-input.group>

                    <x-dropdown label="Create" class="z-1000" style="z-index: 1000;">
                        <x-dropdown.item type="button" wire:click="showNewFac" class="flex items-center space-x-2 z-1000">
                            <x-icon.plus class="text-cool-gray-400"/><span>Facility</span>
                        </x-dropdown.item>
                        <x-dropdown.item type="button" wire:click="showNewTech" class="flex items-center space-x-2 z-1000">
                            <x-icon.plus class="text-cool-gray-400"/> <span>Technology</span>
                        </x-dropdown.item>
                    </x-dropdown>
                </div>
            </div>

            <!-- Advanced Search -->
            <div>
                @if ($showFilters)
                    <div class="bg-white p-4 rounded shadow flex relative">
                        <div class="w-1/2 pr-2 space-y-4">
                            <x-input.group inline for="filter-system" label="Type">
                                <x-input.select wire:model="filters.type" id="filter-system">
                                    <option value="" disabled>Select Type...</option>
                                    <option value="technology">Technology</option>
                                    <option value="facilities">Facilities</option>
                                    <option value="">Both</option>
                                </x-input.select>
                            </x-input.group>
                            <x-input.group inline for="filter-status" label="Status">
                                <x-input.select wire:model="filters.status" id="filter-status">
                                    <option value="" disabled selected>Select Status...</option>

                                    @foreach (App\WorkOrders::STATUSES as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </x-input.select>
                            </x-input.group>

                            <x-input.group inline for="filter-amount-min" label="Work Order Number">
                                <x-input.text wire:model="filters.wo-number" id="filter-wo-number" />
                            </x-input.group>

                            <x-input.group inline for="filter-fixed-asset" label="Fixed Asset Number">
                                <x-input.text wire:model="filters.fixed-asset" id="filter-fixed-asset" />
                            </x-input.group>
                        </div>

                        <div class="w-1/2 pl-2 space-y-4">
                            <x-input.group inline for="filter-room-number" label="Room Number">
                                <x-input.text wire:model.lazy="filters.room-number" id="filter-room-number"/>
                            </x-input.group>
                            <x-input.group inline for="filter-date-min" label="Min Date">
                                <x-input.date wire:model="filters.date-min" id="filter-date-min" placeholder="MM/DD/YYYY" />
                            </x-input.group>

                            <x-input.group inline for="filter-date-max" label="Maximum Date">
                                <x-input.date wire:model="filters.date-max" id="filter-date-max" placeholder="MM/DD/YYYY" />
                            </x-input.group>

                            <x-button.link wire:click="resetFilters" class="absolute right-0 bottom-0 p-4">Reset Filters</x-button.link>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Transactions Table -->
            <div class="flex-col space-y-4">
                <x-table>
                    <x-slot name="head">
                        <x-table.heading class="pr-0 w-8">
                            <x-input.checkbox wire:model="selectPage" />
                        </x-table.heading>
                        <x-table.heading sortable multi-column wire:click="sortBy('OrderNo')" :direction="$sorts['OrderNo'] ?? null">WO#</x-table.heading>
                        <x-table.heading sortable multi-column wire:click="sortBy('Problem')" :direction="$sorts['Problem'] ?? null">Issue</x-table.heading>
                        <x-table.heading sortable multi-column wire:click="sortBy('SubmitDate')" :direction="$sorts['SubmitDate'] ?? null">Created At</x-table.heading>
                        <x-table.heading sortable multi-column wire:click="sortBy('Status')" :direction="$sorts['Status'] ?? null">Status</x-table.heading>
                        <x-table.heading />
                    </x-slot>

                    <x-slot name="body">
                        @if ($selectPage)
                            <x-table.row class="bg-cool-gray-200" wire:key="row-message">
                                <x-table.cell colspan="6">
                                    @unless ($selectAll)
                                        <div>
                                            <span>You have selected <strong>{{ $transactions->count() }}</strong> transactions, do you want to select all <strong>{{ $transactions->total() }}</strong>?</span>
                                            <x-button.link wire:click="selectAll" class="ml-1 text-blue-600">Select All</x-button.link>
                                        </div>
                                    @else
                                        <span>You are currently selecting all <strong>{{ $transactions->total() }}</strong> transactions.</span>
                                    @endif
                                </x-table.cell>
                            </x-table.row>
                        @endif

                        @forelse ($transactions as $transaction)
                            <x-table.row wire:loading.class.delay="opacity-50" wire:key="row-{{ $transaction->id }}">
                                <x-table.cell class="pr-0">
                                    <x-input.checkbox wire:model="selected" value="{{ $transaction->id }}" />
                                </x-table.cell>

                                <x-table.cell>
                                <p class="text-cool-gray-600 truncate">
                                    {{ $transaction->OrderNo }}
                                </p>
                                </x-table.cell>

                                <x-table.cell>
                                    <span class="text-cool-gray-900 font-medium">{{ $transaction->Problem }} </span>
                                </x-table.cell>

                                <x-table.cell>
                            <span class="inline-flex items-center px-2.5 py-0.5">
                                {{ $transaction->SubmitDate }}
                            </span>
                                </x-table.cell>

                                <x-table.cell>
                                    {{ $transaction->Status }}
                                </x-table.cell>

                                <x-table.cell>
                                    <x-button.link wire:click="edit({{ $transaction->id }})">Edit</x-button.link>
                                </x-table.cell>
                            </x-table.row>
                        @empty
                            <x-table.row>
                                <x-table.cell colspan="6">
                                    <div class="flex justify-center items-center space-x-2">
                                        <x-icon.inbox class="h-8 w-8 text-cool-gray-400" />
                                        <span class="font-medium py-8 text-cool-gray-400 text-xl">No Work Orders found...</span>
                                    </div>
                                </x-table.cell>
                            </x-table.row>
                        @endforelse
                    </x-slot>
                </x-table>

                <div>
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>

        <!-- Delete Transactions Modal -->
        <form wire:submit.prevent="deleteSelected">
            <x-modal.confirmation wire:model.defer="showDeleteModal">
                <x-slot name="title">Delete Transaction</x-slot>

                <x-slot name="content">
                    <div class="py-8 text-cool-gray-700">Are you sure you? This action is irreversible.</div>
                </x-slot>

                <x-slot name="footer">
                    <x-button.secondary wire:click="$set('showDeleteModal', false)">Cancel</x-button.secondary>

                    <x-button.primary type="submit">Delete</x-button.primary>
                </x-slot>
            </x-modal.confirmation>
        </form>

        <!-- Save Transaction Modal -->
        <form wire:submit.prevent="save">
            <x-modal.dialog wire:model.defer="showEditModal">
                <x-slot name="title">Edit Work Order</x-slot>

                <x-slot name="content">
                    <x-input.group for="Problem" label="Problem" :error="$errors->first('editing.Problem')">
                        <x-input.textarea wire:model="editing.Problem" id="Problem" placeholder="Problem" />
                    </x-input.group>

                    <x-input.group for="amount" label="Amount" :error="$errors->first('editing.amount')">
                        <x-input.money wire:model="editing.amount" id="amount" />
                    </x-input.group>

                    <x-input.group for="status" label="Status" :error="$errors->first('editing.status')">
                        <x-input.select wire:model="editing.status" id="status">
                            @foreach (App\WorkOrders::STATUSES as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </x-input.select>
                    </x-input.group>

                    <x-input.group for="date_for_editing" label="Date" :error="$errors->first('editing.date_for_editing')">
                        <x-input.date wire:model="editing.date_for_editing" id="date_for_editing" />
                    </x-input.group>
                </x-slot>

                <x-slot name="footer">
                    <x-button.secondary wire:click="$set('showEditModal', false)">Cancel</x-button.secondary>

                    <x-button.primary type="submit">Save</x-button.primary>
                </x-slot>
            </x-modal.dialog>
        </form>


        <!-- New Tech Work Order Modal -->
        <form wire:submit.prevent="saveNewTechnology">
            <x-modal.dialog wire:model.defer="showNewTechModal">
                <x-slot name="title">New Technology Work Order</x-slot>

                <x-slot name="content">
                    TEST
                </x-slot>

                <x-slot name="footer">

                </x-slot>
            </x-modal.dialog>
        </form>

        <!-- New Fac Work Order Modal -->
        <form wire:submit.prevent="saveNewFacility">
            <x-modal.dialog wire:model.defer="showNewFacModal">
                <x-slot name="title">New Facility Work Order</x-slot>

                <x-slot name="content">
                    TEST
                </x-slot>

                <x-slot name="footer">

                </x-slot>
            </x-modal.dialog>
        </form>
    </div>
</main>
