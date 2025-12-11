@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-success fw-bold">
        <i class="bi bi-journal-check"></i> All Bookings
    </h2>

    @if($bookings->isEmpty())
        <div class="alert alert-secondary text-center">No bookings found.</div>
    @else

    <!-- Search Bar -->
    <div class="mb-4 d-flex justify-content-center">
        <input 
            type="text" 
            id="booking-search" 
            class="form-control rounded" 
            placeholder="Search bookings..." 
            style="width: 400px; max-width: 90%;"
        >
    </div>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle text-center">
            <thead class="table-success">
                <tr>
                    <th>#</th>
                    <th>Property</th>
                    <th>Name</th> <!-- Added -->
                    <th>Email</th>     <!-- Added -->
                    <th>Booking Date</th>
                    <th>Rent</th>
                    <th>Payment Method</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="booking-table-body">
                @foreach($bookings as $booking)
                <tr id="booking-row-{{ $booking->id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @foreach($booking->items as $item)
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <img src="{{ $item->image_url }}" alt="{{ $item->title }}" style="width:60px; height:60px; border-radius:8px; object-fit:cover;">
                            <div class="text-start">
                                <div class="fw-semibold">{{ $item->title }}</div>
                                <div class="text-muted small">{{ $item->city }} • {{ $item->property_type }} • {{ $item->bedrooms ?? 0 }} Bed • {{ $item->bathrooms ?? 0 }} Bath</div>
                                <div class="text-success fw-bold">${{ number_format($item->monthly_rent, 2) }}/mo</div>
                            </div>
                        </div>
                        @endforeach
                    </td>
                    <td>{{ $booking->fullname }}</td> <!-- Display booking name -->
                    <td>{{ $booking->email }}</td>    <!-- Display booking email -->
                    <td>{{ $booking->created_at->format('d M Y, H:i') }}</td>
                    <td>${{ number_format($booking->total, 2) }}</td>
                    <td>
                        <span class="badge bg-{{ $booking->payment_method === 'cash' ? 'warning text-dark' : 'info text-dark' }}">
                            {{ ucfirst($booking->payment_method) }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-danger btn-sm cancel-btn" data-id="{{ $booking->id }}">
                            <i class="bi bi-x-circle"></i> Cancel
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Booking cancel functionality
document.addEventListener("click", function(e){
    const btn = e.target.closest(".cancel-btn");
    if(!btn) return;

    const bookingId = btn.dataset.id;
    const row = document.getElementById(`booking-row-${bookingId}`);

    Swal.fire({
        title: 'Cancel this booking?',
        text: "Are you sure? This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#00a651',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, cancel it',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if(result.isConfirmed){
            fetch(`/admin/bookings/${bookingId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    row.remove();
                    Swal.fire('Cancelled!', data.message, 'success');
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            });
        }
    });
});

// Booking search functionality
const searchInput = document.getElementById('booking-search');
searchInput.addEventListener('input', function() {
    const query = this.value.toLowerCase();
    const rows = document.querySelectorAll('#booking-table-body tr');

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
    });
});
</script>

<style>
.table-hover tbody tr:hover { background-color: #f0f8ff; transition: 0.3s; }
.table th, .table td { vertical-align: middle; }
.cancel-btn:hover { transform: scale(1.05); transition: all 0.2s ease; }
</style>
@endsection
