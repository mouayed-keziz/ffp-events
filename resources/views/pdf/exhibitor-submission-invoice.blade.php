<?php
use App\Settings\CompanyInformationsSettings;
use Illuminate\Support\Number;

// Get company information from settings
$settings = app(CompanyInformationsSettings::class);

// Invoice data
$invoiceNumber = '';
$invoiceDate = date('d/m/Y');
$vendorName = 'ffp-events';

// Company info from settings
$companyInfo = [
    'name' => $settings->name,
    'address' => $settings->address,
    'location' => $settings->location,
    'capital' => $settings->capital,
    'rc' => $settings->rc,
    'nif' => $settings->nif,
    'ai' => $settings->ai,
    'nis' => $settings->nis
];

// Client info
$clientInfo = [
    'name' => $exhibitor->name,
    'location' => '(Alger), Algérie',
    'mobile' => 'Mobile:0559123490'
];

// Get invoice items
$items = $submission->getInvoiceData();

// Calculate totals
$subtotalValue = 0;
$discountValue = 0;

// Calculate subtotal
foreach ($items as $item) {
    $subtotalValue += $item['quantity'] * $item['price'];
}

// Apply discount (if any) - for now using 0
$discountValue = 0;

// Calculate VAT
$vatRate = $settings->tva / 100;
$vatValue = ($subtotalValue - $discountValue) * $vatRate;

// Calculate total
$totalValue = $subtotalValue - $discountValue + $vatValue;

// Format currency values
$subtotal = number_format($subtotalValue, 2, ',', ' ') . ' DA';
$discount = number_format($discountValue, 2, ',', ' ') . ' DA';
$vat = number_format($vatValue, 2, ',', ' ') . ' DA';
$total = number_format($totalValue, 2, ',', ' ') . ' DA';
$currentBalance = $total;

// Convert total to words in French
$totalInWords = Number::spell($totalValue, locale: 'fr') . ' dinars';

// Contact info from settings
$contactInfo = [
    'tel' => $settings->phone,
    'email' => $settings->email,
    'web' => isset($settings->website) ? $settings->website : 'http://www.ffp-events.com'
];

$pageInfo = 'Page: 1 / 1';

?>

<!-- <pre>{{ json_encode(['submission' => $submission], JSON_PRETTY_PRINT) }}</pre> -->
<!DOCTYPE html>
<html dir="{{ App::getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme="ffp-theme-light" lang="{{ App::getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bon de commande {{ $invoiceNumber }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap');
        body {
            font-family: 'Montserrat', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f1f1f1;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen p-8 bg-white text-gray-800">
    <div class="w-full max-w-6xl mx-auto">
        <!-- Header with Logo and Company Info -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="flex items-start" style="zoom:1.5">
                @include('website.components.brand.logo')
            </div>
            <div class="text-right text-sm">
                <p class="font-bold text-base">{{ $companyInfo['name'] }}</p>
                <p>{{ $companyInfo['address'] }}</p>
                <p>{{ $companyInfo['location'] }}</p>
                <p>{{ $companyInfo['capital'] }}</p>
                <p>{{ $companyInfo['rc'] }}</p>
                <p>{{ $companyInfo['nif'] }}</p>
                <p>{{ $companyInfo['ai'] }}</p>
                <p>{{ $companyInfo['nis'] }}</p>
            </div>
        </div>

        <!-- Client Info -->
        
        <div class="text-right mb-6">
            <p class="font-bold">{{ $clientInfo['name'] }}</p>
            <!-- <p>{{ $clientInfo['location'] }}</p>
            <p>{{ $clientInfo['mobile'] }}</p> -->
        </div>

        <!-- Invoice Title -->
        <div class="mb-6 border-t pt-8">
            <h1 class="text-3xl font-bold text-orange-700">Bon de commande</h1>
        </div>
        
        <!-- Invoice Info -->
        <div class="grid grid-cols-2 gap-4 mb-6 pb-4">
            <div>
                <p><span class="font-semibold">Date du devis :</span> {{ $invoiceDate }}</p>
            </div>
            <div>
                <p><span class="font-semibold">Vendeur :</span> {{ $vendorName }}</p>
            </div>
        </div>

        <!-- Invoice Items Table -->
        <div class="overflow-x-auto mb-6">
            <table class="table w-full border border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="text-left">DESCRIPTION</th>
                        <th class="text-center">QUANTITÉ</th>
                        <th class="text-center">PRIX UNITAIRE</th>
                        <!-- <th class="text-center">REM(%)</th> -->
                        <th class="text-center">TAXES</th>
                        <th class="text-right">MONTANT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{ $item['title'] }}</td>
                        <td class="text-center"> {{$item['quantity'] }}</td>
                        <td class="text-center"> {{$item['price']}} DZD</td>
                        <!-- <td class="text-center"> 0</td> -->
                        <td class="text-center"> TVA {{ $settings->tva }}%</td>
                        <td class="text-right"> {{$item['quantity'] * $item['price']}} DZD</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Invoice Summary -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div></div>
            <div>
                <table class="table w-full border border-collapse">
                    <tr>
                        <td class="font-semibold">Sous-total</td>
                        <td class="text-right">{{ $subtotal }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Total Discount</td>
                        <td class="text-right">{{ $discount }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">TVA {{ $settings->tva }}%</td>
                        <td class="text-right">{{ $vat }}</td>
                    </tr>
                    <tr class="bg-amber-600 text-white">
                        <td class="font-bold">Total</td>
                        <td class="text-right font-bold">{{ $total }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Total in Words -->
        <div class="mb-6 border-t border-b py-4">
            <p><span class="font-semibold">Arrêtée le présent devis à la somme de :</span></p>
            <p>{{ $totalInWords }}</p>
        </div>

        <!-- Signatures -->
        <div class="grid grid-cols-2 gap-4 mb-12">
            <div class="border p-4 h-32">
                <p class="font-semibold">Visa / Signature client:</p>
                <p>Solde actuel: {{ $currentBalance }}</p>
            </div>
            <div class="border p-4 h-32">
                <p class="font-semibold text-right">Visa / Signature :</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t pt-4 text-center text-sm">
            <p>
                <span>Tél : {{ $contactInfo['tel'] }}</span> | 
                <span>Mail : {{ $contactInfo['email'] }}</span> | 
                <span>Web: {{ $contactInfo['web'] }}</span>
            </p>
            <p>{{ $pageInfo }}</p>
        </div>
    </div>
</body>

</html>
