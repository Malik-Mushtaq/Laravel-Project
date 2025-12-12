@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-success"><i class="bi bi-journal-check"></i> My Bookings</h2>
        <a href="{{ route('properties') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle"></i> Back to Product List
        </a>
    </div>

    <div id="bookingsContainer" class="row g-4 text-center">
        <div id="loading" class="w-100 text-center py-5">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const container = document.getElementById('bookingsContainer');
    const loading = document.getElementById('loading');
    const userEmail = "{{ Auth::user()->email ?? '' }}";

    try {
        const res = await fetch(`/api/bookings?email=${encodeURIComponent(userEmail)}`, {
            headers: { "Accept": "application/json" },
            credentials: "same-origin"
        });
        const data = await res.json();
        loading.remove();

        if (!data.success || !data.data.length) {
            container.innerHTML = `<div class="alert alert-info text-center w-100">
                <i class="bi bi-info-circle"></i> You have no bookings yet.
            </div>`;
            return;
        }

        // Render each booking and its items
        container.innerHTML = data.data.map(booking => {
            return booking.items.map(item => `
                <div class="col-md-6 col-lg-4 booking-card" data-item-id="${item.id}">
                    <div class="card shadow-sm border-0 h-100">
                        <img src="${item.image_url || 'https://via.placeholder.com/400x250'}"
                             class="card-img-top" alt="${item.title || 'Property'}" style="height:200px;object-fit:cover;">
                        <div class="card-body text-start">
                            <h5 class="card-title fw-bold text-dark">${item.title}</h5>
                            <p class="text-muted small mb-2"><i class="bi bi-geo-alt"></i> ${item.city || 'N/A'}</p>
                            <ul class="list-unstyled small">
                                <li><strong>Rent:</strong> ₹${item.monthly_rent}/month</li>
                                <li><strong>Payment:</strong> ${booking.payment_method}</li>
                                <li><strong>Status:</strong> <span class="badge bg-success">Booked</span></li>
                            </ul>
                            <hr>
                            <p class="mb-1"><strong>Name:</strong> ${booking.fullname}</p>
                            <p class="mb-0"><strong>Email:</strong> ${booking.email}</p>
                        </div>
                        <div class="card-footer bg-light text-center d-flex flex-column gap-2">
                            <small class="text-muted">Booked on ${new Date(booking.created_at).toLocaleString()}</small>
                            <button class="btn btn-danger btn-sm mt-2 cancel-btn">
                                <i class="bi bi-x-circle"></i> Cancel Booking
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }).join('');

        // Cancel button logic
        container.querySelectorAll('.cancel-btn').forEach(btn => {
            btn.addEventListener('click', async e => {
                const card = e.currentTarget.closest('.booking-card');
                const itemId = card.dataset.itemId;

                const result = await Swal.fire({
                    title: "Cancel Booking?",
                    text: "A fine of ₹50 will be applied for cancellation.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, cancel it"
                });
                if (!result.isConfirmed) return;

                try {
                    const res = await fetch(`/api/bookings/item/${itemId}`, {
                        method: "DELETE",
                        headers: {
                            "Accept": "application/json",
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        credentials: "same-origin"
                    });
                    const data = await res.json();
                    if (!res.ok || !data.success) {
                        Swal.fire("Error", data.message || "Cancellation failed.", "error");
                        return;
                    }

                    Swal.fire("Cancelled!", data.message, "success");
                    card.remove();

                    if (!container.querySelectorAll('.booking-card').length) {
                        container.innerHTML = `<div class="alert alert-info text-center w-100">
                            <i class="bi bi-info-circle"></i> You have no bookings yet.
                        </div>`;
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire("Error", "Something went wrong.", "error");
                }
            });
        });

    } catch (err) {
        loading.remove();
        container.innerHTML = `<div class="alert alert-danger text-center w-100">Failed to load bookings.</div>`;
        console.error(err);
    }
});
</script>

<style>
.card:hover { transform: translateY(-4px); transition: 0.2s ease-in-out; }
.booking-card .card { border-radius:1rem; overflow:hidden; transition: transform 0.3s, box-shadow 0.3s; }
.booking-card .card:hover { transform: translateY(-6px); box-shadow:0 6px 18px rgba(0,0,0,0.1); }
.booking-card img { width:100%; height:200px; object-fit:cover; }
.card-body h5 { font-size:1.05rem; line-height:1.3; }
.card-body ul li { font-size:0.9rem; }
.card-footer small { font-size:0.85rem; }

@media (max-width:767px){
    #bookingsContainer { display:flex; flex-direction:column; align-items:center; }
    .booking-card { width:100%; max-width:380px; }
    .booking-card img { height:180px; }
    .card-body h5 { font-size:1rem; }
    .card-body ul li { font-size:0.85rem; }
    .cancel-btn { font-size:0.85rem; padding:0.4rem 0.7rem; }
}
</style>
@endsection
