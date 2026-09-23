<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'manager', 'employee'])->default('employee')->after('email');
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete()->after('role');
            $table->string('employee_id')->unique()->nullable()->after('department_id');
            $table->string('phone')->nullable()->after('employee_id');
            $table->date('join_date')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('join_date');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['role', 'department_id', 'employee_id', 'phone', 'join_date', 'is_active']);
        });
    }
};
