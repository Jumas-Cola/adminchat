<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminChatAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("admin_chat_attachments", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("user_id")
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->set("type", ["image", "file", "image_enc", "file_enc"]);
            $table->text("path");
            $table->integer("attachable_id")->nullable();
            $table->string("attachable_type")->nullable();
            $table->integer("order")->default(0);
            $table->text('origin_name')->nullable();
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
        Schema::dropIfExists("attachments");
    }
}
