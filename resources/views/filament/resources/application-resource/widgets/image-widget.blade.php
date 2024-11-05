<x-filament-widgets::widget>
        <div class="flex justify-center flex-direction-column mb-3" style="flex-direction: column;gap: 1rem;width: max-content;">
            <img src="{{ asset('storage/' . $record->user->photo) }}" alt="Profile Image" class="w-32 h-32 rounded-full d-block" />
        </div>
</x-filament-widgets::widget>
