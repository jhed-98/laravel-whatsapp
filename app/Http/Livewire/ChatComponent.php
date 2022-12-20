<?php

namespace App\Http\Livewire;

use App\Models\Chat;
use App\Models\Contact;
use App\Models\Message;
use App\Notifications\IsReadMessage;
use App\Notifications\NewMessage;
use App\Notifications\UserTyping;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class ChatComponent extends Component
{
    public $search;
    public $bodyMessage;
    public $contactChat, $chat, $chat_id;
    public $users;

    public function mount()
    {
        $this->users = collect();
    }

    //Oyentes
    public function getListeners()
    {
        $user_id = auth()->user()->id;
        return [
            "echo-notification:App.Models.User.{$user_id},notification" => 'render',
            "echo-presence:chat.1,here" => 'chatHere', //inicio el ingreso de la sala de chat
            "echo-presence:chat.1,joining" => 'chatJoining', //entro del chat
            "echo-presence:chat.1,leaving" => 'chatLeaving', //salio de chat
            // recuperar solo un booleano
            // "echo-private:App.Models.User.{$user_id},OrderShipped" => 'notifyNewOrder',
            // Or: recuperar un booleano y el dato retornado
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
        return $this->chat ? $this->chat->users->where('id', '!=', auth()->id()) : collect();
    }

    public function getActiveProperty()
    {
        return $this->users->contains($this->users_notifications->first()->id);
    }



    //Ciclo de vida
    public function updatedBodyMessage($value)
    {
        if ($value) {
            Notification::send($this->users_notifications, new UserTyping($this->chat->id));
        }
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
            $this->chat_id = $chat->id;
            $this->reset('bodyMessage', 'contactChat', 'search');
        } else {
            $this->contactChat = $contact;
            $this->reset('bodyMessage', 'chat', 'search');
        }
    }

    public function open_chat(Chat $chat)
    {
        $this->chat = $chat;
        $this->chat_id = $chat->id;

        $this->reset('bodyMessage', 'contactChat');
    }

    public function sendMessage()
    {
        $this->validate([
            'bodyMessage' => 'required'
        ]);

        if (!$this->chat) {
            $this->chat = Chat::create();
            $this->chat_id = $this->chat->id;
            $this->chat->users()->attach([auth()->user()->id, $this->contactChat->contact_id]);
        }

        $this->chat->messages()->create([
            'body' => $this->bodyMessage,
            'user_id' => auth()->user()->id
        ]);

        Notification::send($this->users_notifications, new NewMessage());

        $this->reset('bodyMessage', 'contactChat');
    }

    public function chatHere($users)
    {
        //almacenamos los usuarios por id
        $this->users = collect($users)->pluck('id');
    }
    public function chatJoining($user)
    {
        //agregamos los usuarios nuevos ingresados por el id
        $this->users->push($user['id']);
    }
    public function chatLeaving($user)
    {
        //eliminamos del listado a los usuarios por el id
        $this->users = $this->users->filter(function ($id) use ($user) {
            return $id != $user['id'];
        });
    }


    public function render()
    {
        if ($this->chat) {
            //Filtramos los mensaje no leidos
            $this->chat->messages()->where('user_id', '!=', auth()->id())->where('is_read', false)->update([
                'is_read' => true
            ]);
            
            Notification::send($this->users_notifications, new IsReadMessage());
            
            $this->emit('scrollIntoView');
        }
        return view('livewire.chat-component')->layout('layouts.chat');
    }
}
