# QR Scanner Authentication Fix

## Problem
The QR Scanner was making API calls to `/api/qr-scanner/process-scan` but `Auth::user()` was returning `null` because the API route was not properly authenticated for session-based authentication used by Filament.

## Root Cause
- QR Scanner page runs within Filament admin panel (session-based auth)
- API routes were not using proper authentication middleware
- Mismatch between frontend (session auth) and backend (no auth)

## Solution

### 1. Moved Route from API to Web Routes
**Before:**
```php
// routes/api.php
Route::middleware([])->prefix('qr-scanner')->group(function () {
    Route::post('/process-scan', [QrScannerController::class, 'processScan']);
});
```

**After:**
```php
// routes/web.php
Route::prefix('admin')->middleware(['auth:web'])->group(function () {
    Route::post('qr-scanner/process-scan', [\App\Http\Controllers\Api\QrScannerController::class, 'processScan'])
        ->name('admin.qr-scanner.process-scan');
});
```

### 2. Updated Frontend Endpoint
**Before:**
```javascript
fetch('/api/qr-scanner/process-scan', {
    // ...
});
```

**After:**
```javascript
fetch('/admin/qr-scanner/process-scan', {
    // ...
});
```

### 3. Authentication Flow
- Route now uses `auth:web` middleware (same as Filament)
- Session cookies are automatically included in same-origin requests
- `Auth::user()` now properly returns the authenticated user
- CSRF protection is maintained with X-CSRF-TOKEN header

## Benefits
- ✅ `Auth::user()` works properly in the controller
- ✅ Hostess role validation works
- ✅ Event assignment validation works
- ✅ Proper audit logging with user information
- ✅ Consistent authentication with Filament admin panel
- ✅ No API tokens needed

## Route Structure
- **GET** `/admin/qr-scanner-page` - QR Scanner page (Filament)
- **POST** `/admin/qr-scanner/process-scan` - Process badge scans (API endpoint)

Both routes are protected by the same `auth:web` middleware ensuring consistent authentication.
