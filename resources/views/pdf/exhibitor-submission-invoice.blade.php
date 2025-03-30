<?php
use App\Settings\CompanyInformationsSettings;
use Illuminate\Support\Number;

// Pagination settings
$maxRowsPerPage = 20;
$maxRowsLastPage = 12;

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
// $items = [...$submission->getInvoiceData(), ...$submission->getInvoiceData(), ...$submission->getInvoiceData(),
// ...$submission->getInvoiceData(), ...$submission->getInvoiceData(), ...$submission->getInvoiceData(),
// ...$submission->getInvoiceData(), ...$submission->getInvoiceData(), ...$submission->getInvoiceData()];
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

// Calculate total pages
$totalItems = count($items);
$totalPages = ($totalItems <= $maxRowsLastPage) ? 1 : ceil(($totalItems - $maxRowsLastPage) / $maxRowsPerPage) + 1;

$pageInfo = 'Page: 1 / ' . $totalPages;
?>

<!DOCTYPE html>
<html dir="{{ App::getLocale() === 'ar' ? 'rtl' : 'ltr' }}" lang="{{ App::getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bon de commande {{ $invoiceNumber }}</title>
    <style>
        @font-face {
            font-family: 'Montserrat';
            src: url('{{ storage_path('fonts/Montserrat-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Montserrat';
            src: url('{{ storage_path('fonts/Montserrat-Bold.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        @page {
            margin-bottom: 40px; /* Space for footer */
        }
        html {
            margin-bottom: 40px; /* Space for footer */
        }
        body {
            font-family: 'Montserrat', sans-serif;
            color: #1F2937;
            margin: 0;
            padding: 1.5rem;
            background-color: white;
            font-size: 9px;
            line-height: 1.2;
        }
        .container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
        }
        .header {
            width: 100%;
            margin-bottom: 1rem;
        }
        .header-table {
            width: 100%;
            border: none;
        }
        .header-table td {
            vertical-align: top;
            border: none;
            padding: 0;
        }
        .logo-cell {
            width: 50%;
            text-align: left;
        }
        .company-info {
            width: 50%;
            text-align: right;
        }
        .company-info p {
            margin: 0.1rem 0;
        }
        .company-name {
            font-weight: bold;
            font-size: 11px;
        }
        .client-info {
            text-align: right;
            margin-bottom: 1rem;
        }
        .client-name {
            font-weight: bold;
        }
        .invoice-title {
            margin-top: 1.5rem;
            border-top: 1px solid #E5E7EB;
            padding-top: 1.5rem;
            margin-bottom: 1rem;
        }
        .invoice-title h1 {
            font-size: 18px;
            font-weight: bold;
            color: #C2410C;
            margin: 0;
        }
        .invoice-info-table {
            width: 100%;
            margin-bottom: 1rem;
            border: none;
        }
        .invoice-info-table td {
            border: none;
            padding: 0.2rem 0;
        }
        .label {
            font-weight: 600;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        table, th, td {
            border: 1px solid #E5E7EB;
        }
        th, td {
            padding: 0.3rem;
        }
        th {
            background-color: #F3F4F6;
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .summary-table-container {
            width: 100%;
            margin-bottom: 1rem;
        }
        .summary-table {
            width: 30%;
            margin-left: 70%;
            border-collapse: collapse;
        }
        .total-row {
            background-color: #D97706;
            color: white;
        }
        .total-row td {
            font-weight: bold;
        }
        .total-words {
            margin-bottom: 1rem;
            border-top: 1px solid #E5E7EB;
            border-bottom: 1px solid #E5E7EB;
            padding: 0.5rem 0;
        }
        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }
        .signatures-table td {
            border: 1px solid #E5E7EB;
            padding: 0.5rem;
            height: 6rem;
            vertical-align: top;
        }
        .signature-client {
            width: 30%;
        }
        .signature-space {
            width: 40%;
            border-left: none;
            border-right: none;
        }
        .signature-company {
            width: 30%;
            text-align: right;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            border-top: 1px solid #E5E7EB;
            padding-top: 0.5rem;
            text-align: center;
            font-size: 8px;
            height: 30px; /* Set a fixed height */
        }
        .footer p {
            margin: 0.1rem 0;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="container">
        @php
            $currentPage = 1;
            $itemsProcessed = 0;
            $itemsPerCurrentPage = ($totalPages == 1) ? $totalItems : $maxRowsPerPage;
        @endphp
        
        @while ($itemsProcessed < $totalItems)
            <!-- Header with Logo and Company Info -->
            <table class="header-table">
                <tr>
                    <td class="logo-cell">
                        <!-- Using PNG image instead of SVG -->
                        <img src="{{ public_path('ffp-logo.png') }}" alt="FFP Events Logo" style="height: 60px;">
                    </td>
                    <td class="company-info">
                        <p class="company-name">{{ $companyInfo['name'] }}</p>
                        <p>{{ $companyInfo['address'] }}</p>
                        <p>{{ $companyInfo['location'] }}</p>
                        <p>{{ $companyInfo['capital'] }}</p>
                        <p>{{ $companyInfo['rc'] }}</p>
                        <p>{{ $companyInfo['nif'] }}</p>
                        <p>{{ $companyInfo['ai'] }}</p>
                        <p>{{ $companyInfo['nis'] }}</p>
                    </td>
                </tr>
            </table>

            <!-- Client Info -->
            <div class="client-info">
                <p class="client-name">{{ $clientInfo['name'] }}</p>
                <!-- <p>{{ $clientInfo['location'] }}</p>
                <p>{{ $clientInfo['mobile'] }}</p> -->
            </div>

            <!-- Invoice Title -->
            <div class="invoice-title">
                <h1>Bon de commande</h1>
            </div>
            
            <!-- Invoice Info -->
            <table class="invoice-info-table">
                <tr>
                    <td style="width: 50%"><span class="label">Date du devis :</span> {{ $invoiceDate }}</td>
                    <td style="width: 50%"><span class="label">Vendeur :</span> {{ $vendorName }}</td>
                </tr>
            </table>

            <!-- Invoice Items Table -->
            <table>
                <thead>
                    <tr>
                        <th>DESCRIPTION</th>
                        <th class="text-center">QUANTITÉ</th>
                        <th class="text-center">PRIX UNITAIRE</th>
                        <th class="text-center">TAXES</th>
                        <th class="text-right">MONTANT</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Determine items for current page
                        $endItem = min($itemsProcessed + $itemsPerCurrentPage, $totalItems);
                        $currentPageItems = array_slice($items, $itemsProcessed, $endItem - $itemsProcessed);
                    @endphp
                    
                    @foreach($currentPageItems as $item)
                    <tr>
                        <td>{{ $item['title'] }}</td>
                        <td class="text-center">{{ $item['quantity'] }}</td>
                        <td class="text-center">{{ $item['price'] }} DZD</td>
                        <td class="text-center">TVA {{ $settings->tva }}%</td>
                        <td class="text-right">{{ $item['quantity'] * $item['price'] }} DZD</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @php
                $itemsProcessed += count($currentPageItems);
                
                // For the last page, show the summary
                if ($itemsProcessed >= $totalItems) {
            @endphp
                
            <!-- Invoice Summary (only on last page) -->
            <div class="summary-table-container">
                <table class="summary-table">
                    <tr>
                        <td class="label">Sous-total</td>
                        <td class="text-right">{{ $subtotal }}</td>
                    </tr>
                    <tr>
                        <td class="label">Total Discount</td>
                        <td class="text-right">{{ $discount }}</td>
                    </tr>
                    <tr>
                        <td class="label">TVA {{ $settings->tva }}%</td>
                        <td class="text-right">{{ $vat }}</td>
                    </tr>
                    <tr class="total-row">
                        <td class="label">Total</td>
                        <td class="text-right">{{ $total }}</td>
                    </tr>
                </table>
            </div>

            <!-- Total in Words (only on last page) -->
            <div class="total-words">
                <p><span class="label">Arrêtée le présent devis à la somme de :</span></p>
                <p>{{ $totalInWords }}</p>
            </div>

            <!-- Signatures (only on last page) - using table layout -->
            <table class="signatures-table">
                <tr>
                    <td class="signature-client">
                        <p class="label">Visa / Signature client:</p>
                        <p>Solde actuel: {{ $currentBalance }}</p>
                    </td>
                    <td class="signature-space"></td>
                    <td class="signature-company">
                        <p class="label">Visa / Signature :</p>
                    </td>
                </tr>
            </table>

            @php
                }
            @endphp

            <!-- Footer -->
            <div class="footer">
                <p>
                    <span>Tél : {{ $contactInfo['tel'] }}</span> | 
                    <span>Mail : {{ $contactInfo['email'] }}</span> | 
                    <span>Web: {{ $contactInfo['web'] }}</span>
                </p>
                <p>Page: {{ $currentPage }} / {{ $totalPages }}</p>
            </div>

            @php
                if ($itemsProcessed < $totalItems) {
                    echo '<div class="page-break"></div>';
                    $currentPage++;
                    // For last page, use the different max rows
                    if ($currentPage == $totalPages) {
                        $itemsPerCurrentPage = $maxRowsLastPage;
                    }
                }
            @endphp
        @endwhile
    </div>
</body>

</html>