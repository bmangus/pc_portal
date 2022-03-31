<x-table.row>
    <x-table.cell>{{$name}}</x-table.cell>
    <x-table.cell>  Site: {{$location}}<br/>
                    Position: {{$subject}}<br/>
                    FTE: {{$fte}}<br/>
                    Hire Date: {{$hireDate}}<br/>
    </x-table.cell>
    <x-table.cell>
        @if($type === 'Not Eligible')
            <div class="text-gray-900">N/A</div>
        @elseif($rehireChangeAllowed)
            <x-input.select wire:model="rehire" id="rehireStatus" placeholder="Unknown">
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </x-input.select>
        @else
            <x-input.select wire:model="rehire" id="rehireStatus" placeholder="Unknown" disabled>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </x-input.select>
        @endif
    </x-table.cell>
    <x-table.cell>
        @if($type=== 'Not Eligible')
            <div class="text-gray-900">N/A</div>
        @else
            <div class="flex rounded-md shadow-sm">
                <textarea wire:model.debounce1000ms="comments" rows="3" class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">{{$comments}}</textarea>
            </div>
        @endif
    </x-table.cell>
    <x-table.cell>
        @if($type==='Not Eligible')
            <div class="text-gray-900">Cannot Rehire</div>
        @elseif($saved)
            <div class="text-green-500">Saved</div>
        @elseif($errored)
            <div class="text-red-500">{{$errorMsg}}</div>
            <x-button.primary wire:click="save">Retry Save</x-button.primary>
        @else
            <x-button.primary wire:click="save">Save</x-button.primary>
        @endif
    </x-table.cell>
</x-table.row>
