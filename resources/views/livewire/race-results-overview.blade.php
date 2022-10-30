<ul>
    @forelse($raceResults as $raceResult)
        <li x-data="{ expanded: false }">
            <div class="flex justify-between"> <!-- header -->
                <span>{{ $raceResult }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"
                     @click="expanded = !expanded">
                    <path fill-rule="evenodd"
                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                          clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="my-4" x-show="expanded" x-transition.duration.500ms>  <!-- Result detail -->
                <p>
                    <span class="inline-block w-6">Total:</span>
                    <span>{{ $raceResult->total_time }}</span>
                </p>
                <x-button>{{ __('Details') }}</x-button>
            </div>
        </li>
    @empty
        <p>Keine Rennergebnisse</p>
    @endforelse
</ul>
