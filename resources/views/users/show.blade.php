@props(['user'])
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Count') }}
        </h2>
    </x-slot>
    {{$user}}
    <div class="px-6 py-4 space-y-2 text-center flex justify-center  w-full m-2">
        <form action="{{ route('users.showdate', $user) }}" method="GET">
            @csrf
            <label for="first_date" class="w-full sm:w-auto">{{ __('First Date') }}</label>
            <input type="date" name="first_date" value="{{ request('first_date') }}" class="w-full sm:w-auto border border-gray-300 rounded-md px-2 py-1">
            <label for="last_date" class="w-full sm:w-auto">{{ __('Last date') }}</label>
            <input type="date" name="last_date" value="{{ request('last_date') }}" class="w-full sm:w-auto border border-gray-300 rounded-md px-2 py-1"> 
    
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">{{ __('Search') }}</button>
                <a href="{{ route('users.clearFilterDate',  $user) }}" class="px-4 py-2 bg-gray-500 text-white rounded-md">{{ __('Clear Fields') }}</a>
      
        </form>
        
    </div>
    
            
    <x-list-date for="date" :user="$user" />
    <br />
    <div class="max-w-7xl mx-auto flex justify-center">
        {{ $user->links() }} 
    </div>
    <br />
</x-app-layout>
