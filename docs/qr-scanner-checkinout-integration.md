# QR Scanner with CheckInOutAction Integration

## Overview

The QR Scanner has been updated to integrate with the `CheckInOutAction` enum and includes a toggle button in the header to switch between check-in and check-out modes.

## Changes Made

### 1. **Livewire Component Updates**

#### **Imports and Properties**
- Added `CheckInOutAction` enum import
- Added `$currentAction` property with default value `CheckInOutAction::CHECK_IN`

#### **New Methods**
- `toggleAction()`: Toggles between CHECK_IN and CHECK_OUT actions

#### **Updated Methods**
- `processScanResult()`: Now uses the current action from the toggle
- `setAttendanceResult()`: Updated to accept `CheckInOutAction` enum instead of string

### 2. **Header UI Updates**

The scanner header now includes:

#### **Action Toggle Section**
- Label showing "Current Action"
- Toggle button that displays:
  - **Check In**: Blue background with check circle icon
  - **Check Out**: Orange background with x-circle icon
- Button shows current action label and icon from the enum

#### **Visual Styling**
- Check-in mode: Blue theme (`bg-blue-600`, `hover:bg-blue-500`)
- Check-out mode: Orange theme (`bg-orange-600`, `hover:bg-orange-500`)
- Responsive layout with proper spacing and borders

### 3. **Translation Updates**

Added new translation keys in all languages (EN, FR, AR):

#### **English**
```php
'toggle_action' => 'Toggle Action',
'current_action' => 'Current Action',
'attendance_status' => 'Attendance Status',
'badge_code' => 'Badge Code',
'name' => 'Name',
'position' => 'Position',
'company' => 'Company',
'status' => 'Status',
'scanner_user' => 'Scanner User',
```

#### **French**
```php
'toggle_action' => 'Basculer l\'Action',
'current_action' => 'Action Actuelle',
'attendance_status' => 'Statut de Présence',
// ... etc
```

#### **Arabic**
```php
'toggle_action' => 'تبديل الإجراء',
'current_action' => 'الإجراء الحالي',
'attendance_status' => 'حالة الحضور',
// ... etc
```

## How It Works

### **1. Initial State**
- Scanner starts in "Check In" mode by default
- Toggle button shows blue background with check-circle icon

### **2. Toggling Actions**
- User clicks the toggle button
- `toggleAction()` method switches between CHECK_IN and CHECK_OUT
- Button appearance updates automatically (blue ↔ orange)
- Icon changes (check-circle ↔ x-circle)

### **3. Scanning Process**
- When QR code is scanned, `processScanResult()` is called
- Method uses `$this->currentAction` (enum value) instead of hardcoded string
- `setAttendanceResult()` receives the enum and extracts:
  - Label from `$action->getLabel()` ("Check In" or "Check Out")
  - Icon from `$action->getIcon()` 
  - Color styling based on action type

### **4. Result Display**
- Success results show the action that was performed
- Status badge reflects the current action (green for check-in, yellow for check-out)
- All labels use translated strings

## Demo Logic

The demo currently works as follows:

```php
// Error case
if (trim(strtolower($qrData)) === 'keziz mouayed') {
    $this->setErrorResult('Access Denied: Invalid badge detected');
    return;
}

// Success case - uses current toggle action
$this->setAttendanceResult(
    $this->currentAction, // Uses enum value from toggle
    now()->format('Y-m-d H:i:s'),
    'Ahmed Ben Salah',
    'Senior Developer', 
    'Tech Solutions Inc.',
    $qrData
);
```

## Usage Examples

### **Checking Someone In**
1. Ensure toggle is set to "Check In" (blue button)
2. Scan QR code (anything except "keziz mouayed")
3. Result shows "Check In at [timestamp]" with green styling

### **Checking Someone Out**  
1. Click toggle to switch to "Check Out" (orange button)
2. Scan QR code (anything except "keziz mouayed")
3. Result shows "Check Out at [timestamp]" with yellow styling

### **Error Handling**
1. Scan QR code with content "keziz mouayed"
2. Error state displays regardless of current action

## Technical Benefits

### **1. Type Safety**
- Using enum instead of strings prevents typos
- IDE auto-completion for action values
- Compile-time checking of action types

### **2. Consistency**
- Icons and labels come from enum definition
- Color schemes defined in one place
- Easy to add new actions in the future

### **3. Internationalization**
- All UI text properly translated
- Enum labels can be localized if needed
- Consistent terminology across languages

### **4. User Experience**
- Clear visual indication of current mode
- Easy toggle between actions
- Immediate feedback on action changes

## Future Enhancements

### **Database Integration**
```php
protected function processScanResult($qrData)
{
    $attendee = Attendee::where('badge_code', $qrData)->first();
    
    if (!$attendee) {
        $this->setErrorResult('Badge not found');
        return;
    }
    
    // Create attendance record
    $attendance = $attendee->attendances()->create([
        'action' => $this->currentAction->value,
        'scanned_by' => Auth::id(),
        'scanned_at' => now()
    ]);
    
    $this->setAttendanceResult(
        $this->currentAction,
        $attendance->scanned_at->format('Y-m-d H:i:s'),
        $attendee->name,
        $attendee->position,
        $attendee->company,
        $qrData
    );
}
```

### **Action History**
- Track when actions were toggled
- Show recent scan history
- Filter by action type

### **Advanced Logic**
- Auto-determine action based on last scan
- Time-based restrictions (no check-out before check-in)
- Multiple event support

## Files Modified

1. **app/Filament/Pages/QrScannerPage.php**
   - Added enum integration
   - Added toggle functionality
   - Updated method signatures

2. **resources/views/panel/pages/components/scanner-header.blade.php**  
   - Added action toggle UI
   - Updated layout and styling

3. **Translation files**
   - lang/en/panel/scanner.php
   - lang/fr/panel/scanner.php  
   - lang/ar/panel/scanner.php

The system is now fully functional with enum integration and proper toggle functionality!
