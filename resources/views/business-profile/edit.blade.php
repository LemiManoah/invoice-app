<x-layouts.app title="Business Profile">
    @php
        $isEditing = $businessProfile !== null;
        $formAction = $isEditing ? route('business-profile.update') : route('business-profile.store');
        $canSave = $isEditing
            ? auth()->user()?->can('update', $businessProfile)
            : auth()->user()?->can('create', \App\Models\BusinessProfile::class);
        $canDelete = $isEditing && auth()->user()?->can('delete', $businessProfile);
    @endphp

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Business Profile</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Set the business details, logo, and signature used across your invoicing workflow.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-900/50 dark:bg-green-900/20 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($isEditing)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Business Information</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Keep these details current so your documents stay accurate.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Business Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $businessProfile?->name) }}" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Location</label>
                        <input type="text" name="location" id="location" value="{{ old('location', $businessProfile?->location) }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                        @error('location')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $businessProfile?->email) }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contacts" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contacts</label>
                        <input type="text" name="contacts" id="contacts" value="{{ old('contacts', $businessProfile?->contacts) }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                        @error('contacts')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                        <textarea name="address" id="address" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">{{ old('address', $businessProfile?->address) }}</textarea>
                        @error('address')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="po_box" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">PO Box</label>
                        <input type="text" name="po_box" id="po_box" value="{{ old('po_box', $businessProfile?->po_box) }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                        @error('po_box')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6 space-y-4">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Business Logo</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Accepted formats: `jpeg`, `jpg`, `png`.</p>
                    </div>

                    @if($businessProfile?->logo_path)
                        <div class="rounded-lg border border-dashed border-gray-300 dark:border-gray-600 p-4">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($businessProfile->logo_path) }}" alt="Business logo"
                                class="max-h-32 rounded-md object-contain">
                        </div>
                    @endif

                    <div>
                        <input type="file" name="logo" id="logo" accept=".jpeg,.jpg,.png,image/jpeg,image/png"
                            class="block w-full text-sm text-gray-600 dark:text-gray-300 file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-300">
                        @error('logo')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($businessProfile?->logo_path)
                        <label class="flex items-center gap-3">
                            <input type="hidden" name="remove_logo" value="0">
                            <input type="checkbox" name="remove_logo" value="1" @checked(old('remove_logo'))
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Remove current logo</span>
                        </label>
                    @endif
                </div>

                <div
                    x-data="signaturePadComponent(@js(old('signature_data')), @js($businessProfile?->signature_path ? \Illuminate\Support\Facades\Storage::url($businessProfile->signature_path) : null))"
                    x-init="init()"
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6 space-y-4">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Company Signature</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Draw a signature in the scratchpad or upload one instead.</p>
                    </div>

                    <div class="rounded-lg border border-dashed border-gray-300 dark:border-gray-600 p-3 bg-gray-50 dark:bg-gray-900/40">
                        <canvas x-ref="canvas" width="700" height="220" class="w-full rounded-md bg-white cursor-crosshair touch-none"></canvas>
                    </div>

                    <input type="hidden" name="signature_data" x-ref="signatureData">

                    <div class="flex flex-wrap gap-3">
                        <button type="button" @click="clearPad()"
                            class="px-3 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50 transition text-sm dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Clear scratchpad
                        </button>
                        <button type="button" @click="useDrawnSignature()"
                            class="px-3 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition text-sm">
                            Save drawn signature
                        </button>
                    </div>

                    <div>
                        <label for="signature_upload" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Upload Signature</label>
                        <input type="file" name="signature_upload" id="signature_upload" accept=".jpeg,.jpg,.png,image/jpeg,image/png"
                            class="block w-full text-sm text-gray-600 dark:text-gray-300 file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-300">
                        @error('signature_upload')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        @error('signature_data')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($businessProfile?->signature_path)
                        <div class="space-y-3">
                            <div class="rounded-lg border border-dashed border-gray-300 dark:border-gray-600 p-4 bg-white">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($businessProfile->signature_path) }}" alt="Saved business signature"
                                    class="max-h-24 object-contain">
                            </div>
                            <label class="flex items-center gap-3">
                                <input type="hidden" name="remove_signature" value="0">
                                <input type="checkbox" name="remove_signature" value="1" @checked(old('remove_signature'))
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Remove current signature</span>
                            </label>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex flex-wrap justify-between gap-3">
            @if($isEditing)
                @if($canDelete)
                    <button type="button" @click="$dispatch('open-delete-modal', { url: '{{ route('business-profile.destroy') }}', item: 'the business profile' })"
                        class="px-4 py-2 rounded-md border border-red-200 text-red-700 hover:bg-red-50 transition text-sm dark:border-red-800 dark:text-red-300 dark:hover:bg-red-900/30">
                        Delete Profile
                    </button>
                @else
                    <span class="text-sm text-gray-500 dark:text-gray-400">You can view this profile, but you do not have permission to delete it.</span>
                @endif
            @else
                <span class="text-sm text-gray-500 dark:text-gray-400">The first save creates the business profile.</span>
            @endif

            <button type="submit" @disabled(! $canSave)
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition disabled:cursor-not-allowed disabled:opacity-60">
                {{ $isEditing ? 'Update Profile' : 'Create Profile' }}
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('signaturePadComponent', (previousData, signatureUrl) => ({
                drawing: false,
                hasStroke: false,
                context: null,

                init() {
                    const canvas = this.$refs.canvas;
                    this.context = canvas.getContext('2d');
                    this.context.lineWidth = 2;
                    this.context.lineCap = 'round';
                    this.context.strokeStyle = '#111827';
                    this.context.fillStyle = '#ffffff';
                    this.context.fillRect(0, 0, canvas.width, canvas.height);

                    if (signatureUrl) {
                        const image = new Image();
                        image.onload = () => {
                            this.context.fillRect(0, 0, canvas.width, canvas.height);
                            this.context.drawImage(image, 20, 20, canvas.width - 40, canvas.height - 40);
                        };
                        image.src = signatureUrl;
                    }

                    if (previousData) {
                        const image = new Image();
                        image.onload = () => {
                            this.context.fillRect(0, 0, canvas.width, canvas.height);
                            this.context.drawImage(image, 0, 0, canvas.width, canvas.height);
                            this.hasStroke = true;
                            this.$refs.signatureData.value = previousData;
                        };
                        image.src = previousData;
                    }

                    const start = (event) => {
                        const point = this.pointFromEvent(event);
                        this.drawing = true;
                        this.hasStroke = true;
                        this.context.beginPath();
                        this.context.moveTo(point.x, point.y);
                        event.preventDefault();
                    };

                    const move = (event) => {
                        if (!this.drawing) {
                            return;
                        }

                        const point = this.pointFromEvent(event);
                        this.context.lineTo(point.x, point.y);
                        this.context.stroke();
                        event.preventDefault();
                    };

                    const end = () => {
                        this.drawing = false;
                    };

                    canvas.addEventListener('mousedown', start);
                    canvas.addEventListener('mousemove', move);
                    canvas.addEventListener('mouseup', end);
                    canvas.addEventListener('mouseleave', end);
                    canvas.addEventListener('touchstart', start, { passive: false });
                    canvas.addEventListener('touchmove', move, { passive: false });
                    canvas.addEventListener('touchend', end);
                },

                pointFromEvent(event) {
                    const rect = this.$refs.canvas.getBoundingClientRect();
                    const touch = event.touches ? event.touches[0] : null;
                    const clientX = touch ? touch.clientX : event.clientX;
                    const clientY = touch ? touch.clientY : event.clientY;
                    const scaleX = this.$refs.canvas.width / rect.width;
                    const scaleY = this.$refs.canvas.height / rect.height;

                    return {
                        x: (clientX - rect.left) * scaleX,
                        y: (clientY - rect.top) * scaleY,
                    };
                },

                clearPad() {
                    const canvas = this.$refs.canvas;
                    this.context.clearRect(0, 0, canvas.width, canvas.height);
                    this.context.fillRect(0, 0, canvas.width, canvas.height);
                    this.$refs.signatureData.value = '';
                    this.hasStroke = false;
                },

                useDrawnSignature() {
                    if (!this.hasStroke) {
                        return;
                    }

                    this.$refs.signatureData.value = this.$refs.canvas.toDataURL('image/png');
                },
            }));
        });
    </script>
</x-layouts.app>
