<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Hash;

class UsersImport implements ToModel, WithHeadingRow
{
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
}
