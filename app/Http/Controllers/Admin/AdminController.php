<?php
namespace App\Http\Controllers\Admin;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.DashboardAdmin');
    }
}

