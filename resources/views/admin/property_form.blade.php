@extends('layouts.app')

@section('content')
<div class="container my-5 pt-5"> <!-- Added top padding to separate from navbar as without this its collapsing-->
    <div class="card shadow-sm p-4 rounded">
        <h2 class="text-center text-success mb-4">Admin Add Property Form</h2>
        <form id="propertyForm">
            <div class="mb-3">
                <label for="property_id" class="form-label">Property ID</label>
                <input type="number" id="property_id" name="property_id" placeholder="Enter property ID" class="form-control">
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" id="title" name="title" placeholder="Enter property title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="property_type" class="form-label">Property Type</label>
                <select id="property_type" name="property_type" class="form-select" required>
                    <option value="">Select Type</option>
                    <option value="Apartment">Apartment</option>
                    <option value="House">House</option>
                    <option value="Studio">Studio</option>
                    <option value="Townhouse">Townhouse</option>
                    <option value="Condo">Condo</option>
                </select>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="monthly_rent" class="form-label">Monthly Rent ($)</label>
                    <input type="number" id="monthly_rent" name="monthly_rent" placeholder="Enter monthly rent" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="bedrooms" class="form-label">Bedrooms</label>
                    <input type="number" id="bedrooms" name="bedrooms" placeholder="Enter number of bedrooms" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="bathrooms" class="form-label">Bathrooms</label>
                    <input type="number" id="bathrooms" name="bathrooms" placeholder="Enter number of bathrooms" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" id="city" name="city" placeholder="Enter city" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Enter property description" class="form-control" required></textarea>
            </div>

            <div class="mb-3">
                <label for="image_url" class="form-label">Image URL</label>
                <input type="url" id="image_url" name="image_url" placeholder="Enter image URL" class="form-control">
                <img id="preview" class="preview mt-2 rounded" alt="Property Preview">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select">
                    <option value="">Select Status</option>
                    <option value="available">Available</option>
                    <option value="rented">Rented</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success w-100 mt-3">Save Property</button>
        </form>
    </div>
</div>

<!-- sweetalert connection-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const form = document.getElementById('propertyForm');
const preview = document.getElementById('preview');

form.image_url.addEventListener('input', (e) => {
    if(e.target.value){
        preview.src = e.target.value;
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
});

form.addEventListener('submit', (e) => {
    e.preventDefault();
    const newProperty = {
        property_id: form.property_id.value,
        title: form.title.value,
        property_type: form.property_type.value,
        monthly_rent: parseInt(form.monthly_rent.value),
        bedrooms: parseInt(form.bedrooms.value),
        bathrooms: parseInt(form.bathrooms.value),
        city: form.city.value,
        description: form.description.value,
        image_url: form.image_url.value,
        status: form.status.value
    };
    console.log("New Property Data:", newProperty);

    Swal.fire({
        icon: 'success',
        title: 'Property Saved!',
        text: 'The property data has been captured successfully.',
        confirmButtonColor: '#2c7a7b'
    });

    form.reset();
    preview.style.display = 'none';
});
</script>

<style>
/* form styling */
.container .card {
    background-color: #fff;
    border-radius: 10px;
}
input, textarea, select {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 1rem;
}
textarea { resize: vertical; }
img.preview {
    max-width: 100%;
    margin-top: 10px;
    display: none;
    object-fit: cover;
    height: 200px;
}
button { cursor: pointer; }
</style>
@endsection
