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

            $departments = DB::table('departments')->get();
            $query = User::query();

            $query->leftjoin('departments', 'users.department_id', '=', 'departments.id');
            $query->leftjoin('room_users', 'users.id', '=', 'room_users.user_id');
            $query->leftjoin('rooms', 'room_users.room_id', '=', 'rooms.id');
            $query->select('users.*', 'departments.name as department_name', 'rooms.name as room_name')
            ->selectSub(function ($query) {
                // Subconsulta para obtener la cantidad total de artículos por pedido
                $query->selectRaw('COUNT(id)')
                    ->from('access')
                    ->whereColumn('user_id', 'users.id');
            }, 'total_date');
        if ($request->has('name')) {
            $query->where('users.name', 'like', '%' . $request->query('name') . '%');
        }
            
        if ($request->has('lastname')) {
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
        return view('users.edit', [ 'user' => $user, 'departments' => $departments]);

    }


    public function showdate(Request $request, User $user) {
        /* $userAccess = User::select('users.id', 'users.name', 'access.registered_at as date_access')
                ->join('access', 'users.id', '=', 'access.user_id')
                ->where('users.id', $user->id)
                ->get(); */
        //dd($user);
        $query = User::query();    
        $query->join('access', 'users.id', '=', 'access.user_id');
        $query->select('users.id', 'users.name', 'access.registered_at as date_access');

        if($request->has('first_date') && $request->has('last_date')) {
            $query->whereBetween('access.registered_at', [$request->query('first_date'), $request->query('last_date')])->get();
        }

        /* if ($request->has('first_date')) {
            $query->where('users.name', 'like', '%' . $request->query('name') . '%');
        }
            
        if ($request->has('last_date')) {
            $query->where('users.lastname', 'like', '%' . $request->query('lastname') . '%');
        }  */
        

        $userAccess = $query->paginate(10);
        return view('users.show', [ 'user' => $userAccess]);
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

        /* if ($request->has('first_date')) {
            $query->where('users.name', 'like', '%' . $request->query('name') . '%');
        }
            
        if ($request->has('last_date')) {
            $query->where('users.lastname', 'like', '%' . $request->query('lastname') . '%');
        }  */
        

        $userAccess = $query->paginate(10);
        return view('users.show', [ 'user' => $userAccess, 'id' => $user->id]);
    }

    public function update(SaveUserRequest $request, User $user) {
        
       //$user->update($request->validated());
       //$uri = $request->path();

        $user->update($request->validated());

       /*  $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]); */

        /* $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save(); */
        //dd($request);
        return to_route('dashboard')->with('status', 'User Updata!');
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

                $import = new UsersImport;
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
