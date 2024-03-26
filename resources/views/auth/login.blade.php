@extends('layouts.app')

@section('title','Create New Post')
@section('content')
    <x-error :$errors></x-error>

    <form id="login-form" method="POST" class="w-2/3 mx-auto">
        <p class="text-red-500" id="server-error"></p>
        <p class="mb-2 text-lg font-medium">Login</p>
        <div class="grid grid-cols-2">
            <div class="mb-4 me-2">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                <x-form.input name="email" placeholder="Enter email address" type="email" value="{{ old('email') }}" autocomplete="email" />
                <p class="text-red-500" id="email-error"></p>
            </div>

            <div class="mb-4">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                <x-form.input name="password" placeholder="Enter password" type="password" value="{{ old('password') }}" autocomplete="current-password" />
                <p class="text-red-500" id="password-error"></p>
            </div>
        </div>
        <div class="flex justify-center">
            <button type="submit" class=" w-[50px] text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full  px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Login</button>
        </div>
    </form>
@endsection 

@push('scripts')
<script type="module">
    import { validateForm } from '/js/validator/index.js';
    // import { data } from "{{-- asset('js/validator/index.js') --}}";
    const fields = ['email', 'password'];
    const loginForm = document.getElementById("login-form");

    loginForm.addEventListener("submit", async function(event) {
        event.preventDefault(); // Prevent the default form submission
        // validateForm(document.querySelectorAll('input[name]'));
        // return;
        const formData = new FormData(this); // Get form data
        const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

        // Send form data to the server using fetch API
        try {
            const response = await fetch("{{ route('login.store') }}", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
            });

            console.log(response,'response');
            const data = await response.json();
            console.log(data,'data');
            resetErrors();

            if (response.status === 422) {
                fields.forEach(field => {
                    if (data.errors.hasOwnProperty(field)) {
                        const errorMessages = data.errors[field];
                        document.getElementById(`${field}-error`).innerText = errorMessages[0];
                    }
                });
                return;
            }else if (response.status === 500) {
                console.log(data,'data');
                document.getElementById(`server-error`).innerText = data.message;
                return;
            }
           

            if (response.ok) {
                loginForm.reset();
                window.location.href = "{{ route('posts.index')}}"
            }
        } catch (error) {
            console.error("Error submitting form:", error);
        }
    });

    function resetErrors() {
        document.getElementById(`server-error`).innerText = '';
        fields.forEach(field => {
            document.getElementById(`${field}-error`).innerText = '';
        });
    }

</script>
@endpush