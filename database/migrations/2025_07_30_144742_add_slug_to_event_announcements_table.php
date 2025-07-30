<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\EventAnnouncement;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if slug column doesn't exist before adding it
        if (!Schema::hasColumn('event_announcements', 'slug')) {
            Schema::table('event_announcements', function (Blueprint $table) {
                $table->string('slug')->unique()->nullable()->after('title');
            });

            // Populate slugs for existing event announcements that don't have slugs
            EventAnnouncement::withTrashed()->whereNull('slug')->get()->each(function ($event) {
                $frenchTitle = $event->getTranslation('title', 'fr');
                $baseSlug = Str::slug($frenchTitle);

                // Ensure uniqueness
                $slug = $baseSlug;
                $counter = 1;

                while (EventAnnouncement::withTrashed()->where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $event->update(['slug' => $slug]);
            });

            // Make slug non-nullable after populating
            Schema::table('event_announcements', function (Blueprint $table) {
                $table->string('slug')->unique()->nullable(false)->change();
            });
        } else {
            // Column already exists, just ensure any missing slugs are populated
            EventAnnouncement::withTrashed()->whereNull('slug')->get()->each(function ($event) {
                $frenchTitle = $event->getTranslation('title', 'fr');
                $baseSlug = Str::slug($frenchTitle);

                // Ensure uniqueness
                $slug = $baseSlug;
                $counter = 1;

                while (EventAnnouncement::withTrashed()->where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $event->update(['slug' => $slug]);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_announcements', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
