# QR Scanner Refactored Architecture

## Overview
The QR Scanner has been refactored to follow clean architecture principles with clear separation of concerns:

- **Controller**: Contains business logic and decision making
- **Service**: Handles result building and formatting only
- **Routes**: Clean API definition with only essential endpoints

## Architecture

### Controller (`QrScannerController`)
**Responsibilities:**
- Input validation
- Business logic and decision making
- Error handling and logging
- API response formatting

**Business Logic:**
```php
// Decision making happens in the controller
if (trim(strtolower($qrData)) === 'http://en.m.wikipedia.org') {
    // Valid badge - build success result
    $result = $this->qrScannerService->buildSuccessResult($action, $qrData, $scanUser);
} else {
    // Invalid badge - build error result
    $result = $this->qrScannerService->buildErrorResult('Access Denied: Invalid badge detected');
}
```

### Service (`QrScannerService`)
**Responsibilities:**
- Build success result data structure
- Build error result data structure
- Format result blocks for UI display

**Methods:**
- `buildSuccessResult(CheckInOutAction $action, string $badgeCode, string $scanUser): array`
- `buildErrorResult(string $message): array`

### API Routes
**Endpoint:** `POST /api/qr-scanner/process-scan`
- Authentication required (`auth:web` middleware)
- Only essential endpoint for processing badges
- No test routes or unnecessary endpoints

## Request/Response Flow

1. **Request comes to controller**
2. **Controller validates input**
3. **Controller applies business logic** (if/else decisions)
4. **Controller calls appropriate service method** to build result
5. **Service formats the result data**
6. **Controller returns JSON response**

## Example Usage

### Valid Badge Scan
```json
POST /api/qr-scanner/process-scan
{
    "qr_data": "http://en.m.wikipedia.org",
    "action": "check_in"
}

Response:
{
    "success": true,
    "data": {
        "state": "success",
        "error_message": "",
        "result_blocks": [...]
    }
}
```

### Invalid Badge Scan
```json
POST /api/qr-scanner/process-scan
{
    "qr_data": "invalid_badge_code",
    "action": "check_in"
}

Response:
{
    "success": true,
    "data": {
        "state": "error",
        "error_message": "Access Denied: Invalid badge detected",
        "result_blocks": []
    }
}
```

## Benefits of This Architecture

1. **Single Responsibility**: Each class has one clear purpose
2. **Business Logic Centralization**: All decisions are in the controller
3. **Testable**: Service methods can be unit tested independently
4. **Maintainable**: Easy to modify business rules without touching formatting logic
5. **Clean API**: Only essential endpoints exposed
6. **Dependency Injection**: Service is injected into controller constructor

## Future Extensions

To add new business logic (e.g., database lookups, different badge types):
1. Add logic to the controller's `processScan` method
2. Service methods remain unchanged
3. No API route changes needed
