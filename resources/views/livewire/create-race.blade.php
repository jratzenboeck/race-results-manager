<form action="" method="post">
    <h2 class="text-xl my-4">Allgemeine Informationen</h2>

    <div>
        <x-label for="name" :value="__('Name')"/>
        <x-input id="name" class="block mt-1 w-full" type="text" name="name" required maxlength="255"/>
    </div>

    <div class="mt-2">
        <x-label for="location" :value="__('Ort')" />
        <x-input id="location" class="block mt-1 w-full" type="text" name="location" required maxlength="255"/>
    </div>

    <div class="mt-2">
        <x-label for="date" :value="__('Datum')" />
        <x-input type="date" id="date" name="date" class="block mt-1 w-full" required />
    </div>
</form>
