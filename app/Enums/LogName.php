<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum LogName: string implements HasLabel, HasColor, HasIcon
{
    case Categories = 'categories';
    case Articles = 'articles';
    case Authentication = 'authentication';
    case EventAnnouncements = 'event_announcements';
    case Products = 'products';
    case Plans = 'plans';
    case PlanTiers = 'plan_tiers';
    case VisitorSubmissions = 'visitor_submissions';
    case ExhibitorSubmissions = 'exhibitor_submissions';
    case CompanyInformation = 'company_information';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Categories => __('panel/logs.names.categories'),
            self::Articles => __('panel/logs.names.articles'),
            self::Authentication => __('panel/logs.names.authentication'),
            self::EventAnnouncements => __('panel/logs.names.event_announcements'),
            self::Products => __('panel/logs.names.products'),
            self::Plans => __('panel/logs.names.plans'),
            self::PlanTiers => __('panel/logs.names.plan_tiers'),
            self::VisitorSubmissions => __('panel/logs.names.visitor_submissions'),
            self::ExhibitorSubmissions => __('panel/logs.names.exhibitor_submissions'),
            self::CompanyInformation => __('panel/logs.names.company_information'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Categories => "primary",
            self::Articles => "success",
            self::Authentication => "info",
            self::EventAnnouncements => "warning",
            self::Products => "danger",
            self::Plans => "violet",
            self::PlanTiers => "indigo",
            self::VisitorSubmissions => "amber",
            self::ExhibitorSubmissions => "emerald",
            self::CompanyInformation => "gray",
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Categories => "heroicon-o-bars-3",
            self::Articles => "heroicon-o-document-duplicate",
            self::Authentication => "heroicon-o-user",
            self::EventAnnouncements => "heroicon-o-megaphone",
            self::Products => "heroicon-o-cube",
            self::Plans => "heroicon-o-clipboard-document-list",
            self::PlanTiers => "heroicon-o-rectangle-stack",
            self::VisitorSubmissions => "heroicon-o-user-group",
            self::ExhibitorSubmissions => "heroicon-o-building-storefront",
            self::CompanyInformation => "heroicon-o-building-office",
        };
    }
}
