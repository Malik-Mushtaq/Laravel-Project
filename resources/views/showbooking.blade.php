@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-success">
            <i class="bi bi-journal-check"></i> My Bookings
        </h2>
        <a href="/properties" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle"></i> Back to Product List
        </a>
    </div>

    <div id="bookingsContainer" class="row g-4 text-center">
        <div class="text-muted py-5">
            <div class="spinner-border text-success" role="status"></div>
            <p class="mt-3">Loading your bookings...</p>
        </div>
    </div>
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// propert array of object
const properties = [
    {
        "property_id": 10004,
        "title": "Suburban Family Home with Spacious Garden",
        "property_type": "House",
        "monthly_rent": 1200,
        "bedrooms": 3,
        "bathrooms": 2,
        "city": "Greenwood",
        "image_url": "https://images.pexels.com/photos/259588/pexels-photo-259588.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=600"
    },
    {
        "property_id": 10005,
        "title": "Modern Apartment with Balcony",
        "property_type": "Apartment",
        "monthly_rent": 4500,
        "bedrooms": 4,
        "bathrooms": 3,
        "city": "Rivertown",
        "image_url": "https://images.pexels.com/photos/439391/pexels-photo-439391.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=600"
    },
    {
        "property_id": 10009,
        "title": "Urban Studio Apartment",
        "property_type": "Studio",
        "monthly_rent": 1950,
        "bedrooms": 1,
        "bathrooms": 1,
        "city": "Springfield",
        "image_url": "https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=600"
    }
];

// booking array of object
const bookings = [
    {
        "id": "68f615d6aead6",
        "property_id": "10005",
        "name": "Malik",
        "phone": "03214568120",
        "date": "2026-06-05",
        "rent": "4,500",
        "payment_method": "cash",
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
        "created_at": "2025-10-22 06:54:55"
    }
];

// 👤 Logged in user from localStorage
const username = localStorage.getItem("username");
const container = document.getElementById("bookingsContainer");

// showing this if user is not logged in
setTimeout(() => {
    if (!username) {
        container.innerHTML = `
            <div class="alert alert-warning text-center w-100">
                <i class="bi bi-exclamation-circle"></i> Please login to view your bookings.
            </div>`;
        return;
    }

    const userBookings = bookings.filter(b => b.name.toLowerCase() === username.toLowerCase());

    if (userBookings.length === 0) {
        container.innerHTML = `
            <div class="alert alert-info text-center w-100">
                <i class="bi bi-info-circle"></i> You have no bookings yet.
            </div>`;
        return;
    }

    container.innerHTML = "";
    userBookings.forEach(booking => {
        const property = properties.find(p => p.property_id == booking.property_id);
        const createdAt = new Date(booking.created_at).toLocaleString("en-US", {
            dateStyle: "medium",
            timeStyle: "short"
        });

        container.innerHTML += `
            <div class="col-md-6 col-lg-4 booking-card">
                <div class="card shadow-sm border-0 h-100">
                    <img src="${property?.image_url || 'https://via.placeholder.com/400x250'}" 
                         class="card-img-top" 
                         alt="${property?.title || 'Property'}"
                         style="height: 200px; object-fit: cover;">

                    <div class="card-body text-start">
                        <h5 class="card-title fw-bold text-dark">
                            ${property?.title || 'Unknown Property'}
                        </h5>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-geo-alt"></i> ${property?.city || 'N/A'}
                        </p>
                        <ul class="list-unstyled small">
                            <li><strong>Type:</strong> ${property?.property_type || 'N/A'}</li>
                            <li><strong>Rent:</strong> ₹${booking.rent}/month</li>
                            <li><strong>Date:</strong> ${booking.date}</li>
                            <li><strong>Payment:</strong> ${booking.payment_method}</li>
                            <li><strong>Status:</strong> 
                                <span class="badge bg-success">Booked</span>
                            </li>
                        </ul>
                        <hr>
                        <p class="mb-1"><strong>Name:</strong> ${booking.name}</p>
                        <p class="mb-0"><strong>Phone:</strong> ${booking.phone}</p>
                    </div>

                    <div class="card-footer bg-light text-center d-flex flex-column gap-2">
                        <small class="text-muted">
                            Booked on ${createdAt}
                        </small>
                        <button class="btn btn-danger btn-sm mt-2 cancel-btn" data-id="${booking.id}">
                            <i class="bi bi-x-circle"></i> Cancel Booking
                        </button>
                    </div>
                </div>
            </div>
        `;
    });

    //  handle cancel booking with sweetalert
    document.querySelectorAll('.cancel-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            const bookingId = e.target.closest('button').dataset.id;
            Swal.fire({
                title: "Cancel Booking?",
                text: "A fine of ₹50 will be applied for cancellation.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, cancel it"
            }).then(result => {
                if (result.isConfirmed) {
                    // canceled sweetalert
                    Swal.fire({
                        title: "Cancelled!",
                        text: "Your booking has been cancelled.",
                        icon: "success",
                        confirmButtonColor: "#198754"
                    });
                    e.target.closest('.booking-card').remove();
                }
            });
        });
    });

}, 500);
</script>

<style>
.card:hover {
    transform: translateY(-4px);
    transition: 0.2s ease-in-out;
}/* === My Bookings Responsive Design === */

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

/* === Tablet (768px–1024px) === */
@media (min-width: 768px) and (max-width: 1024px) {
  .booking-card {
    flex: 0 0 50%;
    max-width: 50%;
  }

  .booking-card img {
    height: 220px;
  }

  .card-body h5 {
    font-size: 1.1rem;
  }

  .cancel-btn {
    font-size: 0.9rem;
    padding: 0.45rem 0.8rem;
  }

  .container.py-5 {
    padding: 2rem 1.5rem !important;
  }
}

/* === Mobile (<768px) === */
@media (max-width: 767px) {
  #bookingsContainer {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .booking-card {
    width: 100%;
    max-width: 380px;
  }

  .booking-card img {
    height: 180px;
  }

  .card-body {
    padding: 1rem 1.2rem;
  }

  .card-body h5 {
    font-size: 1rem;
  }

  .card-body ul li {
    font-size: 0.85rem;
  }

  .cancel-btn {
    font-size: 0.85rem;
    padding: 0.4rem 0.7rem;
  }

  .d-flex.justify-content-between {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
  }

  .d-flex.justify-content-between h2 {
    font-size: 1.4rem;
  }

  .btn-outline-secondary {
    width: 100%;
  }
}

</style>
@endsection
