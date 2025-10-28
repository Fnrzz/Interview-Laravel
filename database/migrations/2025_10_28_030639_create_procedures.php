<?php

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
        DB::unprepared('DROP PROCEDURE IF EXISTS create_sale');
        DB::unprepared('DROP PROCEDURE IF EXISTS get_top_product');

        $sql_create_sale = <<<SQL
            CREATE PROCEDURE `create_sale`(
                IN p_sales_reference VARCHAR(255),
                IN p_product_code VARCHAR(255),
                IN p_quantity INT,
                IN p_sale_price DECIMAL(15, 2)
            )
            BEGIN
                DECLARE v_current_stock INT;
                DECLARE v_subtotal DECIMAL(15, 2);

                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                    RESIGNAL; 
                END;

                START TRANSACTION;

                SELECT stock INTO v_current_stock
                FROM products
                WHERE product_code COLLATE utf8mb4_unicode_ci = p_product_code
                FOR UPDATE;

                IF v_current_stock >= p_quantity THEN
                
                    UPDATE products
                    SET stock = stock - p_quantity
                    WHERE product_code COLLATE utf8mb4_unicode_ci = p_product_code;

                    SET v_subtotal = p_quantity * p_sale_price;

                    INSERT INTO sales (
                        sales_reference, 
                        sales_date,
                        product_code,
                        quantity,
                        price,
                        subtotal,
                        created_at,
                        updated_at
                    ) VALUES (
                        p_sales_reference,
                        NOW(),
                        p_product_code,
                        p_quantity,
                        p_sale_price,
                        v_subtotal,
                        NOW(),
                        NOW()
                    );

                ELSE
                    SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Stok tidak mencukupi';
                END IF;

                COMMIT;
            END
        SQL;
        DB::unprepared($sql_create_sale);


        $sql_get_top_product = <<<SQL
            CREATE PROCEDURE `get_top_product`()
            BEGIN
                SELECT
                    p.product_name,
                    SUM(s.quantity) AS total_sales
                FROM
                    sales s
                JOIN
                    products p ON s.product_code = p.product_code
                GROUP BY
                    p.product_name
                ORDER BY
                    total_sales DESC
                LIMIT 5;
            END
        SQL;
        DB::unprepared($sql_get_top_product);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS create_sale');
        DB::unprepared('DROP PROCEDURE IF EXISTS get_top_product');
    }
};
