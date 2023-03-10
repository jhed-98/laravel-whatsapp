<x-app-layout>


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Actualizar Contacto') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lo:px-9 py-12">
        <form action="{{ route('contacts.update', $contact) }}" method="POST" class="bg-white rounded-lg shadow p-6">
            @csrf
            @method('PUT')

            <x-jet-validation-errors class="mb-4" />

            <div class="mb-4">
                <x-jet-label>Nombre del contacto</x-jet-label>
                <x-jet-input type="text" name="name" value="{{ old('name', $contact->name) }}" class="w-full"
                    placeholder="Ingrese el nombre del contacto" />
            </div>

            <div class="mb-4">
                <x-jet-label>Correo electronico</x-jet-label>
                <x-jet-input type="email" name="email" value="{{ old('email', $contact->user->email) }}" class="w-full"
                    placeholder="Ingrese el correo electronico" />
            </div>

            <div class="flex justify-end">
                <x-jet-button>
                    Actualizar Contacto
                </x-jet-button>
            </div>

        </form>
    </div>

</x-app-layout>
