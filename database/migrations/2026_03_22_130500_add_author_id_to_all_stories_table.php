<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuthorIdToAllStoriesTable extends Migration
{
    public function up()
    {
        Schema::table('all_stories', function (Blueprint $table) {
            if (!Schema::hasColumn('all_stories', 'author_id')) {
                $table->unsignedBigInteger('author_id')->nullable()->after('story_type')->index();
            }
        });

        Schema::table('all_stories', function (Blueprint $table) {
            if (Schema::hasColumn('all_stories', 'author_id')) {
                // Keep the FK optional to avoid breaking existing databases where all_stories isn't InnoDB.
                // If supported, this provides proper author ownership linkage.
                try {
                    $table->foreign('author_id')->references('id')->on('authors')->nullOnDelete();
                } catch (\Throwable $e) {
                    // no-op
                }
            }
        });
    }

    public function down()
    {
        Schema::table('all_stories', function (Blueprint $table) {
            if (Schema::hasColumn('all_stories', 'author_id')) {
                try {
                    $table->dropForeign(['author_id']);
                } catch (\Throwable $e) {
                    // no-op
                }

                $table->dropColumn('author_id');
            }
        });
    }
}

