@extends('website.layouts.app')

@php
    $event = [
        'id' => "1",
        'title' => 'Forum International de l\'Économie Sociale et Solidaire',
        'image' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=2070&auto=format&fit=crop',
        'location' => 'Palais des Congrès, Tunis',
        'dateStart' => '12 Mars 2024',
        'dateEnd' => '14 Mars 2024',
        'countdown' => [
            'days' => '45',
            'hours' => '12',
            'minutes' => '30',
            'seconds' => '00',
        ],
        'description' => '
            <p class="mb-4">Le Forum International de l\'Économie Sociale et Solidaire (FIESS) est un événement majeur qui rassemble les acteurs de l\'économie sociale et solidaire du monde entier.</p>
            <h3 class="text-xl font-bold mb-3">À propos de l\'événement</h3>
            <p class="mb-4">Pendant trois jours, les participants auront l\'occasion de :</p>
            <ul class="list-disc list-inside space-y-2 mb-4">
                <li>Échanger sur les bonnes pratiques</li>
                <li>Participer à des ateliers thématiques</li>
                <li>Rencontrer des experts internationaux</li>
                <li>Développer leur réseau professionnel</li>
            </ul>
            <p class="mb-4">Le Forum International de l\'Économie Sociale et Solidaire (FIESS) est un événement majeur qui rassemble les acteurs de l\'économie sociale et solidaire du monde entier.</p>
            <h3 class="text-xl font-bold mb-3">À propos de l\'événement</h3>
            <p class="mb-4">Pendant trois jours, les participants auront l\'occasion de :</p>
            <ul class="list-disc list-inside space-y-2 mb-4">
                <li>Échanger sur les bonnes pratiques</li>
                <li>Participer à des ateliers thématiques</li>
                <li>Rencontrer des experts internationaux</li>
                <li>Développer leur réseau professionnel</li>
            </ul>
            <p class="mb-4">Le Forum International de l\'Économie Sociale et Solidaire (FIESS) est un événement majeur qui rassemble les acteurs de l\'économie sociale et solidaire du monde entier.</p>
            <h3 class="text-xl font-bold mb-3">À propos de l\'événement</h3>
            <p class="mb-4">Pendant trois jours, les participants auront l\'occasion de :</p>
            <ul class="list-disc list-inside space-y-2 mb-4">
                <li>Échanger sur les bonnes pratiques</li>
                <li>Participer à des ateliers thématiques</li>
                <li>Rencontrer des experts internationaux</li>
                <li>Développer leur réseau professionnel</li>
            </ul>
            <p class="mb-4">Le Forum International de l\'Économie Sociale et Solidaire (FIESS) est un événement majeur qui rassemble les acteurs de l\'économie sociale et solidaire du monde entier.</p>
            <h3 class="text-xl font-bold mb-3">À propos de l\'événement</h3>
            <p class="mb-4">Pendant trois jours, les participants auront l\'occasion de :</p>
            <ul class="list-disc list-inside space-y-2 mb-4">
                <li>Échanger sur les bonnes pratiques</li>
                <li>Participer à des ateliers thématiques</li>
                <li>Rencontrer des experts internationaux</li>
                <li>Développer leur réseau professionnel</li>
            </ul>',
        'registrationDeadlines' => [
            'exhibitors' => '1 Février 2024',
            'visitors' => '5 Mars 2024',
        ],
        'website' => 'www.fiess2024.org',
        'projectManager' => [
            'name' => 'Sarah Ben Ahmed',
            'email' => 'contact@fiess2024.org',
            'phone' => '+216 71 234 567',
        ],
    ];
@endphp

@section('content')
    @include('website.components.event.banner', ['event' => $event])
    <div class="w-full max-w-7xl mx-auto px-4 py-8">
        @include('website.components.event.details', ['event' => $event])
    </div>
    <div class="w-full max-w-7xl mx-auto px-4 py-8">
        @include('website.components.home.events')
    </div>
@endsection
