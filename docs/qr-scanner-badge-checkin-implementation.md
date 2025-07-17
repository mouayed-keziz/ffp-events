# QR Scanner Badge Check-in/Check-out Implementation

## Overview

The QR Scanner system has been updated to handle real badge check-in and check-out functionality for events. Hostess users can now scan badge QR codes to manage attendee presence at events they are assigned to.

## Key Features

### 1. Authentication & Authorization
- Only authenticated hostess users can perform check-in/check-out actions
- Hostesses must be assigned to the specific event to scan badges for that event
- Event assignment is verified through the `event_announcement_user` pivot table

### 2. Badge Validation
- System looks up badges by their QR code (badge code)
- Validates that the badge belongs to a submission (visitor or exhibitor) for the current event
- Ensures data integrity by cross-referencing badge → submission → event relationships

### 3. Check-in/Check-out Logic
- **Check-in**: Creates or updates a `CurrentAttendee` record and logs the action in `BadgeCheckLog`
- **Check-out**: Removes the `CurrentAttendee` record and logs the action in `BadgeCheckLog`
- All actions are wrapped in database transactions for consistency

### 4. Data Logging
- Every scan action creates a `BadgeCheckLog` entry with:
  - Badge information (code, name, email, position, company)
  - Action type (check_in/check_out)
  - Timestamp
  - Hostess user who performed the action
  - Event context

## API Endpoint

### POST `/api/qr-scanner/process-scan`

**Request Parameters:**
- `qr_data` (required): The badge code from the QR scan
- `action` (required): Either "check_in" or "check_out"

**Event Detection:**
The event is automatically determined from the badge's submission relationship:
- Badge → VisitorSubmission → EventAnnouncement
- Badge → ExhibitorSubmission → EventAnnouncement

This ensures the correct event context without requiring manual event selection.

**Response Format:**
```json
{
    "success": true,
    "data": {
        "state": "success",
        "error_message": "",
        "result_blocks": [
            {
                "label": "Attendance Status",
                "data": "Check In at 2025-01-17 14:30:25",
                "icon": "heroicon-s-check-circle",
                "style": "highlight",
                "type": "card",
                "layout": "full"
            },
            {
                "label": "Badge Code",
                "data": "BADGE123",
                "icon": "heroicon-o-qr-code",
                "style": "default",
                "type": "raw",
                "layout": "full"
            },
            {
                "label": "Name",
                "data": "John Doe",
                "icon": "heroicon-o-user",
                "style": "info",
                "type": "badge",
                "layout": "grid"
            },
            // ... additional result blocks
        ]
    }
}
```

## Database Changes

### Models Involved
- `Badge`: Contains badge information and relationships to submissions
- `BadgeCheckLog`: Logs all check-in/check-out actions
- `CurrentAttendee`: Tracks who is currently checked in
- `VisitorSubmission` & `ExhibitorSubmission`: Link badges to events
- `EventAnnouncement`: Events that hostesses are assigned to
- `User`: Hostess users with role-based permissions

### Relationships Used
- `User::assignedEvents()` - Events where user is assigned as hostess
- `Badge::visitorSubmission()` & `Badge::exhibitorSubmission()` - Badge to submission relationships
- `VisitorSubmission::eventAnnouncement()` & `ExhibitorSubmission::eventAnnouncement()` - Submission to event relationships

## Error Handling

The system handles various error scenarios:

1. **Badge Not Found**: Returns error message "Badge not found"
2. **Event Not Found**: Returns 404 error
3. **Access Denied**: Returns 403 when hostess is not assigned to the event
4. **Invalid Event Badge**: When badge doesn't belong to the specified event
5. **Database Errors**: Wrapped in try-catch with proper logging

## Security Considerations

- All actions require authentication
- Event assignment verification prevents unauthorized access
- Database transactions ensure data consistency
- Comprehensive error logging for audit trails
- Input validation on all request parameters

## Usage Flow

1. Hostess authenticates and opens the QR scanner
2. Hostess scans a badge QR code
3. System validates:
   - Badge exists in the database
   - Badge is linked to a valid submission (visitor or exhibitor)
   - Submission is associated with an event
   - Hostess is assigned to that event
4. System processes check-in/check-out:
   - Creates/updates/removes `CurrentAttendee` record
   - Logs action in `BadgeCheckLog`
5. Returns success response with badge details for UI display

## Future Enhancements

- Real-time attendance tracking dashboard
- Bulk check-in/check-out operations
- Attendance reports and analytics
- Badge photo integration
- Offline mode support
