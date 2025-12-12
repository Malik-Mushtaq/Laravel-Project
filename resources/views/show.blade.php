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

/* Property Card */
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

/* Buttons */
.button-row {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 20px;
}
.button-row .back-btn {
    margin-left: auto;
}
.book-btn, .back-btn {
    display: inline-block;
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

/* Review Section */
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

/* Review Form Modal */
#review-top-form {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}
#review-top-form.show {
    display: flex;
}
#review-top-form .review-content {
    background: var(--card-bg);
    padding: 40px 35px;
    border-radius: var(--radius);
    width: 95%;
    max-width: 600px;
    position: relative;
    box-shadow: var(--shadow);
    display: flex;
    flex-direction: column;
    gap: 15px;
}

/* Inputs styled like Add Property form */
#review-top-form input,
#review-top-form textarea,
#review-top-form select {
    width: 100%;
    padding: 14px 18px;
    border-radius: var(--radius);
    border: 1px solid #ddd;
    font-size: 1rem;
    outline: none;
    transition: border 0.3s ease, box-shadow 0.3s ease;
}
#review-top-form input:focus,
#review-top-form textarea:focus,
#review-top-form select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 10px rgba(0,166,81,0.2);
}
#review-top-form textarea { resize: vertical; }

/* Buttons */
#review-top-form .review-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 10px;
}
#submit-review-top {
    background: var(--primary);
    color: #fff;
    font-weight: 700;
    padding: 12px 25px;
    border-radius: var(--radius);
    border: none;
    cursor: pointer;
    font-size: 1rem;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}
#submit-review-top:hover {
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 8px 20px rgba(0,0,0,0.25);
}
#review-top-form .close-review-top {
    background: #eee;
    color: #333;
    padding: 12px 25px;
    border-radius: var(--radius);
    font-weight: 600;
    transition: all 0.3s ease;
}
#review-top-form .close-review-top:hover {
    background: #ddd;
    transform: translateY(-2px);
}

#review-top-form h5 {
    text-align: center;
    font-weight: 700;
    margin-bottom: 25px;
    color: var(--text-dark);
}:root {
    --primary: #00a651;
    --card-bg: #fff;
    --text-dark: #222;
    --text-muted: #6b7280;
    --radius: 14px;
    --shadow: 0 6px 25px rgba(0,0,0,0.08);
}
body { background-color: #f5f7fa; font-family: 'Inter', sans-serif; }
/* ... rest of your CSS ... */
</style>

<div class="container">
    <!-- Review Form Modal -->
    <div id="review-top-form">
        <div class="review-content">
            <h5>Add Your Review</h5>
            <input type="text" id="reviewer-name" placeholder="Your Name">
            <select id="review-rating">
                <option value="">Select Rating</option>
                <option value="1">★ 1</option>
                <option value="2">★★ 2</option>
                <option value="3">★★★ 3</option>
                <option value="4">★★★★ 4</option>
                <option value="5">★★★★★ 5</option>
            </select>
            <textarea id="review-text" rows="4" placeholder="Your review"></textarea>
            <div class="review-actions">
                <button id="submit-review-top">Submit Review</button>
                <button class="close-review-top">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Property Card -->
    <div class="property-card">
        <div class="property-image">
            <img src="{{ asset($property->image_path) }}" alt="{{ $property->title }}">
        </div>
        <div class="property-body">
            <h2 class="property-title">{{ $property->title }}</h2>
            <p class="property-details">
                Type: {{ $property->property_type }} • {{ $property->bedrooms }} Bed • {{ $property->bathrooms }} Bath • {{ $property->city }}
            </p>
            <p class="property-price">${{ number_format($property->monthly_rent) }}/month</p>
            <p class="property-details">{{ $property->description }}</p>

            <div class="button-row">
                <button class="book-btn" id="book-now">Book Now</button>
                <button class="book-btn" id="add-review-btn">Add Review</button>
                <a href="/properties" class="back-btn">← Back to Listings</a>
            </div>

            <div class="review-section">
                <h5 class="fw-bold mb-3">Reviews</h5>
                <div id="reviews-container">
                    <p class="text-muted">Loading reviews...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="toast">Property added to cart!</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const toast = document.getElementById('toast');
    const reviewsContainer = document.getElementById('reviews-container');
    const propertyId = {{ $property->id }};

    // Fetch and display reviews
    function loadReviews() {
        fetch(`/api/reviews/${propertyId}`)
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    reviewsContainer.innerHTML = '';
                    if(data.reviews.length === 0){
                        reviewsContainer.innerHTML = '<p class="text-muted">No reviews yet.</p>';
                        return;
                    }
                    data.reviews.forEach(review => {
                        const reviewDiv = document.createElement('div');
                        reviewDiv.classList.add('review');
                        reviewDiv.innerHTML = `
                            <div class="review-user">${review.name}</div>
                            <div class="review-rating">${'★'.repeat(review.rating)}${'☆'.repeat(5 - review.rating)}</div>
                            <div class="review-text">${review.text}</div>
                        `;
                        reviewsContainer.appendChild(reviewDiv);
                    });
                } else {
                    reviewsContainer.innerHTML = '<p class="text-muted">Failed to load reviews.</p>';
                }
            })
            .catch(err => {
                console.error(err);
                reviewsContainer.innerHTML = '<p class="text-muted">Error loading reviews.</p>';
            });
    }

    loadReviews(); // Initial fetch

    // Book Now
    document.getElementById('book-now').addEventListener('click', () => {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart.push({ 
            id: propertyId,
            title: '{{ $property->title }}',
            monthly_rent: {{ $property->monthly_rent }},
            image_url: '{{ asset($property->image_path) }}',
            city: '{{ $property->city }}',
            property_type: '{{ $property->property_type }}',
            bedrooms: {{ $property->bedrooms }},
            bathrooms: {{ $property->bathrooms }}
        });
        localStorage.setItem('cart', JSON.stringify(cart));
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 2000);
    });

    // Review Modal
    const addReviewBtn = document.getElementById('add-review-btn');
    const reviewForm = document.getElementById('review-top-form');
    const closeBtn = reviewForm.querySelector('.close-review-top');
    const submitBtn = document.getElementById('submit-review-top');

    addReviewBtn.addEventListener('click', () => reviewForm.classList.add('show'));
    closeBtn.addEventListener('click', () => reviewForm.classList.remove('show'));

    submitBtn.addEventListener('click', () => {
        const name = document.getElementById('reviewer-name').value.trim();
        const rating = document.getElementById('review-rating').value;
        const text = document.getElementById('review-text').value.trim();

        if(!name || !rating || !text){
            alert('Please fill all fields.');
            return;
        }

        fetch('/api/reviews', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                property_id: propertyId,
                name: name,
                rating: rating,
                text: text
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                // Clear inputs
                document.getElementById('reviewer-name').value = '';
                document.getElementById('review-rating').value = '';
                document.getElementById('review-text').value = '';

                reviewForm.classList.remove('show');
                loadReviews(); // reload reviews dynamically
            } else {
                alert(data.message || 'Something went wrong.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred.');
        });
    });
});
</script>
@endsection
