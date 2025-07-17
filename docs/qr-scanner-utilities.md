# QR Scanner Utility Functions

This document describes the utility functions available in the QrScannerPage component for handling scan results.

## Available Utility Functions

### 1. setErrorResult()

**Purpose:** Display an error state when QR code scanning fails or badge is invalid.

**Signature:**
```php
protected function setErrorResult($message = null, $icon = 'heroicon-o-exclamation-triangle')
```

**Parameters:**
- `$message` (string, optional): Custom error message. If null, uses default fallback text.
- `$icon` (string, optional): Heroicon component name for the error icon. Default is exclamation triangle.

**Example Usage:**
```php
// Basic error with default message and icon
$this->setErrorResult();

// Custom error message with default icon
$this->setErrorResult('Access denied: Badge not found in system');

// Custom error message and icon
$this->setErrorResult('Badge expired', 'heroicon-o-clock');
```

### 2. setAttendanceResult()

**Purpose:** Display attendance information when a badge is successfully scanned.

**Signature:**
```php
protected function setAttendanceResult($status, $time, $name, $position, $company, $badgeCode)
```

**Parameters:**
- `$status` (string): Either 'checkin' or 'checkout'
- `$time` (string): Check-in/checkout timestamp
- `$name` (string): Attendee's full name
- `$position` (string): Attendee's job position/title
- `$company` (string): Attendee's company name
- `$badgeCode` (string): The actual QR code data/badge identifier

**Example Usage:**
```php
$this->setAttendanceResult(
    'checkin',                    // Status
    now()->format('Y-m-d H:i:s'), // Time
    'Ahmed Ben Salah',            // Name
    'Senior Developer',           // Position
    'Tech Solutions Inc.',       // Company
    $qrData                       // Badge code
);

// For checkout
$this->setAttendanceResult(
    'checkout',
    now()->format('Y-m-d H:i:s'),
    'Sarah Johnson',
    'Project Manager',
    'Digital Agency Ltd.',
    $qrData
);
```

## Current Demo Implementation

The current `processScanResult()` method demonstrates:

1. **Error Case**: If badge data equals "keziz mouayed" (case-insensitive), it triggers an error:
   ```php
   $this->setErrorResult(
       'Access Denied: Invalid badge detected',
       'heroicon-o-exclamation-triangle'
   );
   ```

2. **Success Case**: For all other badge data, it shows a successful check-in:
   ```php
   $this->setAttendanceResult(
       'checkin',
       now()->format('Y-m-d H:i:s'),
       'Ahmed Ben Salah',
       'Senior Developer',
       'Tech Solutions Inc.',
       $qrData
   );
   ```

## Customization Guide

### Integrating with Database

To connect with your database, modify the `processScanResult()` method:

```php
protected function processScanResult($qrData)
{
    try {
        // Look up attendee in database
        $attendee = Attendee::where('badge_code', $qrData)->first();
        
        if (!$attendee) {
            $this->setErrorResult('Badge not found in system');
            return;
        }
        
        if (!$attendee->is_active) {
            $this->setErrorResult('Badge has been deactivated');
            return;
        }
        
        // Record attendance
        $attendance = $attendee->attendances()->create([
            'scanned_by' => Auth::id(),
            'scanned_at' => now(),
            'type' => 'checkin' // or determine based on logic
        ]);
        
        $this->setAttendanceResult(
            'checkin',
            $attendance->scanned_at->format('Y-m-d H:i:s'),
            $attendee->full_name,
            $attendee->position,
            $attendee->company,
            $qrData
        );
        
    } catch (\Exception $e) {
        $this->setErrorResult('System error: ' . $e->getMessage());
    }
}
```

### Adding Check-out Logic

```php
// Determine if this is check-in or check-out
$lastAttendance = $attendee->attendances()
    ->latest()
    ->first();

$isCheckout = $lastAttendance && $lastAttendance->type === 'checkin';
$status = $isCheckout ? 'checkout' : 'checkin';

$this->setAttendanceResult(
    $status,
    now()->format('Y-m-d H:i:s'),
    $attendee->full_name,
    $attendee->position,
    $attendee->company,
    $qrData
);
```

### Custom Error Messages

```php
// Different error types
if (!$attendee) {
    $this->setErrorResult('Badge not found', 'heroicon-o-question-mark-circle');
} elseif ($attendee->is_banned) {
    $this->setErrorResult('Access denied', 'heroicon-o-no-symbol');
} elseif ($event->is_ended) {
    $this->setErrorResult('Event has ended', 'heroicon-o-clock');
}
```

## Display Components

The utility functions work with these result display components:

- **Error State**: Shows error message with icon and custom styling
- **Success State**: Shows attendance information in organized blocks:
  - Highlight section for status and time
  - Raw display for badge code
  - Grid layout for attendee details (name, position, company)
  - Status badges and scanner user info

## Benefits

1. **Separation of Concerns**: Business logic separated from display logic
2. **Reusability**: Functions can be called from different parts of the application
3. **Consistency**: Standardized error and success displays
4. **Flexibility**: Easy to customize messages, icons, and data
5. **Maintainability**: Single place to update display formats
