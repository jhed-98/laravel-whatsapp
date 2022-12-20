<div class="bg-gray-50 rounded-lg shadow border border-gray-200 overflow-hidden">
    <div class="grid grid-cols-3 divide-x divide-gray-200">

        <div class="col-span-1">
            <div class="bg-gray-100 h-16 flex items-center px-4">
                <img class="w-10 h-10 object-cover object-center" src="{{ auth()->user()->profile_photo_url }}"
                    alt="{{ auth()->user()->name }}">
            </div>
            <div class="h-14 flex items-center bg-white px-4">
                <x-jet-input wire:model="search" type="text" class="w-full"
                    placeholder="Busque un chat o inicie uno nuevo" />
            </div>
            <div class="h-[calc(100vh-10.5rem)] overflow-auto border-t border-gray-200">
                @if ($this->chats->count() == 0 || $search)
                    {{-- Lista de contactos --}}
                    <div class="px-4 py-3">
                        <h2 class="text-teal-600 text-lg mb-4">Contáctos</h2>

                        <ul class="space-y-4">
                            @forelse ($this->contacts as $contact)
                                <li class="cursor-pointer" wire:click="open_chat_contact({{ $contact }})">
                                    <div class="flex">
                                        <figure class="flex-shrink-0">
                                            <img class="h-12 w-12 rounded-full"
                                                src="{{ $contact->user->profile_photo_url }}"
                                                alt="{{ $contact->name }}">
                                        </figure>
                                        <div class="flex-1 ml-5 border-b border-gray-200">
                                            <p class="text-gray-800">{{ $contact->name }}</p>
                                            <p class="text-gray-600 text-xs">{{ $contact->user->email }}</p>
                                        </div>
                                    </div>
                                </li>
                            @empty
                            @endforelse
                        </ul>
                    </div>
                @else
                    {{-- Lista de chat activos --}}
                    @foreach ($this->chats as $chatItem)
                        <div wire:keY="chats-{{ $chatItem->id }}" wire:click="open_chat({{ $chatItem }})"
                            class="flex items-center justify-between {{ $chat && $chat->id == $chatItem->id ? 'bg-gray-100' : 'bg-white' }} hover:bg-gray-100 cursor-pointer px-3">
                            <figure class="flex-shrink-0">
                                <img class="h-12 w-12 rounded-full" src="{{ $chatItem->image }}"
                                    alt="{{ $chatItem->name }}">
                            </figure>
                            <div class="w-[calc(100%-4rem)] py-4 border-b border-gray-200">
                                <div class="flex justify-between items-center">
                                    <p class="text-gray-800 truncate">{{ $chatItem->name }}</p>
                                    <p class="text-gray-600 text-xs ml-2">
                                        {{-- {{ $chatItem->messages->last()->created_at->format('h:i A') }}</p> --}}
                                        {{ $chatItem->last_message_at->format('d-m-y h:i A') }}</p>
                                </div>
                                <p class="text-sm text-gray-700 mt-1 truncate">
                                    {{ $chatItem->messages->last()->body }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- CHATS --}}

        <div class="col-span-2">
            @if ($contactChat || $chat)
                <div class="bg-gray-100 h-16 flex items-center p-3">
                    <figure>
                        @if ($chat)
                            <img class="w-10 h-10 rounded-full object-cover object-center" src="{{ $chat->image }}"
                                alt="{{ $chat->name }}">
                        @else
                            <img class="w-10 h-10 rounded-full object-cover object-center"
                                src="{{ $contactChat->user->profile_photo_url }}" alt="{{ $contactChat->name }}">
                        @endif
                    </figure>
                    <div class="ml-4">
                        @if ($chat)
                            <p class="text-gray-800">{{ $chat->name }}</p>
                        @else
                            <p class="text-gray-800">{{ $contactChat->name }}</p>
                        @endif
                        <p class="text-green-500 text-xs">Online</p>
                    </div>
                </div>
                <div class="h-[calc(100vh-11rem)] overflow-auto px-3 py-2">
                    {{-- El contenido de nuestro chat --}}
                    @foreach ($this->messages as $message)
                        <div class="flex {{ $message->user_id == auth()->id() ? 'justify-end' : '' }} mb-2">
                            <div
                                class="rounded px-3 py-2 {{ $message->user_id == auth()->id() ? 'bg-green-200' : 'bg-gray-100' }}">
                                <p class="text-sm">
                                    {{ $message->body }}
                                </p>

                                <p
                                    class="{{ $message->user_id == auth()->id() ? 'text-right' : '' }}text-xs text-gray-600 mt-1">
                                    {{ $message->created_at->format('d-m-y h:i A') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <form wire:submit.prevent="sendMessage()" class="flex items-center px-4 h-16 bg-gray-100">
                    <x-jet-input wire:model="bodyMessage" type="text" class="flex-1"
                        placeholder="Escriba un mensaje aquí" />
                    <button type="submit" class="flex-shrink-0 ml-4 text-2xl text-teal-600">
                        <i class="fas fa-share"></i>
                    </button>
                </form>
            @else
                <div class="w-full h-full flex justify-center items-center">
                    <div style="transform: scale(1); opacity: 1;">
                        <span data-testid="intro-md-beta-logo-dark" data-icon="intro-md-beta-logo-dark" class="IVxyB">
                            <x-icon-intro />
                        </span>
                        <div class="mt-4 flex justify-center">
                            <h1 class="text-2xl">WhatsApp Web para escritorio</h1>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
