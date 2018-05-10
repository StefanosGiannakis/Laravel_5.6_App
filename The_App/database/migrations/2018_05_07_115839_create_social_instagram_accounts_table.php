<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialInstagramAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // The column provider_user_id is Facebook’s 
        // user id, and inprovider this case, will always be Facebook
        Schema::create('social_instagram_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userId')->unsigned();//FK
            $table->bigInteger('instagram_id');// maybe provider_user_id if we want to mplement more authorizations
            $table->string('username');
            $table->string('fullname')->nullable(); //Maybe not 
            $table->string('provider'); // some how we must past the provider, i think hardcoded
            $table->string('bio')->nullable();
            $table->string('website')->nullable();
            $table->string('profilePicture');
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
        Schema::dropIfExists('social_instagram_accounts');
    }
}
