@extends('layouts.app')

@section('title','Create New Post')
@section('content')
    <x-error :$errors></x-error>

    <form id="create-form" method="POST" class="w-1/3 mx-auto" enctype="multipart/form-data">
        @csrf
        <p class="mb-2 text-lg font-medium">Create</p>
        <p class="text-red-500" id="server-error"></p>
        <div class="grid">
            <div class="mb-4">
                <input type="file" name="file">
            </div>
            <div class="mb-4">
                <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                <x-form.input name="title" placeholder="Enter title" type="crazy" value="{{ old('title') }}" />
                <p class="text-red-500" id="title-error"></p>
            </div>
            <div class="mb-4">
                <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Content</label>
                <x-form.textarea name="body" placeholder="Enter Content">{{ old('body') }}</x-form.textarea>
                <p class="text-red-500" id="body-error"></p>
            </div>
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
    </form>
@endsection 

@push('scripts')
<script>
    const fields = ['title', 'body'];
    const createForm = document.getElementById("create-form");

    createForm.addEventListener("submit", async function(event) {
        event.preventDefault(); // Prevent the default form submission
        const formData = new FormData(this); // Get form data
        const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

        // Send form data to the server using fetch API
        try {
            const response = await fetch("{{ route('posts.store') }}", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
            });

            const data = await response.json();
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
                createForm.reset();
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