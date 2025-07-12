<?php

namespace App\Filament\Exports\Traits;

use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

trait HasDynamicColumns
{
    public function __construct(Export $export, array $columnMap, array $options)
    {
        // Add dynamic columns to columnMap before calling parent constructor
        $eventId = $options['event_id'] ?? null;

        // Get dynamic columns and extract their names for columnMap
        $dynamicColumns = $this->getDynamicColumns($eventId);
        $dynamicColumnMap = [];
        foreach ($dynamicColumns as $column) {
            $dynamicColumnMap[$column->getName()] = $column->getLabel();
        }

        $columnMap = array_merge($columnMap, $dynamicColumnMap);

        parent::__construct($export, $columnMap, $options);

        // Set the static property with event ID from options if it exists
        if (property_exists(static::class, 'eventId')) {
            static::$eventId = $eventId;
        }
    }

    public function getCachedColumns(): array
    {
        // Get the event ID from options
        $eventId = $this->getOptions()['event_id'] ?? null;

        // Get all base columns from getColumns()
        $baseColumns = static::getColumns();

        // Add dynamic event-specific columns
        $dynamicColumns = $this->getDynamicColumns($eventId);

        // Combine base columns with dynamic columns
        $allColumns = array_merge($baseColumns, $dynamicColumns);

        // Process and cache the columns (same logic as parent)
        return $this->cachedColumns ?? array_reduce($allColumns, function (array $carry, ExportColumn $column): array {
            $carry[$column->getName()] = $column->exporter($this);
            return $carry;
        }, []);
    }

    /**
     * Get dynamic columns based on event ID
     * Override this method in each exporter to define event-specific columns
     */
    abstract protected function getDynamicColumns(?int $eventId): array;
}
