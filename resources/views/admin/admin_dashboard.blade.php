@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')
<div class="dashboard-container">
    <header class="dashboard-header">
        <h1>Employee List</h1>
        <form method="POST" action="{{ route('admin_logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </header>

    <div class="employee-list-card">
        <table class="employee-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                <tr>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('admin_view_details', $employee) }}" class="action-link">View Details</a>
                    </td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="4">No employees found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection