<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delete_messages', function (Blueprint $table) {
            $table->id();
            
             //Forma de crear una llave foranea
            
             $table->foreignId('user_id')->constrained();
             $table->foreignId('message_id')->constrained(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delete_messages');
    }
};
