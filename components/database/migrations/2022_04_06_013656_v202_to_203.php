<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class V202To203 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('generals', function (Blueprint $table) {
            $table->boolean('captcha_for_registered')->after('captcha_status')->default(true);
            $table->string('heading_background')->after('blog_page_count')->default('bg-teal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
