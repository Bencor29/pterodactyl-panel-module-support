<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTriggersToForeignOnPluginTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['server_id']);
            $table->dropForeign(['admin_id']);
            $table->dropForeign(['user_id']);

            $table->foreign('server_id')
                ->references('id')
                ->on('servers')
                ->onDelete('set null');

            $table->foreign('admin_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        Schema::table('ticket_messages', function (Blueprint $table) {
            $table->dropForeign(['ticket_id']);
            $table->dropForeign(['user_id']);

            $table->foreign('ticket_id')
                ->references('id')
                ->on('tickets')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['server_id']);
            $table->dropForeign(['admin_id']);
            $table->dropForeign(['user_id']);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('server_id')->references('id')->on('servers');
            $table->foreign('admin_id')->references('id')->on('users');
        });

        Schema::table('ticket_messages', function (Blueprint $table) {
            $table->dropForeign(['ticket_id']);
            $table->dropForeign(['user_id']);

            $table->foreign('ticket_id')->references('id')->on('tickets');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
}
