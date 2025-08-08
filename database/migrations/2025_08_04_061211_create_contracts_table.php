<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("contracts", function (Blueprint $table) {
            $table->id();
            $table->string("contract_number")->unique();
            $table->foreignId("user_id")->constrained()->onDelete("cascade");
            $table->foreignId("branch_id")->constrained()->onDelete("cascade");
            $table->string("client");
            $table->string("instagram");
            $table->string("iin", 12);
            $table->string("phone");
            $table->string("phone2");
            $table->text("address")->nullable();
            $table->string("payment")->default("Наличный");
            $table->date("date");
            $table->string("category");
            $table->string("model");
            $table->decimal("width", 8, 2);
            $table->decimal("height", 8, 2);
            $table->string("design")->default("Меняется");
            $table->string("leaf");
            $table->string("framugawidth");
            $table->string("framugaheight");
            $table->string("forging")->nullable();
            $table->string("opening")->default("Правое");
            $table->string("frame")->default("Стандарт");
            $table->string("outer_panel");
            $table->string("outer_cover");
            $table->string("outer_cover_color")->nullable();
            $table->string("metal_cover_hidden")->default("Порошково-полимерное");
            $table->string("metal_cover_color")->nullable();
            $table->string("inner_trim");
            $table->string("inner_cover");
            $table->string("inner_trim_color")->nullable();
            $table->string("glass_unit");
            $table->string("extra")->nullable();
            $table->string("lock");
            $table->string("handle");
            $table->decimal("steel_thickness", 3, 1);
            $table->integer("canvas_thickness");
            $table->string("measurement")->default("online");
            $table->string("delivery")->default("+");
            $table->text("installation")->nullable();
            $table->decimal("order_total", 12, 2);
            $table->decimal("order_deposit", 12, 2);
            $table->decimal("order_remainder", 12, 2);
            $table->decimal("order_due", 12, 2);
            $table->string("photo_path")->nullable();
            $table->string("attachment_path")->nullable();
            $table->enum("status", ["draft", "review", "approved", "ready_to_print"])->default("draft");
            $table->json("data")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("contracts");
    }
};
