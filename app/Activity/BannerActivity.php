<?php

namespace App\Activity;

use App\Enums\LogEvent;
use App\Enums\LogName;
use App\Models\Banner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BannerActivity
{
    /**
     * Log a banner creation
     *
     * @param Banner $banner
     * @return void
     */
    public static function logCreation(Banner $banner): void
    {
        $properties = self::buildProperties($banner);

        activity()
            ->useLog(LogName::Banners->value) // Will add Banners to LogName enum later
            ->event(LogEvent::Creation->value)
            ->performedOn($banner)
            ->withProperties($properties)
            ->causedBy(self::getCurrentUser())
            ->log("Création d'une nouvelle bannière");
    }

    /**
     * Log a banner update
     *
     * @param Banner $banner
     * @param array $changes
     * @return void
     */
    public static function logUpdate(Banner $banner, array $changes): void
    {
        if (!empty($changes)) {
            activity()
                ->useLog(LogName::Banners->value)
                ->event(LogEvent::Modification->value)
                ->performedOn($banner)
                ->withProperties($changes)
                ->causedBy(self::getCurrentUser())
                ->log("Modification de la bannière");
        }
    }

    /**
     * Log a banner action (delete, force delete, restore)
     *
     * @param Banner $banner
     * @param LogEvent $event
     * @param string $description
     * @return void
     */
    public static function logEventAction(Banner $banner, LogEvent $event, string $description): void
    {
        $properties = self::buildSimpleProperties($banner);

        activity()
            ->useLog(LogName::Banners->value)
            ->event($event->value)
            ->performedOn($banner)
            ->causedBy(self::getCurrentUser())
            ->withProperties($properties)
            ->log($description);
    }

    /**
     * Build complete properties array for a banner
     *
     * @param Banner $banner
     * @return array
     */
    private static function buildProperties(Banner $banner): array
    {
        return [
            'titre' => $banner->title,
            'url' => $banner->url,
            'ordre' => $banner->order,
            'actif' => $banner->is_active ? 'Oui' : 'Non',
            'image' => $banner->image, // Assuming getImageAttribute exists
        ];
    }

    /**
     * Build simplified properties array for deletion, restoration, etc.
     *
     * @param Banner $banner
     * @return array
     */
    private static function buildSimpleProperties(Banner $banner): array
    {
        return [
            'titre' => $banner->title,
            'url' => $banner->url,
        ];
    }

    /**
     * Get logged in user from any guard (web, visitor, exhibitor)
     *
     * @return Model|null
     */
    private static function getCurrentUser(): ?Model
    {
        // Check all relevant guards
        foreach (['web', 'visitor', 'exhibitor'] as $guard) { // Adjust guards if needed
            if (Auth::guard($guard)->check()) {
                return Auth::guard($guard)->user();
            }
        }

        return null;
    }
}
