<x-app-layout>
    <div class="mx-auto mt-8 px-4">
        <div class="flex items-center justify-between">
            <h3 class="mb-4 mt-4 text-4xl text-gray-500 dark:text-gray-200 font-extrabold">Teams</h3>

            <div>
                <x-primary-button x-data="" x-on:click="$dispatch('open-modal','new-team-modal')">
                    {{ __('New Team') }}
                </x-primary-button>

            </div>
        </div>
        <div>
            @if ($errors->any())
                <x-display-validation-errors :errors="$errors" />
            @endif
            @if (session('successMessage'))
                <x-display-validation-success :messages="[session('successMessage')]" />
            @endif
        </div>
        
        <div class="w-full">
             <div class="relative">
                <input
                    class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg cursor-pointer  focus:outline-none mb-4"
                    name="search" type="text" placeholder="Search Teams" />
            </div>
            <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            
                <div class="block w-full overflow-x-auto">
                    <table class="items-center bg-transparent w-full border-collapse ">
                        <thead>
                            <tr class="bg-indigo-800 dark:bg-indigo-700">
                                <th
                                    class="px-6 align-middle py-3 text-lg uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center dark:text-gray-200">
                                    Team name
                                </th>
                                <th
                                    class="px-6  align-middle   py-3 text-lg uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center dark:text-gray-200">
                                    Description
                                </th>
                                <th
                                    class="px-6  align-middle   py-3 text-lg uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center dark:text-gray-200">
                                    # of Members
                                </th>
                                <th
                                    class="px-6  align-middle   py-3 text-lg uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center dark:text-gray-200">
                                    Edit
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($teams as $team)
                                <tr>
                                    <td  class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-lg whitespace-nowrap p-4 text-center  ">
                                        {{ $team->name }}
                                    </td>
                                    <td  class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-lg whitespace-nowrap p-4 text-center  ">
                                        {{ $team->description }}
                                    </td>
                                    <td  class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-lg whitespace-nowrap p-4 text-center  ">
                                        {{ $team->members->count() }}
                                    </td>
                                    <td  class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-lg whitespace-nowrap p-4 text-center  ">
                                        <a href="{{route('teams.show',['team' => $team->id])}}">
                                            <x-primary-button  >
                                            {{ __('Edit') }}
                                        </x-primary-button>
                                        </a>
                                    </td>
                                </tr>
                                
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>


    <x-modal name="new-team-modal" focusable class="bg-gray-500">
        <form method="post" action="{{ route('teams.store') }}" enctype="multipart/form-data" class="p-6">
            @csrf

            <h3 class="mb-4 mt-4 text-3xl text-gray-500 font-extrabold dark:text-gray-200">Create Team</h3>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2 text-lg font-medium text-gray-900 dark:text-gray-200" for="name">Team Name</label>
                    <input
                        class="block w-full text-lg text-gray-900  border border-gray-300 rounded-lg cursor-pointer  focus:outline-none "
                        name="name" type="text">
                </div>
                <div>
                    <label class="block mb-2 text-lg font-medium text-gray-900 dark:text-gray-200" for="description">Team Description</label>
                    <textarea
                        class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg cursor-pointer focus:outline-none "
                        name="description">
                    </textarea>
                </div>
                
                
            </div>

            <div class="mt-6 flex justify-end" data-id="2">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button class="ml-3 import-submit-button">
                    {{ __('Create Team') }}
                </x-primary-button>
                <span
                    class="loading-spinner ml-3 px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-lg text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 hidden">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </span>
            </div>
        </form>
    </x-modal>

</x-app-layout>
