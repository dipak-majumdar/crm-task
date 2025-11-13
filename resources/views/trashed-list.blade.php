@extends('layout.main')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Contacts</h1>
            <p class="text-muted mb-0">List of contacts in the system.</p>
        </div>
        <div>
            <button type="button" class="btn btn-secondary" onclick="window.location.href = '{{ route('list') }}'">
                <i class="bi bi-arrow-left me-1"></i> Go to Contacs
            </button>
        </div>
    </div>

    {{-- @if (isset($contacts) && $contacts->count()) --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width:70px">ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th style="width:160px">Created</th>
                    <th style="width:140px">Deleted On</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contacts as $contact)
                    <tr>
                        <td class="text-muted">{{ $contact->id }}</td>
                        <td>
                            <img src="{{ asset($contact->profile_img ? 'storage/' . $contact->profile_img : 'assets/img/person.png') }}"
                                alt="Profile Image" class="img-fluid rounded-circle border"
                                style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $contact->name }}</div>
                        </td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ $contact->phone }}</td>
                        <td class="text-muted small">
                            {{ $contact->created_at ? $contact->created_at->format('d-m-Y') : '-' }}</td>
                        <td class="text-muted small">
                            {{ $contact->deleted_at ? $contact->deleted_at->format('d-m-Y') : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('elements')
    
@endsection
