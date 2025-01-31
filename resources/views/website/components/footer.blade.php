{{-- resources/views/website/components/footer.blade.php --}}
@if (!empty($hasContactCard))
    <div class="relative -mb-24 z-10">
        <div class="container mx-auto px-4">
            <div class="max-w-xl mx-auto bg-white rounded-xl shadow-2xl p-6 text-center">
                <h2 class="text-xl font-bold mb-2">Vous avez d'autres questions?</h2>
                <p class="text-gray-600 text-sm mb-4">Contactez nous pour avoir les détails de votre demande</p>
                <a href="#" class="btn btn-neutral normal-case text-sm">Nous contacter</a>
            </div>
        </div>
    </div>
@endif

<footer class="bg-neutral text-white py-8 md:py-12">
    <div class="w-full max-w-7xl mx-auto px-4">
        @if (!empty($hasContactCard))
            <div class="mt-24"></div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            {{-- Logo and Contact Info --}}
            <div class="space-y-4">
                <div class="h-16 mb-8 md:mb-0">
                    @include('website.components.brand.logo-dark')
                </div>

                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold">Tel:</span>
                        <a href="tel:+213558335723"
                            class="text-sm text-neutral-400 hover:text-primary transition-colors">
                            +213 558 33 57 23
                        </a>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold">Email:</span>
                        <a href="mailto:+213558335723"
                            class="text-sm text-neutral-400 hover:text-primary transition-colors">
                            +213 558 33 57 23
                        </a>
                    </div>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="md:col-start-3">
                <h3 class="text-neutral-400 text-[0.9rem] mb-4">Liens rapides</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="text-sm hover:text-primary transition-colors">Nos évènements</a>
                    </li>
                    <li>
                        <a href="#" class="text-sm hover:text-primary transition-colors">Nos articles</a>
                    </li>
                    <li>
                        <a href="#" class="text-sm hover:text-primary transition-colors">Termes et conditions</a>
                    </li>
                </ul>
            </div>

            {{-- Enterprise Links --}}
            <div>
                <h3 class="text-neutral-400 text-[0.9rem] mb-4">Entreprise</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="#"
                            class="text-sm hover:text-primary transition-colors inline-flex items-center gap-2">
                            ffp-events.com
                            <x-heroicon-o-plus class="w-4 h-4" />
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="text-sm hover:text-primary transition-colors inline-flex items-center gap-2">
                            Nous contacter
                            <x-heroicon-o-plus class="w-4 h-4" />
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-zinc-700 mt-6"></div>

        {{-- Footer Bottom --}}
        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-xs text-gray-400">
                Droits d'auteur © 2024 <a href="#" class="text-primary">FFP Events</a>. Tous droits réservés.
            </div>

            <div class="flex items-center gap-4">
                <a href="#" class="text-gray-400 hover:text-white transition-colors">
                    <x-heroicon-o-plus class="w-5 h-5" />
                </a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors">
                    <x-heroicon-o-plus class="w-5 h-5" />
                </a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors">
                    <x-heroicon-o-plus class="w-5 h-5" />
                </a>
            </div>
        </div>
    </div>
</footer>
