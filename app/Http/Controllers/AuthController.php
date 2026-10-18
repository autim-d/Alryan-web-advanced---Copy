<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Display the Signup (Registration) Form.
     *
     * Demonstrates:
     * - Request Inspection: Reading client IP, User-Agent, and Accept-Language header.
     * - Request Query Parameters: Checking optional referral codes via $request->query().
     * - Response Building: Returning a view with response()->view() including custom headers.
     */
    public function showSignupForm(Request $request): Response
    {
        // 1. Inspecting HTTP Request headers & client metadata
        $clientIp = $request->ip();
        $userAgent = $request->userAgent();
        $preferredLanguage = $request->header('Accept-Language', 'en-US');

        // 2. Inspecting Query String parameters (e.g., /signup?ref=cs401)
        $referralCode = $request->query('ref', 'None');

        // 3. Constructing an HTTP Response object with a 200 OK status and educational headers
        return response()
            ->view('auth.register', [
                'clientIp' => $clientIp,
                'userAgent' => $userAgent,
                'preferredLanguage' => $preferredLanguage,
                'referralCode' => $referralCode,
            ], 200)
            ->header('X-Academic-Module', 'CS401-Advanced-Web')
            ->header('X-Form-Type', 'Signup-View');
    }

    /**
     * Handle incoming Signup form submission.
     *
     * Demonstrates:
     * - $request->has(): Checking if a field exists in the payload (e.g., terms agreement).
     * - $request->filled(): Verifying a field exists and is NOT empty.
     * - $request->only(): Whitelisting and extracting only specific input keys.
     * - $request->input(): Retrieving a single parameter with optional default fallback.
     * - $request->all(): Inspecting entire incoming payload.
     * - $request->validate(): Validating input fields directly from the Request.
     * - Response: Returning a RedirectResponse with flash session data (with()).
     */
    public function register(Request $request): RedirectResponse
    {
        // 1. Inspecting the HTTP Request method and URL
        $httpMethod = $request->method(); // 'POST'
        $submissionUrl = $request->fullUrl();

        // 2. Checking presence and state of input data
        // $request->has('terms') checks if the checkbox was sent in the request payload
        if (! $request->has('terms')) {
            return redirect()
                ->back()
                ->withInput($request->only('name', 'email'))
                ->withErrors(['terms' => 'You must agree to the Terms of Service to register.']);
        }

        // $request->filled('name') checks if 'name' exists AND is not empty/whitespace
        if (! $request->filled('name')) {
            return redirect()
                ->back()
                ->withInput($request->only('email'))
                ->withErrors(['name' => 'Name cannot be blank.']);
        }

        // 3. Validating the incoming request payload
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // 4. Demonstrating $request->only() to extract safe subset of fields
        $userData = $request->only(['name', 'email']);

        // 5. Demonstrating $request->input() to retrieve a specific field
        $plainPassword = $request->input('password');

        // Persist User to the database
        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => $plainPassword, // User model automatically casts 'password' to 'hashed'
        ]);

        // Automatically log the new user in
        Auth::login($user);

        // 6. Response Demonstration: Redirecting to a named route with flashed session status
        return redirect()
            ->route('dashboard')
            ->with('status', 'Registration successful! Welcome to the application, '.$user->name.'.');
    }

    /**
     * Display the Login Form.
     *
     * Demonstrates:
     * - Request Cookie inspection: $request->cookie().
     * - Request Query parameters: $request->query().
     * - Standard View response: view().
     */
    public function showLoginForm(Request $request): View
    {
        // 1. Reading an incoming Cookie sent by the client browser
        $rememberedEmail = $request->cookie('remember_email', '');

        // 2. Inspecting Query parameters (e.g. ?redirect_to=settings)
        $redirectTo = $request->query('redirect_to', 'dashboard');

        // 3. Simple View response returning resources/views/auth/login.blade.php
        return view('auth.login', [
            'rememberedEmail' => $rememberedEmail,
            'redirectTo' => $redirectTo,
        ]);
    }

    /**
     * Handle incoming Login form submission.
     *
     * Demonstrates:
     * - $request->only(): Extracting credentials.
     * - $request->input(): Retrieving single inputs with defaults.
     * - $request->boolean(): Parsing boolean checkbox inputs ('true', '1', 'on', 'yes').
     * - $request->header(): Checking custom or standard HTTP request headers.
     * - Response: Conditional redirects, returning cookies, flashing session errors.
     */
    public function login(Request $request): RedirectResponse
    {
        // 1. Header inspection: verifying client details
        $clientAgent = $request->header('User-Agent', 'Unknown Client');

        // 2. Validate incoming request
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 3. Demonstrating $request->boolean() to safely evaluate 'remember me' checkbox
        $remember = $request->boolean('remember');

        // 4. Attempt authentication using credentials extracted with $request->only()
        if (Auth::attempt($request->only('email', 'password'), $remember)) {
            // Regenerate session ID to prevent Session Fixation attacks
            $request->session()->regenerate();

            // Prepare redirect response
            $response = redirect()
                ->intended(route('dashboard'))
                ->with('status', 'Login successful! Welcome back, '.Auth::user()->name.'.');

            // 5. Response Mutation: Attach a Cookie if user opted to save their email for convenience
            if ($request->boolean('save_email_cookie')) {
                // Attach a 30-day cookie to the HTTP Response
                $response->cookie('remember_email', $request->input('email'), 60 * 24 * 30);
            } else {
                // Remove cookie if unchecked
                $response->withoutCookie('remember_email');
            }

            return $response;
        }

        // 6. Response on Failure: Redirect back with flashed error message and re-populated input
        return redirect()
            ->back()
            ->withInput($request->only('email')) // Re-populate email only, discard sensitive password
            ->withErrors([
                'email' => 'The provided credentials do not match our registered records.',
            ]);
    }

    /**
     * User Dashboard displaying request introspection.
     *
     * Demonstrates:
     * - $request->user(): Retrieving the authenticated user directly from the Request.
     * - $request->session(): Inspecting session data and ID.
     * - $request->headers->all(): Dumping all incoming HTTP headers for student inspection.
     * - $request->method(), $request->url(), $request->path().
     */
    public function dashboard(Request $request): View
    {
        // 1. Inspecting the authenticated User model from the Request object
        $user = $request->user();

        // 2. Gathering comprehensive request introspection data for classroom display
        $requestTelemetry = [
            'method' => $request->method(),
            'full_url' => $request->fullUrl(),
            'path' => $request->path(),
            'client_ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'session_id' => $request->session()->getId(),
            'is_secure' => $request->secure() ? 'HTTPS (Secure)' : 'HTTP (Standard)',
            'content_type' => $request->header('Content-Type', 'None'),
            'accept' => $request->header('Accept', '*/*'),
        ];

        // 3. Raw headers array
        $allHeaders = $request->headers->all();

        return view('auth.dashboard', [
            'user' => $user,
            'telemetry' => $requestTelemetry,
            'allHeaders' => $allHeaders,
        ]);
    }

    /**
     * Handle user logout.
     *
     * Demonstrates:
     * - $request->session()->invalidate(): Purging session.
     * - $request->session()->regenerateToken(): Refreshing CSRF token.
     * - Redirect with flash message.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        // Invalidate the session from the Request object
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('status', 'You have been successfully logged out.');
    }

    /**
     * Mock API Endpoint: User Lookup & Payload Inspection.
     *
     * Demonstrates:
     * - $request->expectsJson(): Checking if the client requested JSON.
     * - $request->header(): Checking authentication / API tokens.
     * - $request->has(), $request->input(): Extracting query or JSON body payload.
     * - response()->json(): Returning structured JSON data with explicit HTTP status code
     *   and custom HTTP response headers.
     */
    public function apiCheckUser(Request $request): JsonResponse
    {
        // 1. Inspecting Request headers
        $apiKey = $request->header('X-API-KEY');
        $acceptHeader = $request->header('Accept');

        // Optional API key simulation for demonstration
        if ($apiKey && $apiKey !== 'advanced-web-2026') {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized: Invalid X-API-KEY header provided.',
            ], 401);
        }

        // 2. Checking payload data with $request->has()
        if (! $request->has('email')) {
            return response()->json([
                'success' => false,
                'error' => 'Bad Request: "email" field is required in payload or query.',
                'received_payload' => $request->all(),
            ], 400);
        }

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        // 3. Constructing contextual JSON responses
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found in database.',
                'queried_email' => $email,
                'request_meta' => [
                    'client_ip' => $request->ip(),
                    'method' => $request->method(),
                ],
            ], 404, [
                'X-App-Service' => 'UserVerificationAPI',
                'X-Search-Status' => 'Not-Found',
            ]);
        }

        // 4. Return successful 200 OK JSON response with custom headers
        return response()->json([
            'success' => true,
            'message' => 'User record located successfully.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'member_since' => $user->created_at?->toIso8601String(),
            ],
            'request_meta' => [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'has_auth_session' => Auth::check(),
            ],
        ], 200, [
            'X-App-Service' => 'UserVerificationAPI',
            'X-Search-Status' => 'Success',
            'X-Execution-Timestamp' => now()->toDateTimeString(),
        ]);
    }
}
