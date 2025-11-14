@extends('layout.main')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Contacts</h1>
            <p class="text-muted mb-0">List of contacts in the system.</p>
        </div>
        <div>
            <a href="{{ route('trashed-contacts') }}" class="btn btn-danger">
                <i class="bi bi-trash me-1"></i> Trashed Contacts
            </a>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                <i class="bi bi-plus-circle me-1"></i> New Contact
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
                    <th style="width:140px">Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    {{-- @if (method_exists($contacts, 'links'))
            <div class="mt-3">
                {{ $contacts->links('pagination::bootstrap-5') }}
            </div>
        @endif --}}
    {{-- @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <h5 class="card-title">No contacts found</h5>
                <p class="text-muted">You haven't added any contacts yet. Click the button to create a new one.</p>
                <a href="{{ url('/') }}" class="btn btn-primary">Create contact</a>
            </div>
        </div>
    @endif --}}
@endsection
@section('elements')
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">New Contact</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="contactForm" method="POST" action="{{ route('store') }}" enctype="multipart/form-data">
                    @csrf
                    <div id="form-messages" class="alert mx-2 mt-2" style="display: none;"></div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label me-5">Gender</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="inlineRadio1"
                                    value="Male">
                                <label class="form-check-label" for="inlineRadio1">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="inlineRadio2"
                                    value="Female">
                                <label class="form-check-label" for="inlineRadio2">Female</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="inlineRadio3"
                                    value="Other">
                                <label class="form-check-label" for="inlineRadio3">Other</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="profile_image" class="form-label">Profile Image</label>
                            <input type="file" class="form-control" id="profile_image" name="profile_image"
                                accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="additional_file" class="form-label">Additional File</label>
                            <input type="file" class="form-control" id="additional_file" name="additional_file">
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-sm btn-secondary addFieldBtn">
                                <i class="bi bi-plus-circle me-1"></i> Additional Information
                            </button>
                        </div>
                        <div id="additional-info">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true"></span>
                            Save Contact
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Update Contact</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editContactForm" method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PUT" id="edit-method-input">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit-email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="edit-phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="edit-phone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label me-5">Gender</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="edit-gender-male"
                                    value="Male">
                                <label class="form-check-label" for="edit-gender-male">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="edit-gender-female"
                                    value="Female">
                                <label class="form-check-label" for="edit-gender-female">Female</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="edit-gender-other"
                                    value="Other">
                                <label class="form-check-label" for="edit-gender-other">Other</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="profile_image" class="form-label">Profile Image</label>
                            <input type="file" class="form-control" id="edit-profile_image" name="profile_image"
                                accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="additional_file" class="form-label">Additional File</label>
                            <input type="file" class="form-control" id="edit-additional_file" name="additional_file">
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-sm btn-secondary addFieldBtn">
                                <i class="bi bi-plus-circle me-1"></i> Additional Information
                            </button>
                        </div>
                        <div id="additional-info">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="updateBtn">
                            <span class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true"></span>
                            Save Contact
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Merge Modal -->
    <div class="modal fade" id="mergeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="mergeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="mergeModalLabel">Merge Contact</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="mergeForm" method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PUT" id="edit-method-input">
                    <div id="merge-form-messages" class="alert mx-2 mt-2" style="display: none;"></div>
                    <div class="modal-body">
                        <input type="hidden" name="master_contact_id" id="master_contact_id">
                        <div class="mb-3">
                            <label for="merge-contact" class="form-label">Select Merge Contact <span class="text-danger">*</span></label>
                            <select name="merge-contact" id="merge-contact">
                                @foreach ($contacts as $contact)
                                    <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label me-5">Which Contact to Keep?</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="keep" id="both"
                                    value="both">
                                <label class="form-check-label" for="both">Both</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="keep" id="master"
                                    value="master">
                                <label class="form-check-label" for="master">First/Master</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="mergeBtn">
                            <span class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true"></span>
                            Merge
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>


