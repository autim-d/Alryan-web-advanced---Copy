@extends('layouts.auth')

@section('title', 'Sign Up | Demonstrating Laravel Request & Response')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <div class="page-header">
            <h1>Create Account</h1>
            <p>Demonstrating <span class="badge-code">Illuminate\Http\Request</span> in incoming data handling</p>
        </div>

        <form action="{{ route('signup.post') }}" method="POST">
            {{-- Essential Security Directive: Generates a hidden _token input verified by VerifyCsrfToken middleware --}}
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" placeholder="e.g. Sarah Connor" required autocomplete="name">
                <small style="color: var(--text-muted); font-size: 0.8rem; margin-top: 0.25rem; display: block;">
                    Inspected by controller using <code class="badge-code">$request->filled('name')</code>
                </small>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="student@alryan.edu" required autocomplete="email">
                <small style="color: var(--text-muted); font-size: 0.8rem; margin-top: 0.25rem; display: block;">
                    Extracted cleanly using <code class="badge-code">$request->only(['name', 'email'])</code>
                </small>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required autocomplete="new-password">
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="••••••••" required autocomplete="new-password">
                </div>
            </div>

            <div class="form-group">
                <label class="form-checkbox-label">
                    <input type="checkbox" name="terms" value="1" {{ old('terms') ? 'checked' : '' }}>
                    <span>I accept the Academic Honor Code & Terms of Service</span>
                </label>
                <small style="color: var(--text-muted); font-size: 0.8rem; margin-left: 1.6rem; display: block;">
                    Verified using <code class="badge-code">$request->has('terms')</code>
                </small>
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="margin-top: 0.75rem;">
                Submit POST Request (Register)
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: var(--text-sub);">
            Already have an account? <a href="{{ route('login') }}" style="color: #818cf8; text-decoration: none; font-weight: 600;">Sign In here</a>
        </div>

        {{-- Classroom Introspection Panel --}}
        <div class="pedagogy-box">
            <div class="pedagogy-title">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>HTTP Request Introspection (Passed from GET /signup)</span>
            </div>
            <div style="display: grid; grid-template-columns: auto 1fr; gap: 0.4rem 1rem; font-size: 0.84rem; color: var(--text-sub);">
                <strong>Client IP:</strong>
                <span><code class="badge-code">{{ $clientIp }}</code> via <code class="badge-code">$request->ip()</code></span>

                <strong>User Agent:</strong>
                <span style="word-break: break-all;"><code class="badge-code">{{ Str::limit($userAgent, 50) }}</code> via <code class="badge-code">$request->userAgent()</code></span>

                <strong>Accept-Language:</strong>
                <span><code class="badge-code">{{ $preferredLanguage }}</code> via <code class="badge-code">$request->header('Accept-Language')</code></span>

                <strong>Referral Code:</strong>
                <span><code class="badge-code">{{ $referralCode }}</code> via <code class="badge-code">$request->query('ref')</code> (Try visiting <code class="badge-code">/signup?ref=cs401</code>)</span>
            </div>
        </div>
    </div>
</div>
@endsection
