@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body { background: #f8f9fa; font-family: 'Inter', sans-serif; padding-top: 80px; }
.checkout-container { max-width: 1200px; margin: 36px auto; padding: 20px; }
.checkout-header { text-align: center; margin-bottom: 20px; }
.cards-row { display: flex; flex-wrap: wrap; gap: 18px; margin-bottom: 22px; align-items: stretch; }
.property-card { background: #fff; border-radius: 12px; box-shadow: 0 6px 18px rgba(0,0,0,0.06); display: flex; gap: 14px; padding: 14px; align-items: center; transition: transform .18s ease, box-shadow .18s ease; }
.property-card:hover { transform: translateY(-6px); box-shadow: 0 10px 28px rgba(0,0,0,0.09); }
.property-card img { width: 140px; height: 100px; border-radius: 8px; object-fit: cover; }
.property-info { display: flex; flex-direction: column; gap: 6px; padding: 6px 0; flex: 1; }
.property-title { font-weight: 700; color: #222; font-size: 1rem; }
.property-meta { color: #666; font-size: .88rem; }
.property-price { color: #1a73e8; font-weight: 800; margin-top: 6px; }
.summary-row { display: flex; justify-content: flex-end; margin: 10px 0 22px 0; font-weight: 700; color: #222; }
.payment-form { background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 6px 18px rgba(0,0,0,0.06); }
.payment-form h4 { margin-bottom: 14px; font-weight: 700; }
.payment-form label { font-weight: 600; margin-bottom: 6px; display:block; }
.payment-form input, .payment-form select { width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #dcdcdc; margin-bottom: 12px; font-size: .95rem; }
.card-small-row { display:flex; gap:10px; }
.card-small-row input { flex:1; }
.pay-btn { background: #1a73e8; color: #fff; border: none; padding: 12px 16px; border-radius: 8px; font-weight: 700; width: 100%; }
.pay-btn:hover { background: #155fb3; cursor: pointer; }
@media (max-width: 767px) {
    .property-card { flex-direction: column; align-items: stretch; }
    .property-card img { width: 100%; height: 150px; }
}
</style>

<div class="container checkout-container">
    <div class="checkout-header">
        <h2 class="fw-bold">💳 Checkout</h2>
        <p class="text-muted">Review your selected properties then complete the payment.</p>
    </div>

    <div id="cards-row" class="cards-row">
        <p class="text-center text-muted w-100">Loading selected properties...</p>
    </div>

    <div id="summary-row" class="summary-row" style="display:none;">
        <div id="summary-total">Total: $0</div>
    </div>

    <div id="payment-section" class="payment-form">
        <h4>Payment Information</h4>
        <form id="payment-form">
            <label for="fullname">Full name</label>
            <input id="fullname" type="text" placeholder="John Doe" required>

            <label for="email">Email</label>
            <input id="email" type="email" placeholder="you@example.com" required>

            <label for="payment-method">Payment method</label>
            <select id="payment-method" required>
                <option value="">Select payment method</option>
                <option value="card">Credit / Debit Card</option>
                <option value="cash">Cash on Arrival</option>
            </select>

            <div id="card-fields" style="display:none; margin-top:6px;">
                <label for="card-number">Card number</label>
                <input id="card-number" type="text" placeholder="1234 5678 9012 3456" maxlength="19">

                <div class="card-small-row">
                    <div style="flex:1;">
                        <label for="expiry">Expiry (MM/YY)</label>
                        <input id="expiry" type="text" placeholder="MM/YY" maxlength="5">
                    </div>
                    <div style="width:110px;">
                        <label for="cvv">CVV</label>
                        <input id="cvv" type="text" placeholder="123" maxlength="4">
                    </div>
                </div>
            </div>

            <button class="pay-btn" type="submit">Complete Booking</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const cardsRow = document.getElementById('cards-row');
    const summaryRow = document.getElementById('summary-row');
    const summaryTotal = document.getElementById('summary-total');
    const paymentMethod = document.getElementById('payment-method');
    const cardFields = document.getElementById('card-fields');
    const paymentForm = document.getElementById('payment-form');

    const cartItems = JSON.parse(localStorage.getItem('cart')) || [];

    if (!cartItems.length) {
        cardsRow.innerHTML = `<p class="text-center text-muted w-100">Your cart is empty. <a href="/" class="text-primary">Browse properties</a></p>`;
        document.getElementById('payment-section').style.display = 'none';
        return;
    }

    const total = cartItems.reduce((sum, p) => sum + (p.monthly_rent || 0), 0);

    // Render property cards
    cardsRow.innerHTML = cartItems.map(p => `
        <div class="property-card">
            <img src="${p.image_url}" alt="${escapeHtml(p.title)}">
            <div class="property-info">
                <div class="property-title">${escapeHtml(p.title)}</div>
                <div class="property-meta">
                    ${escapeHtml(p.city)} • ${escapeHtml(p.property_type)}
                    ${p.bedrooms ? '• ' + p.bedrooms + ' Bed' : ''} 
                    ${p.bathrooms ? '• ' + p.bathrooms + ' Bath' : ''}
                </div>
                <div class="property-price">$${Number(p.monthly_rent).toLocaleString()}/mo</div>
            </div>
        </div>
    `).join('');

    summaryTotal.textContent = `Total: $${Number(total).toLocaleString()}/mo`;
    summaryRow.style.display = 'flex';

    paymentMethod.addEventListener('change', e => {
        cardFields.style.display = e.target.value === 'card' ? 'block' : 'none';
    });

    paymentForm.addEventListener('submit', async ev => {
        ev.preventDefault();
        if (!cartItems.length) return Swal.fire({ icon: 'warning', title: 'Your cart is empty!' });

        const method = paymentMethod.value;
        const fullname = document.getElementById('fullname').value.trim();
        const email = document.getElementById('email').value.trim();

        if (!method) return Swal.fire({ icon: 'warning', title: 'Select a payment method!' });

        if (method === 'card') {
            const card = document.getElementById('card-number').value.trim();
            const expiry = document.getElementById('expiry').value.trim();
            const cvv = document.getElementById('cvv').value.trim();
            if (!card || !expiry || !cvv) return Swal.fire({ icon: 'warning', title: 'Incomplete card details!' });
        }

        Swal.fire({ title: 'Processing booking…', text: 'Please wait while we finalize your booking.', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        try {
            const response = await fetch('{{ route("bookings.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    user: { fullname, email },
                    payment_method: method,
                    items: cartItems.map(p => {
                        const imageUrl = decodeURIComponent(p.image_url || '');
                        console.log('IMAGE_URL being sent:', imageUrl); // ✅ log image_url
                        return {
                            title: p.title,
                            city: p.city || null,
                            property_type: p.property_type || null,
                            bedrooms: p.bedrooms || null,
                            bathrooms: p.bathrooms || null,
                            monthly_rent: p.monthly_rent,
                            image_url: imageUrl
                        };
                    })
                })
            });

            const data = await response.json();
            console.log('Response from server:', data); // ✅ log response

            if (data.success) {
                localStorage.removeItem('cart');
                Swal.fire({ icon: 'success', title: 'Booking confirmed!', text: 'Your booking has been saved successfully.' })
                    .then(() => window.location.href = '/properties');
            } else {
                throw new Error(data.message || 'Booking failed');
            }
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Booking failed', text: err.message });
        }
    });

    function escapeHtml(str) {
        if (!str && str !== 0) return '';
        return String(str).replace(/&/g, '&amp;')
                          .replace(/"/g, '&quot;')
                          .replace(/'/g, '&#39;')
                          .replace(/</g, '&lt;')
                          .replace(/>/g, '&gt;');
    }
});
</script>
@endsection
