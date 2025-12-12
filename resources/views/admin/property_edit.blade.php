@extends('layouts.app')

@section('content')
<style>
:root {
    --primary: #00a651;
    --secondary: #007a42;
    --bg: #f5f7fa;
    --card-bg: #fff;
    --text-dark: #222;
    --text-muted: #6b7280;
    --radius: 12px;
}
body { background-color: var(--bg); }
.card { border-radius: var(--radius); box-shadow: 0 6px 15px rgba(0,0,0,0.1); }
.btn-primary { background-color: var(--primary); border: none; }
.btn-primary:hover { background-color: var(--secondary); }
img.preview { max-width: 100%; max-height: 200px; border-radius: 8px; margin-top: 10px; display: block; }
</style>

<div class="container py-5">
    <div class="card p-4">
        <h2 class="fw-bold text-success mb-4">
            <i class="bi bi-pencil-square"></i> Edit Property
        </h2>

        <form id="editPropertyForm" onsubmit="handleUpdate(event)" method="POST" enctype="multipart/form-data" action="{{ route('property.update', $property->id) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Title</label>
                    <input type="text" class="form-control" name="title" value="{{ $property->title }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Property Type</label>
                    <select class="form-select" name="property_type" required>
                        <option value="Apartment" {{ $property->property_type == 'Apartment' ? 'selected' : '' }}>Apartment</option>
                        <option value="House" {{ $property->property_type == 'House' ? 'selected' : '' }}>House</option>
                        <option value="Condo" {{ $property->property_type == 'Condo' ? 'selected' : '' }}>Condo</option>
                        <option value="Townhouse" {{ $property->property_type == 'Townhouse' ? 'selected' : '' }}>Townhouse</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Monthly Rent ($)</label>
                    <input type="number" class="form-control" name="monthly_rent" value="{{ $property->monthly_rent }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Bedrooms</label>
                    <input type="number" class="form-control" name="bedrooms" value="{{ $property->bedrooms }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Bathrooms</label>
                    <input type="number" class="form-control" name="bathrooms" value="{{ $property->bathrooms }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">City</label>
                    <input type="text" class="form-control" name="city" value="{{ $property->city }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea class="form-control" rows="4" name="description">{{ $property->description }}</textarea>
                </div>

                <!-- File upload input -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold mt-3">Property Image</label>
                    <input type="file" class="form-control" name="image_file" id="image_file" accept="image/*">
                    @if($property->image_path)
                        <img id="imagePreview" src="{{ asset($property->image_path) }}" class="preview mt-2">
                    @else
                        <img id="imagePreview" class="preview mt-2" style="display:none;">
                    @endif
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary px-4">Update Property</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const imageInput = document.getElementById('image_file');
const imagePreview = document.getElementById('imagePreview');

imageInput.addEventListener('change', function() {
    const file = this.files[0];
    if(file){
        const reader = new FileReader();
        reader.onload = function(e){
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});

function handleUpdate(event){
    event.preventDefault();
    const form = event.target;

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: new FormData(form)
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            Swal.fire({
                icon: 'success',
                title: 'Property Updated!',
                text: data.message,
                confirmButtonColor: '#00a651',
                timer: 2000
            }).then(()=>{ window.location.href = '/admin/dashboard'; });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: Object.values(data.errors || {}).map(arr => arr.join('<br>')).join('<br>'),
                confirmButtonColor: '#00a651'
            });
        }
    })
    .catch(err => console.error(err));
}
</script>
@endsection
