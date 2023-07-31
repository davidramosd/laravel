<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
    <div class="px-6 py-4 space-y-2 text-center flex justify-center  w-full m-2">
        <form action="{{ route('users.index') }}" method="GET">

            <div class="flex flex-wrap items-center justify-center gap-4">
                <div  class="flex flex-col block">
                    <label for="id" class="w-full sm:w-auto">{{ __('Search id') }}</label>
                    <input type="number" name="id" value="{{ request('id') }}" class="w-full sm:w-auto border border-gray-300 rounded-md px-2 py-1">
                    <x-input-error class="mt-2" :messages="$errors->get('id')" />    
                </div>
                <div  class="flex flex-col block">
                    <label for="name" class="w-full sm:w-auto">{{ __('Search name') }}</label>
                    <input type="text" name="name" value="{{ request('name') }}" class="w-full sm:w-auto border border-gray-300 rounded-md px-2 py-1">
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />    
                </div>
                
                <div  class="flex flex-col block">
                    <label for="lastname" class="w-full sm:w-auto">{{ __('Search lastname') }}</label>
                    <input type="text" name="lastname" value="{{ request('lastname') }}" class="w-full sm:w-auto border border-gray-300 rounded-md px-2 py-1">
                    <x-input-error class="mt-2" :messages="$errors->get('lastname')" />
                </div>
                
                <div  class="flex flex-col block">
                    <span class="w-full sm:w-auto">{{ __('Department') }}</span>
                    <select name="department_id" id="department_id" class="w-full sm:w-auto border border-gray-300 rounded-md px-2 py-1">
                        <option value="none" selected disabled hidden>Select</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}"
                                {{ old('department_id') == $department->id ? 'selected="selected"' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            
                {{-- <label for="first_date" class="w-full sm:w-auto">{{ __('First Date') }}</label>
                <input type="date" name="first_date" value="{{ request('first_date') }}" class="w-full sm:w-auto border border-gray-300 rounded-md px-2 py-1">
            
                <label for="last_date" class="w-full sm:w-auto">{{ __('Last date') }}</label>
                <input type="date" name="last_date" value="{{ request('last_date') }}" class="w-full sm:w-auto border border-gray-300 rounded-md px-2 py-1"> --}}

                <!-- Botones de búsqueda y limpieza (opcional) -->
                <div class="w-full sm:w-auto flex gap-2 m-2">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">{{ __('Search') }}</button>
                    <a href="{{ route('users.clearFilter') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md">{{ __('Clear Fields') }}</a>
                </div>
            
            </div>
            





{{--             <label for="name">Search name</label>
            <input type="text" name="name" value="{{ request('name') }}" >
    
            <label for="lastname">Search lastname:</label>
            <input type="text" name="lastname" value="{{ request('lastname') }}">

            <span class=""> {{__('Department')}} </span>
                <select name="department_id" id="department_id">
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            <label class="flex justify-center w-4/12">
                
            </label>
            <div class="flex justify-center w-full mt-5">
                <button type="submit" class="text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    Search
                </button>
                <a href="{{ route('users.clearFilter') }}" class="py-2.5 px-5 mr-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-full border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    Clear Fields
                </a>
            </div> --}}
    
            
            
        </form>
    </div>
    
    <div class="px-6 py-4 space-y-2 text-center">
        <a class="text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2"
            href="{{ route('users.data') }}">
                Import Users
        </a>
        <a class="text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2"
            href="{{ route('users.export') }}">
                Export Users
        </a>
        <a class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2"
            href="{{ route('users.create') }}">
                Add Employee
        </a>
    </div>
    
    <div class="overflow-auto rounded-lg shadow">
        <x-head-table for="users" >
            @foreach ($users as $user)
                <x-item-table for="user" :id="$user->id" :active="$user->active" :name="$user->name" :lastname="$user->lastname" :department-name="$user->department_name" :total-access="$user->total_access"  />
                {{-- @dump($user) --}}
                
            @endforeach
           
        </x-input-label>
        
    </div>
    <div class="max-w-7xl mx-auto flex justify-center">
        {{-- {{ $users->links() }}  --}}
        {{ $users->appends(request()->input())->links() }}
    </div>
    <br />
        
    
</x-app-layout>
