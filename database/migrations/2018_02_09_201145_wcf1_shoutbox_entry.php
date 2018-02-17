<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Wcf1ShoutboxEntry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('wcf1_shoutbox_entry')) {

            Schema::table('wcf1_shoutbox_entry', function (Blueprint $table) {
                $table->boolean('fromApi')->default(0);
                $table->boolean('syncWithExternalServices')->default(1);
            });

        } else {

            Schema::create('wcf1_shoutbox_entry', function (Blueprint $table) {
                $table->increments('entryID');
                $table->integer('userID');
                $table->string('username');
                $table->timestamp('time');
                $table->mediumText('message');
                $table->string('ipAddress', 40);
                $table->boolean('fromApi');
                $table->boolean('syncWithExternalServices');
            });

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wcf1_shoutbox_entry', function (Blueprint $table) {
            $table->dropColumn('fromApi');
            $table->dropColumn('syncWithExternalServices');
        });
    }
}
