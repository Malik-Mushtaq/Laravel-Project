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
        @if($bookings->isEmpty())
            <div class="alert alert-info text-center w-100">
                <i class="bi bi-info-circle"></i> You have no bookings yet.
            </div>
        @else
            @foreach($bookings as $booking)
                @php $property = $booking->items->first(); @endphp
                <div class="col-md-6 col-lg-4 booking-card">
                    <div class="card shadow-sm border-0 h-100">
                       <img src="{{ $property && $property->image_url ? asset($property->image_url) : 'https://via.placeholder.com/400x250' }}" 
                             class="card-img-top" 
                             alt="{{ $property ? $property->title : 'Property' }}"
                             style="height: 200px; object-fit: cover;">

                        <div class="card-body text-start">
                            <h5 class="card-title fw-bold text-dark">{{ $property ? $property->title : 'Unknown Property' }}</h5>
                            <p class="text-muted small mb-2"><i class="bi bi-geo-alt"></i> {{ $property ? $property->city : 'N/A' }}</p>
                            <ul class="list-unstyled small">
                                <li><strong>Rent:</strong> {{ $property ? '₹'.$property->monthly_rent.'/month' : 'N/A' }}</li>
                                <li><strong>Payment:</strong> {{ $booking->payment_method }}</li>
                                <li><strong>Status:</strong> <span class="badge bg-success">Booked</span></li>
                            </ul>
                            <hr>
                            <p class="mb-1"><strong>Name:</strong> {{ $booking->fullname }}</p>
                            <p class="mb-0"><strong>Email:</strong> {{ $booking->email }}</p>
                        </div>

                        <div class="card-footer bg-light text-center d-flex flex-column gap-2">
                            <small class="text-muted">Booked on {{ $booking->created_at->format('M d, Y h:i A') }}</small>
                            <button class="btn btn-danger btn-sm mt-2 cancel-btn" data-id="{{ $booking->id }}">
                                <i class="bi bi-x-circle"></i> Cancel Booking
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.cancel-btn').forEach(btn => {
        btn.addEventListener('click', async e => {
            const bookingId = e.currentTarget.dataset.id;

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
                const res = await fetch(`/bookings/${bookingId}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json",
                        "Content-Type": "application/json"
                    },
                });

                const data = await res.json();

                if (!res.ok) {
                    Swal.fire("Error", data.message || "Booking cancellation failed.", "error");
                    return;
                }

                Swal.fire("Cancelled!", data.message, "success");

                const bookingCard = e.currentTarget.closest(".booking-card");
                if (bookingCard) bookingCard.remove();

            } catch (err) {
                 Swal.fire({ icon: 'success', title: 'Successfully Deleted!', text: 'Your booking has been Deleted successfully.' })
                    .then(() => location.reload());
            }
        });
    });
});
</script>

<style>
.card:hover {
    transform: translateY(-4px);
    transition: 0.2s ease-in-out;
}
.booking-card .card {
  border-radius: 1rem;
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.booking-card .card:hover {
  transform: translateY(-6px);
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
}
.booking-card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
}
.card-body h5 {
  font-size: 1.05rem;
  line-height: 1.3;
}
.card-body ul li {
  font-size: 0.9rem;
}
.card-footer small {
  font-size: 0.85rem;
}
@media (min-width: 768px) and (max-width: 1024px) {
  .booking-card { flex: 0 0 50%; max-width: 50%; }
  .booking-card img { height: 220px; }
  .card-body h5 { font-size: 1.1rem; }
  .cancel-btn { font-size: 0.9rem; padding: 0.45rem 0.8rem; }
  .container.py-5 { padding: 2rem 1.5rem !important; }
}
@media (max-width: 767px) {
  #bookingsContainer { display: flex; flex-direction: column; align-items: center; }
  .booking-card { width: 100%; max-width: 380px; }
  .booking-card img { height: 180px; }
  .card-body { padding: 1rem 1.2rem; }
  .card-body h5 { font-size: 1rem; }
  .card-body ul li { font-size: 0.85rem; }
  .cancel-btn { font-size: 0.85rem; padding: 0.4rem 0.7rem; }
  .d-flex.justify-content-between { flex-direction: column; gap: 1rem; text-align: center; }
  .d-flex.justify-content-between h2 { font-size: 1.4rem; }
  .btn-outline-secondary { width: 100%; }
}
</style>
@endsection
