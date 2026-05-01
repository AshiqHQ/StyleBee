@extends('layouts.app')

@section('content')

<style>
  :root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --accent-color: #ff385c;
    --gold-accent: #ffd700;
    --neon-pink: #ff00ff;
    --electric-blue: #00ffff;
    --glass-effect: rgba(255, 255, 255, 0.95);
    --card-shadow: 0 20px 60px -15px rgba(0, 0, 0, 0.3);
    --card-shadow-hover: 0 30px 90px -20px rgba(255, 56, 92, 0.4);
    --transition-hq: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    --transition-bounce: all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
  }

  /* Main Container Styling - FIXED: Added width constraints */
  .login-register {
    background: var(--glass-effect);
    backdrop-filter: blur(20px);
    border-radius: 25px;
    padding: 40px;
    box-shadow: var(--card-shadow);
    border: 1px solid rgba(255, 255, 255, 0.2);
    position: relative;
    overflow: hidden;
    max-width: 500px;
    margin: 0 auto;
    width: 100%;
  }

  .login-register::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
      45deg,
      transparent 30%,
      rgba(102, 126, 234, 0.1) 50%,
      transparent 70%
    );
    animation: shimmer 6s infinite linear;
    z-index: 0;
  }

  @keyframes shimmer {
    0% { transform: translateX(-100%) rotate(45deg); }
    100% { transform: translateX(100%) rotate(45deg); }
  }

  /* Tab Navigation - : Center alignment */
  .nav-tabs {
    border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    position: relative;
    z-index: 1;
    display: flex;
    justify-content: center;
  }

  .nav-link_underscore {
    background: transparent !important;
    border: none !important;
    color: #666 !important;
    font-weight: 600;
    font-size: 1.1rem;
    padding: 12px 30px !important;
    position: relative;
    transition: var(--transition-hq);
    white-space: nowrap;
  }

  .nav-link_underscore::before {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--accent-color), var(--electric-blue));
    border-radius: 3px 3px 0 0;
    transition: width 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
  }

  .nav-link_underscore.active {
    color: #333 !important;
    font-weight: 700;
  }

  .nav-link_underscore.active::before {
    width: 100%;
    animation: pulse-underline 2s infinite;
  }

  @keyframes pulse-underline {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
  }

  .nav-link_underscore:hover:not(.active) {
    color: var(--accent-color) !important;
    transform: translateY(-3px);
  }

  /* Form Container */
  .login-form {
    position: relative;
    z-index: 1;
  }

  /* Floating Label System - CRITICAL FIX */
  .form-floating {
    position: relative;
    margin-bottom: 25px;
    min-height: 60px; /* Ensure enough space for label animation */
  }

  .form-control_gray {
    background: rgba(248, 249, 250, 0.8) !important;
    border: 2px solid rgba(255, 255, 255, 0.3) !important;
    border-radius: 12px !important;
    padding: 16px 20px !important;
    font-size: 16px;
    transition: var(--transition-hq) !important;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    width: 100%;
    height: 56px; /* Fixed height for consistency */
  }

  /* Label positioning */
  .form-floating label {
    position: absolute;
    top: 18px;
    left: 20px;
    color: #666;
    padding: 0;
    transition: var(--transition-hq);
    font-weight: 500;
    pointer-events: none;
    z-index: 2;
    background: transparent;
    max-width: calc(100% - 40px);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  /* Label moves up when typing - THIS WAS THE MAIN ISSUE */
  .form-floating > .form-control:focus ~ label,
  .form-floating > .form-control:not(:placeholder-shown) ~ label {
    top: -10px;
    left: 15px;
    color: var(--accent-color);
    font-size: 12px;
    font-weight: 600;
    background: rgba(255, 255, 255, 0.9);
    padding: 2px 8px;
    border-radius: 10px;
    transform: scale(0.85);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }

  /* Placeholder handling */
  .form-control::placeholder {
    color: transparent;
  }

  .form-control:focus::placeholder {
    color: #999;
  }

  .form-control_gray:focus {
    background: rgba(255, 255, 255, 0.95) !important;
    border-color: transparent !important;
    box-shadow: 
      0 0 0 2px rgba(255, 56, 92, 0.3),
      0 15px 30px rgba(255, 56, 92, 0.2) !important;
    transform: translateY(-2px);
    outline: none;
  }

  /* Error States */
  .form-control.is-invalid {
    border-color: #ff4757 !important;
    background: rgba(255, 71, 87, 0.05) !important;
    animation: shake 0.5s ease-in-out;
  }

  @keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
  }

  .invalid-feedback {
    display: block; /* FIXED: Ensure error messages show */
    background: linear-gradient(135deg, #ff4757, #ff6b81);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 600;
    margin-top: 8px;
    padding: 8px 12px;
    border-radius: 8px;
    background-color: rgba(255, 71, 87, 0.1);
    animation: fadeIn 0.3s ease;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
  }

  /* Submit Button - FIXED: Full width */
  .btn-primary {
    background: linear-gradient(135deg, var(--accent-color), #ff6b9d) !important;
    border: none !important;
    padding: 16px 30px !important;
    font-weight: 700 !important;
    letter-spacing: 1px;
    border-radius: 12px !important;
    position: relative;
    overflow: hidden;
    transition: var(--transition-hq) !important;
    box-shadow: 0 10px 30px rgba(255, 56, 92, 0.4);
    width: 100%; /* FIXED: Make button full width */
  }

  .btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.7s ease;
  }

  .btn-primary:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 20px 40px rgba(255, 56, 92, 0.6);
  }

  .btn-primary:hover::before {
    left: 100%;
  }

  .btn-primary:active {
    transform: translateY(-1px) scale(0.98);
  }

  /* Customer Option Links - FIXED: Responsive layout */
  .customer-option {
    position: relative;
    padding-top: 20px;
    text-align: center;
  }

  .customer-option::before {
    content: '';
    position: absolute;
    top: 0;
    left: 10%; /* FIXED: Better centering */
    width: 80%;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(0, 0, 0, 0.1), transparent);
  }

  .text-secondary {
    color: #666 !important;
    font-weight: 500;
  }

  .btn-text {
    background: linear-gradient(90deg, var(--electric-blue), var(--neon-pink));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 700;
    text-decoration: none;
    position: relative;
    padding: 5px 0;
    transition: var(--transition-hq);
    display: inline-block;
    margin-left: 8px;
  }

  .btn-text::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, var(--electric-blue), var(--neon-pink));
    transition: width 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
    border-radius: 1px;
  }

  .btn-text:hover {
    letter-spacing: 0.5px;
  }

  .btn-text:hover::after {
    width: 100%;
  }

  /* Password Input Enhancement - : Removed pseudo-element, use actual button */
  .password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    z-index: 10;
    padding: 5px;
    transition: var(--transition-hq);
    font-size: 18px;
  }

  .password-toggle:hover {
    color: var(--accent-color);
    transform: translateY(-50%) scale(1.1);
  }

  /* Responsive Adjustments */
  @media (max-width: 768px) {
    .login-register {
      padding: 30px 20px;
      margin: 0 auto;
    }
    
    .nav-link_underscore {
      padding: 10px 20px !important;
      font-size: 1rem;
    }
    
    .btn-primary {
      padding: 14px 20px !important;
      font-size: 15px;
    }
    
    .form-control_gray {
      padding: 14px 16px !important;
      font-size: 15px;
      height: 52px;
    }
    
    .form-floating label {
      top: 16px;
      left: 16px;
    }
    
    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
      top: -12px;
      left: 12px;
    }
  }

  @media (max-width: 576px) {
    .login-register {
      padding: 25px 15px;
      margin: 0 10px;
      border-radius: 15px;
    }
    
    .nav-tabs {
      flex-direction: column;
      align-items: center;
    }
    
    .nav-link_underscore {
      padding: 8px 15px !important;
      font-size: 0.95rem;
      width: 100%;
      text-align: center;
    }
    
    .btn-primary {
      padding: 12px 16px !important;
      font-size: 14px;
    }
    
    .btn-text {
      display: block;
      margin: 10px 0 0 0;
      text-align: center;
    }
    
    .customer-option span {
      display: block;
      margin-bottom: 5px;
    }
    
    .customer-option::before {
      left: 5%;
      width: 90%;
    }
  }

  /* Loading Animation for Form Submission */
  .btn-primary.loading {
    position: relative;
    color: transparent;
  }

  .btn-primary.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 20px;
    height: 20px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    to { transform: translate(-50%, -50%) rotate(360deg); }
  }

  /* Success/Error Message Animation */
  .alert {
    animation: slideDown 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
    border: none;
    border-radius: 12px;
    backdrop-filter: blur(10px);
  }

  @keyframes slideDown {
    from {
      opacity: 0;
      transform: translateY(-20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Section Spacing - FIXED: Responsive */
  .pt-90 {
    padding-top: 90px;
  }

  @media (max-width: 768px) {
    .pt-90 {
      padding-top: 70px;
    }
  }

  @media (max-width: 576px) {
    .pt-90 {
      padding-top: 50px;
    }
  }

  .mb-4.pb-4 {
    margin-bottom: 2rem !important;
    padding-bottom: 2rem !important;
  }

  @media (max-width: 576px) {
    .mb-4.pb-4 {
      margin-bottom: 1rem !important;
      padding-bottom: 1rem !important;
    }
  }

  /* Prevent iOS Zoom */
  @media (max-width: 768px) {
    input, select, textarea {
      font-size: 16px !important;
    }
  }
</style>

  <main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="login-register container">
      <ul class="nav nav-tabs mb-5" id="login_register" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="register-tab" data-bs-toggle="tab"
            href="#tab-item-register" role="tab" aria-controls="tab-item-register" aria-selected="true">Register</a>
        </li>
      </ul>
      <div class="tab-content pt-2" id="login_register_tab_content">
        <div class="tab-pane fade show active" id="tab-item-register" role="tabpanel" aria-labelledby="register-tab">
          <div class="register-form">
            <form method="POST" action="{{ route('register') }}" name="register-form" class="needs-validation" novalidate="">
                @csrf
              <div class="form-floating mb-3">
                <input class="form-control form-control_gray @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required="" autocomplete="name" autofocus="">
                <label for="name">Name</label>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <div class="pb-3"></div>
              <div class="form-floating mb-3">
                <input id="email" type="email" class="form-control form-control_gray @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required="" autocomplete="email">
                <label for="email">Email Address</label>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>

              <div class="pb-3"></div>

              <div class="form-floating mb-3">
                <input id="mobile" type="text" class="form-control form-control_gray @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" required="" autocomplete="mobile">
                <label for="mobile">Mobile</label>
                @error('mobile')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>

              <div class="pb-3"></div>

              <div class="form-floating mb-3">
                <input id="password" type="password" class="form-control form-control_gray @error('password') is-invalid @enderror" name="password" required="" autocomplete="new-password">
                <label for="password">Password</label>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>

              <div class="form-floating mb-3">
                <input id="password-confirm" type="password" class="form-control form-control_gray"
                  name="password_confirmation" required="" autocomplete="new-password">
                <label for="password">Confirm Password</label>
              </div>

              <button class="btn btn-primary w-100 text-uppercase" type="submit">Register</button>

              <div class="customer-option mt-4 text-center">
                <span class="text-secondary">Have an account?</span>
                <a href="{{ route('login') }}" class="btn-text js-show-register">Login to your Account</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection
