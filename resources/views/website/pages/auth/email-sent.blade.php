@extends('website.layouts.auth')

@section('content')
<div class="flex justify-center mt-24">
    <div class="card w-full max-w-sm shadow-lg bg-white rounded-lg pt-4 pb-10 px-4">
        <div class="w-1/1 mb-6 mx-auto">
            @include('website.svg.email-sent')
        </div>
        <h2 class="text-xl font-bold text-center mb-2">Email de récupération envoyé</h2>
        <p class="text-xs text-center text-neutral-500">
            Un email de récupération de compte a était envoyé à <strong>amine.mamasse@gmail.com</strong>, veuillez cliquer sur ce dernier pour pouvoir modifier votre mot de passe
        </p>
    </div>
</div>
@endsection
