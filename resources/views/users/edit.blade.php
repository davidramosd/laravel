<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User') }}
        </h2>
    </x-slot>
    <h1 class="my-4 font-serif text-3xl text-center text-sky-600 dark:text-sky-500">
        Edit form
   </h1>
    <form method="post" action="{{ route('users.update',  $user) }}" class="max-w-xl px-8 py-4 mx-auto bg-white rounded shadow dark:bg-slate-800">
        @csrf
        @method('patch')

        <div class="space-y-4">
            <x-text-input id="id" name="id" type="hidden" class="mt-1 block w-full" :value="$user->id"/>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />

            <x-input-label for="lastname" :value="__('Lastname')" />
            <x-text-input id="lastname" name="lastname" type="text" class="mt-1 block w-full" :value="old('lastname', $user->lastname)" required />
            <x-input-error class="mt-2" :messages="$errors->get('lastname')" />

            <x-input-label for="document" :value="__('document')" />
            <x-text-input id="document" name="document" type="text" class="mt-1 block w-full" :value="old('document', $user->document)" required />
            <x-input-error class="mt-2" :messages="$errors->get('document')" />
            
            <x-input-label for="code" :value="__('code')" />
            <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" :value="old('code', $user->code)" required />
            <x-input-error class="mt-2" :messages="$errors->get('code')" />

            
            <div class="mb-6">
                <label class="flex flex-col block">
                    <span class=""> {{__('Department')}} </span>
                    <select name="department_id" id="department_id">
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('department_id')" />
                </label>
            </div>

            <x-input-label for="email" :value="__('email')" />
            <x-text-input  id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
            <x-input-error  class="mt-2" :messages="$errors->get('email')" />

            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password"  name="password" type="password" :value="old('password', $user->password)" class="mt-1 block w-full"  />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                
        </div>
        <br />
        <x-primary-button>{{ __('Save') }}</x-primary-button>
    </form>
    <br />
</x-app-layout>


