<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('users.excel', [
            'data' => User::all()
        ]);
    }
}
