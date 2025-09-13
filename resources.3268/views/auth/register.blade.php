<x-guest-layout>
    <!-- Custom Error Modal -->
    <div id="errorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">خطأ في البيانات</h3>
                <div class="mt-2 px-7 py-3">
                    <p id="errorMessage" class="text-sm text-red-600"></p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="closeModal" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                        إغلاق
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="full_name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="full_name" :value="old('full_name')"
                required autofocus autocomplete="full_name" />
            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="national_id" :value="__('National_id')" />
            <x-text-input id="national_id" class="block mt-1 w-full" type="text" name="national_id" :value="old('national_id')"
                required autofocus autocomplete="national_id" />
            <x-input-error :messages="$errors->get('national_id')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="experience_years" :value="__('عدد سنوات الخبرة')" />
            <select name="experience_years" id="experience_years"
                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200">
                <option value="">اختر عدد سنوات الخبرة</option>
                <option value="أقل من 15 سنة" {{ old('experience_years') == 'أقل من 15 سنة' ? 'selected' : '' }}>أقل من
                    15 سنة</option>
                <option value="أكثر من 15 سنة" {{ old('experience_years') == 'أكثر من 15 سنة' ? 'selected' : '' }}>أكثر
                    من 15 سنة</option>
            </select>
            <x-input-error :messages="$errors->get('experience_years')" class="mt-2" />
        </div>


        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->


        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            const modal = document.getElementById('errorModal');
            const errorMessage = document.getElementById('errorMessage');
            const closeModal = document.getElementById('closeModal');

            // Check for Laravel validation errors on page load
            @if($errors->has('national_id'))
                showError('هذا الرقم القومي موجود بالفعل من فضلك ادخل رقم قومي آخر');
            @endif

            function showError(message) {
                errorMessage.textContent = message;
                modal.classList.remove('hidden');
            }

            function hideError() {
                modal.classList.add('hidden');
            }

            closeModal.addEventListener('click', hideError);

            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    hideError();
                }
            });

            // Handle form submission
            form.addEventListener('submit', function(e) {
                const nationalId = document.getElementById('national_id').value;
                
                // Basic validation for national ID format (14 digits)
                if (nationalId && !/^\d{14}$/.test(nationalId)) {
                    e.preventDefault();
                    showError('الرقم القومي يجب أن يكون 14 رقم');
                    return;
                }
            });
        });
    </script>
</x-guest-layout>
