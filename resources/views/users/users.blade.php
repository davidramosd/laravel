<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __(' Import Users') }}
        </h2>
    </x-slot>
<div class="container m-auto">
    <div class="card mt-3 mb-3">
        <div class="card-header text-center">
            <h4  class="my-4" >File type CSV</h4>
        </div>
        <div class="card-body ">
            <form class="max-w-xl px-8 py-4 mx-auto bg-white rounded shadow dark:bg-slate-800" action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <label class="flex flex-col block">
                        <span class=""> {{__('Room')}} </span>
                        <select name="room_id" id="room_id">
                            <option value="" selected>Select</option>
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}"
                                    {{ old('room_id') == $room->id ? 'selected="selected"' : '' }}>
                                    {{ $room->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('room_id')" />
                    </label>
                </div>
                <div class="mb-6">
                    <label class="flex flex-col block">
                        <span class=""> {{__('Departament')}} </span>
                        <select name="department_id" id="department_id">
                            <option value="" selected>Select</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department_id') == $department->id ? 'selected="selected"' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('room_id')" />
                    </label>
                </div>
                <br>
                <input type="file" name="file" class="form-control">
                <br>
                <button class="inline-flex items-center my-8 px-4 py-2 text-xs font-semibold tracking-widest text-center text-white uppercase transition duration-150 ease-in-out border border-2 border-transparent rounded-md dark:text-sky-200 bg-sky-800 hover:bg-sky-700 active:bg-sky-700 focus:outline-none focus:border-sky-500">Import User Data</button>
            </form>
                @if (isset($errors) && $errors->any())
                <div class="card-header text-center"> 
                    @foreach($errors->all() as $error)
                    {{ $error }}
                    @endforeach
                </div>
                @endif
          {{--   <table class="table table-bordered mt-3">
                <tr>
                    <th colspan="3">
                        List Of Users
                        <a class="btn btn-danger float-end" href="{{ route('users.export') }}">Export User Data</a>
                    </th>
                </tr>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                </tr>
                @endforeach
            </table> --}}
  
        </div>
    </div>
</div>
</x-app-layout>