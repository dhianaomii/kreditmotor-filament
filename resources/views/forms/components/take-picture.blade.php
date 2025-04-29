
<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="{
            photoData: null,
            photoSelected: false,
            webcamError: null,
            initWebcam() {
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then(stream => {
                        this.$refs.video.srcObject = stream;
                    })
                    .catch(error => {
                        console.error('Error accessing webcam:', error);
                        switch (error.name) {
                            case 'NotAllowedError':
                            case 'PermissionDeniedError': // Firefox uses this in older versions
                                this.webcamError = '{{ __('Permission denied. Please allow camera access') }}';
                                break;
                            case 'NotFoundError':
                            case 'DevicesNotFoundError':
                                this.webcamError = '{{ __('No available or connected camera found') }}';
                                break;
                            case 'NotReadableError':
                            case 'TrackStartError': // Old Chrome
                                this.webcamError = '{{ __('The camera is in use by another application or cannot be accessed') }}';
                                break;
                            case 'OverconstrainedError':
                                this.webcamError = '{{ __('Could not meet the requested camera constraints') }}';
                                break;
                            case 'SecurityError':
                                this.webcamError = '{{ __('Access blocked for security reasons. Use HTTPS or a trusted browser') }}';
                                break;
                            case 'AbortError':
                                this.webcamError = '{{ __('The camera access operation was canceled') }}';
                                break;
                            default:
                                this.webcamError = '{{ __('An unknown error occurred while trying to open the camera') }}';
                        }
                    });
            },
            capturePhoto() {
                const video = this.$refs.video;
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                this.photoData = canvas.toDataURL('image/png');
                this.photoSelected = false; // Reset selection when capturing new photo
            },
            usePhoto() {
                $wire.set('{{ $getStatePath() }}', this.photoData);
                this.photoSelected = true;
            }
        }"
        x-init="initWebcam"
        class="flex flex-col space-y-2"
    >
        <!-- Error message if occurs when accessing the camera -->
        <template x-if="webcamError">
            <div class="text-red-600 text-sm">
                <strong>Error:</strong> <span x-text="webcamError"></span>
            </div>
        </template>

        <div class="flex items-center space-x-4">
            <!-- Camera container (only if there's no error) -->
            <template x-if="!webcamError">
                <div @click="capturePhoto" class="w-32 h-32 rounded-full overflow-hidden bg-gray-200 cursor-pointer border border-gray-400 flex items-center justify-center">
                    <video x-ref="video" autoplay class="w-full h-full object-cover"></video>
                </div>
            </template>

            <!-- Preview of the captured photo with "Use this" link -->
            <template x-if="photoData">
                <div class="relative w-32 h-32">
                    <!-- Captured image -->
                    <div
                        :class="photoSelected
                            ? 'w-32 h-32 rounded-full overflow-hidden border-2 border-green-900'
                            : 'w-32 h-32 rounded-full overflow-hidden border border-gray-400'"
                    >
                        <img :src="photoData" class="w-full h-full object-cover">
                        <input {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}" class="hidden">
                    </div>
                    <!-- "Use this" link inside the circle -->
                    <div class="absolute inset-0 flex items-end justify-center pb-4">
                        <a href="#" @click.prevent="usePhoto" class="filepond--label-action">{{ __('Use') }}</a>
                    </div>
                </div>
            </template>
        </div>

        <!-- Hidden field to store the captured photo -->
        <input type="hidden" x-model="photoData" name="{{ $getStatePath() }}">
    </div>
</x-dynamic-component>
