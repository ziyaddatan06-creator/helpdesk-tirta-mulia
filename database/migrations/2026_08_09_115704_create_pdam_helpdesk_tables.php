<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Master Wilayah
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->timestamps();
        });

        // 2. Modifikasi Tabel Users (Menambahkan data spesifik PDAM)
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->string('customer_number')->nullable()->comment('Hanya untuk pelanggan');
            $table->foreignId('district_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('village_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('avatar')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
        });

        // 3. Master Data Tiket
        Schema::create('ticket_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('ticket_subcategories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('ticket_categories')->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('ticket_priorities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->integer('sla_hours'); 
            $table->string('color_code');
            $table->timestamps();
        });

        Schema::create('ticket_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('color_code');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 4. Tabel Utama: Tiket Helpdesk
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique(); 
            $table->string('title');
            $table->text('description');
            
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('admin_id')->nullable()->constrained('users');
            $table->foreignId('technician_id')->nullable()->constrained('users');
            
            $table->foreignId('district_id')->nullable()->constrained();
            $table->foreignId('village_id')->nullable()->constrained();
            $table->text('address_detail')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            
            $table->foreignId('category_id')->constrained('ticket_categories');
            $table->foreignId('subcategory_id')->nullable()->constrained('ticket_subcategories');
            $table->foreignId('priority_id')->constrained('ticket_priorities');
            $table->foreignId('status_id')->constrained('ticket_statuses');
            
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('sla_due_date')->nullable();
            $table->boolean('is_sla_breached')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
        });

        // 5. Lampiran Tiket (Foto/File)
        Schema::create('ticket_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type'); 
            $table->timestamps();
        });

        // 6. Komentar/Diskusi Tiket
        Schema::create('ticket_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->timestamps();
        });

        // 7. Riwayat Aktivitas Tiket
        Schema::create('ticket_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action'); 
            $table->text('description');
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();
            $table->timestamps();
        });

        // 8. Rating dari Pelanggan
        Schema::create('ticket_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('rating'); 
            $table->text('review')->nullable();
            $table->timestamps();
        });

        // 9. Log Aktivitas Sistem
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->text('description');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('ticket_ratings');
        Schema::dropIfExists('ticket_histories');
        Schema::dropIfExists('ticket_comments');
        Schema::dropIfExists('ticket_attachments');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('ticket_statuses');
        Schema::dropIfExists('ticket_priorities');
        Schema::dropIfExists('ticket_subcategories');
        Schema::dropIfExists('ticket_categories');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'phone', 'address', 'customer_number', 'district_id', 'village_id', 'unit_id', 'avatar', 'is_active']);
        });
        
        Schema::dropIfExists('units');
        Schema::dropIfExists('villages');
        Schema::dropIfExists('districts');
    }
};