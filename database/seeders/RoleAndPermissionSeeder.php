<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // list of tables to manage
        $tables = [
            'assets',
            'education levels',
            'subjects',
            'class types',
            'meeting frequencies',
            'programs',
            'users',
            'teachers',
            'students',
            'schedules',
            'classes',
            'student classes',
            'materials',
            'teacher attendances',
            'student attendances',
            'grade categories',
            'leaves',
            'certificates',
            'bankaccounts',
            'transactions',
            'transaction details',
            'student quotas',
            'articles',
            'testimonials',
            'notifications',
            'assignments',
            'grades',
            'certificate grades',
            'jobvacancies',
            'jobapplications',
            'roles',
            'permissions',
            'role permissions',
            'user roles',
        ];

        // create permissions for each table using "mengelola {table}"
        foreach ($tables as $table) {
            Permission::create(['name' => "mengelola {$table}"]);
        }

        // create permissions for each table using "melihat {table}"
        foreach ($tables as $table) {
            Permission::create(['name' => "melihat {$table}"]);
        }

        // create roles
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'Admin']);
        $teacher = Role::create(['name' => 'Teacher']);
        $student = Role::create(['name' => 'Student']);

        // assign all permissions to Super Admin
        $superAdmin->givePermissionTo(Permission::all());

        // For Admin, assign all permissions except those for managing role, user, and permissions
        $adminPermissions = Permission::whereNotIn('name', [
            'mengelola role',
            'mengelola user',
            'mengelola permissions'
        ])->get();
        $admin->givePermissionTo($adminPermissions);

        // For Teacher, assign only specific additional permissions
        $teacher->givePermissionTo([
            'mengelola students',
            'mengelola classes',
            'mengelola student classes',
            'mengelola materials',
            'mengelola student attendances',
            'mengelola grades',
            'mengelola certificates',
            'mengelola articles',
            'mengelola testimonials',
            'mengelola assignments',
            'mengelola schedules',
        ]);

        // For Student, assign only specific additional permissions
        $student->givePermissionTo([
            'melihat students',
            'melihat classes',
            'melihat student classes',
            'melihat materials',
            'melihat student attendances',
            'melihat grades',
            'melihat certificates',
            'melihat articles',
            'melihat testimonials',
            'melihat notifications',
            'melihat assignments',
            'melihat jobvacancies',
            'melihat jobapplications',
            'melihat transactions',
            'melihat transaction details',
            'melihat student quotas',
            'melihat bankaccounts',
            'melihat certificates',
        ]);
    }
}
