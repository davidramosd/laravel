@props(['id','name', 'lastname', 'departmentName', 'active'])
  <tr class="bg-white border-4 border-gray-200">
    <td>
      <span class="text-center ml-2 font-semibold">{{ $id }} </span>
    </td>
    <td>
      <span class="text-center ml-2 font-semibold">{{ $name   ?? 'null' }}</span>
    </td>
    <td>
      <span class="text-center ml-2 font-semibold">{{ $lastname   ?? 'null' }}</span>
    </td>
    <td>
      <span class="text-center ml-2 font-semibold" >{{ $departmentName   ?? 'null' }}</span>
    </td>
    <td>
      <span class="text-center ml-2 font-semibold">{{ $totalAccess  ?? 'null' }}</span>
    </td>

    <td>
      <div class="flex">
        <a class="inline-flex items-center text-xs font-semibold tracking-widest text-center uppercase transition duration-150 ease-in-out dark:text-slate-400 text-slate-500 hover:text-slate-600 dark:hover:text-slate-500 focus:outline-none focus:border-slate-200"
          href="{{ route('users.edit', $id ) }}">
            Update
          </a>
        <form class="inline-flex items-center mx-8" action=" {{ route('users.active', $id) }}" method="POST">
          @csrf @method('patch')
          <a href="#"
          class="inline-flex items-center text-xs font-semibold tracking-widest text-center uppercase transition duration-150 ease-in-out dark:text-slate-400 text-slate-500 hover:text-slate-600 dark:hover:text-slate-500 focus:outline-none focus:border-slate-200"
               onclick="this.closest('form').submit()"
            > {{ $active ? ' Disable'  : 'Enabled' }}</a>
        </form>
          <a href="{{ route('users.show', $id ) }}" class="bg-indigo-500 text-white px-4 py-2 border rounded-md hover:bg-white hover:border-indigo-500 hover:text-black ">
              History
          </a>
        <form class="inline-flex items-center mx-8" action=" {{ route('users.destroy', $id ) }}" method="POST">
          @csrf @method('Delete')
          <button class="inline-flex items-center text-xs font-semibold tracking-widest text-center text-red-500 uppercase transition duration-150 ease-in-out dark:text-red-500/80 hover:text-red-600 focus:outline-none"
              type="submit">
              Delete
          </button>
        </form>
      </div>
    </td>
  </tr>

{{--   
  <td >
    <span class="text-green-500">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="w-5 h5 "
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="#2c3e50"
        fill="none"
        stroke-linecap="round"
        stroke-linejoin="round"
      >
        <path stroke="none" d="M0 0h24v24H0z" />
        <path d="M5 12l5 5l10 -10" />
      </svg>
    </span>
  </td> --}}