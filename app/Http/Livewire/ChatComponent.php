<?php

namespace App\Http\Livewire;

use App\Models\Chat;
use App\Models\Contact;
use App\Models\Message;
use App\Notifications\NewMessage;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class ChatComponent extends Component
{
    public $search, $bodyMessage;
    public $contactChat, $chat;

    //Oyentes
    public function getListeners()
    {
        $user_id = auth()->user()->id;
        return [
            "echo-notification:App.Models.User.{$user_id},notification" => 'render',
            // "echo-private:App.Models.User.{$user_id},OrderShipped" => 'notifyNewOrder',
            // Or:
            // "echo-presence:orders.{$this->orderId},OrderShipped" => 'notifyNewOrder',
        ];
    }

    //Propiedad computadas
    public function getContactsProperty()
    {
        return Contact::where('user_id', auth()->id())
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($query) {
                            $query->where('email', 'like', '%' . $this->search . '%');
                        });
                });
            })->get() ?? [];
    }
    public function getMessagesProperty()
    {
        // return $this->chat ? Message::where('chat_id', $this->chat->id)->get() : [];
        return $this->chat ? $this->chat->messages()->get() : [];
    }
    //Recuperar chats por usuario
    public function getChatsProperty()
    {
        //ordenar por colecciones
        return auth()->user()->chats()->get()->sortByDesc('last_message_at');
    }

    public function getUsersNotificationsProperty()
    {
        return $this->chat ? $this->chat->users->where('id', '!=', auth()->id()) : [];
    }

    ///Métodos
    public function open_chat_contact(Contact $contact)
    {
        $chat = auth()->user()->chats()
            ->whereHas('users', function ($query) use ($contact) {
                $query->where('users.id', $contact->contact_id);
            })->first();
        // $chat = auth()->user()->chats()
        //     ->whereHas('users', function ($query) use ($contact) {
        //         $query->where('user_id', $contact->contact_id);
        //     })->first();
        if ($chat) {
            $this->chat = $chat;
            $this->reset('bodyMessage', 'contactChat', 'search');
        } else {
            $this->contactChat = $contact;
            $this->reset('bodyMessage', 'chat', 'search');
        }
    }

    public function open_chat(Chat $chat)
    {
        $this->chat = $chat;
        $this->reset('bodyMessage', 'contactChat');
    }

    public function sendMessage()
    {
        $this->validate([
            'bodyMessage' => 'required'
        ]);

        if (!$this->chat) {
            $this->chat = Chat::create();
            $this->chat->users()->attach([auth()->user()->id, $this->contactChat->contact_id]);
        }

        $this->chat->messages()->create([
            'body' => $this->bodyMessage,
            'user_id' => auth()->user()->id
        ]);

        Notification::send($this->users_notifications, new NewMessage());

        $this->reset('bodyMessage', 'contactChat');
    }

    public function render()
    {
        return view('livewire.chat-component')->layout('layouts.chat');
    }
}