<div class="toast-container position-fixed top-0 end-0 p-3">
  <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header text-white">
      {{-- <img src="..." class="rounded me-2" alt="..."> --}}
      <i class="bi bi-check-circle-fill text-white me-2"></i>
      <strong class="me-auto text-white"></strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body"></div>
  </div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const liveToast = document.getElementById('liveToast');
            const toastHeader = liveToast.querySelector('.toast-header');
            const toastTitle = liveToast.querySelector('.toast-header strong');
            const toastBody = liveToast.querySelector('.toast-body');
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(liveToast);

            function loadContacts() {
                $.ajax({
                    url: '{{ route('contact-list') }}',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            updateContactsTable(data.contacts);
                        } else {
                            console.error('Failed to load contacts:', data.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading contacts:', error);
                    }
                });
            }

            // Function to update the contacts table
            function updateContactsTable(contacts) {
                const tbody = document.querySelector('table tbody');
                if (!tbody) return;

                // Clear existing rows
                tbody.innerHTML = '';

                if (contacts.length === 0) {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">No contacts found</div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                    return;
                }

                // Add new rows
                contacts.forEach(contact => {
                    const tr = document.createElement('tr');
                    const profileImage = contact.profile_image ? 'storage/'+contact.profile_image : 'assets/img/person.png';

                    // Declare variables with let to ensure they're in the correct scope
                    let mergeBtnClass, mergeBtnData, toggle, target, mergeBtnText, disabled;

                    if(contact.is_merged){
                        console.log(contact.is_merged);
                        mergeBtnClass = "btn btn-sm btn-danger";
                        mergeBtnData = '';
                        toggle = "";
                        target = "";
                        mergeBtnText = "Merged";
                        disabled = "disabled";
                    }else{
                        mergeBtnClass = "merge-button btn btn-sm btn-outline-primary";
                        mergeBtnData = contact.id;
                        toggle = "modal";
                        target = "#mergeModal";
                        mergeBtnText = "Merge";
                        disabled = "";
                    }

                    if (contact.merged_into) {
                        tr.innerHTML = `
                            <td class="text-muted">${contact.id}</td>
                            <td>
                                <img src="{{ asset('${profileImage}') }}" alt="Profile Image" class="img-fluid rounded-circle border" style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td>
                                <div class="fw-semibold">${contact.name || '-'}</div>
                            </td>
                            <td colspan="3" class="text-center">Merged into ${contact.merged_into}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-danger" disabled>Merged</button>
                                    <button type="button" class="edit-button btn btn-sm btn-outline-secondary" data-id="${contact.id}" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                                    <form class="d-inline delete-form" data-id="${contact.id}">
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>`;
                    }else{
                    tr.innerHTML = `
                        <td class="text-muted">${contact.id}</td>
                        <td>
                            <img src="{{ asset('${profileImage}') }}" alt="Profile Image" class="img-fluid rounded-circle border" style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td>
                            <div class="fw-semibold">${contact.name || '-'}</div>
                        </td>
                        <td>${contact.email || '-'}</td>
                        <td>${contact.phone || '-'}</td>
                        <td class="text-muted small">${contact.created_at ? new Date(contact.created_at).toISOString().split('T')[0] : '-'}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button type="button" class="${mergeBtnClass}" data-id="${mergeBtnData}" data-bs-toggle="${toggle}" data-bs-target="${target}" ${disabled}>${mergeBtnText}</button>
                                <button type="button" class="edit-button btn btn-sm btn-outline-secondary" data-id="${contact.id}" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                                <form class="d-inline delete-form" data-id="${contact.id}">
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    `;
                    }

                    tbody.appendChild(tr);

                });
            }

            // Load contacts when page loads
            loadContacts();

            document.getElementById('contactForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = e.target;
                const formData = new FormData(form);
                const submitBtn = document.getElementById('submitBtn');
                const spinner = submitBtn.querySelector('.spinner-border');

                // Show loading state
                submitBtn.disabled = true;
                spinner.classList.remove('d-none');

                $.ajax({
                    url: form.action,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {

                        toastHeader.classList.remove('bg-danger');
                        toastHeader.classList.add('bg-primary');
                        toastTitle.textContent = 'Success!';
                        toastBody.textContent = data.message;
                        toastBootstrap.show();

                        // Reset form and reload contacts after 2 seconds
                        form.reset();
                        $('#additional-info').empty();
                        const modal = bootstrap.Modal.getInstance(document.getElementById('staticBackdrop'));
                        modal.hide();
                        loadContacts(); // Reload the contacts list
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON?.message ||
                            'An error occurred while saving the contact.';

                        toastHeader.classList.remove('bg-primary');
                        toastHeader.classList.add('bg-danger');
                        toastTitle.textContent = 'Failed!';
                        toastBody.textContent = errorMessage;
                        toastBootstrap.show();

                        console.error('Error:', xhr);
                    },
                    complete: function() {
                        submitBtn.disabled = false;
                        spinner.classList.add('d-none');
                    }
                });

            });

            // Handle click on all buttons with addFieldBtn class
            document.querySelectorAll('.addFieldBtn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Find the closest form and then find the additional-info container within it
                    const form = this.closest('form');
                    const container = form.querySelector('#additional-info');
                    const fieldCount = container.querySelectorAll('.additional-field-row').length;
                    const fieldIndex = fieldCount + 1;

                const fieldHTML = `
                <div class="additional-field-row mt-3 p-3 border rounded" style="background-color: #f8f9fa;">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="custom_field_name_${fieldIndex}" class="form-label">Field Name</label>
                            <input type="text" class="form-control" id="custom_field_name_${fieldIndex}" name="custom_fields[${fieldIndex}][name]" placeholder="e.g., Department">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="custom_field_value_${fieldIndex}" class="form-label">Field Value</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="custom_field_value_${fieldIndex}" name="custom_fields[${fieldIndex}][value]" placeholder="e.g., Sales">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-field" data-field-id="${fieldIndex}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                container.insertAdjacentHTML('beforeend', fieldHTML);

                // Add remove button listener to the newly added field
                document.querySelector(`[data-field-id="${fieldIndex}"]`).addEventListener('click',
                    function() {
                        this.closest('.additional-field-row').remove();
                    });
            });
            });


            // Delegate edit-button clicks from the table body (buttons are created dynamically)
            const tableBody = document.querySelector('table tbody');
            if (tableBody) {
                tableBody.addEventListener('click', function(e) {
                    const btn = e.target.closest('.edit-button');
                    if (!btn) return;
                    const contactId = btn.getAttribute('data-id');

                    // Set edit form action to the contact update endpoint
                    const editForm = document.getElementById('editContactForm');
                    if (editForm) {
                        editForm.action = '/update-contact/' + contactId;
                    }

                    $.ajax({
                        url: '/get-contact/' + contactId,
                        method: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            if (data.success) {
                                const contact = data.contact;
                                // Populate the edit modal fields
                                $('#editModal #edit-name').val(contact.name);
                                $('#editModal #edit-email').val(contact.email);
                                $('#editModal #edit-phone').val(contact.phone);
                                // Set gender radio
                                if (contact.gender) {
                                    $(`#editModal input[name="gender"][value="${contact.gender}"]`).prop('checked', true);
                                } else {
                                    $(`#editModal input[name="gender"]`).prop('checked', false);
                                }

                                // Optionally populate additional custom fields if returned
                                if (contact.custom_fields && Array.isArray(contact.custom_fields)) {
                                    const addContainer = document.querySelector('#editModal #additional-info');
                                    if (addContainer) {
                                        addContainer.innerHTML = '';
                                        contact.custom_fields.forEach((f, idx) => {
                                            const index = idx + 1;
                                            const html = `
                                                <div class="additional-field-row mt-3 p-3 border rounded" style="background-color: #f8f9fa;">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-2">
                                                            <label class="form-label">Field Name</label>
                                                            <input type="text" class="form-control" name="custom_fields[${index}][name]" value="${f.field_name || ''}">
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <label class="form-label">Field Value</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="custom_fields[${index}][value]" value="${f.field_value || ''}">
                                                                <button type="button" class="btn btn-outline-danger btn-sm remove-field"><i class="bi bi-trash"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            `;
                                            addContainer.insertAdjacentHTML('beforeend', html);
                                        });
                                    }
                                }

                                // Show the edit modal (Bootstrap will show because button triggered it already), ensure inputs reflect changes.
                            }
                        },
                        error: function(xhr, status, error) {
                            toastHeader.classList.remove('bg-primary');
                            toastHeader.classList.add('bg-danger');
                            toastTitle.textContent = 'Failed!';
                            toastBody.textContent = xhr.responseJSON?.message;
                            toastBootstrap.show();

                            console.error('Error fetching contact data:', error);
                        }
                    });
                });
            }

            document.getElementById('editContactForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = e.target;
                const formData = new FormData(form);
                const submitBtn = document.getElementById('updateBtn');
                const spinner = submitBtn.querySelector('.spinner-border');

                // Show loading state
                submitBtn.disabled = true;
                spinner.classList.remove('d-none');

                $.ajax({
                    url: form.action,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {

                        toastHeader.classList.remove('bg-danger');
                        toastHeader.classList.add('bg-primary');
                        toastTitle.textContent = 'Success';
                        toastBody.textContent = data.message || 'Contact Updated Successfully!';
                        toastBootstrap.show();

                        loadContacts();
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON?.message ||
                            'An error occurred while saving the contact.';
                            
                        toastHeader.classList.remove('bg-primary');
                        toastHeader.classList.add('bg-danger');
                        toastTitle.textContent = 'Error';
                        toastBody.textContent = errorMessage;
                        toastBootstrap.show();

                        console.error('Error:', xhr);
                    },
                    complete: function() {
                        submitBtn.disabled = false;
                        spinner.classList.add('d-none');
                    }
                });

            });

            if (tableBody) {
                tableBody.addEventListener('click', function(e) {
                    const btn = e.target.closest('.merge-button');
                    if (!btn) return;
                    const contactId = btn.getAttribute('data-id');

                    // Set the master contact ID
                    document.getElementById('master_contact_id').value = contactId;

                    // Set edit form action to the contact update endpoint
                    const editForm = document.getElementById('mergeForm');
                    if (editForm) {
                        editForm.action = '/merge/' + contactId;
                    }
                });
            }
            
            // Merge Contact
            document.getElementById('mergeForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const contactId = form.data('id');

                if (confirm('Are you sure you want to merge this contact?')) {
                    $.ajax({
                        url: form.attr('action'),
                        method: 'PUT',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            _method: 'PUT'
                        },
                        success: function(response) {
                            if (response.status) {
                                loadContacts(); // Refresh the contacts list
                                // Optional: Show success message
                                toastHeader.classList.remove('bg-danger');
                                toastHeader.classList.add('bg-primary');
                                toastTitle.textContent = 'Merged!';
                                toastBody.textContent = response.message;
                                toastBootstrap.show();
                            }
                        },
                        error: function(xhr) {
                            const errorMessage = xhr.responseJSON?.message ||
                            'An error occurred while saving the contact.';

                            toastHeader.classList.remove('bg-primary');
                            toastHeader.classList.add('bg-danger');
                            toastTitle.textContent = 'Error';
                            toastBody.textContent = errorMessage;
                            toastBootstrap.show();

                            console.error('Error merging contact:', xhr);
                        }
                    });
                }
            });

            
            // Add this inside your DOMContentLoaded event listener
            $(document).on('submit', '.delete-form', function(e) {
                e.preventDefault();
                const form = $(this);
                const contactId = form.data('id');

                if (confirm('Are you sure you want to delete this contact?')) {
                    $.ajax({
                        url: '/delete/' + contactId,
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            if (response.success) {
                                loadContacts(); // Refresh the contacts list
                                // Optional: Show success message
                                toastHeader.classList.remove('bg-primary');
                                toastHeader.classList.add('bg-danger');
                                toastTitle.textContent = 'Deleted!';
                                toastBody.textContent = response.message;
                                toastBootstrap.show();
                            }
                        },
                        error: function(xhr) {
                            toastHeader.classList.remove('bg-primary');
                            toastHeader.classList.add('bg-danger');
                            toastTitle.textContent = 'Failed!';
                            toastBody.textContent = xhr.responseJSON?.message;
                            toastBootstrap.show();
                            console.error('Error deleting contact:', xhr);
                        }
                    });
                }
            });
        });
    </script>
@endsection
