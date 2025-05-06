<?php

use Livewire\Volt\Component;
use App\Models\EventAnnouncement;
use App\Models\ExhibitorSubmission;
use App\Models\Badge;

new class extends Component {
    public $event;
    public $submission;
    public $badges = [];
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

        // For now, just dump the badges data
        dd($this->badges);
    }

    public function saveAndDownloadBadges()
    {
        $this->validate();

        // For now, just dump the badges data
        dd($this->badges);
    }

    public function deleteBadge($index)
    {
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
                    <input type="text" wire:model.lazy="badges.{{ $index }}.name"
                        class="input input-bordered bg-white mb-2 rounded-md {{ $errors->has('badges.' . $index . '.name') ? 'input-error' : '' }}"
                        required placeholder="{{ __('website/manage-badges.name_placeholder') }}">
                    @error('badges.' . $index . '.name')
                        <span class="text-error text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col mb-4">
                    <label class="mb-2 label-text-alt font-semibold text-[#83909B] text-sm">
                        {{ __('website/manage-badges.company') }} *
                    </label>
                    <input type="text" wire:model.lazy="badges.{{ $index }}.company"
                        class="input input-bordered bg-white mb-2 rounded-md {{ $errors->has('badges.' . $index . '.company') ? 'input-error' : '' }}"
                        required placeholder="{{ __('website/manage-badges.company_placeholder') }}">
                    @error('badges.' . $index . '.company')
                        <span class="text-error text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col mb-4">
                    <label class="mb-2 label-text-alt font-semibold text-[#83909B] text-sm">
                        {{ __('website/manage-badges.position') }} *
                    </label>
                    <input type="text" wire:model.lazy="badges.{{ $index }}.position"
                        class="input input-bordered bg-white mb-2 rounded-md {{ $errors->has('badges.' . $index . '.position') ? 'input-error' : '' }}"
                        required placeholder="{{ __('website/manage-badges.position_placeholder') }}">
                    @error('badges.' . $index . '.position')
                        <span class="text-error text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex gap-2 mt-6 justify-start items-center flex-wrap">
        <button wire:click="addBadge" class="btn font-semibold btn-sm rounded-md btn-outline border-base-200 border-2">
            {{ __('website/manage-badges.add_member') }}
        </button>
        <button wire:click="saveBadges"
            class="btn font-semibold btn-sm rounded-md btn-outline border-base-200 border-2">
            {{ __('website/manage-badges.save') }}
        </button>
        <button wire:click="saveAndDownloadBadges" class="btn font-semibold btn-sm rounded-md btn-primary">
            {{ __('website/manage-badges.save_and_download') }}
        </button>
    </div>
</div>
