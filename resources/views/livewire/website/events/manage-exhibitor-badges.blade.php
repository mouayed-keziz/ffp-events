<?php

use Livewire\Volt\Component;
use App\Models\EventAnnouncement;
use App\Models\ExhibitorSubmission;
use App\Models\Badge;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    public $event;
    public $submission;
    public $badges = [];
    public $originalBadges = [];
    public $deletedBadgeIds = [];
    public $newBadge = [
        'name' => '',
        'company' => '',
        'position' => '',
    ];

    public function mount(EventAnnouncement $event, ExhibitorSubmission $submission)
    {
        $this->event = $event;
        $this->submission = $submission;
        $this->loadBadges();

        // Show one empty badge by default if there are no badges
        if (empty($this->badges)) {
            $this->addBadge();
        }
    }

    public function loadBadges()
    {
        $this->badges = $this->submission->badges->toArray();
        $this->originalBadges = json_encode($this->badges); // Store original badges as JSON for comparison
    }

    public function addBadge()
    {
        $this->badges[] = [
            'name' => '',
            'company' => '',
            'position' => '',
            'is_new' => true,
        ];
    }

    public function saveBadges()
    {
        $this->validate();

        $savedBadges = $this->processBadges();

        if ($savedBadges->isNotEmpty()) {
            // Log the activity
            \App\Activity\ExhibitorSubmissionActivity::logBadgesUpdated($this->submission->exhibitor, $this->submission, ['badge_count' => $savedBadges->count()]);

            $this->notifyExhibitor($savedBadges);
            $this->notifyAdmins($savedBadges);

            session()->flash('success', __('website/manage-badges.badges_saved_success'));
            $this->loadBadges();

            // Redirect to event details page after saving
            return redirect()->route('event_details', ['id' => $this->event->id]);
        } else {
            session()->flash('error', __('website/manage-badges.badges_save_error'));
        }
    }

    public function saveAndDownloadBadges()
    {
        $this->validate();

        $savedBadges = $this->processBadges();

        if ($savedBadges->isNotEmpty()) {
            // Log the activity
            \App\Activity\ExhibitorSubmissionActivity::logBadgesUpdated($this->submission->exhibitor, $this->submission, ['badge_count' => $savedBadges->count()]);

            $this->notifyExhibitor($savedBadges);
            $this->notifyAdmins($savedBadges);

            session()->flash('success', __('website/manage-badges.badges_saved_success'));
            $this->loadBadges();

            // Generate the zip file for download
            $zipPath = $this->generateBadgesZip($savedBadges);

            // Redirect to the badge download route with a query parameter to redirect afterward
            return redirect()->route('exhibitor.badges.download', [
                'event' => $this->event->id,
                'submission' => $this->submission->id,
                'zipPath' => basename($zipPath),
                'redirect_to' => route('event_details', ['id' => $this->event->id]),
            ]);
        } else {
            session()->flash('error', __('website/manage-badges.badges_save_error'));
        }
    }

    /**
     * Process and save badges
     *
     * @return \Illuminate\Support\Collection
     */
    protected function processBadges()
    {
        $badgeService = app(\App\Services\BadgeService::class);
        $savedBadges = collect([]);

        // First, delete any badges that were removed
        if (!empty($this->deletedBadgeIds)) {
            foreach ($this->deletedBadgeIds as $badgeId) {
                $badgeToDelete = Badge::find($badgeId);
                if ($badgeToDelete) {
                    $badgeToDelete->delete();
                    \Illuminate\Support\Facades\Log::info("Badge deleted: {$badgeId}");
                }
            }
            // Clear the deleted badges array
            $this->deletedBadgeIds = [];
        }

        // Get the template path for exhibitor badges
        $templatePath = $badgeService::getTemplatePath($this->event->id, 'exhibitor');

        if (!$templatePath) {
            session()->flash('error', __('website/manage-badges.template_not_found'));
            return collect([]);
        }

        foreach ($this->badges as $badgeData) {
            // Skip empty badges
            if (empty($badgeData['name']) || empty($badgeData['company']) || empty($badgeData['position'])) {
                continue;
            }

            // Create or update the badge
            $badge = isset($badgeData['id']) ? Badge::find($badgeData['id']) : new Badge();

            if (!$badge) {
                $badge = new Badge();
            }

            // Generate a unique code for new badges
            if (!isset($badgeData['id'])) {
                $badge->code = 'EXH-' . $this->event->id . '-' . $this->submission->id . '-' . uniqid();
            }

            $badge->fill([
                'name' => $badgeData['name'],
                'company' => $badgeData['company'],
                'position' => $badgeData['position'],
                'email' => $this->submission->exhibitor->email, // Use exhibitor email
                'exhibitor_submission_id' => $this->submission->id,
            ]);

            $badge->save(); // Generate badge image
            $imageData = [
                'name' => $badge->name,
                'company' => $badge->company,
                'job' => $badge->position, // Change position to job for compatibility with BadgeService
                'qr_data' => $badge->code,
            ];

            // Log the data for debugging
            \Illuminate\Support\Facades\Log::info('Generating badge image', [
                'badge_id' => $badge->id,
                'template_path' => $templatePath,
                'image_data' => $imageData,
            ]);

            $badgeImage = $badgeService::generateBadgePreview($templatePath, $imageData);
            if ($badgeImage) {
                // Create temp file
                $tempPath = storage_path('app/temp/' . $badge->code . '.png');

                // Ensure temp directory exists
                if (!file_exists(storage_path('app/temp'))) {
                    mkdir(storage_path('app/temp'), 0755, true);
                }

                // Log badge image creation
                \Illuminate\Support\Facades\Log::info('Badge image generated successfully', [
                    'badge_id' => $badge->id,
                    'temp_path' => $tempPath,
                ]);

                try {
                    // Save the image as PNG
                    $badgeImage->toPng()->save($tempPath);

                    if (file_exists($tempPath)) {
                        // Add the image to the badge's media collection
                        $badge->clearMediaCollection('image');
                        $badge
                            ->addMedia($tempPath)
                            ->withCustomProperties(['mime_type' => 'image/png']) // Set PNG mime type
                            ->toMediaCollection('image');

                        // Delete temp file after adding to media collection
                        @unlink($tempPath);
                    }
                } catch (\Exception $e) {
                    // Log the error
                    \Illuminate\Support\Facades\Log::error('Error saving badge image', [
                        'badge_id' => $badge->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }

                $savedBadges->push($badge);
            }
        }

        return $savedBadges;
    }

    protected function generateBadgesZip($badges)
    {
        // Create a temporary zip file
        $zipFileName = 'badges_' . $this->submission->id . '_' . time() . '.zip';
        $zipFilePath = storage_path('app/temp/' . $zipFileName);

        // Ensure the temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipFilePath, \ZipArchive::CREATE) === true) {
            foreach ($badges as $badge) {
                if ($badge->hasMedia('image')) {
                    $badgePath = $badge->getFirstMediaPath('image');
                    // Use PNG extension for the files in the ZIP
                    $badgeFileName = "badge_{$badge->code}.png";
                    $zip->addFile($badgePath, $badgeFileName);
                }
            }
            $zip->close();
        }

        return $zipFilePath;
    }

    /**
     * Notify the exhibitor about their badges
     *
     * @param \Illuminate\Support\Collection $badges
     * @return void
     */
    protected function notifyExhibitor($badges)
    {
        $exhibitor = $this->submission->exhibitor;
        $exhibitor->notify(new \App\Notifications\Exhibitor\ExhibitorGeneratedBadges($this->event, $this->submission, $badges));
    }

    /**
     * Notify admins about the badges submission
     *
     * @param \Illuminate\Support\Collection $badges
     * @return void
     */
    protected function notifyAdmins($badges)
    {
        // Get all admin and super_admin users for database notifications
        $adminUsers = \App\Models\User::role(['admin', 'super_admin'])->get();

        // Send a single email to the company email from settings
        $companySettings = app(\App\Settings\CompanyInformationsSettings::class);

        // Send email notification to company email only using notification routing
        \Illuminate\Support\Facades\Notification::route('mail', $companySettings->email)->notify(new \App\Notifications\Admin\ExhibitorUpdatedBadges($this->event, $this->submission->exhibitor, $this->submission, $badges, true));

        // Send database notifications to all admins and super_admins
        foreach ($adminUsers as $admin) {
            // Send database notification for record keeping
            $admin->notify(new \App\Notifications\Admin\ExhibitorUpdatedBadges($this->event, $this->submission->exhibitor, $this->submission, $badges));

            // Send direct database notification for Filament panel
            \Filament\Notifications\Notification::make()
                ->title('Badges mis à jour')
                ->body("L'exposant {$this->submission->exhibitor->name} a mis à jour {$badges->count()} badges pour l'événement {$this->event->title}.")
                ->actions([
                    \Filament\Notifications\Actions\Action::make('voir')
                        ->label('Voir la soumission')
                        ->url(route('filament.admin.resources.exhibitor-submissions.view', $this->submission->id)),
                ])
                ->icon('heroicon-o-identification')
                ->iconColor('success')
                ->sendToDatabase($admin);

            \Illuminate\Support\Facades\Log::info("Admin notification sent to: {$admin->email} for badge updates");
        }
    }

    public function deleteBadge($index)
    {
        // If the badge has an ID, store it for deletion from the database later
        if (isset($this->badges[$index]['id'])) {
            $this->deletedBadgeIds[] = $this->badges[$index]['id'];
        }

        array_splice($this->badges, $index, 1);

        // Add an empty badge if all badges are deleted
        if (empty($this->badges)) {
            $this->addBadge();
        }
    }

    protected function rules()
    {
        return [
            'badges.*.name' => 'required|string|max:255',
            'badges.*.company' => 'required|string|max:255',
            'badges.*.position' => 'required|string|max:255',
        ];
    }

    protected function messages()
    {
        return [
            'badges.*.name.required' => __('website/manage-badges.name_required'),
            'badges.*.name.max' => __('website/manage-badges.name_max'),
            'badges.*.company.required' => __('website/manage-badges.company_required'),
            'badges.*.company.max' => __('website/manage-badges.company_max'),
            'badges.*.position.required' => __('website/manage-badges.position_required'),
            'badges.*.position.max' => __('website/manage-badges.position_max'),
        ];
    }

    /**
     * Check if badges have been modified
     *
     * @return bool
     */
    public function badgesHaveChanged()
    {
        // If the badges count is different, something has changed
        $originalBadgesArray = json_decode($this->originalBadges, true) ?: [];
        if (count($this->badges) !== count($originalBadgesArray)) {
            return true;
        }

        // Compare each badge to see if any fields changed
        foreach ($this->badges as $index => $badge) {
            // Skip checking empty new badges
            if (!isset($badge['id']) && empty($badge['name']) && empty($badge['company']) && empty($badge['position'])) {
                continue;
            }

            // If this is a new badge with some data, it's a change
            if (!isset($badge['id']) && (!empty($badge['name']) || !empty($badge['company']) || !empty($badge['position']))) {
                return true;
            }

            // Check if the badge exists in original badges
            $originalBadge = null;
            foreach ($originalBadgesArray as $ob) {
                if (isset($ob['id']) && isset($badge['id']) && $ob['id'] === $badge['id']) {
                    $originalBadge = $ob;
                    break;
                }
            }

            // If the badge was in the original set but fields changed
            if ($originalBadge) {
                if ($originalBadge['name'] !== $badge['name'] || $originalBadge['company'] !== $badge['company'] || $originalBadge['position'] !== $badge['position']) {
                    return true;
                }
            }
        }

        return false;
    }
}; ?>

