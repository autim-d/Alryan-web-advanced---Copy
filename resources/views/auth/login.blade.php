@extends('layouts.auth')

@section('title', 'Sign In | Demonstrating Laravel Request & Response')

@section('content')
<div style="max-width: 540px; margin: 0 auto;">
    <div class="card">
        <div class="page-header">
            <h1>Sign In</h1>
            <p>Observing <span class="badge-code">Illuminate\Http\Response</span> & Redirects in action</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-input" 
                    value="{{ old('email', $rememberedEmail) }}" 
                    placeholder="student@alryan.edu" 
                    required 
                    autocomplete="email"
                >
                @if($rememberedEmail)
                    <small style="color: #34d399; font-size: 0.8rem; margin-top: 0.25rem; display: block;">
                        &check; Pre-filled from incoming cookie: <code class="badge-code">$request->cookie('remember_email')</code>
                    </small>
                @endif
            </div>

            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <label for="password" class="form-label" style="margin-bottom: 0;">Password</label>
                </div>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input" 
                    placeholder="••••••••" 
                    required 
                    autocomplete="current-password"
                >
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1.5rem;">
                <label class="form-checkbox-label">
                    <input type="checkbox" name="remember" value="1">
                    <span>Keep me signed in on this device (Session Remember)</span>
                </label>

                <label class="form-checkbox-label">
                    <input type="checkbox" name="save_email_cookie" value="1" {{ $rememberedEmail ? 'checked' : '' }}>
                    <span>Save email address in a Response Cookie (HTTP Cookie Demo)</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                Authenticate (POST /login)
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: var(--text-sub);">
            Don't have an account? <a href="{{ route('signup') }}" style="color: #818cf8; text-decoration: none; font-weight: 600;">Sign Up here</a>
        </div>

        {{-- Pedagogical Explanation Box --}}
        <div class="pedagogy-box">
            <div class="pedagogy-title">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>HTTP Lifecycle: What Happens When You Click Submit?</span>
            </div>
            <ul style="margin-left: 1.25rem; font-size: 0.84rem; color: var(--text-sub); display: flex; flex-direction: column; gap: 0.35rem;">
                <li>Browser dispatches a <code class="badge-code">POST /login</code> with form body containing credentials and <code class="badge-code">_token</code>.</li>
                <li>Controller calls <code class="badge-code">$request->only('email', 'password')</code> to isolate authenticatable input.</li>
                <li>Controller calls <code class="badge-code">$request->boolean('save_email_cookie')</code> to parse checkbox presence.</li>
                <li>On failure, server generates: <code class="badge-code">redirect()->back()->withInput()->withErrors(...)</code> (HTTP 302).</li>
                <li>On success, server attaches cookie: <code class="badge-code">$response->cookie(...)</code> and issues <code class="badge-code">redirect()->intended()</code>.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
