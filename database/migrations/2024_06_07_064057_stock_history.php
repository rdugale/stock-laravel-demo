<?php

use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Stock::class);
            $table->foreignIdFor(User::class);
            $table->integer('qty');
            $table->decimal('price_before', total: 8, places: 2);
            $table->decimal('price_after', total: 8, places: 2);
            $table->dateTime('datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->date('date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_histories');
    }
};
