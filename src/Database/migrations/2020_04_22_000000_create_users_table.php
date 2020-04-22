<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('password_reset')){
            shell_exec('php artisan migrate --path=vendor/laravel/ui/stubs/migrations/');
        }

        Schema::table('role-permission', function (Blueprint $table) {
//            $table->id();
//            $table->string('full_name');
//            $table->enum('gender', ['NAM', 'NU']);
//            $table->dateTime('birthday');
//            $table->string('id_card');
//            $table->dateTime('date_of_issue_id_card');
//            $table->string('place_of_issue_id_card');
//            $table->string('passport');
//            $table->dateTime('date_of_issue_passport');
//            $table->string('place_of_issue_passport');
//            $table->dateTime('date_of_expiry_passport');
//            $table->string('nation');
//            $table->string('religion');
//            $table->enum('marriage_status', ['DOC_THAN', 'DA_KET_HON']);
//            $table->string('email')->unique();
//            $table->timestamp('email_verified_at')->nullable();
//            $table->string('password');
//            $table->rememberToken();
//            $table->timestamps();
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
