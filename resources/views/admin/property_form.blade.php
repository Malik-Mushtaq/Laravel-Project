@extends('layouts.app')

@section('content')
<div class="container my-5 pt-5">
    <div class="card shadow-sm p-4 rounded">
        <h2 class="text-center text-success mb-4">Admin Add Property Form</h2>
        <form id="propertyForm" enctype="multipart/form-data">

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
                <label for="image_file" class="form-label">Upload Property Image</label>
                <input type="file" id="image_file" name="image_file" accept="image/*" class="form-control" required>
                <img id="preview" class="preview mt-2 rounded" alt="Property Preview">
            </div>

            <button type="submit" class="btn btn-success w-100 mt-3">Save Property</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const form = document.getElementById('propertyForm');
const imageInput = document.getElementById('image_file');
const preview = document.getElementById('preview');

// Image preview
imageInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if(file){
        const reader = new FileReader();
        reader.onload = function(e){
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});

// Submit form via API
form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(form);

    try {
        const res = await fetch('/api/admin/properties', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: formData,
            credentials: 'same-origin'
        });

        const data = await res.json();

        if(data.success){
            Swal.fire({
                icon: 'success',
                title: 'Property Saved!',
                text: data.message,
                confirmButtonColor: '#2c7a7b'
            }).then(() => window.location.href = '/admin/dashboard');
        } else {
            // Show validation errors
            let errors = data.errors ? Object.values(data.errors).flat().join('<br>') : data.message;
            Swal.fire({ icon: 'error', title: 'Validation Failed', html: errors });
        }
    } catch(err) {
        console.error(err);
        Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.' });
    }
});
</script>

<style>
/* form styling */
.container .card { background-color: #fff; border-radius: 10px; }
input, textarea, select { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem; }
textarea { resize: vertical; }
img.preview { max-width: 100%; margin-top: 10px; display: none; object-fit: cover; height: 200px; }
button { cursor: pointer; }
</style>
@endsection
