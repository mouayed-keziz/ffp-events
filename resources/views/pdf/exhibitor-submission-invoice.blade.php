<!DOCTYPE html>
<html dir="{{ App::getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme="ffp-theme-light" lang="{{ App::getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bon de commande</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap');
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-start mb-8">
            <div>
                @include('website.components.brand.logo')
            </div>
            <div class="text-right">
                <p class="text-sm">EURL FFP EVENTS</p>
                <p class="text-sm">Cité 20 Aout 55 Ouest romane N°76, 1er étage</p>
                <p class="text-sm">El Achour (Alger), Algérie</p>
                <p class="text-sm">Capital social : 100 000,00 DA</p>
                <p class="text-sm">RC N° : 17B1012150-00/16</p>
                <p class="text-sm">NIF : 001716102154114</p>
                <p class="text-sm">AI : 16540510921</p>
                <p class="text-sm">NIS : 001716540169038</p>
            </div>
        </div>

        <!-- Invoice Title -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold">Bon de commande N° D232/2023</h1>
        </div>

        <!-- Invoice Info -->
        <div class="flex justify-between mb-8">
            <div>
                <p class="text-sm">Date: 05/05/2023</p>
                <p class="text-sm">Vendeur: Hanaa Sellaj</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold">SH DESIGN</p>
                <p class="text-sm">(Alger), Algérie</p>
                <p class="text-sm">Mobile:0559123490</p>
            </div>
        </div>

        <!-- Invoice Table -->
        <table class="w-full mb-8">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">DESCRIPTION</th>
                    <th class="text-center py-2">QUANTITÉ</th>
                    <th class="text-right py-2">PRIX UNITAIRE</th>
                    <th class="text-right py-2">REM (%)</th>
                    <th class="text-right py-2">TAXES</th>
                    <th class="text-right py-2">MONTANT</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b">
                    <td class="py-2">Location d'un espace d'exposition non aménagé sur cuisine balnexpo 2023</td>
                    <td class="text-center py-2">25.0 U(m²/u)</td>
                    <td class="text-right py-2">12 600,00</td>
                    <td class="text-right py-2">15,00</td>
                    <td class="text-right py-2">TVA 19%</td>
                    <td class="text-right py-2">478 850,00 DA</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2">Majoration façade</td>
                    <td class="text-center py-2">1.0 U(m²/u)</td>
                    <td class="text-right py-2">7 000,00</td>
                    <td class="text-right py-2">15,00</td>
                    <td class="text-right py-2">TVA 19%</td>
                    <td class="text-right py-2">5 950,00 DA</td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>

        <!-- Totals -->
        <div class="flex justify-end mb-8">
            <div class="w-64">
                <div class="flex justify-between border-b py-2">
                    <span>Sous-total</span>
                    <span>636 050,00 DA</span>
                </div>
                <div class="flex justify-between border-b py-2">
                    <span>Total Discount</span>
                    <span>76 990,00 DA</span>
                </div>
                <div class="flex justify-between border-b py-2">
                    <span>TVA 19%</span>
                    <span>74 774,50 DA</span>
                </div>
                <div class="flex justify-between font-bold py-2">
                    <span>Total</span>
                    <span>510 824,50 DA</span>
                </div>
            </div>
        </div>

        <!-- Signatures -->
        <div class="flex justify-between mt-8">
            <div class="w-1/3 border-t pt-4">
                <p class="text-sm">Visa / Signature client:</p>
                <p class="text-sm">Solde actuel: 510 824,50 DA</p>
            </div>
            <div class="w-1/3 border-t pt-4 text-right">
                <p class="text-sm">Visa / Signature :</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-sm mt-16 pt-8 border-t">
            <p>Tél. : 0552 67 37 56 Mail : Contact@ffp-events.com Web: http://www.ffp-events.com</p>
            <p>Page: 1 / 1</p>
        </div>
    </div>
</body>
</html>
