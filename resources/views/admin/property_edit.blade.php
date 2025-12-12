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

        <!-- same UI, but we won't rely on $property variable -->
        <form id="editPropertyForm" onsubmit="handleUpdate(event)" enctype="multipart/form-data">
            <input type="hidden" id="propertyId" value=""> <!-- populated by JS -->

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Title</label>
                    <input type="text" class="form-control" name="title" id="title" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Property Type</label>
                    <select class="form-select" name="property_type" id="property_type" required>
                        <option value="Apartment">Apartment</option>
                        <option value="House">House</option>
                        <option value="Condo">Condo</option>
                        <option value="Townhouse">Townhouse</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Monthly Rent ($)</label>
                    <input type="number" class="form-control" name="monthly_rent" id="monthly_rent" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Bedrooms</label>
                    <input type="number" class="form-control" name="bedrooms" id="bedrooms" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Bathrooms</label>
                    <input type="number" class="form-control" name="bathrooms" id="bathrooms" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">City</label>
                    <input type="text" class="form-control" name="city" id="city" required>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea class="form-control" rows="4" name="description" id="description"></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold mt-3">Property Image</label>
                    <input type="file" class="form-control" name="image_file" id="image_file" accept="image/*">
                    <img id="imagePreview" class="preview mt-2" style="display:none;">
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
/*
  This view does not rely on a server-passed $property variable.
  It obtains the property ID from the current URL and loads data via:
    GET  /api/properties/{id}         -> returns { success: true, property: { ... } }
  It updates via:
    PUT  /api/admin/properties/{id}   -> accepts multipart/form-data (image optional)
*/

// helper: get property id from URL (assumes url contains the id, e.g. /admin/property/123/edit or /properties/123/edit)
function getIdFromUrl() {
    const parts = window.location.pathname.split('/').filter(Boolean);
    // try to find a numeric segment - last numeric segment is most likely the id
    for (let i = parts.length - 1; i >= 0; i--) {
        if (!isNaN(parseInt(parts[i], 10))) return parts[i];
    }
    return null;
}

const propertyIdInput = document.getElementById('propertyId');
const titleEl = document.getElementById('title');
const typeEl = document.getElementById('property_type');
const rentEl = document.getElementById('monthly_rent');
const bedroomsEl = document.getElementById('bedrooms');
const bathroomsEl = document.getElementById('bathrooms');
const cityEl = document.getElementById('city');
const descEl = document.getElementById('description');
const imageInput = document.getElementById('image_file');
const imagePreview = document.getElementById('imagePreview');

const id = getIdFromUrl();
if (!id) {
    Swal.fire({ icon: 'error', title: 'Invalid URL', text: 'Property ID not found in URL.' });
} else {
    propertyIdInput.value = id;
    loadProperty(id);
}

imageInput.addEventListener('change', () => {
    const file = imageInput.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

async function loadProperty(id) {
    try {
        const res = await fetch(`/api/properties/${id}`, {
            headers: { 'Accept': 'application/json' }
        });

        if (!res.ok) {
            const text = await res.text();
            console.error('Load property failed:', res.status, text);
            Swal.fire({ icon: 'error', title: 'Error loading property', text: 'Check console for details.' });
            return;
        }

        const data = await res.json();
        if (!data.success || !data.property) {
            Swal.fire({ icon: 'error', title: 'Property not found' });
            return;
        }

        const p = data.property;

        // populate fields
        titleEl.value = p.title ?? '';
        typeEl.value = p.property_type ?? '';
        rentEl.value = p.monthly_rent ?? '';
        bedroomsEl.value = p.bedrooms ?? '';
        bathroomsEl.value = p.bathrooms ?? '';
        cityEl.value = p.city ?? '';
        descEl.value = p.description ?? '';

        // show image if available
        if (p.image_path) {
            imagePreview.src = p.image_path;
            imagePreview.style.display = 'block';
        } else {
            imagePreview.style.display = 'none';
        }

    } catch (err) {
        console.error(err);
        Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to fetch property' });
    }
}

async function handleUpdate(event) {
    event.preventDefault();
    const id = propertyIdInput.value;
    if (!id) { Swal.fire({ icon:'error', title:'ID missing' }); return; }

    const form = document.getElementById('editPropertyForm');
    const formData = new FormData(form);

    // Use PUT for update. Laravel accepts PUT with form-data.
    try {
        const res = await fetch(`/api/admin/properties/${id}`, {
            method: 'POST', // many servers accept POST + _method=PUT — Laravel accepts real PUT too, but some proxies block non-POST multipart forms
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: (function() {
                // Laravel: if server expects PUT, include _method=PUT
                // We'll include _method so server side can handle via Route::put(...) or fallback
                formData.set('_method', 'PUT');
                return formData;
            })()
        });

        // handle non-JSON responses gracefully
        const contentType = res.headers.get('content-type') || '';
        let data;
        if (contentType.includes('application/json')) {
            data = await res.json();
        } else {
            const txt = await res.text();
            console.error('Non-JSON response:', txt);
            Swal.fire({ icon: 'error', title: 'Server error', text: 'Check console for details.' });
            return;
        }

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Property Updated Successfully',
                confirmButtonColor: '#00a651'
            }).then(() => window.location.href = '/admin/dashboard');
        } else {
            // show validation errors
            const errors = data.errors ? Object.values(data.errors).flat().join('<br>') : (data.message || 'Update failed');
            Swal.fire({ icon: 'error', title: 'Validation Error', html: errors });
        }

    } catch (err) {
        console.error(err);
        Swal.fire({ icon: 'error', title: 'Update Failed', text: 'Something went wrong.' });
    }
}
</script>
@endsection
