<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Welcome') }} 
            </h2>
            @if (session('status'))
            <div class="font-serif font-bold" style="color:green;">
                {{ session('status') }}
            </div>
            @endif

            @if(count($errors) > 0)
            <div class="font-serif font-bold" style="color:red;">
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 flex justify-center">
                    @if( Auth::user()->type === $adminRole)
                        <x-admin-action-page />
                    @else
                        <x-user-action-page />
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
