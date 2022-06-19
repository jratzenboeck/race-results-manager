<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @if (session()->has('successMessage'))
            <div class="alert alert-success">
                {{ session('successMessage') }}
            </div>
        @endif

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <ul>
                        <li>
                            <div class="flex justify-between"> <!-- header -->
                                <span>
                                    28.05.2022 Linz Triathlon
                                </span>
                                <span>Caret</span>
                            </div>
                            <ul class="my-4"> <!-- Result detail -->
                               <li>
                                   <span class="inline-block w-6">Total:</span>
                                   <span>04:43:20</span>
                               </li>
                                <li>
                                    <span class="inline-block w-6">Swim:</span>
                                    <span>00:33:50</span>
                                </li>
                                <li>
                                    <span class="inline-block w-6">Bike:</span>
                                    <span>02:30:50</span>
                                </li>
                                <li>
                                    <span class="inline-block w-6">Run:</span>
                                    <span>01:30:52</span>
                                </li>
                            </ul>
                            <x-button>{{ __('Details') }}</x-button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
