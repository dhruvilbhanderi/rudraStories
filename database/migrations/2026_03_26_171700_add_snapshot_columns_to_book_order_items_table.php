<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('book_order_items')) {
            return;
        }

        Schema::table('book_order_items', function (Blueprint $table) {
            if (! Schema::hasColumn('book_order_items', 'book_type_snapshot')) {
                $table->string('book_type_snapshot', 50)->default('physical');
            }

            if (! Schema::hasColumn('book_order_items', 'access_type_snapshot')) {
                $table->string('access_type_snapshot', 50)->default('paid');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('book_order_items')) {
            return;
        }

        Schema::table('book_order_items', function (Blueprint $table) {
            if (Schema::hasColumn('book_order_items', 'book_type_snapshot')) {
                $table->dropColumn('book_type_snapshot');
            }

            if (Schema::hasColumn('book_order_items', 'access_type_snapshot')) {
                $table->dropColumn('access_type_snapshot');
            }
        });
    }
};

