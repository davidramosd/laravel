<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Hash;

class UsersImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation
{
    use Importable, SkipsErrors;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function  __construct($room_id, $department_id,$type_user_id)
    {
        $this->room_id= $room_id;
        $this->department_id= $department_id;
        $this->type_user_id= $type_user_id;
    }
    public function model(array $row)
    {
        //dd($this->room_id);
        /* return new User([
            'name'     => $row['name'],
            'lastname'     => $row['lastname'],
            'email'    => $row['email'], 
            'document'    => $row['document'],
            'code'      =>$row['code'],
            'password' => Hash::make($row['password']),
            'department_id'      =>$row['department'],
        ]); */
        $user = User::insertGetId([
            'name'     => $row['name'],
            'lastname'     => $row['lastname'],
            'email'    => $row['email'], 
            'document'    => $row['document'],
            'code'      =>$row['code'],
            'password' => Hash::make($row['password']),
            'department_id' => $this->department_id,
            'type_user_id' => $this->type_user_id,
        ]);
        DB::table('room_users')->insert([
            'user_id' => $user,
            'room_id' => $this->room_id,
        ]);

    }

    public function rules(): array 
    {
        return [
        '*.email' => ['email', 'unique:users,email'],
        'code' => 'required|unique:users,code',
        ];
    }
}
//'email' => 'unique:App\Models\User,email_address'