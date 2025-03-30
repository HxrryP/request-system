@extends('layouts.app')

@section('content')
<div class="container">
    <h1>User Document Requests</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Document Type</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($requests as $request)
            <tr>
                <td>{{ $request->id }}</td>
                <td>{{ $request->documentType->name }}</td>
                <td>{{ $request->status }}</td>
                <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('requests.show', $request->id) }}" class="btn btn-primary">View</a>
                    <a href="{{ route('requests.edit', $request->id) }}" class="btn btn-secondary">Edit</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">No document requests found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $requests->links() }}
</div>
@endsection

