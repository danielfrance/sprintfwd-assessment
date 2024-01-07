@if ($messages)
    <div class="flex">
        <div class="w-full bg-green-300 border border-green-400 text-grey-700 px-4 py-3 rounded relative mt-2 mb-2" role="alert">
            @foreach ($messages as $message)
                <p>{{ $message }}</p>
            @endforeach
        </div>
    </div>
@endif
