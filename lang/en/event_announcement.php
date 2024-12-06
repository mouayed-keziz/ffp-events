<?php

return [
    'resource' => [
        'label' => 'Event Announcement',
        'plural_label' => 'Event Announcements',
    ],
    'fields' => [
        'title' => 'Title',
        'description' => 'Description',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
        'location' => 'Location',
        'status' => 'Status',
        'image_path' => 'Image',
        'max_exhibitors' => 'Max Exhibitors',
        'max_visitors' => 'Max Visitors',
        'is_featured' => 'Featured',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'deleted_at' => 'Deleted At',
    ],
    'filters' => [
        'status' => 'Status',
        'published' => 'Published',
        'draft' => 'Draft',
        'archived' => 'Archived',
        'featured' => 'Featured',
        'trashed' => 'Trashed',
    ],
    'actions' => [
        'create' => 'Create Announcement',
        'edit' => 'Edit Announcement',
        'delete' => 'Delete Announcement',
        'view' => 'View Announcement',
        'restore' => 'Restore Announcement',
        'force_delete' => 'Force Delete Announcement',
    ],
    'messages' => [
        'created' => 'Announcement created successfully',
        'updated' => 'Announcement updated successfully',
        'deleted' => 'Announcement deleted successfully',
        'restored' => 'Announcement restored successfully',
    ],
];
