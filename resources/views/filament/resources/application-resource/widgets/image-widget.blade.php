<x-filament-widgets::widget>
        <div class="flex justify-center flex-direction-column mb-3" style="flex-direction: column;gap: 1rem;width: max-content;">
            <img src="{{ $record->user->photo ? asset('storage/' . $record->user->photo) : asset('/default-avatar-icon-of-social-media-user-vector.jpg') }}" alt="Profile Image" class="w-32 h-32 rounded-full d-block" />
        </div>
</x-filament-widgets::widget>
