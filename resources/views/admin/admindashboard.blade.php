@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold text-success mb-4 text-center text-md-start">
        <i class="bi bi-speedometer2"></i> Admin Dashboard
    </h2>

    <!-- Stats Section -->
    <div class="row g-3 g-md-4 mb-5 justify-content-center" id="stats-section">
        <!-- Stats cards will be dynamically filled by JS -->
    </div>

    <!-- Property List -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center rounded-top-4 flex-wrap">
            <h5 class="mb-2 mb-md-0"><i class="bi bi-list"></i> Properties Overview</h5>
            <a href="{{ url('/admin/propertycreate') }}" class="btn btn-light btn-sm fw-semibold">
                <i class="bi bi-plus-circle"></i> Add New Property
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-success text-center">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>City</th>
                        <th>Rent</th>
                        <th>Bedrooms</th>
                        <th>Bathrooms</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center" id="property-table-body">
                    <!-- Property rows will be dynamically filled by JS -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- SweetAlert for delete -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Stats data
const stats = {
    total: {{ $stats['total'] }},
    houses: {{ $stats['houses'] }},
    apartments: {{ $stats['apartments'] }},
    avgRent: {{ $stats['avgRent'] }},
    cities: {{ $stats['cities'] }}
};

// Gradients & icons
const statCards = [
    { key: 'total', label: 'Total', icon: 'bi-building-fill', class: 'bg-primary text-white' },
    { key: 'houses', label: 'Houses', icon: 'bi-house-fill', class: 'bg-success text-white' },
    { key: 'apartments', label: 'Apartments', icon: 'bi-building', class: 'bg-info text-white' },
    { key: 'avgRent', label: 'Avg Rent', icon: 'bi-cash-stack', class: 'bg-warning text-dark' },
    { key: 'cities', label: 'Cities', icon: 'bi-geo-alt-fill', class: 'bg-secondary text-white' }
];

// Render stats cards
const statsSection = document.getElementById('stats-section');
statCards.forEach(card => {
    const value = card.key === 'avgRent' ? `$${Number(stats[card.key]).toLocaleString()}` : stats[card.key];
    const col = document.createElement('div');
    col.className = 'col-6 col-md-4 col-lg-2';
    col.innerHTML = `
        <div class="card ${card.class} text-center shadow-sm h-100">
            <div class="card-body py-4">
                <i class="bi ${card.icon} fs-1 mb-2"></i>
                <h4 class="fw-bold mb-0">${value}</h4>
                <small>${card.label}</small>
            </div>
        </div>
    `;
    statsSection.appendChild(col);
});

// Properties data (replace with actual $properties JSON if needed)
const properties = @json($properties);

// Render property table rows
const tableBody = document.getElementById('property-table-body');
properties.forEach(property => {
    const row = document.createElement('tr');
    row.id = `property-${property.id}`;
    row.innerHTML = `
        <td>${property.id}</td>
        <td class="text-start">${property.title}</td>
        <td>${property.property_type}</td>
        <td>${property.city}</td>
        <td class="text-success fw-semibold">$${property.monthly_rent}</td>
        <td>${property.bedrooms}</td>
        <td>${property.bathrooms}</td>
        <td>
            <a href="/admin/property/${property.id}/edit" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-pencil"></i>
            </a>
            <button class="btn btn-outline-danger btn-sm delete-btn" data-id="${property.id}">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    tableBody.appendChild(row);
});

// Delete property
// Delete property
document.addEventListener("click", function(e) {
    if(e.target.closest(".delete-btn")) {
        const btn = e.target.closest(".delete-btn");
        const propertyId = btn.dataset.id;

        Swal.fire({
            title: "Are you sure?",
            text: "This property will be permanently removed.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if(result.isConfirmed) {
                fetch(`/admin/propertydelete/${propertyId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        document.getElementById(`property-${propertyId}`).remove();
                        Swal.fire('Deleted!', data.message, 'success');
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                })
                .catch(err => {
                    Swal.fire('Error!', 'Something went wrong.', 'error');
                    console.error(err);
                });
            }
        });
    }
});
</script>
@endsection
