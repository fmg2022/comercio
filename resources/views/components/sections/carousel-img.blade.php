@props(['listId', 'btnsId', 'class' => '', 'fullwidth' => false])

@php
$classUl = $fullwidth ? '' : 'sm:auto-cols-[50%] lg:auto-cols-[calc(32%)]';
@endphp

<section class="mb-7 {{ $class }}">
    <div class="relative w-full">
        <ul id="{{ $listId }}" style="scrollbar-width: none;"
            class="grid grid-flow-col auto-cols-[100%] {{ $classUl }} gap-4 overflow-x-auto snap-x snap-mandatory scroll-smooth">
            {{ $slot }}
        </ul>
        <div id="{{ $btnsId }}" class="absolute top-[45%] left-[4%] w-[92%] flex justify-between text-slate-600">
            <x-button-circle id="{{ $btnsId }}Prev">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.939 4.939L6.879 12l7.06 7.061l2.122-2.122L11.121 12l4.94-4.939z" fill="currentColor" />
                </svg>
            </x-button-circle>
            <x-button-circle id="{{ $btnsId }}Next">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.061 19.061L17.121 12l-7.06-7.061l-2.122 2.122L12.879 12l-4.94 4.939z"
                        fill="currentColor" />
                </svg>
            </x-button-circle>
        </div>
    </div>
</section>