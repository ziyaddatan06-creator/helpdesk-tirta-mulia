<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Perbarui nama, alamat email, dan foto profil akun Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Bagian Foto Profil -->
        <div class="flex items-center space-x-6">
            <div class="shrink-0">
                @if($user->avatar)
                    <img class="h-16 w-16 object-cover rounded-full shadow-sm border border-gray-200" src="{{ asset('storage/' . $user->avatar) }}" alt="Foto Profil" />
                @else
                    <div class="h-16 w-16 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-2xl shadow-sm border border-blue-200">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
            </div>
            <label class="block">
                <span class="sr-only">Pilih foto profil</span>
                <input type="file" name="avatar" accept="image/*" class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100 transition
                "/>
            </label>
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('avatar')" />

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>