<x-app-layout>


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lista de Contacto') }}
        </h2>
    </x-slot>


    <div class="max-w-4xl mx-auto px-4 sm:px-6 lo:px-9 py-12">
        <div class="flex justify-end mb-4">
            <a href="{{ route('contacts.create') }}"
                class="flex items-center px-4 py-2 font-medium tracking-wide text-white capitalize transition-colors duration-300 transform bg-blue-600 rounded-lg hover:bg-blue-500 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-80">
                <svg class="w-5 h-5 mx-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                        clip-rule="evenodd" />
                </svg>

                <span class="mx-1">Crear contacto</span>
            </a>
        </div>
        @if (count($contacts))
            <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-6">
                                Nname
                            </th>
                            <th scope="col" class="py-3 px-6">
                                Email
                            </th>
                            <th scope="col" class="py-3 px-6">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($contacts as $contact)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row"
                                    class="flex items-center py-4 px-6 text-gray-900 whitespace-nowrap dark:text-white">
                                    <img class="w-10 h-10 rounded-full" src="{{ $contact->user->profile_photo_url }}"
                                        alt="{{ $contact->user->name }}">
                                    <div class="pl-3">
                                        <div class="text-base font-semibold">{{ $contact->name }}</div>
                                        <div class="font-normal text-gray-500">{{ $contact->user->name }}</div>
                                    </div>
                                </th>
                                <td class="py-4 px-6">
                                    {{ $contact->user->email }}
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div
                                        class="p-1.5 w-full inline-block sm:w-auto overflow-hidden bg-white border rounded-lg dark:bg-gray-800 dark:border-gray-700">
                                        <div class="space-y-2 sm:space-y-0 sm:flex sm:-mx-1">
                                            <a href="{{ route('contacts.edit', $contact) }}"
                                                class="flex items-center justify-center w-full px-2 py-2 text-white transition-colors duration-300 transform bg-blue-600 rounded-md focus:outline-none sm:w-auto sm:mx-1 hover:bg-blue-500 focus:bg-blue-500 focus:ring focus:ring-blue-300 focus:ring-opacity-40">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <form action="{{ route('contacts.destroy', $contact) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="flex items-center justify-center w-full px-2 py-2 text-white transition-colors duration-300 transform bg-red-600 rounded-md focus:outline-none sm:w-auto sm:mx-1 hover:bg-red-500 focus:bg-red-500 focus:ring focus:ring-red-300 focus:ring-opacity-40">
                                                    <i class="fa-solid fa-trash"></i>
                                                    {{-- <span class="mx-1">
                                                Delete
                                            </span> --}}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        @else
            <div class="flex w-full mx-auto overflow-hidden bg-white rounded-lg shadow-md dark:bg-gray-800">
                <div class="flex items-center justify-center w-12 bg-blue-500">
                    <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM21.6667 28.3333H18.3334V25H21.6667V28.3333ZM21.6667 21.6666H18.3334V11.6666H21.6667V21.6666Z" />
                    </svg>
                </div>

                <div class="px-4 py-2 -mx-3">
                    <div class="mx-3">
                        <span class="font-semibold text-blue-500 dark:text-blue-400">Ups !!!</span>
                        <p class="text-sm text-gray-600 dark:text-gray-200">Usted no tiene contactos</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

</x-app-layout>
