@props(['user'])
<style>
    #container {
        display: flex;
        justify-content: center;
        margin: auto;
    }
    #customers {
      font-family: Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 80%;
    }
    
    #customers td, #customers th {
      border: 1px solid #ddd;
      padding: 8px;
    }
    
    #customers tr:nth-child(even){background-color: #f2f2f2;}
    
    #customers tr:hover {background-color: #ddd;}
    
    #customers th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
      background-color: #4c6d81;
      color: white;
    }
</style>
<div class="container mt-5">
    <div class="px-6 py-4 space-y-2 text-center">
        <a class="text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2"
            href="{{route('users.pdf', ['id' => $user[0]->id ?? '0'])}}">
                Export to PDF
        </a>
    </div>
    <div id="container">
        <table id="customers">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Date</th>
            </tr>
            @foreach($user as $userAccess)
            <tr>
                <td>{{ $userAccess->id }}</td>
                <td>{{ $userAccess->name }}</td>
                <td>{{ $userAccess->date_access }}</td>
            </tr>
            @endforeach
              
        </table>
    </div>
   
</div>