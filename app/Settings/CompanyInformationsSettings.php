<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class CompanyInformationsSettings extends Settings
{
    public string $name;

    public string $email;

    public string $phone;

    public string $address;

    public string $city;

    public string $state;

    public string $zip;

    public string $country;

    public array $faq;

    public string $applicationTerms;

    public ?string $facebookLink;

    public ?string $linkedinLink;

    public ?string $instagramLink;

    public string $detailedAddress;

    public string $location;

    public string $capital;

    public string $rc;

    public string $nif;

    public string $ai;

    public string $nis;

    public int $tva;

    public array $jobs;

    public static function group(): string
    {
        return 'default';
    }
}
