<?php

namespace App\Providers;

use App\Models\Student;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Services\Implementations\RoleService;
use App\Services\Implementations\UserService;
use App\Repositories\Eloquent\ProgramRepository;
use App\Repositories\Eloquent\StudentRepository;
use App\Repositories\Eloquent\SubjectRepository;
use App\Repositories\Eloquent\TeacherRepository;
use App\Services\Contracts\RoleServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\Implementations\ProgramService;
use App\Services\Implementations\StudentService;
use App\Services\Implementations\SubjectService;
use App\Services\Implementations\TeacherService;
use App\Repositories\Eloquent\MaterialRepository;
use App\Services\Implementations\MaterialService;
use App\Repositories\Eloquent\ClassTypeRepository;
use App\Services\Implementations\ClassTypeService;
use App\Repositories\Eloquent\PermissionRepository;
use App\Services\Contracts\ProgramServiceInterface;
use App\Services\Contracts\StudentServiceInterface;
use App\Services\Contracts\SubjectServiceInterface;
use App\Services\Contracts\TeacherServiceInterface;
use App\Services\Implementations\PermissionService;
use App\Services\Contracts\MaterialServiceInterface;
use App\Services\Contracts\ClassTypeServiceInterface;
use App\Services\Contracts\PermissionServiceInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\EducationLevelRepository;
use App\Services\Implementations\EducationLevelService;
use App\Repositories\Eloquent\MeetingFrequencyRepository;
use App\Services\Implementations\MeetingFrequencyService;
use App\Repositories\Contracts\ProgramRepositoryInterface;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\Contracts\SubjectRepositoryInterface;
use App\Repositories\Contracts\TeacherRepositoryInterface;
use App\Services\Contracts\EducationLevelServiceInterface;
use App\Repositories\Contracts\MaterialRepositoryInterface;
use App\Repositories\Contracts\ClassTypeRepositoryInterface;
use App\Services\Contracts\MeetingFrequencyServiceInterface;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use App\Repositories\Contracts\EducationLevelRepositoryInterface;
use App\Repositories\Contracts\MeetingFrequencyRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Binding User
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);

        // Binding Role
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(RoleServiceInterface::class, RoleService::class);

        // Binding Permission
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(PermissionServiceInterface::class, PermissionService::class);

        // Binding Education Level
        $this->app->bind(EducationLevelRepositoryInterface::class, EducationLevelRepository::class);
        $this->app->bind(EducationLevelServiceInterface::class, EducationLevelService::class);

        // Binding Meeting Frequency
        $this->app->bind(MeetingFrequencyRepositoryInterface::class, MeetingFrequencyRepository::class);
        $this->app->bind(MeetingFrequencyServiceInterface::class, MeetingFrequencyService::class);

        // Binding Subject
        $this->app->bind(SubjectRepositoryInterface::class, SubjectRepository::class);
        $this->app->bind(SubjectServiceInterface::class, SubjectService::class);

        // Binding Class Type
        $this->app->bind(ClassTypeRepositoryInterface::class, ClassTypeRepository::class);
        $this->app->bind(ClassTypeServiceInterface::class, ClassTypeService::class);

        // Binding Program
        $this->app->bind(ProgramRepositoryInterface::class, ProgramRepository::class);
        $this->app->bind(ProgramServiceInterface::class, ProgramService::class);

        // Binding Material
        $this->app->bind(MaterialRepositoryInterface::class, MaterialRepository::class);
        $this->app->bind(MaterialServiceInterface::class, MaterialService::class);

        // Binding Teacher
        $this->app->bind(TeacherRepositoryInterface::class, TeacherRepository::class);
        $this->app->bind(TeacherServiceInterface::class, TeacherService::class);

        // Binding Student
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
    $this->app->bind(StudentServiceInterface::class, StudentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
