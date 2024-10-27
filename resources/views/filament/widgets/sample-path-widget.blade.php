<x-filament::widget>
    <x-filament::card>
        @php
            $row = App\Models\Sample::first();
        @endphp
        <h1>Upload Files</h1>
        <br>

        <!-- Success message -->
        @if(session('success'))
            <div style="color: green;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error messages -->
        @if ($errors->any())
            <div style="color: red;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form to upload files -->
        <form action="{{ route('sample.updateOrCreate') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="file">Video 1:</label>
                <input type="file" name="path" id="file">
            </div>
            <br>
            <div>
                <label for="file2">Video 2:</label>
                <input type="file" name="path_2" id="file2">
            </div>
            <br>
            <button type="submit" style="background: #851c1f;color: #fff;padding: 4px 16px;border-radius: 8px">Submit</button>        </form>
    </x-filament::card>
</x-filament::widget>
