<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);  //foreignIdFor creates col 'user_id' that point to the 'id' col of tab users
            $table->string('name');
            $table->text('description')->nullable();  //can be null
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->timestamps();  //fields created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void   //drop this table if you run rollerback php artisan migrate:rollback
    {
        Schema::dropIfExists('events');
    }
};
