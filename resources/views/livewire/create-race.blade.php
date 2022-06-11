<form wire:submit.prevent="submit" x-data>
    <section>
        <h2 class="text-xl my-4">Allgemeine Informationen</h2>

        <div>
            <x-label for="name" :value="__('Name')"/>
            <x-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required maxlength="255"/>
        </div>

        <div class="mt-2">
            <x-label for="location" :value="__('Ort')" />
            <x-input wire:model="location" id="location" class="block mt-1 w-full" type="text" name="location" required maxlength="255"/>
        </div>

        <div class="mt-2">
            <x-label for="date" :value="__('Datum')" />
            <x-input wire:model="date" type="date" id="date" name="date" class="block mt-1 w-full" required />
        </div>

        <div class="mt-2">
            <x-label for="sport_type" :value="__('Art des Wettkampfs')"></x-label>
            <select wire:model="sport_type" name="sport_type" id="sport_type" class="block mt-1 w-full" required>
                <option value="triathlon">{{ __('Triathlon') }}</option>
                <option value="bike">{{ __('Radrennen') }}</option>
                <option value="run">{{ __('Lauf') }}</option>
            </select>
        </div>
    </section>

    <section x-show="$wire.sport_type">

    <h2 class="text-xl my-4">Wettkampfspezifische Informationen</h2>

        <div x-show="$wire.sport_type == 'triathlon'" class="mt-2">
            <x-label for="triathlon_race_type" :value="__('Art des Triathlons')"/>
            <select wire:model="type" name="type" id="triathlon_race_type" class="block mt-1 w-full">
                <option value="Supersprint Distanz">{{ __('Supersprint Distanz') }}</option>
                <option value="Sprintdistanz">{{ __('Sprintdistanz') }}</option>
                <option value="Olympische Distanz">{{ __('Olympische Distanz') }}</option>
                <option value="Mitteldistanz">{{ __('Mitteldistanz') }}</option>
                <option value="Langdistanz">{{ __('Langdistanz') }}</option>
            </select>
        </div>

        <div x-show="$wire.sport_type == 'bike' || $wire.sport_type == 'run'" class="mt-2">
            <x-label for="bike_or_run_race_type" :value="__('Art des Wettkampfs')"/>
            <x-input wire:model="type" id="bike_or_run_race_type" class="block mt-1 w-full" type="text" name="type"/>
        </div>

        <div x-show="$wire.sport_type == 'triathlon'" class="mt-2">
            <x-label for="swim_distance_in_m" :value="__('Schwimmstrecke in Meter')"/>
            <x-input wire:model="swim_distance_in_m" id="swim_distance_in_m" class="block mt-1 w-full" type="number"
                     name="swim_distance_in_m"/>
        </div>

        <div class="mt-2" x-show="$wire.sport_type == 'triathlon' || $wire.sport_type == 'bike'">
            <x-label for="bike_distance_in_km" :value="__('Radstrecke in Kilometer')"/>
            <x-input wire:model="bike_distance_in_km" id="bike_distance_in_km" class="block mt-1 w-full" type="number"
                     name="bike_distance_in_km"/>
        </div>

        <div class="mt-2" x-show="$wire.sport_type == 'triathlon' || $wire.sport_type == 'run'">
            <x-label for="run_distance_in_km" :value="__('Laufstrecke in Kilometer')"/>
            <x-input wire:model="run_distance_in_km" id="run_distance_in_km" class="block mt-1 w-full" type="number"
                     name="run_distance_in_km"/>
        </div>

        <div class="mt-2" x-show="$wire.sport_type == 'triathlon'">
            <x-label for="swim_venue_type" :value="__('Art des Gewässers')"/>
            <select wire:model="swim_venue_type" name="swim_venue_type" id="swim_venue_type" class="block mt-1 w-full">
                <option value="See" selected>{{ __('See') }}</option>
                <option value="Meer">{{ __('Meer') }}</option>
                <option value="Fluss">{{ __('Fluss') }}</option>
            </select>
        </div>

        <div class="mt-2" x-show="$wire.sport_type == 'triathlon' || $wire.sport_type == 'bike'">
            <x-label for="bike_course_elevation" :value="__('Höhenmeter der Radstrecke')"/>
            <x-input wire:model="bike_course_elevation" id="bike_course_elevation" class="block mt-1 w-full"
                     type="number" name="bike_course_elevation"/>
        </div>

        <div class="mt-2" x-show="$wire.sport_type == 'triathlon' || $wire.sport_type == 'run'">
            <x-label for="run_course_elevation" :value="__('Höhenmeter der Laufstrecke')"/>
            <x-input wire:model="run_course_elevation" id="run_course_elevation" class="block mt-1 w-full" type="number"
                     name="run_course_elevation"/>
        </div>

        <button class="mt-2" type="submit">{{ __('Speichern') }}</button>
    </section>
</form>
