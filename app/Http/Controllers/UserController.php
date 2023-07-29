<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\SaveUserRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class UserController extends Controller
{
    public function index(Request $request) {
        /* $users = User::select('users.*', 'departments.name as department_name', 'rooms.name as room_name')
                ->selectSub(function ($query) {
                    // Subconsulta para obtener la cantidad total de artículos por pedido
                    $query->selectRaw('COUNT(id) as total_items')
                        ->from('access')
                        ->whereColumn('user_id', 'users.id');
                }, 'total_access')
                ->leftjoin('departments', 'users.department_id', '=', 'departments.id')
                ->leftjoin('room_users', 'users.id', '=', 'room_users.user_id')
                ->leftjoin('rooms', 'room_users.room_id', '=', 'rooms.id')
                ->paginate(10); */
            $validated = $request->validate([
                    'name' => 'nullable|string|max:50',
                    'lastname' => 'nullable|string|max:50'
            ]);
            $departments = DB::table('departments')->get();
            $query = User::query();

            $query->leftjoin('departments', 'users.department_id', '=', 'departments.id');
            $query->leftjoin('room_users', 'users.id', '=', 'room_users.user_id');
            $query->leftjoin('rooms', 'room_users.room_id', '=', 'rooms.id');
            $query->rightjoin('type_users', 'users.type_user_id', '=', 'type_users.id');
            $query->where('type_users.name', 'employee');
            $query->select('users.*', 'departments.name as department_name', 'rooms.name as room_name')
            ->selectSub(function ($query) {
                $query->selectRaw('COUNT(id)')
                    ->from('access')
                    ->whereColumn('user_id', 'users.id');
            }, 'total_date');
        if ($request->has('name') && $request->input('name') != '') {
            $query->where('users.name', 'like', '%' . $request->query('name') . '%');
        }
            
        if ($request->has('lastname')  && $request->input('lastname') != '') {
            $query->where('users.lastname', 'like', '%' . $request->query('lastname') . '%');
        } 

        if ($request->has('department_id')) {
            $query->where('users.department_id', 'like', '%' . $request->query('department_id') . '%');
        } 

        $users = $query->paginate(10);
        return view('dashboard', [ 'users' => $users, 'departments' => $departments]);
    }


    public function clearFilter(Request $request)
    {
        $request->session()->forget('name');
        $request->session()->forget('lastname');
        $request->session()->forget('department');
        $request->session()->forget('first_date');
        $request->session()->forget('last_date');

        return redirect()->route('users.index');
    }

    
    public function clearFilterDate(Request $request, User $user)
    {
        $request->session()->forget('first_date');
        $request->session()->forget('last_date');

        return redirect()->route('users.index');
    }

    public function edit(User $user) {
        $departments = DB::table('departments')->get();
        $rooms = DB::table('rooms')->get();
        $user_room = DB::table('room_users')->where('room_users.user_id',$user->id)->first();
        return view('users.edit', [ 'user' => $user, 'user_room' => $user_room, 'departments' => $departments, 'rooms' => $rooms]);

    }


    public function show(Request $request, User $user) {
        /* $userAccess = User::select('users.id', 'users.name', 'access.registered_at as date_access')
                ->join('access', 'users.id', '=', 'access.user_id')
                ->where('users.id', $user->id)
                ->get(); */
        //dd($user);
        $query = User::query();    
        $query->join('access', 'users.id', '=', 'access.user_id');
        $query->select('users.id', 'users.name', 'access.registered_at as date_access');
        $query->where('users.id', $user->id);
        if($request->has('first_date') && $request->has('last_date')) {
            $query->whereBetween('access.registered_at', [$request->query('first_date'), $request->query('last_date')])->get();
        }
        $userAccess = $query->paginate(10);
        return view('users.show', [ 'user' => $userAccess, 'id' => $user->id]);
    }

    public function update(SaveUserRequest $request, User $user) {
/*         $validated_room = $request->validate([
            'room_id' => ['required', 'numeric'],
        ]); */
        $validated = $request->validated();
        $userRoom = DB::table('room_users')
            ->where('room_users.user_id', $user->id)
            //->where('room_users.room_id', $request->room_id)
            ->first();
            //->get();
        
        $userUpdate = DB::table('users')
            ->join('type_users', 'users.type_user_id', '=', 'type_users.id')
            ->where('type_users.name', 'employee')
            ->where('users.id', $user->id)
            ->select('users.*', 'type_users.name')
            ->first();

        if($userUpdate){
            if(!$userRoom){ 
                DB::table('room_users')->insert([
                    'user_id' => $user->id,
                    'room_id' => $request->room_id,
                ]);
            }else if($request->room_id){
                DB::table('room_users')
                ->where('room_users.user_id', $user->id)
                ->update([
                    'room_id' => $request->room_id,
                ]);
            }
            $user->update($validated);
            return to_route('dashboard')->with('status', 'User Updata!');
        }else {
            back()->withErrors('User not is Employee');
        }
       //$uri = $request->path();
        //$request->user()->fill($request->validated());
    }


    public function destroy(User $user) {
        $user->delete();
        return to_route('dashboard')->with('status', 'User Deleted!');
    }


    public function createPDF(Request $request, User $user) {
        $id = $request->query('id');
        // retreive all records from db
        $userAccess = User::select('users.id', 'users.name', 'access.registered_at as date_access')
                ->join('access', 'users.id', '=', 'access.user_id')
                ->where('users.id', $id)
                ->get();

    
        //dd($request);
        try {
            $pdfContent = view('components.list-date', [ 'user' => $userAccess])->render();
            $pdf = PDF::loadHTML($pdfContent);
            // download PDF file with download method
            return $pdf->download('pdf_file.pdf');
        } catch (Throwable $e) {
            session()->flash('status', 'Problemas con pdf');
        }
        
    }

/**
    * @return \Illuminate\Support\Collection
    */
    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
       
    /**
    * @return \Illuminate\Support\Collection
    */
    public function import() 
    {
        try {
            if( request()->file('file')) {

                $file = request()->file('file');

                $import = new UsersImport(12);
                $import->import($file);
                //dd($import->errors());
                //Excel::import(new UsersImport,request()->file('file'));

                //$importedData = Excel::toArray(new UsersImport, request()->file('file'));
                //dd($importedData[0]);
                /* if(count($importedData)){
                    foreach($importedData as $clave) {
                        //dd($clave[0]['email']);
                        $user = DB::table('users')
                        ->where('email', $clave[0]['email'])
                        ->first();
                        DB::table('room_users')->insert([
                            'user_id' => $user->id,
                            'room_id' => 1
                        ]);
                    }
                } */
                return back();
            }
        } catch (Throwable $e) {
            //session()->flash('status', 'Problemas con el csv');
            return back()->withErrors('Error de base de datos: ' . $e->getMessage());
            //return to_route('dashboard')->with('status', 'Problemas con el csv');
        }
        
    }
}
