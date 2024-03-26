<!-- resources/views/child.blade.php -->
 
@extends('layouts.app')
 
@section('title', 'Examples')
 
<!-- @section('sidebar')
    @parent
 
    <p>This is appended to the master sidebar.</p>
@endsection
  -->
@section('content')
    <section class="container mx-auto">
            SHEESH
    </section>

        @php
            $isActive = false;
            $hasError = true;
            $products = [
                ['id' =>1, 'name' => 'Kevin' ],
                ['id' =>2, 'name' => 'Rojenson' ]
            ];
        @endphp
        
        <span @class([
            'p-4',
            'font-bold' => $isActive,
            'text-gray-500' => ! $isActive,
            'bg-zinc-400' => $hasError,
        ])>CLASS</span>

        
        <span @style([
            'background-color: red',
            'font-weight: bold' => $isActive,
        ])>STYLE</span>
        
        <input type="checkbox" name="active" value="active" @checked(old('active', 0)) />
        <select name="version">
            @foreach ($products as $product)
                <option value="{{ $product['id'] }}" @selected(old("version") == 2)>
                    {{ $product['name'] }}
                </option>
            @endforeach
        </select>
        {{--dd(\App\Models\Post::get())--}}

        @php
            $message = 'Hello World';
        @endphp

        <x-form.alert bg-color="violet-700" :message="$message"></x-form.alert>
        <x-form.alert bg-color="purple-400" :$message class="text-red-300">
            <x-slot:title>
                Server Error
            </x-slot>
        </x-form.alert>

        <button  class="bg-teal-600 p-2" type="submit" @disabled(0)>Submit</button>

        <input type="email" name="email" value="email@laravel.com"  @readonly(1) />
        
        <form action="">
            <input type="text" name="title" value="" @required(1) />
            <button type="submit">Submit Bro</button>
        </form>
    
        @unless (0)
            You are not signed in.
        @endunless
        @empty(0)
            $records is "empty"...
        @endempty
@endsection

@section('scripts')
    <script src="{{asset('js/index.js')}}"></script>
@endsection

