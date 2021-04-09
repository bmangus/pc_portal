<div class="py-4 px-4 space-y-4">


    <x-table>
        <x-slot name="head">
            <x-table.heading>Name</x-table.heading>
            <x-table.heading>Details</x-table.heading>
            <x-table.heading>Rehire Decision</x-table.heading>
            <x-table.heading>Comments</x-table.heading>
        </x-slot>
        <x-slot name="body">
            @foreach($staff as $s)
                <livewire:rehire-table-row
                    :rowId="$s['id']"
                    :name="$s['Name']"
                    :subject="$s['Subject']"
                    :rehire="$s['Rehire']"
                    :comments="$s['Comments']"
                    :location="$s['Location']"
                    :fte="$s['FTE']"
                    :hireDate="$s['HireDate']"
                    :type="$s['Type']"
                    :id="$s['id']"/>
            @endforeach
        </x-slot>
    </x-table>
</div>

