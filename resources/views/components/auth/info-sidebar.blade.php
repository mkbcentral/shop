{{-- Login Info Sidebar Panel --}}
<div class="hidden lg:flex relative bg-gradient-to-br from-indigo-600 via-purple-600 to-fuchsia-600 p-8 text-white overflow-hidden">
    {{-- Background effects --}}
    <div class="absolute inset-0">
        <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(at 40% 20%, hsla(250,100%,70%,0.4) 0px, transparent 50%),radial-gradient(at 80% 0%, hsla(290,100%,70%,0.3) 0px, transparent 50%);"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/4"></div>
    </div>

    <div class="relative z-10 flex flex-col justify-between w-full h-full">
        {{-- Status badge --}}
        <div class="flex justify-end">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur rounded-full px-4 py-2 text-sm border border-white/20">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                </span>
                <span>Système en ligne</span>
            </div>
        </div>

        {{-- Main content --}}
        <div class="space-y-6">
            {{-- Title --}}
            <div>
                <h3 class="text-3xl font-bold mb-3">
                    Gérez votre commerce<br>
                    <span class="text-amber-300">simplement</span>
                </h3>
                <p class="text-indigo-100 max-w-md">
                    La solution tout-en-un pour piloter votre activité : stock, ventes, clients et rapports.
                </p>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-3">
                <div class="text-center p-3 bg-white/10 backdrop-blur rounded-xl border border-white/20">
                    <div class="text-xl font-bold">500+</div>
                    <div class="text-indigo-200 text-xs">Entreprises</div>
                </div>
                <div class="text-center p-3 bg-white/10 backdrop-blur rounded-xl border border-white/20">
                    <div class="text-xl font-bold">24/7</div>
                    <div class="text-indigo-200 text-xs">Disponibilité</div>
                </div>
                <div class="text-center p-3 bg-white/10 backdrop-blur rounded-xl border border-white/20">
                    <div class="text-xl font-bold">99%</div>
                    <div class="text-indigo-200 text-xs">Satisfaction</div>
                </div>
            </div>

            {{-- Features --}}
            <div class="space-y-2">
                {{-- Stock --}}
                <div class="flex items-center gap-3 p-2.5 bg-white/10 backdrop-blur rounded-lg border border-white/10">
                    <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-sm">Gestion de stock</h4>
                        <p class="text-indigo-200 text-xs">Suivi en temps réel</p>
                    </div>
                </div>

                {{-- POS --}}
                <div class="flex items-center gap-3 p-2.5 bg-white/10 backdrop-blur rounded-lg border border-white/10">
                    <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-sm">Point de vente</h4>
                        <p class="text-indigo-200 text-xs">Interface intuitive</p>
                    </div>
                </div>

                {{-- Reports --}}
                <div class="flex items-center gap-3 p-2.5 bg-white/10 backdrop-blur rounded-lg border border-white/10">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-sm">Rapports</h4>
                        <p class="text-indigo-200 text-xs">Statistiques détaillées</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Testimonial --}}
        <div class="bg-white/10 backdrop-blur rounded-xl p-4 border border-white/20">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-fuchsia-500 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                    MD
                </div>
                <div class="flex-1">
                    <div class="flex gap-0.5 mb-1">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="w-3 h-3 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <p class="text-white/90 text-xs italic">"Une solution complète et intuitive !"</p>
                    <p class="mt-1 text-xs font-semibold">
                        Marie Dupont
                        <span class="font-normal text-indigo-200">• Gérante</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
