@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold text-success mb-4 text-center text-md-start">
        <i class="bi bi-speedometer2"></i> Admin Dashboard
    </h2>

    <!-- Stats Section -->
    <div class="row g-3 g-md-4 mb-5 justify-content-center" id="stats-section"></div>

    <!-- Search Bar -->
    <div class="mb-4 d-flex justify-content-center">
        <input type="text" id="property-search" class="form-control rounded" placeholder="Search properties..." style="width: 500px; max-width: 90%;">
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
                <tbody class="text-center" id="property-table-body"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// -----------------
// Fetch and render stats
// -----------------
async function fetchStats() {
    try {
        const res = await fetch('/api/admin/properties/stats');
        const data = await res.json();
        if(!data.success) return;

        const stats = data.stats;
        const statCards = [
            { key: 'total', label: 'Total', icon: 'bi-building-fill', class: 'bg-primary text-white' },
            { key: 'houses', label: 'Houses', icon: 'bi-house-fill', class: 'bg-success text-white' },
            { key: 'apartments', label: 'Apartments', icon: 'bi-building', class: 'bg-info text-white' },
            { key: 'avgRent', label: 'Avg Rent', icon: 'bi-cash-stack', class: 'bg-warning text-dark' },
            { key: 'cities', label: 'Cities', icon: 'bi-geo-alt-fill', class: 'bg-secondary text-white' }
        ];

        const statsSection = document.getElementById('stats-section');
        statsSection.innerHTML = '';
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
    } catch(err) { console.error(err); }
}

// -----------------
// Render Properties
// -----------------
async function fetchProperties() {
    const tableBody = document.getElementById('property-table-body');
    tableBody.innerHTML = '';
    try {
        const res = await fetch('/api/admin/properties');
        const data = await res.json();
        if(!data.success) return;

        window.properties = data.properties; // store globally for search

        data.properties.forEach(property => {
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
    } catch(err) { console.error(err); }
}

// Delete Property
document.addEventListener("click", function(e) {
    if(e.target.closest(".delete-btn")) {
        const btn = e.target.closest(".delete-btn");
        const id = btn.dataset.id;

        Swal.fire({
            title: "Are you sure?",
            text: "This property will be permanently removed.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then(async (result) => {
            if(result.isConfirmed) {
                try {
                    const res = await fetch(`/api/admin/properties/${id}`, { method: 'DELETE', headers: { 'Accept': 'application/json' } });
                    const data = await res.json();
                    if(data.success) {
                        document.getElementById(`property-${id}`).remove();
                        Swal.fire('Deleted!', data.message, 'success');
                    }
                } catch(err) { Swal.fire('Error!', 'Something went wrong.', 'error'); }
            }
        });
    }
});

// Property search functionality
const searchInput = document.getElementById('property-search');
searchInput.addEventListener('input', function() {
    const query = this.value.trim().toLowerCase();
    const filtered = window.properties.filter(p =>
        [p.id, p.title, p.property_type, p.city, p.monthly_rent, p.bedrooms, p.bathrooms]
            .join(' ').toLowerCase().includes(query)
    );
    renderFiltered(filtered);
});

function renderFiltered(properties) {
    const tableBody = document.getElementById('property-table-body');
    tableBody.innerHTML = '';
    properties.forEach(p => {
        const row = document.createElement('tr');
        row.id = `property-${p.id}`;
        row.innerHTML = `
            <td>${p.id}</td>
            <td class="text-start">${p.title}</td>
            <td>${p.property_type}</td>
            <td>${p.city}</td>
            <td class="text-success fw-semibold">$${p.monthly_rent}</td>
            <td>${p.bedrooms}</td>
            <td>${p.bathrooms}</td>
            <td>
                <a href="/admin/property/${p.id}/edit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-pencil"></i>
                </a>
                <button class="btn btn-outline-danger btn-sm delete-btn" data-id="${p.id}">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

// Initial load
fetchStats();
fetchProperties();
</script>
@endsection
