{{--<div class="bg-{{ $bgColor }}--}}
<span class="alert-title">{{ $title ?? 'Title Dummy'  }}</span>

<div {{ $attributes->merge(['class' => 'bg-'.$bgColor]) }}>
    {{ $message }}
</div>