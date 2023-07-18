<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Count') }}
        </h2>
    </x-slot>
    <x-list-date for="date" :user="$user" />
</x-app-layout>
