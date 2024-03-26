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
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <td class="px-6 py-4">
                           {{$details['title']}}
                        </td>
                        <td class="px-6 py-4">
                            {{$details['body']}}
                        </td>
                        <td class="px-6 py-4 flex ">
                            <a class="px-4 py-2 text-sm bg-sky-500 text-white block mb-2 rounded hover:bg-sky-900 text-center me-3" href="{{ route('posts.edit', $details['id']) }}"><i class="fa-solid fa-pen-to-square"></i></a>
                            <form action="{{ route('posts.destroy',$details['id']) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="px-4 py-2 text-sm bg-red-500 text-white block mb-2 rounded hover:bg-red-900 text-center" type="submit"><i class="fa-solid fa-trash"></i></button>
                            </form>
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

<!-- 
@push('scripts')
    <script>
    </script>
@endpush -->