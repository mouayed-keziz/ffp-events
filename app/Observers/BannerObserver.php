<?php

namespace App\Observers;

use App\Activity\BannerActivity;
use App\Enums\LogEvent;
use App\Models\Banner;

class BannerObserver
{
    public function created(Banner $banner)
    {
        BannerActivity::logCreation($banner);
    }

    public function updated(Banner $banner)
    {
        $changes = [];

        if ($banner->isDirty('title')) {
            $changes['titre ancien'] = $banner->getOriginal('title');
            $changes['titre nouveau'] = $banner->title;
        }
        if ($banner->isDirty('url')) {
            $changes['url ancien'] = $banner->getOriginal('url');
            $changes['url nouveau'] = $banner->url;
        }
        if ($banner->isDirty('order')) {
            $changes['ordre ancien'] = $banner->getOriginal('order');
            $changes['ordre nouveau'] = $banner->order;
        }
        if ($banner->isDirty('is_active')) {
            $changes['actif ancien'] = $banner->getOriginal('is_active') ? 'Oui' : 'Non';
            $changes['actif nouveau'] = $banner->is_active ? 'Oui' : 'Non';
        }
        // Note: Image changes might need specific handling depending on how media library updates are tracked.
        // This observer currently doesn't log image changes explicitly.

        BannerActivity::logUpdate($banner, $changes);
    }

    public function deleted(Banner $banner)
    {
        // Assuming Banners don't use SoftDeletes based on the model provided
        // If they did, you'd add a check like: if (!$banner->isForceDeleting()) { ... }
        BannerActivity::logEventAction($banner, LogEvent::Deletion, "Suppression de la bannière");
    }

    // Add restored and forceDeleted methods if Banner model uses SoftDeletes
    /*
    public function restored(Banner $banner)
    {
        BannerActivity::logEventAction($banner, LogEvent::Restoration, "Restauration de la bannière");
    }

    public function forceDeleted(Banner $banner)
    {
        BannerActivity::logEventAction($banner, LogEvent::ForceDeletion, "Suppression définitive de la bannière");
    }
    */
}
