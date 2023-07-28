<?php

namespace App\Imports;

use App\Models\User;
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
    
    public function model(array $row)
    {
        return new User([
            'name'     => $row['name'],
            'lastname'     => $row['lastname'],
            'email'    => $row['email'], 
            'document'    => $row['document'],
            'code'      =>$row['code'],
            'password' => Hash::make($row['password']),
            'department_id'      =>$row['department'],
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