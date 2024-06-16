<?php

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTableEasypanel extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function getConnection()
    {
        return config('easy_panel.database.connection');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('easy_panel.database.roles_table'), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('permissions');
            $table->timestamps();
        });

        Schema::create(config('easy_panel.database.roles_users_table'), function (Blueprint $table) {

            $table->bigInteger('role_id')->unsigned()->index();
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
            /*
             * Check if model use UUID or ULID then create custom key for it
             */
            if (in_array(HasUuids::class, class_uses_recursive(config('easy_panel.user_model')))) {
                $table->foreignUuid('user_id');
            } elseif (in_array(HasUlids::class, class_uses_recursive(config('easy_panel.user_model')))) {
                $table->foreignUlid('user_id');
            } else {
                $table->foreignIdFor(config('easy_panel.user_model'));
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('easy_panel.database.roles_table'));
        Schema::dropIfExists(config('easy_panel.database.roles_users_table'));

    }
}
