<?php

return [
    'resource' => [
        'label' => 'Event Announcement',
        'plural_label' => 'Event Announcements',
    ],
    'fields' => [
        'event' => 'Event',
        'title' => 'Title',
        'content' => 'Content',
        'status' => 'Status',
        'publish_at' => 'Publish Date',
        'image_path' => 'Image',
        'is_pinned' => 'Pinned',
    ],
    'filters' => [
        'status' => 'Status',
        'published' => 'Published',
        'draft' => 'Draft',
        'archived' => 'Archived',
        'pinned' => 'Pinned',
    ],
    'actions' => [
        'create' => 'Create Announcement',
        'edit' => 'Edit Announcement',
        'delete' => 'Delete Announcement',
        'view' => 'View Announcement',
    ],
    'messages' => [
        'created' => 'Announcement created successfully',
        'updated' => 'Announcement updated successfully',
        'deleted' => 'Announcement deleted successfully',
    ],
];
