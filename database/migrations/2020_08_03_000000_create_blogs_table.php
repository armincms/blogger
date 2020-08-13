<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->description('title'); 
            $table->string('marked_as')->default('draft');
            $table->string('resource')->default(\Armincms\Blogger\Nova\Post::class);
            $table->string('password')->nullable();
            $table->json('source')->nullable();
            $table->string('sequence_key')->nullable();
            $table->auth();
            $table->hits();
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('publish_date')->nullable(); 
            $table->timestamp('archive_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
