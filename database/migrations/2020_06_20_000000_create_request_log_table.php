<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_log', function (Blueprint $table) {
            $table->id();
            $table->boolean('request_secure')->nullable();
            $table->string('request_method')->nullable();
            $table->longText('request_uri')->nullable();
            $table->ipAddress('request_ip')->nullable();
            $table->longText('request_user_agent')->nullable();
            $table->string('request_content_type')->nullable();
            $table->string('response_content_type')->nullable();
            $table->integer('response_status_code')->nullable();
            $table->integer('execution_time')->nullable();
            $table->longText('request')->nullable();
            $table->longText('response')->nullable();
            $table->timestamp('datetime')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_log');
    }
}
