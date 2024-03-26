@extends('layouts.app')

@section('title','Edit Post')
@section('content')
    <form action="{{route('posts.update',$post['id'])}}" method="POST" class="w-1/3 mx-auto">
        @csrf
        <p class="mb-2 text-lg font-medium">Edit</p>
        @method('PATCH')
        <div class="grid">
            <div class="mb-4">
                <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                <x-form.input name="title" placeholder="Enter title" type="crazy" :is-required="true" value="{{$post['title']}}" />
            </div>
            <div class="mb-4">
                <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Content</label>
                <x-form.textarea name="body" placeholder="Enter Content" :is-required="true">{{$post['body']}}</x-form.textarea>
            </div>
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
    </form>
@endsection 

