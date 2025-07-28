<?php

namespace App\Traits;

trait HasVisitorSubmissionExportedAttributes
{
    /**
     * Get the visitor name for export (handles anonymous submissions with badge fallback)
     */
    public function getExportVisitorNameAttribute(): string
    {
        if (!$this->isAnonymous() && $this->visitor) {
            return $this->visitor->name ?? 'N/A';
        }

        return $this->badge?->name ?? 'N/A';
    }

    /**
     * Get the visitor email for export (handles anonymous submissions)
     */
    public function getExportVisitorEmailAttribute(): ?string
    {
        if (!$this->isAnonymous() && $this->visitor) {
            return $this->visitor->email;
        }

        return $this->anonymous_email;
    }
    /**
     * Get the submission type for export
     */
    public function getExportSubmissionTypeAttribute(): string
    {
        return $this->isAnonymous() ? 'Anonymous' : 'Authenticated';
    }

    /**
     * Get the status value for export
     */
    public function getExportStatusAttribute(): ?string
    {
        return $this->status ? $this->status->value : null;
    }

    /**
     * Get whether the submission has a badge for export
     */
    public function getExportHasBadgeAttribute(): string
    {
        return $this->badge ? 'Yes' : 'No';
    }

    /**
     * Get the badge ID for export
     */
    public function getExportBadgeIdAttribute(): ?int
    {
        return $this->badge?->id;
    }

    /**
     * Get the badge name for export
     */
    public function getExportBadgeNameAttribute(): ?string
    {
        return $this->badge?->name;
    }

    /**
     * Get the badge email for export
     */
    public function getExportBadgeEmailAttribute(): ?string
    {
        return $this->badge?->email;
    }

    /**
     * Get the badge position for export
     */
    public function getExportBadgePositionAttribute(): ?string
    {
        return $this->badge?->position;
    }

    /**
     * Get the badge company for export
     */
    public function getExportBadgeCompanyAttribute(): ?string
    {
        return $this->badge?->company;
    }

    /**
     * Get the number of attachments for export
     */
    public function getExportAttachmentsCountAttribute(): int
    {
        return $this->getMedia('attachments')->count();
    }

    /**
     * Get formatted answers for export
     */
    public function getExportFormattedAnswersAttribute(): ?string
    {
        return $this->getFormattedAnswersJsonAttribute();
    }

    /**
     * Get answers as JSON for export
     */
    public function getExportAnswersJsonAttribute(): string
    {
        return json_encode($this->answers);
    }

    /**
     * Get answers as readable JSON for export
     */
    public function getExportAnswersJsonReadableAttribute(): string
    {
        return json_encode($this->answers, JSON_UNESCAPED_UNICODE);
    }
}
