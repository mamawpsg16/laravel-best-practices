@extends('layouts.app')

@section('title','POSTS')
@section('content')
    <div class="buttons flex justify-end">
        <a class="px-4 py-2 text-sm bg-teal-500 text-white block mb-2 rounded hover:bg-teal-900" href="{{ route('posts.create') }}">Create</a>
    </div>
    <x-table :$columns>
        <x-slot:details>
            @isset($data)
                @foreach($data as $details)
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700" data-id="{{ $details['id'] }}">
                        <td class="px-6 py-4">{{ $details['title'] }}</td>
                        <td class="px-6 py-4">{{ $details['body'] }}</td>
                        <td class="px-6 py-4 flex">
                            <a class="px-4 py-2 text-sm bg-sky-500 text-white block mb-2 rounded hover:bg-sky-900 text-center me-3" href="{{ route('posts.edit', $details['id']) }}">Edit</a>
                            <button class="px-4 py-2 text-sm bg-red-500 text-white block mb-2 rounded hover:bg-red-900 text-center btnDelete" data-id="{{ $details['id'] }}"><i class="fa-solid fa-trash"></i> Delete</button>
                        </td>
                    </tr>
                @endforeach
            @endisset
        </x-slot:details>
    </x-table>
@endsection 
<!-- @section('scripts')
<script src="{{asset('js/index.js')}}">
</script>

@endsection  -->


@push('scripts')
    <script>
        // console.log(@json($data), typeof "{{ $data }}");
        const csrfToken = "{{ csrf_token()}}"
        const btnDeleteList = document.querySelectorAll('.btnDelete');
        btnDeleteList.forEach(btnDelete =>{
            btnDelete.addEventListener('click', function(e){
                e.preventDefault();
                const id = btnDelete.getAttribute('data-id');
                deleteConfirmation(id)
            })
        })
 
        function deleteConfirmation(id){
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                confirmButtonText: "Confirm",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                reverseButtons:true
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                deletePost(id)
            } else if (result.isDismissed) {
                Swal.fire({
                    icon:'info',
                    title: "Deletion Cancelled",
                    showConfirmButton:false,
                    timer:1500
                }, "", "info");
            }
            });
        }

        async function deletePost(id) {
            try {
                const response = await fetch(`/posts/${id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                });
                const data = await response.json();
                if(response.ok){
                    Swal.fire({
                        icon: "success",
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    if (row) {
                        row.remove();
                    }
                }
            } catch (error) {
                console.log('error', error);
            }
        }
    </script>
@endpush