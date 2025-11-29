<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index() 
    {
       $employees=User::latest()->get();
       return view('admin.admin_dashboard', compact('employees'));
    }

    public function show(User $employee)
    {
        $employee->load(['addresses', 'qualifications', 'experiences']);
        return view('admin.admin_view_details', compact('employee'));
    }
}

