# QR Scanner Results System

This flexible QR scanner results system allows you to customize how scan results are displayed with different states, styles, and modular components.

## Overview

The system consists of:
- **Result State Component**: Main container that handles different states (empty, success, error)
- **Result Block Component**: Individual data blocks with various styles and types
- **Result Grid Component**: Layout manager for organizing blocks
- **Livewire Integration**: Backend logic for processing scans and building result arrays

## Components

### 1. Result State Component (`result-state.blade.php`)

Main component that renders different states:
- `empty`: Default state before scanning
- `success`: Displays custom result blocks
- `error`: Shows error message with custom text

**Props:**
- `state` - Current state ('empty', 'success', 'error')
- `title` - Header title (optional, defaults to translation)
- `description` - Header description (optional)
- `icon` - Header icon (optional)
- `errorMessage` - Custom error message for error state
- `successBlocks` - Array of result blocks for success state

### 2. Result Block Component (`result-block.blade.php`)

Individual data blocks with customizable appearance:

**Props:**
- `label` - Block label/title
- `data` - Block data/content
- `icon` - Heroicon component name (optional)
- `style` - Visual style (default, highlight, success, warning, danger, info)
- `type` - Block type (info, card, badge, raw)
- `colSpan` - Grid column span (1, 2)

**Styles:**
- `default` - Gray background
- `highlight` - Green background for important info
- `success` - Green theme
- `warning` - Yellow theme
- `danger` - Red theme
- `info` - Blue theme

**Types:**
- `info` - Standard info block
- `card` - Card-style with spacing
- `badge` - Compact badge style
- `raw` - Code/data display with monospace font

### 3. Result Grid Component (`result-grid.blade.php`)

Layout manager that organizes blocks:
- Handles full-width blocks (layout: 'full')
- Organizes grid blocks in columns (layout: 'grid')
- Supports responsive grid system

## Usage in Livewire

### Basic Setup

In your Livewire component, add these properties:

```php
public $resultState = 'empty'; // empty, success, error
public $errorMessage = '';
public $resultBlocks = [];
```

### Setting Success State

Create an array of result blocks:

```php
$this->resultState = 'success';
$this->resultBlocks = [
    [
        'label' => 'Scan Result',
        'data' => 'Success',
        'icon' => 'heroicon-s-check-circle',
        'style' => 'highlight',
        'type' => 'card',
        'layout' => 'full'
    ],
    [
        'label' => 'QR Data',
        'data' => $qrCode,
        'icon' => 'heroicon-o-qr-code',
        'style' => 'default',
        'type' => 'raw',
        'layout' => 'full'
    ],
    // Grid blocks
    [
        'label' => 'User',
        'data' => Auth::user()->name,
        'icon' => 'heroicon-o-user',
        'style' => 'info',
        'type' => 'badge',
        'layout' => 'grid'
    ],
    [
        'label' => 'Status',
        'data' => 'Verified',
        'icon' => 'heroicon-o-check',
        'style' => 'success',
        'type' => 'badge',
        'layout' => 'grid'
    ]
];
```

### Setting Error State

```php
$this->resultState = 'error';
$this->errorMessage = 'Invalid QR code format';
$this->resultBlocks = [];
```

### Blade Template Usage

```blade
<x-scanner.result-state 
    :state="$resultState"
    :errorMessage="$errorMessage"
    :successBlocks="$resultBlocks"
    title="Scan Results"
    description="View scan information"
    icon="heroicon-o-clipboard-document-list"
/>
```

## Result Block Configuration

### Block Structure

Each block in `$resultBlocks` should be an array with these keys:

```php
[
    'label' => 'Block Label',           // Required: Display label
    'data' => 'Block Data',             // Required: Data to display
    'icon' => 'heroicon-o-icon-name',   // Optional: Heroicon component
    'style' => 'success',               // Optional: Visual style
    'type' => 'badge',                  // Optional: Block type
    'layout' => 'grid',                 // Optional: Layout preference
    'colSpan' => 1                      // Optional: Grid column span
]
```

### Examples

**Highlight Block (Full Width):**
```php
[
    'label' => 'Scan Status',
    'data' => 'Successfully processed',
    'icon' => 'heroicon-s-check-circle',
    'style' => 'highlight',
    'type' => 'card',
    'layout' => 'full'
]
```

**Raw Data Block:**
```php
[
    'label' => 'QR Code Data',
    'data' => 'https://example.com/user/123',
    'icon' => 'heroicon-o-link',
    'style' => 'default',
    'type' => 'raw',
    'layout' => 'full'
]
```

**Grid Info Block:**
```php
[
    'label' => 'User Name',
    'data' => 'John Doe',
    'icon' => 'heroicon-o-user',
    'style' => 'info',
    'type' => 'info',
    'layout' => 'grid'
]
```

**Badge with HTML:**
```php
[
    'label' => 'Status',
    'data' => '<span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800">Active</span>',
    'icon' => 'heroicon-o-check-circle',
    'style' => 'success',
    'type' => 'badge',
    'layout' => 'grid'
]
```

## Demo Integration

The system includes demo methods in the Livewire component:

- `demoUrl()` - Demonstrates URL QR code processing
- `demoId()` - Demonstrates numeric ID processing
- `demoText()` - Demonstrates text processing
- `demoError()` - Demonstrates error state

These can be triggered via buttons in the interface for testing different scenarios.

## Customization

### Adding New Styles

To add new styles, modify the `result-block.blade.php` component's style matching:

```php
$styleClasses = match($style) {
    'custom' => 'bg-indigo-50 dark:bg-indigo-800/50',
    // ... existing styles
};
```

### Adding New Types

Add new block types by extending the type conditions in `result-block.blade.php`.

### Custom Icons

Use any Heroicon component by passing the component name (e.g., 'heroicon-o-star', 'heroicon-s-heart').

## Best Practices

1. **Consistent Layouts**: Use 'full' for important/primary data, 'grid' for metadata
2. **Style Hierarchy**: Use 'highlight' for main results, 'success/warning/danger' for status, 'default/info' for details
3. **Icon Consistency**: Choose icons that match the data type (users, documents, links, etc.)
4. **Data Formatting**: Use 'raw' type for codes/URLs, 'badge' for statuses, 'info' for regular data
5. **Responsive Design**: The grid automatically adjusts for mobile devices

## Translation Support

All static text uses Laravel's localization system. Add custom messages to your translation files:

```php
// lang/en/panel/scanner.php
'custom_result' => 'Custom Result Message',
```

Use in blocks:
```php
'label' => __('panel/scanner.custom_result'),
```
