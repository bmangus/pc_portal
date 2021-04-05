<x-table.row>
    <x-table.cell>{{$name}}</x-table.cell>
    <x-table.cell>  Site: {{$site}}<br/>
                    Subject: {{$subject}}<br/>
                    Category: {{$category}}<br/>
                    OCAS Job: {{$ocas}}<br/>
                    Type: {{$type}}
    </x-table.cell>
    <x-table.cell>
        <x-input.select wire:model="rehire" id="rehireStatus" placeholder="Unknown">
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </x-input.select>
    </x-table.cell>
    <x-table.cell>
        <div class="flex rounded-md shadow-sm">
            <textarea wire:model.defer="comments" rows="3" class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">{{$comments}}</textarea>
        </div>
    </x-table.cell>
    <x-table.cell>
        @if($saved)
            <div class="text-green-500">Saved</div>
        @elseif($errored)
            <div class="text-red-500">{{$errorMsg}}</div>
            <x-button.primary wire:click="save">Retry Save</x-button.primary>
        @else
            <x-button.primary wire:click="save">Save</x-button.primary>
        @endif
    </x-table.cell>
</x-table.row>
