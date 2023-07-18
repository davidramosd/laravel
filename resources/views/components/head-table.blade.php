

<div>
    <table class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <thead class="justify-between">
        <tr class="bg-gray-800">
          <th class="px-10 py-2">
            <span class="text-gray-300">Id</span>
          </th>
          <th class="px-10 py-2">
            <span class="text-gray-300">Name</span>
          </th>
          <th class="px-10 py-2">
            <span class="text-gray-300">LastName</span>
          </th>
          <th class="px-10 py-2">
            <span class="text-gray-300">Departament</span>
          </th>

          <th class="px-10 py-2">
            <span class="text-gray-300">Total Access</span>
          </th>
          <th class="px-10 py-2">
            <span class="text-gray-300">Actions</span>
          </th>
        </tr>
      </thead>
      <tbody class="bg-gray-200">
          {{ $slot }}
      </tbody>
    </table>
  </div>