<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()
                                        ->constrained()
                                        ->cascadeOnUpdate()
                                        ->nullOnDelete();
            $table->unsignedInteger('admin_user_id')->nullable()
                                              ->constrained()
                                              ->cascadeOnUpdate()
                                              ->nullOnDelete();
            $table->boolean('from_user');
            $table->timestamp('admin_read_at')->nullable();
            $table->text('text')->nullable();
            $table->text('file')->nullable();
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
        Schema::dropIfExists('admin_chat_messages');
    }
}
