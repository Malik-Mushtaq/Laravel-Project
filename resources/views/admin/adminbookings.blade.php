@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-success fw-bold"><i class="bi bi-journal-check"></i> All Bookings</h2>

    <div class="mb-4 d-flex justify-content-center">
        <input type="text" id="booking-search" class="form-control rounded" placeholder="Search bookings..." style="width: 400px; max-width: 90%;">
    </div>

    <div id="no-bookings" class="alert alert-secondary text-center d-none">No bookings found.</div>

    <div class="table-responsive shadow-sm rounded d-none" id="booking-table-wrapper">
        <table class="table table-hover align-middle text-center">
            <thead class="table-success">
                <tr>
                    <th>#</th>
                    <th>Property</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Booking Date</th>
                    <th>Rent</th>
                    <th>Payment Method</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="booking-table-body"></tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", loadBookings);

async function loadBookings() {
    try {
        const res = await fetch('/api/admin/bookings');
        const data = await res.json();

        if (!data.success || data.data.length === 0) {
            document.getElementById("no-bookings").classList.remove("d-none");
            return;
        }

        document.getElementById("booking-table-wrapper").classList.remove("d-none");
        const tbody = document.getElementById("booking-table-body");
        tbody.innerHTML = "";

        data.data.forEach((item, index) => {
            const html = buildBookingRow(item, index + 1);
            tbody.insertAdjacentHTML("beforeend", html);
        });

    } catch(err) {
        console.error(err);
        const noBookings = document.getElementById("no-bookings");
        noBookings.classList.remove("d-none");
        noBookings.innerText = "Failed to load bookings.";
    }
}

function buildBookingRow(item, index) {
    return `
    <tr id="booking-row-${item.item_id}">
        <td>${index}</td>
        <td>
            <div class="d-flex align-items-center gap-2 mb-2">
                <img src="${item.image_url}" style="width:60px;height:60px;border-radius:8px;object-fit:cover;">
                <div class="text-start">
                    <div class="fw-semibold">${item.title}</div>
                    <div class="small text-muted">${item.city || ''}</div>
                </div>
            </div>
        </td>
        <td>${item.fullname}</td>
        <td>${item.email}</td>
        <td>${new Date(item.created_at).toLocaleString()}</td>
        <td>₹${item.monthly_rent}</td>
        <td><span class="badge bg-${item.payment_method === 'cash' ? 'warning text-dark' : 'info text-dark'}">${item.payment_method}</span></td>
        <td>
            <button class="btn btn-danger btn-sm cancel-btn" onclick="cancelBookingItem(${item.item_id})">
                <i class="bi bi-x-circle"></i> Cancel
            </button>
        </td>
    </tr>`;
}

// Cancel a single booking item
function cancelBookingItem(itemId) {
    Swal.fire({
        title: "Cancel this booking item?",
        text: "This action cannot be undone.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, cancel it"
    }).then(async (result) => {
        if (!result.isConfirmed) return;

        try {
            const res = await fetch(`/api/bookings/item/${itemId}`, {
                method: "DELETE",
                headers: { "Accept": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" }
            });

            const data = await res.json();

            if (data.success) {
                document.getElementById(`booking-row-${itemId}`).remove();
                Swal.fire("Cancelled!", data.message, "success");
            } else {
                Swal.fire("Error!", data.message, "error");
            }
        } catch(err) {
            console.error(err);
            Swal.fire("Error!", "Failed to cancel booking item.", "error");
        }
    });
}

// Search
document.getElementById("booking-search").addEventListener("input", function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll("#booking-table-body tr").forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(q) ? "" : "none";
    });
});
</script>

<style>
.table-hover tbody tr:hover { background-color: #f0f8ff; transition: 0.3s; }
.table th, .table td { vertical-align: middle; }
.cancel-btn:hover { transform: scale(1.05); transition: all 0.2s ease; }
</style>
@endsection
