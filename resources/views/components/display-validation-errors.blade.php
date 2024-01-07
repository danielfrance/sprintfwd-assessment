@if ($errors->any())
    <div class="flex">
        <div class="w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-2 mb-2"
            role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif