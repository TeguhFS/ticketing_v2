<x-officer-layout>

    <div class="mb-5">
        <h1 class="text-lg font-bold text-gray-900">Scan Tiket</h1>
        <p class="text-xs text-gray-400 mt-0.5">Scan QR code atau input manual kode tiket</p>
    </div>

    {{-- Pilih Event --}}
    <div class="mb-4" x-data="{ selectedEvent: '{{ $events->first()?->id ?? '' }}' }">
        <label class="text-xs font-medium text-gray-500 block mb-1.5">Event</label>
        <select x-model="selectedEvent" id="event-select"
            class="w-full h-11 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
            @foreach ($events as $event)
                <option value="{{ $event->id }}">{{ $event->title }}</option>
            @endforeach
        </select>
    </div>

    {{-- QR Scanner --}}
    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden mb-4" x-data="qrScanner()">

        {{-- Tab Mode --}}
        <div class="flex border-b border-gray-100">
            <button @click="mode = 'camera'"
                :class="mode === 'camera' ? 'border-b-2 border-gray-900 text-gray-900' : 'text-gray-400'"
                class="flex-1 py-3 text-sm font-medium flex items-center justify-center gap-2 transition">
                <i class="ti ti-camera text-base"></i> Kamera
            </button>
            <button @click="mode = 'manual'"
                :class="mode === 'manual' ? 'border-b-2 border-gray-900 text-gray-900' : 'text-gray-400'"
                class="flex-1 py-3 text-sm font-medium flex items-center justify-center gap-2 transition">
                <i class="ti ti-keyboard text-base"></i> Manual
            </button>
        </div>

        {{-- Camera Mode --}}
        <div x-show="mode === 'camera'" class="p-4">
            <div class="relative bg-gray-900 rounded-xl overflow-hidden aspect-square mb-4">
                <video id="qr-video" class="w-full h-full object-cover" playsinline></video>

                {{-- Scanning Overlay --}}
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="relative w-48 h-48">
                        {{-- Corner borders --}}
                        <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-white rounded-tl-lg">
                        </div>
                        <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-white rounded-tr-lg">
                        </div>
                        <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-white rounded-bl-lg">
                        </div>
                        <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-white rounded-br-lg">
                        </div>
                        {{-- Scan line --}}
                        <div class="absolute top-0 left-0 right-0 h-0.5 bg-emerald-400 opacity-80"
                            style="animation: scan 2s linear infinite;">
                        </div>
                    </div>
                </div>

                {{-- Status overlay --}}
                <div x-show="scanning" class="absolute bottom-3 left-0 right-0 flex justify-center">
                    <span class="bg-black/60 text-white text-xs px-3 py-1.5 rounded-full backdrop-blur-sm">
                        <i class="ti ti-scan text-sm mr-1"></i> Arahkan QR code ke kamera
                    </span>
                </div>
            </div>

            <button @click="toggleCamera()"
                :class="scanning ? 'bg-red-500 hover:bg-red-600' : 'bg-gray-900 hover:bg-gray-700'"
                class="w-full h-11 text-white text-sm rounded-xl transition font-medium flex items-center justify-center gap-2">
                <i class="ti text-base" :class="scanning ? 'ti-player-stop' : 'ti-camera'"></i>
                <span x-text="scanning ? 'Stop Kamera' : 'Buka Kamera'"></span>
            </button>
        </div>

        {{-- Manual Mode --}}
        <div x-show="mode === 'manual'" class="p-4">
            <div class="mb-4">
                <label class="text-xs font-medium text-gray-500 block mb-1.5">Kode Tiket</label>
                <input type="text" x-model="manualCode" placeholder="Masukkan kode tiket..."
                    @keyup.enter="submitManual()"
                    class="w-full h-11 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white uppercase tracking-widest font-mono">
            </div>
            <button @click="submitManual()" :disabled="!manualCode || loading"
                class="w-full h-11 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition font-medium flex items-center justify-center gap-2 disabled:opacity-50">
                <i class="ti ti-search text-base"></i> Cek Tiket
            </button>
        </div>

    </div>

    {{-- Result Card --}}
    <div x-data="scanResult()" @scan-result.window="showResult($event.detail)" class="mb-4">
        <div x-show="result" x-transition class="rounded-2xl border-2 p-5"
            :class="{
                'bg-emerald-50 border-emerald-200': result?.status === 'valid',
                'bg-red-50 border-red-200': result?.status === 'invalid',
                'bg-amber-50 border-amber-200': result?.status === 'already_used',
            }">

            {{-- Icon --}}
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
                    :class="{
                        'bg-emerald-500': result?.status === 'valid',
                        'bg-red-500': result?.status === 'invalid',
                        'bg-amber-500': result?.status === 'already_used',
                    }">
                    <i class="ti text-white text-2xl"
                        :class="{
                            'ti-circle-check': result?.status === 'valid',
                            'ti-circle-x': result?.status === 'invalid',
                            'ti-clock': result?.status === 'already_used',
                        }"></i>
                </div>
                <div>
                    <p class="text-base font-bold"
                        :class="{
                            'text-emerald-700': result?.status === 'valid',
                            'text-red-700': result?.status === 'invalid',
                            'text-amber-700': result?.status === 'already_used',
                        }"
                        x-text="result?.status === 'valid' ? 'TIKET VALID ✓' : (result?.status === 'already_used' ? 'SUDAH DIGUNAKAN' : 'TIKET TIDAK VALID')">
                    </p>
                    <p class="text-sm mt-0.5"
                        :class="{
                            'text-emerald-600': result?.status === 'valid',
                            'text-red-600': result?.status === 'invalid',
                            'text-amber-600': result?.status === 'already_used',
                        }"
                        x-text="result?.message">
                    </p>
                </div>
            </div>

            {{-- Detail --}}
            <template x-if="result?.holder">
                <div class="space-y-2 border-t pt-3"
                    :class="{
                        'border-emerald-200': result?.status === 'valid',
                        'border-amber-200': result?.status === 'already_used',
                    }">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Pemegang</span>
                        <span class="font-semibold text-gray-900" x-text="result?.holder"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Event</span>
                        <span class="font-medium text-gray-700 text-right max-w-[60%]" x-text="result?.event"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Tipe Tiket</span>
                        <span class="text-gray-700" x-text="result?.ticket_type"></span>
                    </div>
                    <div class="flex justify-between text-sm" x-show="result?.used_at">
                        <span class="text-gray-500">Digunakan</span>
                        <span class="text-amber-700 font-medium" x-text="result?.used_at"></span>
                    </div>
                    <div class="flex justify-between text-sm" x-show="result?.scanned_at">
                        <span class="text-gray-500">Scan pada</span>
                        <span class="text-emerald-700 font-medium" x-text="result?.scanned_at"></span>
                    </div>
                </div>
            </template>

            {{-- Kode --}}
            <div class="mt-3 text-center">
                <span class="font-mono text-xs bg-white/60 px-3 py-1.5 rounded-lg" x-text="result?.code"></span>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
        <style>
            @keyframes scan {
                0% {
                    top: 0;
                }

                100% {
                    top: calc(100% - 2px);
                }
            }
        </style>
        <script>
            const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
            const VALIDATE_URL = '{{ route('officer.scan.validate') }}';

            function getEventId() {
                return document.getElementById('event-select').value;
            }

            async function validateTicket(code) {
                const res = await fetch(VALIDATE_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                    },
                    body: JSON.stringify({
                        ticket_code: code,
                        event_id: getEventId(),
                    }),
                });
                return await res.json();
            }

            function qrScanner() {
                return {
                    mode: 'camera',
                    scanning: false,
                    loading: false,
                    manualCode: '',
                    videoStream: null,
                    animFrame: null,

                    async toggleCamera() {
                        if (this.scanning) {
                            this.stopCamera();
                        } else {
                            await this.startCamera();
                        }
                    },

                    async startCamera() {
                        try {
                            this.videoStream = await navigator.mediaDevices.getUserMedia({
                                video: {
                                    facingMode: 'environment'
                                }
                            });
                            const video = document.getElementById('qr-video');
                            video.srcObject = this.videoStream;
                            await video.play();
                            this.scanning = true;
                            this.scanLoop(video);
                        } catch (err) {
                            alert('Tidak dapat mengakses kamera: ' + err.message);
                        }
                    },

                    stopCamera() {
                        if (this.videoStream) {
                            this.videoStream.getTracks().forEach(t => t.stop());
                            this.videoStream = null;
                        }
                        if (this.animFrame) {
                            cancelAnimationFrame(this.animFrame);
                            this.animFrame = null;
                        }
                        this.scanning = false;
                    },

                    scanLoop(video) {
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');

                        const tick = async () => {
                            if (!this.scanning) return;
                            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                                canvas.width = video.videoWidth;
                                canvas.height = video.videoHeight;
                                ctx.drawImage(video, 0, 0);
                                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                                const code = jsQR(imageData.data, canvas.width, canvas.height);

                                if (code && code.data) {
                                    this.stopCamera();
                                    const result = await validateTicket(code.data);
                                    window.dispatchEvent(new CustomEvent('scan-result', {
                                        detail: result
                                    }));
                                    return;
                                }
                            }
                            this.animFrame = requestAnimationFrame(tick);
                        };

                        this.animFrame = requestAnimationFrame(tick);
                    },

                    async submitManual() {
                        if (!this.manualCode || this.loading) return;
                        this.loading = true;
                        try {
                            const result = await validateTicket(this.manualCode);
                            window.dispatchEvent(new CustomEvent('scan-result', {
                                detail: result
                            }));
                            this.manualCode = '';
                        } finally {
                            this.loading = false;
                        }
                    },
                }
            }

            function scanResult() {
                return {
                    result: null,
                    showResult(data) {
                        this.result = data;
                        // Vibrate feedback
                        if ('vibrate' in navigator) {
                            navigator.vibrate(data.status === 'valid' ? [100] : [100, 50, 100]);
                        }
                        // Auto clear after 10 detik
                        setTimeout(() => this.result = null, 10000);
                    }
                }
            }
        </script>
    @endpush

</x-officer-layout>
