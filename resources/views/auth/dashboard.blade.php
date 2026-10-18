@extends('layouts.auth')

@section('title', 'Student Dashboard | HTTP Introspection')

@section('content')
<div style="display: flex; flex-direction: column; gap: 2rem;">

    {{-- Top Welcome & Identity Card --}}
    <div class="card" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
        <div>
            <div style="font-size: 0.85rem; color: #818cf8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">
                Authenticated Session Active
            </div>
            <h1 style="font-size: 1.85rem; font-weight: 800; color: #ffffff;">
                Welcome, {{ $user->name }}
            </h1>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.25rem;">
                Retrieved directly from the request instance via <code class="badge-code">$request->user()</code> &bull; Email: <strong>{{ $user->email }}</strong>
            </p>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin-right: 0.4rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout (POST /logout)
                </button>
            </form>
        </div>
    </div>

    {{-- Request Telemetry Card --}}
    <div class="card">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; border-bottom: 1px solid var(--bg-card-border); padding-bottom: 1rem;">
            <div>
                <h2 style="font-size: 1.35rem; font-weight: 700;">HTTP Request Object Telemetry</h2>
                <p style="color: var(--text-muted); font-size: 0.88rem;">Live inspection of properties extracted from <code class="badge-code">$request</code> inside <code class="badge-code">AuthController@dashboard</code></p>
            </div>
            <span class="badge-code" style="background: rgba(16, 185, 129, 0.15); color: #34d399; font-weight: 600; padding: 0.4rem 0.8rem; border-radius: 8px;">
                HTTP 200 OK
            </span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
            @foreach($telemetry as $key => $val)
                <div style="background: var(--bg-input); padding: 1rem 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--bg-card-border);">
                    <div style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 700; margin-bottom: 0.35rem;">
                        {{ str_replace('_', ' ', $key) }}
                    </div>
                    <div style="font-family: var(--font-mono); font-size: 0.85rem; color: #38bdf8; word-break: break-all;">
                        {{ $val }}
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Collapsible Raw HTTP Headers --}}
        <details style="margin-top: 1.5rem; background: rgba(0, 0, 0, 0.2); border-radius: var(--radius-md); border: 1px solid var(--bg-card-border); padding: 1rem 1.25rem;">
            <summary style="cursor: pointer; font-weight: 600; color: var(--text-sub); user-select: none;">
                Inspect All Incoming HTTP Headers (<code class="badge-code">$request->headers->all()</code>)
            </summary>
            <div style="margin-top: 1rem; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-family: var(--font-mono); font-size: 0.82rem;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--bg-card-border); text-align: left; color: var(--text-muted);">
                            <th style="padding: 0.5rem 0.75rem;">Header Name</th>
                            <th style="padding: 0.5rem 0.75rem;">Header Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allHeaders as $headerKey => $headerVal)
                            <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.03);">
                                <td style="padding: 0.5rem 0.75rem; color: #a5b4fc;">{{ $headerKey }}</td>
                                <td style="padding: 0.5rem 0.75rem; color: #e2e8f0; word-break: break-all;">{{ implode(', ', $headerVal) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </details>
    </div>

    {{-- Mock API Testing Lab --}}
    <div class="card">
        <div style="margin-bottom: 1.25rem; border-bottom: 1px solid var(--bg-card-border); padding-bottom: 1rem;">
            <h2 style="font-size: 1.35rem; font-weight: 700;">Classroom Lab: Mock API & JSON Response Tester</h2>
            <p style="color: var(--text-muted); font-size: 0.88rem;">
                Dispatches an asynchronous request to <code class="badge-code">POST /api/check-user</code> to observe <code class="badge-code">response()->json()</code>, HTTP Status Codes, and custom headers in real-time.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr auto; gap: 1rem; margin-bottom: 1rem;">
            <div>
                <label for="api_email" class="form-label">Email to Query (Try registered email or random)</label>
                <input type="email" id="api_email" class="form-input" value="{{ $user->email }}" placeholder="test@alryan.edu">
            </div>
            <div style="display: flex; align-items: flex-end;">
                <button type="button" id="btn-run-api" class="btn btn-primary" onclick="testMockApi()">
                    Send Request & Inspect JSON
                </button>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 0.75rem;">
            <label class="form-checkbox-label" style="font-size: 0.85rem;">
                <input type="checkbox" id="api_include_key" checked>
                <span>Include Valid Header (<code class="badge-code">X-API-KEY: advanced-web-2026</code>)</span>
            </label>
        </div>

        {{-- Live API Response Output Panel --}}
        <div id="api-output-card" style="display: none; margin-top: 1.5rem; background: #050811; border: 1px solid var(--bg-card-border); border-radius: var(--radius-md); padding: 1.25rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                <div style="font-weight: 700; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">
                    HTTP Response Received
                </div>
                <div id="api-status-badge" style="font-family: var(--font-mono); font-size: 0.85rem; padding: 0.2rem 0.6rem; border-radius: 6px;"></div>
            </div>

            <div style="margin-bottom: 0.75rem; font-size: 0.82rem; color: var(--text-muted);">
                Custom Response Headers: <span id="api-custom-headers" style="font-family: var(--font-mono); color: #a5b4fc;"></span>
            </div>

            <pre id="api-json-display" style="font-family: var(--font-mono); font-size: 0.85rem; color: #38bdf8; background: #090e1a; padding: 1rem; border-radius: 8px; overflow-x: auto; max-height: 300px;"></pre>
        </div>
    </div>

</div>

<script>
async function testMockApi() {
    const email = document.getElementById('api_email').value;
    const includeKey = document.getElementById('api_include_key').checked;
    const outputCard = document.getElementById('api-output-card');
    const statusBadge = document.getElementById('api-status-badge');
    const headersSpan = document.getElementById('api-custom-headers');
    const jsonDisplay = document.getElementById('api-json-display');
    const btn = document.getElementById('btn-run-api');

    btn.disabled = true;
    btn.innerText = 'Dispatching...';

    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    };

    if (includeKey) {
        headers['X-API-KEY'] = 'advanced-web-2026';
    }

    try {
        const response = await fetch('{{ route('api.check-user') }}', {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({ email: email })
        });

        const data = await response.json();

        outputCard.style.display = 'block';

        // Display Status Code with appropriate styling
        statusBadge.innerText = `${response.status} ${response.statusText}`;
        if (response.status === 200) {
            statusBadge.style.background = 'rgba(16, 185, 129, 0.2)';
            statusBadge.style.color = '#34d399';
        } else if (response.status === 404) {
            statusBadge.style.background = 'rgba(234, 179, 8, 0.2)';
            statusBadge.style.color = '#facc15';
        } else {
            statusBadge.style.background = 'rgba(244, 63, 94, 0.2)';
            statusBadge.style.color = '#fb7185';
        }

        // Extract Custom Response Headers
        const appService = response.headers.get('X-App-Service') || 'N/A';
        const searchStatus = response.headers.get('X-Search-Status') || 'N/A';
        headersSpan.innerText = `X-App-Service: ${appService} | X-Search-Status: ${searchStatus}`;

        // Format and display JSON
        jsonDisplay.innerText = JSON.stringify(data, null, 2);
    } catch (err) {
        outputCard.style.display = 'block';
        statusBadge.innerText = 'Network Error';
        statusBadge.style.background = 'rgba(244, 63, 94, 0.2)';
        statusBadge.style.color = '#fb7185';
        jsonDisplay.innerText = String(err);
    } finally {
        btn.disabled = false;
        btn.innerText = 'Send Request & Inspect JSON';
    }
}
</script>
@endsection
