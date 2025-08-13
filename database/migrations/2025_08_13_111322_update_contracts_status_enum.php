<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Для SQLite нужно пересоздать таблицу
        if (DB::connection()->getDriverName() === 'sqlite') {
            // Создаем временную таблицу с новой структурой
            DB::statement('CREATE TABLE contracts_new (
                "id" integer primary key autoincrement not null,
                "contract_number" varchar not null,
                "user_id" integer not null,
                "branch_id" integer not null,
                "client" varchar not null,
                "instagram" varchar not null,
                "iin" varchar not null,
                "phone" varchar not null,
                "phone2" varchar not null,
                "address" text,
                "payment" varchar not null default "Наличный",
                "date" date not null,
                "category" varchar not null,
                "model" varchar not null,
                "width" numeric not null,
                "height" numeric not null,
                "design" varchar not null default "Меняется",
                "leaf" varchar not null,
                "framugawidth" varchar not null,
                "framugaheight" varchar not null,
                "forging" varchar,
                "opening" varchar not null default "Правое",
                "frame" varchar not null default "Стандарт",
                "outer_panel" varchar not null,
                "outer_cover" varchar not null,
                "outer_cover_color" varchar,
                "metal_cover_hidden" varchar not null default "Порошково-полимерное",
                "metal_cover_color" varchar,
                "inner_trim" varchar not null,
                "inner_cover" varchar not null,
                "inner_trim_color" varchar,
                "glass_unit" varchar not null,
                "extra" varchar,
                "lock" varchar not null,
                "handle" varchar not null,
                "steel_thickness" numeric not null,
                "canvas_thickness" integer not null,
                "measurement" varchar not null default "online",
                "delivery" varchar not null default "+",
                "installation" text,
                "order_total" numeric not null,
                "order_deposit" numeric not null,
                "order_remainder" numeric not null,
                "order_due" numeric not null,
                "photo_path" varchar,
                "attachment_path" varchar,
                "status" varchar check ("status" in ("draft", "pending_rop", "pending_accountant", "approved", "rejected", "on_hold")) not null default "draft",
                "version" integer not null default 1,
                "current_reviewer_id" integer,
                "data" text,
                "created_at" datetime,
                "updated_at" datetime,
                "manager" varchar,
                "canvas_height" numeric,
                foreign key("user_id") references "users"("id") on delete cascade,
                foreign key("branch_id") references "branches"("id") on delete cascade,
                foreign key("current_reviewer_id") references "users"("id") on delete set null
            )');

            // Копируем данные, преобразуя старые статусы в новые
            DB::statement('INSERT INTO contracts_new SELECT 
                id, contract_number, user_id, branch_id, client, instagram, iin, phone, phone2,
                address, payment, date, category, model, width, height, design, leaf,
                framugawidth, framugaheight, forging, opening, frame, outer_panel, outer_cover,
                outer_cover_color, metal_cover_hidden, metal_cover_color, inner_trim, inner_cover,
                inner_trim_color, glass_unit, extra, lock, handle, steel_thickness, canvas_thickness,
                measurement, delivery, installation, order_total, order_deposit, order_remainder,
                order_due, photo_path, attachment_path,
                CASE 
                    WHEN status = "draft" THEN "draft"
                    WHEN status = "review" THEN "pending_rop"
                    WHEN status = "approved" THEN "approved"
                    WHEN status = "ready_to_print" THEN "approved"
                    ELSE "draft"
                END as status,
                1 as version,
                NULL as current_reviewer_id,
                data, created_at, updated_at, manager, canvas_height
                FROM contracts');

            // Удаляем старую таблицу и переименовываем новую
            DB::statement('DROP TABLE contracts');
            DB::statement('ALTER TABLE contracts_new RENAME TO contracts');

            // Создаем индексы
            DB::statement('CREATE INDEX contracts_status_branch_id_index ON contracts (status, branch_id)');
            DB::statement('CREATE INDEX contracts_current_reviewer_id_index ON contracts (current_reviewer_id)');
            DB::statement('CREATE UNIQUE INDEX contracts_contract_number_unique ON contracts (contract_number)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Для отката создаем таблицу со старыми статусами
        if (DB::connection()->getDriverName() === 'sqlite') {
            DB::statement('CREATE TABLE contracts_old (
                "id" integer primary key autoincrement not null,
                "contract_number" varchar not null,
                "user_id" integer not null,
                "branch_id" integer not null,
                "client" varchar not null,
                "instagram" varchar not null,
                "iin" varchar not null,
                "phone" varchar not null,
                "phone2" varchar not null,
                "address" text,
                "payment" varchar not null default "Наличный",
                "date" date not null,
                "category" varchar not null,
                "model" varchar not null,
                "width" numeric not null,
                "height" numeric not null,
                "design" varchar not null default "Меняется",
                "leaf" varchar not null,
                "framugawidth" varchar not null,
                "framugaheight" varchar not null,
                "forging" varchar,
                "opening" varchar not null default "Правое",
                "frame" varchar not null default "Стандарт",
                "outer_panel" varchar not null,
                "outer_cover" varchar not null,
                "outer_cover_color" varchar,
                "metal_cover_hidden" varchar not null default "Порошково-полимерное",
                "metal_cover_color" varchar,
                "inner_trim" varchar not null,
                "inner_cover" varchar not null,
                "inner_trim_color" varchar,
                "glass_unit" varchar not null,
                "extra" varchar,
                "lock" varchar not null,
                "handle" varchar not null,
                "steel_thickness" numeric not null,
                "canvas_thickness" integer not null,
                "measurement" varchar not null default "online",
                "delivery" varchar not null default "+",
                "installation" text,
                "order_total" numeric not null,
                "order_deposit" numeric not null,
                "order_remainder" numeric not null,
                "order_due" numeric not null,
                "photo_path" varchar,
                "attachment_path" varchar,
                "status" varchar check ("status" in ("draft", "review", "approved", "ready_to_print")) not null default "draft",
                "data" text,
                "created_at" datetime,
                "updated_at" datetime,
                "manager" varchar,
                "canvas_height" numeric,
                foreign key("user_id") references "users"("id") on delete cascade,
                foreign key("branch_id") references "branches"("id") on delete cascade
            )');

            // Копируем данные обратно
            DB::statement('INSERT INTO contracts_old SELECT 
                id, contract_number, user_id, branch_id, client, instagram, iin, phone, phone2,
                address, payment, date, category, model, width, height, design, leaf,
                framugawidth, framugaheight, forging, opening, frame, outer_panel, outer_cover,
                outer_cover_color, metal_cover_hidden, metal_cover_color, inner_trim, inner_cover,
                inner_trim_color, glass_unit, extra, lock, handle, steel_thickness, canvas_thickness,
                measurement, delivery, installation, order_total, order_deposit, order_remainder,
                order_due, photo_path, attachment_path,
                CASE 
                    WHEN status = "draft" THEN "draft"
                    WHEN status = "pending_rop" THEN "review"
                    WHEN status = "pending_accountant" THEN "review"
                    WHEN status = "approved" THEN "approved"
                    WHEN status = "rejected" THEN "review"
                    WHEN status = "on_hold" THEN "review"
                    ELSE "draft"
                END as status,
                data, created_at, updated_at, manager, canvas_height
                FROM contracts');

            DB::statement('DROP TABLE contracts');
            DB::statement('ALTER TABLE contracts_old RENAME TO contracts');
            DB::statement('CREATE UNIQUE INDEX contracts_contract_number_unique ON contracts (contract_number)');
        }
    }
};
