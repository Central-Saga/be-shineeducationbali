<?php

namespace App\Providers;

use App\Models\Student;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Services\Implementations\RoleService;
use App\Services\Implementations\UserService;
use App\Repositories\Eloquent\GradeRepository;
use App\Services\Implementations\GradeService;
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
use App\Repositories\Eloquent\ScheduleRepository;
use App\Services\Contracts\GradeServiceInterface;
use App\Services\Implementations\MaterialService;
use App\Services\Implementations\ScheduleService;
use App\Repositories\Eloquent\ClassRoomRepository;
use App\Repositories\Eloquent\ClassTypeRepository;
use App\Services\Implementations\ClassRoomService;
use App\Services\Implementations\ClassTypeService;
use App\Repositories\Eloquent\AssignmentRepository;
use App\Repositories\Eloquent\PermissionRepository;
use App\Services\Contracts\ProgramServiceInterface;
use App\Services\Contracts\StudentServiceInterface;
use App\Services\Contracts\SubjectServiceInterface;
use App\Services\Contracts\TeacherServiceInterface;
use App\Services\Implementations\AssignmentService;
use App\Services\Implementations\PermissionService;
use App\Repositories\Eloquent\CertificateRepository;
use App\Services\Contracts\MaterialServiceInterface;
use App\Services\Contracts\ScheduleServiceInterface;
use App\Services\Implementations\CertificateService;
use App\Services\Contracts\ClassRoomServiceInterface;
use App\Services\Contracts\ClassTypeServiceInterface;
use App\Repositories\Eloquent\GradeCategoryRepository;
use App\Services\Contracts\AssignmentServiceInterface;
use App\Services\Contracts\PermissionServiceInterface;
use App\Services\Implementations\GradeCategoryService;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\EducationLevelRepository;
use App\Services\Contracts\CertificateServiceInterface;
use App\Services\Implementations\EducationLevelService;
use App\Repositories\Contracts\GradeRepositoryInterface;
use App\Repositories\Eloquent\CertificateGradeRepository;
use App\Repositories\Eloquent\MeetingFrequencyRepository;
use App\Services\Contracts\GradeCategoryServiceInterface;
use App\Services\Implementations\CertificateGradeService;
use App\Services\Implementations\MeetingFrequencyService;
use App\Repositories\Contracts\ProgramRepositoryInterface;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\Contracts\SubjectRepositoryInterface;
use App\Repositories\Contracts\TeacherRepositoryInterface;
use App\Repositories\Eloquent\StudentAttendanceRepository;
use App\Services\Contracts\EducationLevelServiceInterface;
use App\Services\Implementations\StudentAttendanceService;
use App\Repositories\Contracts\MaterialRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use App\Repositories\Contracts\ClassRoomRepositoryInterface;
use App\Repositories\Contracts\ClassTypeRepositoryInterface;
use App\Services\Contracts\CertificateGradeServiceInterface;
use App\Services\Contracts\MeetingFrequencyServiceInterface;
use App\Repositories\Contracts\AssignmentRepositoryInterface;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use App\Services\Contracts\StudentAttendanceServiceInterface;
use App\Repositories\Contracts\CertificateRepositoryInterface;
use App\Repositories\Eloquent\StudentQuotaRepository;
use App\Repositories\Eloquent\TeacherAttendanceRepository;
use App\Services\Implementations\StudentQuotaService;
use App\Services\Implementations\TeacherAttendanceService;
use App\Repositories\Contracts\StudentQuotaRepositoryInterface;
use App\Repositories\Contracts\TeacherAttendanceRepositoryInterface;
use App\Services\Contracts\StudentQuotaServiceInterface;
use App\Services\Contracts\TeacherAttendanceServiceInterface;
use App\Repositories\Contracts\EducationLevelRepositoryInterface;
use App\Repositories\Contracts\GradeCategoryRepositoryInterface;
use App\Repositories\Contracts\CertificateGradeRepositoryInterface;
use App\Repositories\Contracts\MeetingFrequencyRepositoryInterface;
use App\Repositories\Contracts\StudentAttendanceRepositoryInterface;

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

        // Binding Grade Categories
        $this->app->bind(GradeCategoryRepositoryInterface::class, GradeCategoryRepository::class);
        $this->app->bind(GradeCategoryServiceInterface::class, GradeCategoryService::class);

        // Binding Certificate
        $this->app->bind(CertificateRepositoryInterface::class, CertificateRepository::class);
        $this->app->bind(CertificateServiceInterface::class, CertificateService::class);
      
        // Binding Schedule
        $this->app->bind(ScheduleRepositoryInterface::class, ScheduleRepository::class);
        $this->app->bind(ScheduleServiceInterface::class, ScheduleService::class);

        // Binding Assignment
        $this->app->bind(AssignmentRepositoryInterface::class, AssignmentRepository::class);
        $this->app->bind(AssignmentServiceInterface::class, AssignmentService::class);

        // Binding Class Room
        $this->app->bind(ClassRoomRepositoryInterface::class, ClassRoomRepository::class);
        $this->app->bind(ClassRoomServiceInterface::class, ClassRoomService::class);
        
        // Binding Grade
        $this->app->bind(GradeRepositoryInterface::class, GradeRepository::class);
        $this->app->bind(GradeServiceInterface::class, GradeService::class);
        
        // Binding Certificate Grade
        $this->app->bind(CertificateGradeRepositoryInterface::class, CertificateGradeRepository::class);
        $this->app->bind(CertificateGradeServiceInterface::class, CertificateGradeService::class);
        
        // Binding Student Attendance
        $this->app->bind(StudentAttendanceRepositoryInterface::class, StudentAttendanceRepository::class);
        $this->app->bind(StudentAttendanceServiceInterface::class, StudentAttendanceService::class);

        // Binding Student Quota
        $this->app->bind(StudentQuotaRepositoryInterface::class, StudentQuotaRepository::class);
        $this->app->bind(StudentQuotaServiceInterface::class, StudentQuotaService::class);

        // Binding Teacher Attendance
        $this->app->bind(TeacherAttendanceRepositoryInterface::class, TeacherAttendanceRepository::class);
        $this->app->bind(TeacherAttendanceServiceInterface::class, TeacherAttendanceService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
