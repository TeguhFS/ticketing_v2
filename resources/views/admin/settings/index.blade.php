<x-app-layout>
    <x-slot name="title">Settings</x-slot>

    @if (session('success'))
        <div
            class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
            <i class="ti ti-circle-check text-base"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Tab Navigation --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden" x-data="{ tab: 'general' }">

        {{-- Tab Header --}}
        <div class="flex border-b border-gray-100 overflow-x-auto">
            @foreach ([['key' => 'general', 'icon' => 'ti-settings', 'label' => 'General'], ['key' => 'social', 'icon' => 'ti-share', 'label' => 'Social Media'], ['key' => 'seo', 'icon' => 'ti-search', 'label' => 'SEO']] as $tab)
                <button @click="tab = '{{ $tab['key'] }}'"
                    :class="tab === '{{ $tab['key'] }}'
                        ?
                        'border-b-2 border-gray-900 text-gray-900 bg-gray-50/50' :
                        'text-gray-400 hover:text-gray-600'"
                    class="flex items-center gap-2 px-6 py-4 text-sm font-medium transition whitespace-nowrap flex-shrink-0">
                    <i class="ti {{ $tab['icon'] }} text-base"></i>
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>

        {{-- ─── General Tab ──────────────────────────────────────── --}}
        <div x-show="tab === 'general'" x-transition class="p-6">
            <form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PATCH')

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                    {{-- Left --}}
                    <div class="xl:col-span-2 space-y-5">

                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-1">Informasi Aplikasi</h3>
                            <p class="text-xs text-gray-400">Pengaturan dasar nama dan identitas aplikasi.</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                    Nama Aplikasi <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="app_name"
                                    value="{{ $settings['app_name']->value ?? 'TicketIn' }}"
                                    class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                            </div>

                            <div class="col-span-2">
                                <label class="text-xs font-medium text-gray-500 block mb-1.5">Deskripsi</label>
                                <textarea name="app_description" rows="3" placeholder="Deskripsi singkat aplikasi..."
                                    class="w-full px-3 py-2.5 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white resize-none">{{ $settings['app_description']->value ?? '' }}</textarea>
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-500 block mb-1.5">Email Kontak</label>
                                <div class="relative">
                                    <i
                                        class="ti ti-mail absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                                    <input type="email" name="app_email"
                                        value="{{ $settings['app_email']->value ?? '' }}"
                                        placeholder="hello@example.com"
                                        class="w-full h-10 pl-9 pr-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                                </div>
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-500 block mb-1.5">Nomor Telepon</label>
                                <div class="relative">
                                    <i
                                        class="ti ti-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                                    <input type="text" name="app_phone"
                                        value="{{ $settings['app_phone']->value ?? '' }}"
                                        placeholder="+62 8xx-xxxx-xxxx"
                                        class="w-full h-10 pl-9 pr-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                                </div>
                            </div>

                            <div class="col-span-2">
                                <label class="text-xs font-medium text-gray-500 block mb-1.5">Alamat</label>
                                <div class="relative">
                                    <i class="ti ti-map-pin absolute left-3 top-3 text-gray-300 text-sm"></i>
                                    <textarea name="app_address" rows="2" placeholder="Alamat lengkap..."
                                        class="w-full pl-9 pr-3 py-2.5 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white resize-none">{{ $settings['app_address']->value ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Right: Logo & Favicon --}}
                    <div class="space-y-5">

                        {{-- Logo --}}
                        <div x-data="imagePreview('{{ $settings['app_logo']->value ? Storage::url($settings['app_logo']->value) : '' }}')">
                            <label class="text-xs font-medium text-gray-500 block mb-3">Logo Aplikasi</label>
                            <div class="border-2 border-dashed border-gray-200 rounded-2xl overflow-hidden cursor-pointer hover:border-gray-300 transition h-32 flex items-center justify-center bg-gray-50"
                                @click="$refs.logoInput.click()">
                                <img x-show="preview" :src="preview"
                                    class="max-h-24 max-w-full object-contain p-3" alt="">
                                <div x-show="!preview" class="text-center">
                                    <i class="ti ti-photo text-3xl text-gray-200 block mb-1.5"></i>
                                    <p class="text-xs text-gray-400">Upload logo</p>
                                    <p class="text-xs text-gray-300 mt-0.5">PNG, SVG, WEBP</p>
                                </div>
                            </div>
                            <input type="file" name="app_logo" accept="image/*" x-ref="logoInput"
                                @change="showPreview($event)" class="hidden">
                        </div>

                        {{-- Favicon --}}
                        <div x-data="imagePreview('{{ $settings['app_favicon']->value ? Storage::url($settings['app_favicon']->value) : '' }}')">
                            <label class="text-xs font-medium text-gray-500 block mb-3">Favicon</label>
                            <div class="border-2 border-dashed border-gray-200 rounded-2xl overflow-hidden cursor-pointer hover:border-gray-300 transition h-24 flex items-center justify-center bg-gray-50"
                                @click="$refs.favInput.click()">
                                <img x-show="preview" :src="preview" class="w-12 h-12 object-contain"
                                    alt="">
                                <div x-show="!preview" class="text-center">
                                    <i class="ti ti-photo text-2xl text-gray-200 block mb-1"></i>
                                    <p class="text-xs text-gray-400">Upload favicon</p>
                                    <p class="text-xs text-gray-300 mt-0.5">ICO, PNG max 512KB</p>
                                </div>
                            </div>
                            <input type="file" name="app_favicon" accept="image/*" x-ref="favInput"
                                @change="showPreview($event)" class="hidden">
                        </div>

                    </div>

                </div>

                {{-- Save --}}
                <div class="flex justify-end mt-6 pt-5 border-t border-gray-100">
                    <button type="submit"
                        class="inline-flex items-center gap-2 h-10 px-6 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition font-medium">
                        <i class="ti ti-device-floppy text-base"></i> Simpan Pengaturan
                    </button>
                </div>

            </form>
        </div>

        {{-- ─── Social Media Tab ─────────────────────────────────── --}}
        <div x-show="tab === 'social'" x-transition class="p-6">
            <form action="{{ route('admin.settings.social.update') }}" method="POST">
                @csrf @method('PATCH')

                <div class="max-w-2xl">

                    <div class="mb-5">
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">Social Media</h3>
                        <p class="text-xs text-gray-400">Tambahkan link media sosial yang akan ditampilkan di website.
                        </p>
                    </div>

                    <div class="space-y-4">

                        @foreach ([['key' => 'social_instagram', 'label' => 'Instagram', 'icon' => 'ti-brand-instagram', 'color' => 'text-pink-500', 'placeholder' => 'https://instagram.com/username'], ['key' => 'social_twitter', 'label' => 'Twitter / X', 'icon' => 'ti-brand-x', 'color' => 'text-gray-900', 'placeholder' => 'https://twitter.com/username'], ['key' => 'social_facebook', 'label' => 'Facebook', 'icon' => 'ti-brand-facebook', 'color' => 'text-blue-600', 'placeholder' => 'https://facebook.com/page'], ['key' => 'social_youtube', 'label' => 'YouTube', 'icon' => 'ti-brand-youtube', 'color' => 'text-red-500', 'placeholder' => 'https://youtube.com/@channel'], ['key' => 'social_tiktok', 'label' => 'TikTok', 'icon' => 'ti-brand-tiktok', 'color' => 'text-gray-900', 'placeholder' => 'https://tiktok.com/@username'], ['key' => 'social_whatsapp', 'label' => 'WhatsApp', 'icon' => 'ti-brand-whatsapp', 'color' => 'text-emerald-500', 'placeholder' => '6281234567890']] as $social)
                            <div>
                                <label
                                    class="text-xs font-medium text-gray-500 block mb-1.5">{{ $social['label'] }}</label>
                                <div class="relative">
                                    <i
                                        class="ti {{ $social['icon'] }} {{ $social['color'] }} absolute left-3 top-1/2 -translate-y-1/2 text-lg"></i>
                                    <input type="{{ $social['key'] === 'social_whatsapp' ? 'text' : 'url' }}"
                                        name="{{ $social['key'] }}"
                                        value="{{ $settings[$social['key']]->value ?? '' }}"
                                        placeholder="{{ $social['placeholder'] }}"
                                        class="w-full h-10 pl-10 pr-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                                </div>
                            </div>
                        @endforeach

                    </div>

                </div>

                <div class="flex justify-end mt-6 pt-5 border-t border-gray-100">
                    <button type="submit"
                        class="inline-flex items-center gap-2 h-10 px-6 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition font-medium">
                        <i class="ti ti-device-floppy text-base"></i> Simpan Social Media
                    </button>
                </div>

            </form>
        </div>

        {{-- ─── SEO Tab ───────────────────────────────────────────── --}}
        <div x-show="tab === 'seo'" x-transition class="p-6">
            <form action="{{ route('admin.settings.seo.update') }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PATCH')

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                    {{-- Left --}}
                    <div class="xl:col-span-2 space-y-5">

                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-1">SEO Settings</h3>
                            <p class="text-xs text-gray-400">Optimasi mesin pencari untuk website Anda.</p>
                        </div>

                        <div class="space-y-4">

                            <div>
                                <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                    Meta Title
                                    <span class="text-gray-400 font-normal ml-1">max 70 karakter</span>
                                </label>
                                <input type="text" name="seo_title" maxlength="70"
                                    value="{{ $settings['seo_title']->value ?? '' }}"
                                    placeholder="Judul halaman yang tampil di hasil pencarian..."
                                    class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white"
                                    x-data x-on:input="$el.nextElementSibling.textContent = $el.value.length + '/70'">
                                <p class="text-xs text-gray-400 mt-1 text-right">
                                    {{ strlen($settings['seo_title']->value ?? '') }}/70
                                </p>
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                    Meta Description
                                    <span class="text-gray-400 font-normal ml-1">max 160 karakter</span>
                                </label>
                                <textarea name="seo_description" rows="3" maxlength="160"
                                    placeholder="Deskripsi singkat yang tampil di hasil pencarian Google..."
                                    class="w-full px-3 py-2.5 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white resize-none"
                                    x-data x-on:input="$el.nextElementSibling.textContent = $el.value.length + '/160'">{{ $settings['seo_description']->value ?? '' }}</textarea>
                                <p class="text-xs text-gray-400 mt-1 text-right">
                                    {{ strlen($settings['seo_description']->value ?? '') }}/160
                                </p>
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-500 block mb-1.5">
                                    Keywords
                                    <span class="text-gray-400 font-normal ml-1">pisahkan dengan koma</span>
                                </label>
                                <input type="text" name="seo_keywords"
                                    value="{{ $settings['seo_keywords']->value ?? '' }}"
                                    placeholder="tiket, event, konser, seminar..."
                                    class="w-full h-10 px-3 border border-gray-100 rounded-xl text-sm text-gray-700 outline-none focus:border-gray-300 bg-white">
                            </div>

                        </div>

                        {{-- Preview --}}
                        <div class="border border-gray-100 rounded-2xl p-4 bg-gray-50/50" x-data="{
                            title: '{{ addslashes($settings['seo_title']->value ?? 'Meta Title') }}',
                            desc: '{{ addslashes($settings['seo_description']->value ?? 'Meta Description') }}'
                        }"
                            x-init="document.querySelector('[name=seo_title]').addEventListener('input', e => title = e.target.value || 'Meta Title');
                            document.querySelector('[name=seo_description]').addEventListener('input', e => desc = e.target.value || 'Meta Description');">
                            <p class="text-xs font-medium text-gray-500 mb-3">
                                <i class="ti ti-eye text-sm mr-1"></i> Preview Google
                            </p>
                            <div class="bg-white border border-gray-100 rounded-xl p-4">
                                <p class="text-xs text-green-700 mb-1">ticketin.id › ...</p>
                                <p class="text-base text-blue-700 hover:underline cursor-pointer font-medium leading-snug"
                                    x-text="title.substring(0, 70)"></p>
                                <p class="text-sm text-gray-500 mt-1 leading-relaxed" x-text="desc.substring(0, 160)">
                                </p>
                            </div>
                        </div>

                    </div>

                    {{-- Right: OG Image --}}
                    <div>
                        <div x-data="imagePreview('{{ $settings['seo_og_image']->value ? Storage::url($settings['seo_og_image']->value) : '' }}')">
                            <label class="text-xs font-medium text-gray-500 block mb-3">
                                OG Image
                                <span class="text-gray-400 font-normal">(Social Share Image)</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-200 rounded-2xl overflow-hidden cursor-pointer hover:border-gray-300 transition aspect-video flex items-center justify-center bg-gray-50"
                                @click="$refs.ogInput.click()">
                                <img x-show="preview" :src="preview" class="w-full h-full object-cover"
                                    alt="">
                                <div x-show="!preview" class="text-center p-4">
                                    <i class="ti ti-photo text-3xl text-gray-200 block mb-2"></i>
                                    <p class="text-xs text-gray-400">Upload OG Image</p>
                                    <p class="text-xs text-gray-300 mt-1">1200 × 630px disarankan</p>
                                </div>
                            </div>
                            <input type="file" name="seo_og_image" accept="image/*" x-ref="ogInput"
                                @change="showPreview($event)" class="hidden">
                            <p class="text-xs text-gray-400 mt-2">
                                Gambar yang tampil saat link dibagikan di media sosial.
                            </p>
                        </div>
                    </div>

                </div>

                <div class="flex justify-end mt-6 pt-5 border-t border-gray-100">
                    <button type="submit"
                        class="inline-flex items-center gap-2 h-10 px-6 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition font-medium">
                        <i class="ti ti-device-floppy text-base"></i> Simpan SEO
                    </button>
                </div>

            </form>
        </div>

    </div>

    @push('scripts')
        <script>
            function imagePreview(existing = '') {
                return {
                    preview: existing || null,
                    showPreview(event) {
                        const file = event.target.files[0];
                        if (!file) return;
                        const reader = new FileReader();
                        reader.onload = (e) => this.preview = e.target.result;
                        reader.readAsDataURL(file);
                    }
                }
            }
        </script>
    @endpush

</x-app-layout>
