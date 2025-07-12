@props(['data', 'answerPath', 'disabled' => false])

<div class="form-control">
    <label class="label">
        <span class="label-text font-semibold text-[#546675] text-sm">
            {{ $data['label'][app()->getLocale()] ?? '' }}
            @if ($data['required'] ?? false)
                <span class="text-error">*</span>
            @endif
        </span>
    </label>

    @if (isset($data['description']) && !empty($data['description'][app()->getLocale()]))
        <small
            class="mb-2 label-text-alt font-semibold text-[#83909B] text-sm {{ app()->getLocale() === 'ar' ? 'pr-[10px]' : 'pl-[10px]' }}">{{ $data['description'][app()->getLocale()] }}</small>
    @endif

    @php
        // Get countries using the CountrySelect class
        $countries = App\Enums\Fields\CountrySelect::getCountriesOptions();

        // Get the current selected country code
        $answerData = data_get($this, 'formData.' . $answerPath, []);
        $selectedCountryCode = $answerData['selected_country_code'] ?? '';
    @endphp

    <div class="relative">
        <select
            class="select select-bordered bg-white mb-2 rounded-md w-full  {{ $disabled ? 'opacity-60 cursor-not-allowed' : '' }}"
            wire:change="updateCountrySelectOption('{{ $answerPath }}', $event.target.value)"
            @if ($data['required'] ?? false) required @endif {{ $disabled ? 'disabled' : '' }}>

            {{-- Default option --}}
            <option value="" {{ $selectedCountryCode ? '' : 'selected' }} disabled>
                {{ __('panel/forms.exhibitors.country_select.select_country') }}
            </option>

            {{-- Loop through countries --}}
            @foreach ($countries as $countryCode => $countryName)
                <option value="{{ $countryCode }}" {{ $selectedCountryCode === $countryCode ? 'selected' : '' }}>
                    {{ $countryName }}
                </option>
            @endforeach
        </select>

        {{-- Globe icon --}}
        <div
            class="absolute {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }} top-1/2 transform -translate-y-1/2 pointer-events-none">
            {{-- <x-heroicon-o-globe-alt /> --}}


        </div>
    </div>
</div>
