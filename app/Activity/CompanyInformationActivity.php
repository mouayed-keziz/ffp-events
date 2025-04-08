<?php

namespace App\Activity;

use App\Enums\LogEvent;
use App\Enums\LogName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CompanyInformationActivity
{
    /**
     * Log company information update
     *
     * @param array $newData
     * @param string|null $companyName
     * @param array|null $oldData
     * @return void
     */
    public static function logUpdate(array $newData, ?string $companyName = null, ?array $oldData = null): void
    {
        if (!empty($newData)) {
            $properties = [];

            // Add company name to properties if provided
            if ($companyName) {
                $properties['nom_entreprise'] = $companyName;
            }

            // If oldData is provided, compare and format as "ancien" and "nouveau"
            if ($oldData) {
                foreach ($newData as $key => $value) {
                    if (isset($oldData[$key]) && $oldData[$key] !== $value) {
                        // Convert field names to French
                        $frenchFieldName = self::getFieldNameInFrench($key);

                        // Add old and new values
                        $properties["$frenchFieldName ancien"] = $oldData[$key];
                        $properties["$frenchFieldName nouveau"] = $value;
                    }
                }
            } else {
                // No old data, just add new values with French field names
                foreach ($newData as $key => $value) {
                    $frenchFieldName = self::getFieldNameInFrench($key);
                    $properties[$frenchFieldName] = $value;
                }
            }

            activity()
                ->useLog(LogName::CompanyInformation->value)
                ->event(LogEvent::Modification->value)
                ->withProperties($properties)
                ->causedBy(self::getCurrentUser())
                ->log("Modification des informations de l'entreprise");
        }
    }

    /**
     * Get logged in user from any guard (web, visitor, exhibitor)
     *
     * @return Model|null
     */
    private static function getCurrentUser(): ?Model
    {
        // Check all three guards in priority order
        foreach (['web', 'visitor', 'exhibitor'] as $guard) {
            if (Auth::guard($guard)->check()) {
                return Auth::guard($guard)->user();
            }
        }

        return null;
    }

    /**
     * Convert field name to French
     * 
     * @param string $fieldName
     * @return string
     */
    private static function getFieldNameInFrench(string $fieldName): string
    {
        return match ($fieldName) {
            'name' => 'nom',
            'email' => 'email',
            'phone' => 'téléphone',
            'address' => 'adresse',
            'city' => 'ville',
            'state' => 'état',
            'country' => 'pays',
            'zip' => 'code_postal',
            'facebookLink' => 'lien_facebook',
            'linkedinLink' => 'lien_linkedin',
            'instagramLink' => 'lien_instagram',
            'applicationTerms' => 'conditions_utilisation',
            'detailedAddress' => 'adresse_détaillée',
            'location' => 'emplacement',
            'capital' => 'capital_social',
            'rc' => 'registre_commerce',
            'nif' => 'nif',
            'ai' => 'ai',
            'nis' => 'nis',
            'tva' => 'tva',
            default => $fieldName,
        };
    }
}
