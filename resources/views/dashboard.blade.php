<x-app-layout>
    <div  class="mx-auto mt-8 px-4">
        <div x-data="chartData()" x-init="loadData()" class="lg:flex gap-4 items-stretch px-4">
            <div class="bg-white dark:bg-gray-800 md:p-2 p-6 rounded-lg border border-gray-200 dark:border-gray-900 mb-4 lg:mb-0 shadow-md lg:w-1/2 ">
                <div class="flex justify-center items-center space-x-5 h-full">
                    <div>
                        <canvas id="projectsChart"></canvas>
                        <h2 class="text-2xl font-bold text-gray-600 dark:text-gray-100 text-center">Project API Calls</h2>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 md:p-2 p-6 rounded-lg border border-gray-200 dark:border-gray-900 mb-4 lg:mb-0 shadow-md lg:w-1/2 ">
                <div class="flex justify-center items-center space-x-5 h-full">
                    <div>
                        <canvas id="membersChart"></canvas>
                        <h2 class="text-2xl font-bold text-gray-600 dark:text-gray-100 text-center">Member API Calls</h2>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 md:p-2 p-6 rounded-lg border border-gray-200 dark:border-gray-900 mb-4 lg:mb-0 shadow-md lg:w-1/2 ">
                <div class="flex justify-center items-center space-x-5 h-full">
                    <div>
                        <canvas id="teamsChart"></canvas>
                        <h2 class="text-2xl font-bold text-gray-600 dark:text-gray-100 text-center">Team API Calls</h2>
                    </div>
                </div>
            </div>
        </div>
        <div x-data="recordData()" x-init="init()" class="lg:flex gap-4 items-stretch px-4 mt-12">
            <div class="bg-white dark:bg-gray-800  p-12 rounded-lg border border-gray-200 dark:border-gray-900 mb-4 lg:mb-0 shadow-md lg:w-1/2 ">
                <div class="flex justify-center items-center space-x-5 h-full">
                    <div>
                        <h2 id="projectCount" class="text-4xl font-bold text-gray-200 text-center" x-text="projects"></h2>
                        <h2 class="text-2xl font-bold text-gray-600 dark:text-gray-100 text-center mt-4">Total Projects</h2>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800  p-12 rounded-lg border border-gray-200 dark:border-gray-900 mb-4 lg:mb-0 shadow-md lg:w-1/2 ">
                <div class="flex justify-center items-center space-x-5 h-full">
                    <div>
                        <h2 id="memberCount" class="text-4xl font-bold text-gray-200 text-center" x-text="members"></h2>
                        <h2 class="text-2xl font-bold text-gray-600 dark:text-gray-100 text-center mt-4">Total Members</h2>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800  p-12 rounded-lg border border-gray-200 dark:border-gray-900 mb-4 lg:mb-0 shadow-md lg:w-1/2 ">
                <div class="flex justify-center items-center space-x-5 h-full">
                    <div>
                        <h2 id="memberCount" class="text-4xl font-bold text-gray-200 text-center" x-text="teams"></h2>
                        <h2 class="text-2xl font-bold text-gray-600 dark:text-gray-100 text-center mt-4">Total Teams</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
