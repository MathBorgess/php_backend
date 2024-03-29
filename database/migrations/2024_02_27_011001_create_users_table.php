<?php
//Class "App\Enums\TransactionTypeRequestStatus" not found
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string('fullname');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('cpf')->unique();
            $table->timestamps();
        });

        Schema::create('transaction_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->uuid('user_id')->index();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->enum('type', ['income', 'expense'])->change();
            $table->string('name');
            $table->integer('value');
            $table->uuid('category_id')->index();
            $table->uuid('user_id')->index();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('transaction_categories')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('transaction_categories');
        Schema::dropIfExists('transactions');
    }
};
