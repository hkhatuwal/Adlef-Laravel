# Activity Components System

This document explains how to work with the activity components system for displaying different activity types.

## Overview

The activity system is now modularized into separate components for each activity type, making it easier to maintain and extend.

## Component Structure

### Main Components

1. **activity-renderer.blade.php** - Main router component that directs to specific activity components
2. **activity-asset-transfer.blade.php** - Handles asset transfer activities
3. **activity-otc-trade.blade.php** - Handles OTC trade activities  
4. **activity-default.blade.php** - Fallback component for undefined activity types

## How to Add New Activity Types

### Step 1: Create the Component
Create a new blade component file in `resources/views/components/`:

```bash
# Example for crypto withdrawal
touch resources/views/components/activity-crypto-withdrawal.blade.php
```

### Step 2: Component Structure
Use this template for your new component:

```blade
@props(['activity', 'loop'])

<!-- Crypto Withdrawal Activity Row -->
<div class="px-6 py-4 {{ !$loop->last ? 'border-b border-slate-100' : '' }} hover:bg-slate-50/50 transition-all duration-150">
    <div class="grid grid-cols-12 gap-4 items-center">
        <!-- Type & Date -->
        <div class="col-span-3">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 bg-opacity-10 flex items-center justify-center">
                        <i class="fa-solid {{ $activity->metadata['icon_class'] ?? 'fa-wallet' }} text-sm"></i>
                    </div>
                </div>
                <div>
                    <div class="text-sm font-medium text-slate-900">
                        {{ ucfirst(str_replace('_', ' ', $activity->action)) }}
                    </div>
                    <div class="text-xs text-slate-500">
                        {{ $activity->created_at->format('d M Y') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Counterparty -->
        <div class="col-span-3">
            <!-- Custom logic for this activity type -->
        </div>

        <!-- Reference No. -->
        <div class="col-span-2">
            <div class="text-sm font-medium text-slate-900">
                {{ $activity->reference_number }}
            </div>
        </div>

        <!-- Status -->
        <div class="col-span-2">
            <!-- Standard status display -->
        </div>

        <!-- Amount -->
        <div class="col-span-2 text-right">
            <!-- Custom amount display logic -->
        </div>
    </div>
</div>
```

### Step 3: Register in Router
Add your new component to `activity-renderer.blade.php`:

```blade
@case(\App\Models\UserActivity::TYPE_CRYPTO_WITHDRAWAL)
    <x-activity-crypto-withdrawal :activity="$activity" :loop="$loop" />
    @break
```

## Color Coding Guidelines

- **Asset Transfer**: Green (`bg-green-100 text-green-600`)
- **OTC Trade**: Purple (`bg-purple-100 text-purple-600`)
- **Crypto Withdrawal**: Orange (`bg-orange-100 text-orange-600`)
- **Fiat Withdrawal**: Red (`bg-red-100 text-red-600`)
- **Deposits**: Emerald (`bg-emerald-100 text-emerald-600`)

## Available Props

Each component receives an `$activity` object and a `$loop` object with the following properties:

### Activity Object:
- `$activity->activity_type` - The type constant
- `$activity->action` - The specific action
- `$activity->description` - Activity description
- `$activity->amount` - Transaction amount
- `$activity->currency_symbol` - Currency code
- `$activity->status` - Activity status
- `$activity->reference_number` - Reference number
- `$activity->metadata` - Additional metadata array
- `$activity->created_at` - Creation timestamp

### Loop Object:
- `$loop->first` - True if this is the first iteration
- `$loop->last` - True if this is the last iteration  
- `$loop->index` - The index of the current loop iteration (starts at 0)
- `$loop->iteration` - The current loop iteration (starts at 1)
- `$loop->remaining` - The iterations remaining in the loop
- `$loop->count` - The total number of items in the array being iterated

## Best Practices

1. **Consistent Styling**: Follow the existing grid layout and styling patterns
2. **Responsive Design**: Ensure components work well on different screen sizes
3. **Icon Usage**: Use FontAwesome icons consistently
4. **Color Coding**: Use distinct colors for different activity types
5. **Metadata Usage**: Leverage the metadata array for type-specific information
6. **Fallback Handling**: Always provide fallback values for optional fields

## Example Usage

In the main view, activities are rendered using:

```blade
@forelse($activities as $activity)
    <x-activity-renderer :activity="$activity" :loop="$loop" />
@empty
    <!-- Empty state -->
@endforelse
```

This system makes it easy to add new activity types without modifying the main view file. 