@props(['listId', 'btnNextId', 'btnPrevId'])

<section class="px-3 py-6 h-[600px]">
    <div class="relative w-full h-full overflow-hidden">
        <ul id="{{ $listId }}" class="absolute top-0 left-0 w-max h-full flex transition-all duration-700 ease-in-out">
            {{ $slot }}
        </ul>
        <div class="absolute top-[45%] left-[5%] w-[90%] flex justify-between text-white">
            <x-button-circle id="{{ $btnPrevId }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.939 4.939L6.879 12l7.06 7.061l2.122-2.122L11.121 12l4.94-4.939z" fill="currentColor" />
                </svg>
            </x-button-circle>
            <x-button-circle id="{{ $btnNextId }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.061 19.061L17.121 12l-7.06-7.061l-2.122 2.122L12.879 12l-4.94 4.939z"
                        fill="currentColor" />
                </svg>
            </x-button-circle>
        </div>
    </div>
</section>