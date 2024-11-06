<x-filament-widgets::widget>
    <style>
        .video-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .fi-section {
            height: 100%;
        }
        .video-controls {
            margin-top: 10px;
        }

        .control-button,
        .play-button {
            margin: 5px;
            padding: 8px 8px;
            background-color: #cf2e32;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .control-button:hover,
        .play-button:hover {
            background-color: #c6494b;
        }

        .video-controls svg {
            width: 30px
        }
    </style>
    <x-filament::section>
        <div class="video-section">
            <!-- Display the two videos: sample_2 and video_2 -->
            <div class="video-wrapper">
                <video id="sample_2" width="320" style="width: 100%;">
                    <source src="{{ $sample2 }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <video id="video_2" width="320" style="width: 100%">
                    <source src="{{ $video2 }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <div class="video-controls">
                    <button id="prev10_videos_2" class="control-button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 16.811c0 .864-.933 1.406-1.683.977l-7.108-4.061a1.125 1.125 0 0 1 0-1.954l7.108-4.061A1.125 1.125 0 0 1 21 8.689v8.122ZM11.25 16.811c0 .864-.933 1.406-1.683.977l-7.108-4.061a1.125 1.125 0 0 1 0-1.954l7.108-4.061a1.125 1.125 0 0 1 1.683.977v8.122Z" />
                        </svg>
                    </button>
                    <button id="play_resume_videos_2" class="play-button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5}
                            stroke="currentColor" className="size-6">
                            <path strokeLinecap="round" strokeLinejoin="round"
                                d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            <path strokeLinecap="round" strokeLinejoin="round"
                                d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" />
                        </svg>
                    </button>
                    <button id="next10_videos_2" class="control-button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061A1.125 1.125 0 0 1 3 16.811V8.69ZM12.75 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061a1.125 1.125 0 0 1-1.683-.977V8.69Z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </x-filament::section>
    <script>
        // Function to toggle between play and pause for the first pair (sample_2 and video_2)
        document.getElementById('play_resume_videos_2').addEventListener('click', function() {
            var sample2 = document.getElementById('sample_2');
            var video2 = document.getElementById('video_2');
            var button = document.getElementById('play_resume_videos_2');

            if (sample2.paused || video2.paused) {
                sample2.play();
                video2.play();
                button.innerHTML  = `
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9v6m-4.5 0V9M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                `; // Change to 'Pause' when playing
            } else {
                sample2.pause();
                video2.pause();
                button.innerHTML  = `
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5}
                            stroke="currentColor" className="size-6">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            <path strokeLinecap="round" strokeLinejoin="round"
                                d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" />
                        </svg>
`; // Change to 'Play/Resume' when paused
            }
        });

        // Function to update button when the video ends for the first pair (sample_2 and video_2)
        var sample2 = document.getElementById('sample_2');
        var video2 = document.getElementById('video_2');
        sample2.addEventListener('ended', function() {
            button.innerHTML  = `
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5}
                            stroke="currentColor" className="size-6">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            <path strokeLinecap="round" strokeLinejoin="round"
                                d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" />
                        </svg>
`; // Change to 'Play/Resume' when paused
        });
        video2.addEventListener('ended', function() {
            button.innerHTML  = `
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5}
                            stroke="currentColor" className="size-6">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            <path strokeLinecap="round" strokeLinejoin="round"
                                d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" />
                        </svg>
`; // Change to 'Play/Resume' when paused
        });

        // Function to skip forward 10 seconds for the first pair (sample_2 and video_2)
        document.getElementById('next10_videos_2').addEventListener('click', function() {
            sample2.currentTime += 10;
            video2.currentTime += 10;
        });

        // Function to skip backward 10 seconds for the first pair (sample_2 and video_2)
        document.getElementById('prev10_videos_2').addEventListener('click', function() {
            sample2.currentTime -= 10;
            video2.currentTime -= 10;
        });
    </script>
</x-filament-widgets::widget>
