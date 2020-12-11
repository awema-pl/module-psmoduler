
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePsmodulerHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create(config('psmoduler.database.tables.psmoduler_histories'), function (Blueprint $table) {
            $table->id();
            $table->boolean('with_package');
            $table->string('name');
            $table->timestamps();
        });
        
        Schema::table(config('psmoduler.database.tables.psmoduler_histories'), function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->constrained(config('psmoduler.database.tables.users'))
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table(config('psmoduler.database.tables.psmoduler_histories'), function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        
        Schema::drop(config('psmoduler.database.tables.psmoduler_histories'));
    }
}
