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
            $table->id();
            $table->multilingualContent(); 
            $table->markable(); 
            $table->string('resource')->default(\Armincms\Blogger\Nova\Post::class);
            $table->string('password')->nullable();
            $table->string('source', 500)->nullable();
            $table->multilingualRefer(); 
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
