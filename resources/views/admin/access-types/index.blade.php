@extends('layouts.admin')

@section('title', 'Access Types')
@section('page-title', 'Access Types')

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 pb-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-1 fw-bold">Create Access Type</h5>
                        <small class="text-muted">Manage subscription tiers and pricing</small>
                    </div>
                    <span class="badge bg-primary-subtle text-primary">New</span>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.access-types.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Code</label>
                        <input type="text" name="code" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Describe what this access type includes"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Plan Fee (Tk)</label>
                        <input type="number" name="fee" class="form-control" min="0" step="0.01" value="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <button class="btn btn-primary w-100 fw-semibold">Save Access Type</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 pb-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-1 fw-bold">Access Types</h5>
                        <small class="text-muted">Current subscription plans</small>
                    </div>
                    <span class="badge bg-success-subtle text-success">{{ $accessTypes->count() }} Plans</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Fee</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($accessTypes as $accessType)
                                <tr>
                                    <td class="fw-semibold">{{ $accessType->name }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $accessType->code }}</span></td>
                                    <td class="fw-semibold text-primary">৳ {{ number_format((float) ($accessType->fee ?? 0), 2) }}</td>
                                    <td>
                                        @if($accessType->is_active)
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <button type="button"
                                                class="btn btn-sm btn-warning me-1 edit-access-type"
                                                data-id="{{ $accessType->id }}"
                                                data-name="{{ $accessType->name }}"
                                                data-code="{{ $accessType->code }}"
                                                data-description="{{ $accessType->description }}"
                                                data-fee="{{ $accessType->fee ?? 0 }}"
                                                data-is-active="{{ (int) $accessType->is_active }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.access-types.update', $accessType->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="name" value="{{ $accessType->name }}">
                                            <input type="hidden" name="code" value="{{ $accessType->code }}">
                                            <input type="hidden" name="description" value="{{ $accessType->description }}">
                                            <input type="hidden" name="fee" value="{{ $accessType->fee ?? 0 }}">
                                            <input type="hidden" name="is_active" value="{{ $accessType->is_active ? 0 : 1 }}">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">Toggle</button>
                                        </form>
                                        <a href="{{ route('admin.access-types.delete', $accessType->id) }}" class="btn btn-sm btn-danger ms-1" onclick="return confirm('Delete this access type?')">Delete</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">No access types found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editAccessTypeModal" tabindex="-1" aria-labelledby="editAccessTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="editAccessTypeForm" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title fw-bold" id="editAccessTypeModalLabel">Edit Access Type</h5>
                        <small class="text-muted">Update plan details and pricing</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Code</label>
                        <input type="text" name="code" id="edit_code" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Plan Fee (Tk)</label>
                        <input type="number" name="fee" id="edit_fee" class="form-control" min="0" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="is_active" id="edit_is_active" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Access Type</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('editAccessTypeModal');
        const form = document.getElementById('editAccessTypeForm');
        const buttons = document.querySelectorAll('.edit-access-type');

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                const id = button.dataset.id;
                const name = button.dataset.name || '';
                const code = button.dataset.code || '';
                const description = button.dataset.description || '';
                const fee = button.dataset.fee || 0;
                const isActive = button.dataset.isActive || 1;

                form.action = '/admin/access-types/update/' + id;
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_code').value = code;
                document.getElementById('edit_description').value = description;
                document.getElementById('edit_fee').value = fee;
                document.getElementById('edit_is_active').value = isActive;

                const bootstrapModal = bootstrap.Modal.getOrCreateInstance(modal);
                bootstrapModal.show();
            });
        });
    });
</script>
@endsection
