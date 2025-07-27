<?php

return [
    'events' => 'Events',
    'event' => 'Event',
    'view_announcement' => 'View Event',

    'update_announcement' => 'Update Event',
    'update_terms' => 'Update Terms',
    'update_badge_models' => 'Update Badge Templates',

    'update_visitor_forms' => 'Update Visitor Forms',
    'manage_exhibitor_forms' => 'Manage Exhibitor Forms',
    'manage_exhibitor_post_payment_forms' => 'Manage Exhibitor Post Payment Forms',

    'visitor_registrations' => 'Visitor Registrations',
    'exhibitor_registrations' => 'Exhibitor Registrations',

    'current_participants' => 'Current Participants',
    'badge_check_logs' => 'Badge Check Logs',
];

/*
 * Example breadcrumb implementation for EventAnnouncement resource pages:
 * 
 * public function getBreadcrumbs(): array
 * {
 *     return [
 *         static::getResource()::getUrl() => __('panel/breadcrumbs.events'),
 *         $this->getRecord()->name ?? $this->getRecord()->title => $this->getRecord()->name ?? $this->getRecord()->title,
 *         __('panel/breadcrumbs.view_announcement'), // or any other page key
 *     ];
 * }
 * 
 * Available page keys and their corresponding routes:
 * - 'view_announcement' => 'view' route (ViewEventAnnouncement::class)
 * - 'update_announcement' => 'edit' route (EditEventAnnouncement::class)
 * - 'update_terms' => 'edit-terms' route (EditEventAnnouncementTerms::class)
 * - 'update_badge_models' => 'edit-badge-templates' route (EditEventAnnouncementBadgeTemplates::class)
 * - 'update_visitor_forms' => 'edit-visitor-form' route (EditEventAnnouncementVisitorForm::class)
 * - 'manage_exhibitor_forms' => 'exhibitor-forms' route (ManageEventAnnouncementExhibitorForms::class)
 * - 'manage_exhibitor_post_payment_forms' => 'exhibitor-post-payment-forms' route (ManageEventAnnouncementExhibitorPostPaymentForms::class)
 * - 'visitor_registrations' => 'visitor-submissions' route (ManageEventAnnouncementVisitorSubmissions::class)
 * - 'exhibitor_registrations' => 'exhibitor-submissions' route (ManageEventAnnouncementExhibitorSubmissions::class)
 * - 'current_participants' => 'current-attendees' route (ManageEventAnnouncementCurrentAttendees::class)
 * - 'badge_check_logs' => 'badge-check-logs' route (ManageEventAnnouncementBadgeCheckLogs::class)
 * 
 * Route URLs:
 * - ListEventAnnouncements: '/'
 * - ViewEventAnnouncement: '/{record}'
 * - EditEventAnnouncement: '/{record}/edit'
 * - EditEventAnnouncementTerms: '/{record}/edit-terms'
 * - EditEventAnnouncementBadgeTemplates: '/{record}/edit-badge-templates'
 * - EditEventAnnouncementVisitorForm: '/{record}/visitor-form'
 * - ManageEventAnnouncementExhibitorForms: '/{record}/exhibitor-forms'
 * - ManageEventAnnouncementExhibitorPostPaymentForms: '/{record}/exhibitor-post-payment-forms'
 * - ManageEventAnnouncementVisitorSubmissions: '/{record}/visitor-submissions'
 * - ManageEventAnnouncementExhibitorSubmissions: '/{record}/exhibitor-submissions'
 * - ManageEventAnnouncementCurrentAttendees: '/{record}/current-attendees'
 * - ManageEventAnnouncementBadgeCheckLogs: '/{record}/badge-check-logs'
 * 
 * To implement breadcrumbs in any EventAnnouncement page:
 * 1. Add the getBreadcrumbs() method to the page class
 * 2. Use the appropriate translation key for the page
 * 3. The structure will be: Events > Event Name > Page Name
 */
