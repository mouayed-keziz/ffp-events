---
filepath: /home/mouayed/projects/ffp-events/docs/settings.md
---
# Application Settings

This document outlines the various settings available in the FFP Event Platform. These settings are managed via the `spatie/laravel-settings` package.

## Company Information Settings

These settings are managed by the `App\Settings\CompanyInformationsSettings` class.

```php
namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class CompanyInformationsSettings extends Settings
{
    public string $name; // Name of the company
    public string $email; // Contact email address
    public string $phone; // Contact phone number
    public string $address; // Primary address line
    public string $city; // City
    public string $state; // State or Province
    public string $zip; // Postal or Zip code
    public string $country; // Country
    public array $faq; // Frequently Asked Questions (array of Q&A)
    public string $applicationTerms; // Terms and conditions for the application
    public ?string $facebookLink; // Optional link to Facebook page
    public ?string $linkedinLink; // Optional link to LinkedIn profile
    public ?string $instagramLink; // Optional link to Instagram profile

    // Properties primarily used for PDF generation (e.g., invoices, official documents)
    public string $detailedAddress; // Full detailed address for official documents
    public string $location; // Specific location information, if different from address
    public string $capital; // Company's registered capital
    public string $rc; // Registre du Commerce (Trade Register number)
    public string $nif; // Numéro d'Identification Fiscale (Tax Identification Number)
    public string $ai; // Article d'Imposition (Tax Article number)
    public string $nis; // Numéro d'Identification Statistique (Statistical Identification Number)
    public int $tva; // TVA percentage (Value Added Tax rate)

    public static function group(): string
    {
        return 'default';
    }
}

```

### Property Descriptions:

*   `name`: The official name of the company or organization.
*   `email`: The primary contact email address for the company.
*   `phone`: The primary contact phone number.
*   `address`: The main street address of the company.
*   `city`: The city where the company is located.
*   `state`: The state or province.
*   `zip`: The postal or ZIP code.
*   `country`: The country where the company is located.
*   `faq`: An array containing frequently asked questions and their answers. Each item in the array could be an object or associative array with 'question' and 'answer' keys.
*   `applicationTerms`: The text for the application's terms and conditions.
*   `facebookLink`: (Optional) URL to the company's Facebook page.
*   `linkedinLink`: (Optional) URL to the company's LinkedIn page.
*   `instagramLink`: (Optional) URL to the company's Instagram page.

### Invoice Generation Specific Settings:

The following settings are primarily utilized during the generation of Invoices (PDF documents), such as invoices, registration confirmations, or other official communications.

*  `detailedAddress`, `location`, `capital`, `rc` `nif` ,`ai` ,`nis` ,`tva`

