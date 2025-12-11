@extends('layouts.app')

@section('title', 'Your Cart')

@section('content')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body {
    background: #f8f9fa;
    padding-top: 90px; /* Fix navbar overlap */
}
.cart-container {
    max-width: 1000px;
    margin: 40px auto;
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
}
.cart-item {
    display: flex;
    gap: 16px;
    border-bottom: 1px solid #eee;
    padding: 16px 0;
}
.cart-item img {
    width: 150px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
}
.cart-details {
    flex: 1;
}
.remove-btn {
    border: none;
    background: none;
    color: red;
    font-weight: 600;
    cursor: pointer;
}
.reviews {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
    margin-top: 10px;
}
.review {
    font-size: 0.9rem;
    color: #444;
    margin-bottom: 8px;
}
.checkout-btn {
    display: block;
    margin: 30px auto 0;
    padding: 12px 28px;
    font-size: 1rem;
    border-radius: 8px;
}
@media (max-width: 768px) {
    .cart-item {
        flex-direction: column;
        align-items: center;
    }
    .cart-item img {
        width: 100%;
        height: 180px;
    }
}
</style>

<div class="container">
    <h2 class="text-center mt-5 mb-4 fw-bold">🛍️ Your Booked Properties</h2>
    <div id="cart-container" class="cart-container">
        <p class="text-center text-muted">Loading your cart...</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const reviewsData = [
        "⭐️⭐️⭐️⭐️⭐️ Excellent service and clean property!",
        "⭐️⭐️⭐️⭐️ Great location and amenities.",
        "⭐️⭐️⭐️⭐️⭐️ Would definitely book again!",
        "⭐️⭐️⭐️ Comfortable stay and responsive host."
    ];

    const container = document.getElementById('cart-container');
    const cart = JSON.parse(localStorage.getItem('cart')) || [];

    if (!cart.length) {
        container.innerHTML = `<p class="text-center text-muted">Your cart is empty. <a href="/properties" class="text-primary">Browse properties</a></p>`;
        return;
    }

    // Render cart items
    container.innerHTML = `
        ${cart.map((p, i) => `
            <div class="cart-item">
                <img src="${p.image_url}" alt="${p.title}">
                <div class="cart-details">
                    <h5>${p.title}</h5>
                    <p class="text-muted">${p.city || 'City Unknown'} • ${p.property_type || 'Type Unknown'}</p>
                    <p><strong>$${p.monthly_rent.toLocaleString()}/mo</strong></p>
                    <div class="reviews">
                        <strong>Feedback:</strong>
                        <div class="review">${reviewsData[i % reviewsData.length]}</div>
                    </div>
                </div>
                <button class="remove-btn" data-id="${p.id}">Remove ✖️</button>
            </div>
        `).join('')}
        <div class="text-center">
            <a href="/checkout" class="btn btn-primary checkout-btn">Proceed to Checkout</a>
        </div>
    `;

    // Handle remove button
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = parseInt(btn.dataset.id);
            Swal.fire({
                title: "Remove this property?",
                text: "Are you sure you want to delete this from your cart?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, remove it!",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    const newCart = cart.filter(p => p.id !== id);
                    localStorage.setItem('cart', JSON.stringify(newCart));

                    Swal.fire({
                        title: "Removed!",
                        text: "Property removed from your cart.",
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false
                    });

                    setTimeout(() => window.location.reload(), 1500);
                }
            });
        });
    });
});
</script>
@endsection