<div>
    <h1 class="text-xl font-bold">{{ __('website/manage-badges.team_members') }}</h1>
    <p class="text-gray-400 mt-6 mb-4 text-xs">
        {{ __('website/manage-badges.description') }}
    </p>

    <div class="flex flex-col gap-4 mt-6">
        @foreach ($badges as $index => $badge)
            <div class="bg-base-100 rounded-btn p-4 shadow-sm relative border-[1px] outline-[#919EAB]">
                <button wire:click="deleteBadge({{ $index }})"
                    class="absolute top-2 {{ app()->getLocale() === 'ar' ? 'left-2' : 'right-2' }} text-gray-500 hover:text-error"
                    title="{{ __('website/manage-badges.delete') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" style="direction: ltr;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="flex flex-col mb-4">
                    <label class="mb-2 label-text-alt font-semibold text-[#83909B] text-sm">
                        {{ __('website/manage-badges.name') }} *
                    </label>
                    <input type="text" wire:model.debounce.500ms="badges.{{ $index }}.name"
                        class="input input-bordered bg-white mb-2 rounded-md {{ $errors->has('badges.' . $index . '.name') ? 'input-error' : '' }}"
                        required placeholder="{{ __('website/manage-badges.name_placeholder') }}"
                        @if (isset($badge['id'])) disabled @endif>
                    @error('badges.' . $index . '.name')
                        <span class="text-error text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col mb-4">
                    <label class="mb-2 label-text-alt font-semibold text-[#83909B] text-sm">
                        {{ __('website/manage-badges.company') }} *
                    </label>
                    <input type="text" wire:model.debounce.500ms="badges.{{ $index }}.company"
                        class="input input-bordered bg-white mb-2 rounded-md {{ $errors->has('badges.' . $index . '.company') ? 'input-error' : '' }}"
                        required placeholder="{{ __('website/manage-badges.company_placeholder') }}"
                        @if (isset($badge['id'])) disabled @endif>
                    @error('badges.' . $index . '.company')
                        <span class="text-error text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col mb-4">
                    <label class="mb-2 label-text-alt font-semibold text-[#83909B] text-sm">
                        {{ __('website/manage-badges.position') }} *
                    </label>
                    <input type="text" wire:model.debounce.500ms="badges.{{ $index }}.position"
                        class="input input-bordered bg-white mb-2 rounded-md {{ $errors->has('badges.' . $index . '.position') ? 'input-error' : '' }}"
                        required placeholder="{{ __('website/manage-badges.position_placeholder') }}"
                        @if (isset($badge['id'])) disabled @endif>
                    @error('badges.' . $index . '.position')
                        <span class="text-error text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex gap-2 mt-6 justify-start items-center flex-wrap">
        <button wire:click="addBadge" class="btn font-semibold btn-sm rounded-md btn-outline border-base-200 border-2"
            wire:loading.attr="disabled">
            {{ __('website/manage-badges.add_member') }}
        </button>
        <button wire:click="saveBadges" class="btn font-semibold btn-sm rounded-md btn-outline border-base-200 border-2"
            wire:loading.attr="disabled" wire:target="saveBadges" @if (!$this->badgesHaveChanged()) disabled @endif>
            <span wire:loading.remove wire:target="saveBadges">{{ __('website/manage-badges.save') }}</span>
            <span wire:loading wire:target="saveBadges" class="loading loading-spinner loading-sm"></span>
            <span wire:loading wire:target="saveBadges">{{ __('website/manage-badges.saving') }}</span>
        </button>
        <button wire:click="saveAndDownloadBadges" class="btn font-semibold btn-sm rounded-md btn-primary"
            wire:loading.attr="disabled" wire:target="saveAndDownloadBadges"
            @if (!$this->badgesHaveChanged()) disabled @endif>
            <span wire:loading.remove
                wire:target="saveAndDownloadBadges">{{ __('website/manage-badges.save_and_download') }}</span>
            <span wire:loading wire:target="saveAndDownloadBadges" class="loading loading-spinner loading-sm"></span>
            <span wire:loading wire:target="saveAndDownloadBadges">{{ __('website/manage-badges.downloading') }}</span>
        </button>
    </div>
</div>
