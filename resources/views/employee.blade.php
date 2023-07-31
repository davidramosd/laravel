@props(['user'])
@extends('layouts.user_employee')
@section('content')
    <div class="py-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                

                <div class="border-gray-200 dark:border-gray-600 flex justify-between items-center m-auto">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight m-2">
                        {{ __('Welcome') }}
                    </h2>
                    <div class="mt-3 space-y-1">
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout_employee') }}">
                            @csrf
        
                            <x-responsive-nav-link :href="route('logout_employee')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in employee!") }}
                </div>
            </div>
            <x-list-date for="date" :user="$user" :employee="['user' => 0]"/>
            <br />
            <div class="max-w-7xl mx-auto flex justify-center">
                {{ $user->links() }} 
            </div>
            <br />
        </div>
    </div>


    
@endsection
