@extends('layouts.app')

@section('content')
<style>
:root {
    --primary: #00a651;
    --secondary: #007a42;
    --accent: #009e60;
    --bg: #f5f7fa;
    --card-bg: #ffffff;
    --text-dark: #222;
    --text-muted: #6b7280;
    --radius: 14px;
    --shadow: 0 6px 25px rgba(0,0,0,0.08);
}

body { background-color: var(--bg); font-family: 'Inter', sans-serif; }

/* HERO HEADER */
.elegant-header {
    position: relative;
    background: linear-gradient(135deg, #004225 0%, #00a651 100%);
    border-radius: 0 0 80px 80px;
    padding: 100px 20px 130px;
    color: white;
    overflow: hidden;
    margin-bottom: -60px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
.header-content { position: relative; z-index: 2; text-align: center; }
.header-title { font-size:2.8rem; font-weight:800; letter-spacing:-0.5px; margin-bottom:15px; line-height:1.2; text-shadow:0 4px 14px rgba(0,0,0,0.3);}
.header-title span { background: rgba(255,255,255,0.2); padding:0 12px; border-radius:10px; }
.header-subtitle { font-size:1.1rem; font-weight:400; opacity:0.95; }

/* FILTER BAR */
.filter-bar {
    margin-top: -50px;
    position: relative;
    z-index: 3;
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(10px);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 22px 25px;
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
}

.filter-bar input, .filter-bar select {
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 10px 12px;
    min-width: 150px;
    background-color: #fff;
}

.filter-bar button {
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.filter-bar button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,166,81,0.25);
}

/* PROPERTY CARD */
.property-card {
    border: none;
    border-radius: var(--radius);
    background: var(--card-bg);
    box-shadow: var(--shadow);
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
}
.property-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 35px rgba(0,0,0,0.12);
}

.property-image img { width: 100%; height: 220px; object-fit: cover; transition: transform 0.4s ease; }
.property-card:hover img { transform: scale(1.07); }

.property-type { position: absolute; top: 12px; left: 12px; background: var(--primary); color: #fff; font-size:0.8rem; font-weight:600; padding:5px 10px; border-radius:6px; }
.favorite-btn { position: absolute; top: 12px; right: 12px; background: rgba(255,255,255,0.9); border: none; border-radius: 50%; padding: 7px; cursor:pointer; transition: all 0.3s ease;}
.favorite-btn:hover { background: var(--primary); color:#fff; }

.property-body { padding: 16px 18px; }
.property-title { font-size: 1.1rem; font-weight:700; color: var(--text-dark); margin-bottom:6px; }
.property-location { font-size:0.9rem; color: var(--text-muted); margin-bottom:8px; }
.property-meta { font-size:0.9rem; color: var(--text-muted); margin-bottom:10px; }
.property-meta i { color: var(--primary); margin-right:6px; }

.property-footer {
    border-top: 1px solid #eee;
    padding-top:12px;
    display:flex;
    flex-direction: column;
    gap:6px;
}
.price { color: var(--primary); font-size:1.1rem; font-weight:700; margin-bottom:6px; }

.view-btn {
    width: 100%;
    border: 1px solid var(--primary);
    color: var(--primary);
    background-color: #fff;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.view-btn:hover {
    background-color: var(--primary);
    color: #fff;
}

/* Empty State */
.empty-state { text-align:center; padding:100px 20px; color: var(--text-muted); }
.empty-state img { width:160px; opacity:0.8; margin-bottom:15px; }
</style>

<div class="container">
    <div class="elegant-header">
        <div class="container text-center header-content">
            <h1 class="header-title">
                Find Your <span>Dream Property</span> 🏡
            </h1>
            <p class="header-subtitle">Discover homes, apartments, and plots perfectly tailored to your lifestyle.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <input type="text" id="filterCity" placeholder="Search city...">
        <select id="filterType">
            <option value="">Property Type</option>
            <option value="House">House</option>
            <option value="Apartment">Apartment</option>
            <option value="Condo">Condo</option>
            <option value="Townhouse">Townhouse</option>
        </select>
        <select id="filterBedrooms">
            <option value="">Bedrooms</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4+</option>
        </select>
        <select id="filterSort">
            <option value="">Sort by Price</option>
            <option value="low_high">Low to High</option>
            <option value="high_low">High to Low</option>
        </select>
        <button class="btn btn-success" id="applyFilters">Apply</button>
        <button class="btn btn-outline-secondary" id="resetFilters">Reset</button>
    </div>

    <!-- Property Grid -->
    <div class="row g-4 mt-4" id="propertyGrid"></div>
</div>

<script>
        const properties = @json($properties);
// Function to render properties to the grid
function renderProperties(list) {
    const grid = document.getElementById('propertyGrid');
    grid.innerHTML = '';

    if (list.length === 0) {
        grid.innerHTML = `
            <div class="empty-state col-12">
                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Properties">
                <p>No properties match your criteria.</p>
            </div>
        `;
        return;
    }

    list.forEach(property => {
        const card = document.createElement('div');
        card.className = 'col-md-4';

        card.innerHTML = `
            <div class="property-card">
                <div class="property-image">
                    <img src="${property.image_url}" alt="${property.title}">
                    <div class="property-type">${property.property_type}</div>
                    <button class="favorite-btn">&#9733;</button>
                </div>
                <div class="property-body">
                    <h5 class="property-title">${property.title}</h5>
                    <p class="property-location">${property.city}</p>
                    <p class="property-meta">
                        <i class="bi bi-house-door"></i> ${property.bedrooms} Beds 
                        &nbsp;|&nbsp; <i class="bi bi-droplet"></i> ${property.bathrooms} Baths
                    </p>
                    <div class="property-footer">
                        <div class="price">$${property.monthly_rent.toLocaleString()}</div>
                        <button class="view-btn" onclick="window.location.href = '/properties/${property.id}'">View Details</button>
                    </div>
                </div>
            </div>
        `;

        grid.appendChild(card);
    });
}

// Initial render
renderProperties(properties);

// Filter & Sort Functionality
function applyFilters() {
    const city = document.getElementById('filterCity').value.toLowerCase();
    const type = document.getElementById('filterType').value;
    const bedrooms = document.getElementById('filterBedrooms').value;
    const sort = document.getElementById('filterSort').value;

    let filtered = properties.filter(p => {
        return (!city || p.city.toLowerCase().includes(city)) &&
               (!type || p.property_type === type) &&
               (!bedrooms || (bedrooms === "4+" ? p.bedrooms >= 4 : p.bedrooms == bedrooms));
    });

    if (sort === "low_high") filtered.sort((a,b) => a.monthly_rent - b.monthly_rent);
    if (sort === "high_low") filtered.sort((a,b) => b.monthly_rent - a.monthly_rent);

    renderProperties(filtered);
}

// Reset filters
function resetFilters() {
    document.getElementById('filterCity').value = '';
    document.getElementById('filterType').value = '';
    document.getElementById('filterBedrooms').value = '';
    document.getElementById('filterSort').value = '';
    renderProperties(properties);
}

// Event listeners
document.getElementById('applyFilters').addEventListener('click', applyFilters);
document.getElementById('resetFilters').addEventListener('click', resetFilters);

</script>

@endsection
