@extends('layouts.app')

@section('content')

<style>
/* ===== Hero Section ===== */
.hero-section {
  background: url('{{ asset('assets/img/hero-bg.jpg') }}') center/cover no-repeat;
  height: 100vh;
  position: relative;
  color: #fff;
}

.hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
}

.hero-content {
  position: relative;
  z-index: 2;
}

.hero-subtext {
  max-width: 700px;
  margin: 0 auto;
  color: #f8f9fa;
}

/* ===== Why Choose Section ===== */
.why-box {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.why-box:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

/* ===== How It Works Section ===== */
.how-it-works {
  background: #fff;
}

.step {
  position: relative;
  text-align: center;
  overflow: hidden;
}

.step-number {
  font-size: 3rem;
  font-weight: 700;
  color: #eaf2f8;
}

.step-icon {
  width: 70px;
  height: 70px;
  background: linear-gradient(135deg, #00aaff, #00dd88);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.8rem;
  margin: 10px auto;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  transition: transform 0.5s ease, box-shadow 0.5s ease;
}

.step-icon.animate {
  transform: scale(1.2);
  box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.step h5 {
  font-weight: 600;
  margin-top: 15px;
}

.step p {
  color: #6c757d;
  font-size: 0.95rem;
}

/* Arrow line between steps */
.step::after {
  content: '';
  position: absolute;
  top: 45%;
  right: -50%;
  width: 100%;
  height: 4px;
  background: linear-gradient(to right, #00aaff, #00dd88);
  z-index: -1;
  border-radius: 2px;
}

.step:last-child::after {
  display: none;
}

/* Arrow head */
.step::before {
  content: '';
  position: absolute;
  top: 40%;
  right: -15%;
  border-left: 10px solid #00dd88;
  border-top: 6px solid transparent;
  border-bottom: 6px solid transparent;
  transform: translateY(-50%);
}

.step:last-child::before {
  display: none;
}

/* Responsive */
@media (max-width: 992px) {
  .step::after, .step::before {
    display: none;
  }
  .step {
    margin-bottom: 2rem;
  }
}

@media (max-width: 768px) {
  .hero-section {
    height: auto;
    padding: 100px 20px;
  }
}

/* ===== Tablet Styling (iPad / mid-size screens) ===== */
@media (min-width: 768px) and (max-width: 1024px) {

  /* Hero Section */
  .hero-section {
    height: 80vh;
    background-position: center top;
    padding: 80px 40px;
  }

  .hero-content h1 {
    font-size: 2.8rem;
  }

  .hero-content .lead {
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto 2rem;
  }

  .main-cta a {
    padding: 12px 40px;
    font-size: 1rem;
  }

  .hero-stats h3 {
    font-size: 1.5rem;
  }

  /* Why Choose Section */
  .why-box {
    padding: 2.5rem 1.5rem;
  }

  .why-box h5 {
    font-size: 1.15rem;
  }

  .why-box p {
    font-size: 0.95rem;
  }

  /* How It Works */
  .step-number {
    font-size: 2.2rem;
  }

  .step-icon {
    width: 60px;
    height: 60px;
    font-size: 1.4rem;
  }

  .step h5 {
    font-size: 1rem;
  }

  .step p {
    font-size: 0.9rem;
  }
}

</style>

{{-- Hero Section --}}
<section class="hero-section d-flex align-items-center justify-content-center text-center">
    <div class="hero-overlay"></div>
    <div class="hero-content position-relative container text-white">
        <h1 class="fw-bold display-3 mb-3">
            Find Your Perfect <span class="text-primary">Rental Home</span>
        </h1>

        <p class="lead mt-3 mb-5 hero-subtext">
            Discover thousands of quality rental properties. Start browsing our verified listings today!
        </p>

        <div class="main-cta mt-5 d-flex justify-content-center">
            <a href="/login" class="btn btn-outline-light btn-lg px-5 py-3 fw-bold text-uppercase d-inline-flex align-items-center justify-content-center hero-secondary-btn" role="button">
                <i class="bi bi-search me-2 fs-5"></i> Explore Rentals
            </a>
        </div>

        <div class="d-flex justify-content-center mt-5 pt-3 hero-stats">
            <div class="mx-4 text-center">
                <h3 class="fw-bold text-white mb-0">1000+</h3>
                <p class="text-light">Properties</p>
            </div>
            <div class="mx-4 text-center">
                <h3 class="fw-bold text-white mb-0">500+</h3>
                <p class="text-light">Happy Tenants</p>
            </div>
            <div class="mx-4 text-center">
                <h3 class="fw-bold text-white mb-0">50+</h3>
                <p class="text-light">Cities</p>
            </div>
        </div>
    </div>
</section>

{{-- Why Choose Section --}}
<section id="why" class="why-choose text-center py-5">
    <div class="container">
        <h2 class="fw-bold mb-4">Why Choose Rentify?</h2>
        <p class="text-muted mb-5">We make renting simple, transparent, and fast. Here’s why thousands of tenants trust us.</p>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="why-box p-4 border rounded-4 shadow-sm">
                    <h5 class="fw-bold">Verified Listings</h5>
                    <p class="text-muted">Every property is verified for authenticity to ensure a safe renting experience.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-box p-4 border rounded-4 shadow-sm">
                    <h5 class="fw-bold">Affordable Options</h5>
                    <p class="text-muted">Choose from budget-friendly to luxury homes that suit your lifestyle and needs.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-box p-4 border rounded-4 shadow-sm">
                    <h5 class="fw-bold">Dedicated Support</h5>
                    <p class="text-muted">Our support team helps you from property search to move-in.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- How It Works Section --}}
<section id="how" class="how-it-works text-center py-5">
    <div class="container">
        <h2 class="fw-bold mb-3">How It Works</h2>
        <p class="text-muted mb-5">Getting started is easy. Follow these simple steps to find your perfect rental.</p>

        <div class="row justify-content-center">
            <div class="col-md-3 col-6 step">
                <div class="step-number">01</div>
                <div class="step-icon"><i class="bi bi-person-plus"></i></div>
                <h5>Create Account</h5>
                <p>Sign up in seconds and create your profile.</p>
            </div>

            <div class="col-md-3 col-6 step">
                <div class="step-number">02</div>
                <div class="step-icon"><i class="bi bi-search"></i></div>
                <h5>Search Properties</h5>
                <p>Browse thousands of verified listings.</p>
            </div>

            <div class="col-md-3 col-6 step">
                <div class="step-number">03</div>
                <div class="step-icon"><i class="bi bi-file-earmark-text"></i></div>
                <h5>Submit Application</h5>
                <p>Apply directly through our platform.</p>
            </div>

            <div class="col-md-3 col-6 step">
                <div class="step-number">04</div>
                <div class="step-icon"><i class="bi bi-key"></i></div>
                <h5>Move In</h5>
                <p>Get approved and move into your new home.</p>
            </div>
        </div>
    </div>
</section>

<script>
// animated step icons on scroll
window.addEventListener("scroll", function() {
    document.querySelectorAll(".step-icon").forEach(icon => {
        const rect = icon.getBoundingClientRect();
        if (rect.top < window.innerHeight - 100) {
            icon.classList.add("animate");
        }
    });
});
</script>

@endsection
