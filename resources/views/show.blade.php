@extends('layouts.app')

@section('content')
<style>
:root {
    --primary: #00a651;
    --card-bg: #fff;
    --text-dark: #222;
    --text-muted: #6b7280;
    --radius: 14px;
    --shadow: 0 6px 25px rgba(0,0,0,0.08);
}

body { background-color: #f5f7fa; font-family: 'Inter', sans-serif; }

.property-card {
    max-width: 800px;
    margin: 60px auto;
    border-radius: var(--radius);
    background: var(--card-bg);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.property-image img { width: 100%; height: 400px; object-fit: cover; }
.property-body { padding: 20px; }
.property-title { font-size: 1.6rem; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; }
.property-details { font-size: 0.95rem; color: var(--text-muted); line-height: 1.6; }
.property-price { font-size: 1.3rem; font-weight: 700; color: var(--primary); margin-top: 10px; }
.book-btn, .back-btn {
    display: inline-block;
    margin-top: 20px;
    background: var(--primary);
    color: #fff;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    transition: 0.3s ease;
    cursor: pointer;
    text-decoration: none;
}
.book-btn:hover, .back-btn:hover { background: #007a42; }

.review-section { margin-top: 30px; }
.review { background: #f9fafb; border-radius: 12px; padding: 14px 18px; margin-bottom: 15px; }
.review-user { font-weight: 600; color: var(--text-dark); }
.review-rating { color: #f4b400; font-size: 1rem; }
.review-text { color: var(--text-muted); margin-top: 5px; }

/* Toast */
#toast {
    position: fixed;
    top: 20px;
    right: 20px;
    background: var(--primary);
    color: #fff;
    padding: 12px 18px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease, transform 0.3s ease;
    transform: translateY(-20px);
    z-index: 9999;
}
#toast.show {
    opacity: 1;
    pointer-events: auto;
    transform: translateY(0);
}
</style>

<div class="container">
    <div class="property-card">
        <div class="property-image">
            <img src="{{ $property->image_url }}" alt="{{ $property->title }}">
        </div>
        <div class="property-body">
            <h2 class="property-title">{{ $property->title }}</h2>
            <p class="property-details">
                Type: {{ $property->property_type }} • {{ $property->bedrooms }} Bed • {{ $property->bathrooms }} Bath • {{ $property->city }}
            </p>
            <p class="property-price">${{ number_format($property->monthly_rent) }}/month</p>
            <p class="property-details">{{ $property->description }}</p>

            <button class="book-btn" id="book-now">Book Now</button>
            <a href="/properties" class="back-btn">← Back to Listings</a>

            <div class="review-section">
                <h5 class="fw-bold mb-3">Reviews</h5>
                @forelse($property->reviews as $review)
                    <div class="review">
                        <div class="review-user">{{ $review->name }}</div>
                        <div class="review-rating">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</div>
                        <div class="review-text">{{ $review->text }}</div>
                    </div>
                @empty
                    <p class="text-muted">No reviews yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast">Property added to cart!</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const toast = document.getElementById('toast');

    document.getElementById('book-now').addEventListener('click', () => {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart.push({ 
    id: {{ $property->id }},
    title: '{{ $property->title }}',
    monthly_rent: {{ $property->monthly_rent }},
    image_url: '{{ $property->image_url }}',
    city: '{{ $property->city }}',
    property_type: '{{ $property->property_type }}',
    bedrooms: {{ $property->bedrooms }},
    bathrooms: {{ $property->bathrooms }}
});
        localStorage.setItem('cart', JSON.stringify(cart));

        // Show toast
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 2000);
    });
});
</script>
@endsection
