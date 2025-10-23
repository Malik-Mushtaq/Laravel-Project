@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-success fw-bold">
        <i class="bi bi-journal-check"></i> All Bookings
    </h2>

    <div id="bookingsContainer" class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle text-center" id="bookingsTable">
            <thead class="table-success">
                <tr>
                    <th>#</th>
                    <th>Property</th>
                    <th>Booked By</th>
                    <th>Phone</th>
                    <th>Booking Date</th>
                    <th>Rent</th>
                    <th>Payment Method</th>
                    <th>Card</th>
                    <th>Expiry</th>
                    <th>CVV</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="bookings-body"></tbody>
        </table>
    </div>
</div>

<!-- sweetalert connection-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
//bookings array of object
const bookings = [
    {
        "id": "68f615d6aead6",
        "property_id": "10005",
        "name": "Malik",
        "phone": "03214568120",
        "date": "2026-06-05",
        "rent": "4,500",
        "payment_method": "cash",
        "card_number": null,
        "expiry": null,
        "cvv": null,
        "created_at": "2025-10-20 10:58:30"
    },
    {
        "id": "68f619f4955c8",
        "property_id": "10009",
        "name": "User",
        "phone": "03145879623",
        "date": "2026-07-08",
        "rent": "1,950",
        "payment_method": "card",
        "card_number": "2014532569874563",
        "expiry": "0522",
        "cvv": "652",
        "created_at": "2025-10-20 11:16:04"
    },
    {
        "id": "68f87fbf9ffb5",
        "property_id": "10004",
        "name": "Malik",
        "phone": "07852616546",
        "date": "2025-12-05",
        "rent": "1,200",
        "payment_method": "cash",
        "card_number": null,
        "expiry": null,
        "cvv": null,
        "created_at": "2025-10-22 06:54:55"
    }
]

// Property Map Array
const properties = [
    { "property_id": "10003", "title": "Modern 3 Bed Townhouse with Garage" },
    { "property_id": "10005", "title": "Cozy Downtown Apartment" },
    { "property_id": "10006", "title": "Luxury Villa with Pool" }
];

// Convert to a lookup map for easy matching and saving in propertymap 
const propertyMap = {};
properties.forEach(p => propertyMap[p.property_id] = p.title);

// showing all bookings in a table
const tbody = document.getElementById('bookings-body');
if (bookings.length > 0) {
    bookings.forEach((b, i) => {
        tbody.innerHTML += `
            <tr id="booking-row-${b.id}">
                <td>${i + 1}</td>
                <td class="text-start">${propertyMap[b.property_id] || 'Unknown Property'}</td>
                <td>${b.name || '-'}</td>
                <td>${b.phone || '-'}</td>
                <td><span class="badge bg-primary">${b.date || '-'}</span></td>
                <td><span class="text-success fw-semibold">$${b.rent || '-'}</span></td>
                <td>
                    ${b.payment_method === 'cash' 
                        ? `<span class="badge bg-warning text-dark">Cash</span>`
                        : `<span class="badge bg-info text-dark">${b.payment_method}</span>`}
                </td>
                <td>${b.card_number || '-'}</td>
                <td>${b.expiry || '-'}</td>
                <td>${b.cvv || '-'}</td>
                <td>${b.created_at || '-'}</td>
                <td>
                    <button class="btn btn-danger btn-sm cancel-btn" data-id="${b.id}">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                </td>
            </tr>
        `;
    });
} else {
    document.getElementById('bookingsContainer').innerHTML = `
        <div class="alert alert-secondary text-center mt-5">
            No bookings found.
        </div>
    `;
}

//  cancel with showing sweetalert
document.addEventListener("click", function(e) {
    const cancelBtn = e.target.closest(".cancel-btn");
    if (!cancelBtn) return;

    const bookingId = cancelBtn.dataset.id;
    const row = document.getElementById(`booking-row-${bookingId}`);

    Swal.fire({
        title: 'Cancel this booking?',
        text: "Are you sure you want to cancel? This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#00a651',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, cancel it',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            row.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            row.style.opacity = '0';
            row.style.transform = 'translateX(-20px)';
            setTimeout(() => row.remove(), 500);

            Swal.fire({
                icon: 'success',
                title: 'Booking Cancelled',
                text: 'The booking has been successfully cancelled.',
                showConfirmButton: false,
                timer: 2000
            });
        }
    });
});
</script>

<style>
.table-hover tbody tr:hover {
    background-color: #f0f8ff;
    transition: 0.3s;
}
.table th, .table td {
    vertical-align: middle;
}
.cancel-btn {
    border: none;
    font-weight: 600;
    transition: all 0.2s ease;
}
.cancel-btn:hover {
    transform: scale(1.05);
    background-color: #c82333 !important;
}
</style>
@endsection
