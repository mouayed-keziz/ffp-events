@props(['event'])

<div class="grid md:grid-cols-3 gap-6">
    {{-- Main Content Column --}}
    <div class="md:col-span-2">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-4xl font-bold mb-6">{{ $event['title'] }}</h2>
            <div class="prose max-w-none">
                {!! $event['description'] !!}
            </div>
        </div>
    </div>

    {{-- Sidebar Column --}}
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-lg p-6 space-y-6">
            {{-- Date Section --}}
            <div class="flex gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 shrink-0 mt-1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
                <div>
                    <h3 class="text-neutral-600 mb-2">Date d'évenement</h3>
                    <p class="font-bold">20 Septembre 2025 -</p>
                    <p class="font-bold">23 Septembre 2025</p>
                </div>
            </div>

            {{-- Location Section --}}
            <div class="flex gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 shrink-0 mt-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
                <div>
                    <h3 class="text-neutral-600 mb-2">Localisation</h3>
                    <p class="font-bold">Palais des expositions de la SAFEX (Pavilion Central) Alger</p>
                </div>
            </div>

            {{-- Registration Deadlines --}}
            <div class="flex gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 shrink-0 mt-1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
                <div>
                    <h3 class="text-neutral-600 mb-2">Date de fin d'inscription exposants</h3>
                    <p class="font-bold">20 Juillet 2025</p>
                </div>
            </div>

            <div class="flex gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 shrink-0 mt-1 invisible">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
                <div>
                    <h3 class="text-neutral-600 mb-2">Date de fin d'inscription visiteurs</h3>
                    <p class="font-bold">20 Septembre 2025</p>
                </div>
            </div>

            {{-- Website --}}
            <div class="flex gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 shrink-0 mt-1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                </svg>
                <div>
                    <h3 class="text-neutral-600 mb-2">Site web</h3>
                    <a href="https://siffp.com" class="font-bold hover:text-primary">siffp.com</a>
                </div>
            </div>

            {{-- Contact Information --}}
            <div class="flex gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 shrink-0 mt-1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                </svg>
                <div>
                    <h3 class="text-neutral-600 mb-2">Contact du responsable du projet</h3>
                    <div class="space-y-1">
                        <p><span class="text-neutral-600">Nom:</span> <span class="font-bold">Amir Rabhi</span></p>
                        <p><span class="text-neutral-600">Email:</span> <a href="mailto:amir.rabhi@ffp-events.com"
                                class="font-bold hover:text-primary">amir.rabhi@ffp-events.com</a></p>
                        <p><span class="text-neutral-600">Tel:</span> <a href="tel:0547874323"
                                class="font-bold hover:text-primary">0547 87 43 23</a></p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col gap-3 pt-4">
                <a href="#"
                    class="btn text-base font-bold btn-outline border-base-200 border-2 normal-case">VISITER</a>
                <a href="#" class="btn text-base font-bold btn-primary normal-case">EXPOSER ET SPONSORISER</a>
            </div>
        </div>
    </div>
</div>
