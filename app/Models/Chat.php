<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image_url', 'is_group'];

    //Relacion de uno a muchos
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    //Relacion de muchos a muchos
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    //Agregar mutadores -> modificar la forma de una propiedad
    public function name(): Attribute
    {
        return new Attribute(
            get: function ($value) {

                if ($this->is_group) {
                    return $value;
                }

                $user = $this->users->where('id', '!=', auth()->id())->first();

                $contact = auth()->user()->contacts()->where('contact_id', $user->id)->first();

                return $contact ? $contact->name : $user->email;
            }
        );
    }

    public function image(): Attribute
    {
        return new Attribute(
            get: function () {

                if ($this->is_group) {
                    return Storage::url($this->image_url);
                }

                $user = $this->users->where('id', '!=', auth()->id())->first();

                return  $user->profile_photo_url;
            }
        );
    }

    public function lastMessageAt(): Attribute
    {
        return new Attribute(
            get: function () {
                // Recuperar el último archivo insertado en mi tabla
                return $this->messages->last()->created_at;
            }
        );
    }

    public function unreadMessages(): Attribute
    {
        return new Attribute(
            get: function () {
                // Recuperar el último archivo insertado en mi tabla
                return $this->messages()->where('user_id', '!=', auth()->id())->where('is_read', false)->count();
            }
        );
    }
}
