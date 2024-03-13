<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE OR REPLACE VIEW view_categories_stock
            AS
            SELECT
                categories.id,
                categories.name,
                SUM(products.stock) as stock
            FROM
                categories
                LEFT JOIN products ON categories.id = products.category_id
            WHERE categories.deleted_at IS NULL
            GROUP BY categories.id
            ORDER BY categories.name ASC
        ');
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS view_categories_stock');
    }
};
