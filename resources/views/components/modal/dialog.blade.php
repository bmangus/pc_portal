@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="max-h-screen overflow-x-hidden overflow-y-auto">
        <div class="px-6 py-4">
            <div class="text-lg">
                {{ $title }}
            </div>

            <div class="mt-4">
                {{ $content }}
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-100 text-right">
            {{ $footer }}
        </div>
    </div>
</x-modal>
