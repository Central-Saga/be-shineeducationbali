<?php

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CertificateGradeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\ClassTypeController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EducationLevelController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\MeetingFrequencyController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\GradeCategoryController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentAttendanceController;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::middleware(['auth:sanctum', 'check.user.status'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
    // Permissions
    Route::middleware('permission:mengelola permissions')->group(function () {
        Route::apiResource('permissions', PermissionController::class);
        Route::patch('permissions/{id}/status', [PermissionController::class, 'updateStatus']);
    });
    // Roles
    Route::middleware('permission:mengelola roles')->group(function () {
        Route::apiResource('roles', RoleController::class);
        Route::patch('roles/{id}/status', [RoleController::class, 'updateStatus']);
    });
    // Users
    Route::middleware('permission:mengelola users')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::patch('users/{id}/status', [UserController::class, 'updateStatus']);
    });
    // Class Types
    Route::middleware('permission:mengelola class types')->group(function () {
        Route::apiResource('class-types', ClassTypeController::class);
        Route::patch('class-types/{id}/status', [ClassTypeController::class, 'updateStatus']);
    });
    // Education Levels
    Route::middleware('permission:mengelola education levels')->group(function () {
        Route::apiResource('education-levels', EducationLevelController::class);
        Route::patch('education-levels/{id}/status', [EducationLevelController::class, 'updateStatus']);
    });
    // Meeting Frequencies
    Route::middleware('permission:mengelola meeting frequencies')->group(function () {
        Route::apiResource('meeting-frequencies', MeetingFrequencyController::class);
        Route::patch('meeting-frequencies/{id}/status', [MeetingFrequencyController::class, 'updateStatus']);
    });
    // Subjects
    Route::middleware('permission:mengelola subjects')->group(function () {
        Route::apiResource('subjects', SubjectController::class);
        Route::patch('subjects/{id}/status', [SubjectController::class, 'updateStatus']);
    });
    // Programs
    Route::middleware('permission:mengelola programs')->group(function () {
        Route::apiResource('programs', ProgramController::class);
        Route::patch('programs/{id}/status', [ProgramController::class, 'updateStatus']);
    });
    // Materials
    Route::middleware('permission:mengelola materials')->group(function () {
        Route::apiResource('materials', MaterialController::class);
        Route::patch('materials/{id}/status', [MaterialController::class, 'updateStatus']);
    });
    // Teachers
    Route::middleware('permission:mengelola teachers')->group(function () {
        Route::apiResource('teachers', TeacherController::class);
        Route::patch('teachers/{id}/status', [TeacherController::class, 'updateStatus']);
    });
    // Students
    Route::middleware('permission:mengelola students')->group(function () {
        Route::apiResource('students', StudentController::class);
        Route::patch('students/{id}/status', [StudentController::class, 'updateStatus']);
    });
    // Class Rooms
    Route::middleware('permission:mengelola class rooms')->group(function () {
        Route::apiResource('class-rooms', ClassRoomController::class);
        Route::patch('class-rooms/{id}/status', [ClassRoomController::class, 'updateStatus']);
        Route::post('class-rooms/{id}/attach-students', [ClassRoomController::class, 'attachStudent']);
        Route::post('class-rooms/{id}/detach-students', [ClassRoomController::class, 'detachStudent']);
    });
    // Schedules
    Route::middleware('permission:mengelola schedules')->group(function () {
        Route::apiResource('schedules', ScheduleController::class);
    });

    // Assignments
    Route::middleware('permission:mengelola assignments')->group(function () {
        Route::apiResource('assignments', AssignmentController::class);
        Route::patch('assignments/{id}/status', [AssignmentController::class, 'updateStatus']);
    });
    // Grade Category
    Route::middleware('permission:mengelola grade categories')->group(function () {
        Route::apiResource('grade-categories', GradeCategoryController::class);
    });
    // Certificates
    Route::middleware('permission:mengelola certificates')->group(function () {
        Route::apiResource('certificates', CertificateController::class);
    });
    
    // Grades
    Route::middleware('permission:mengelola grades')->group(function () {
        Route::apiResource('grades', GradeController::class);
        Route::get('grades/student/{studentId}', [GradeController::class, 'getByStudent']);
        Route::get('grades/class-room/{classRoomId}', [GradeController::class, 'getByClassRoom']);
        Route::get('grades/grade-category/{gradeCategoryId}', [GradeController::class, 'getByGradeCategory']);
        Route::get('grades/average/student/{studentId}/material/{materialId}', [GradeController::class, 'getAverageByStudentAndMaterial']);
        Route::get('grades/average/class-room/{classRoomId}/material/{materialId}', [GradeController::class, 'getAverageByClassRoomAndMaterial']);
    });
    
    // Certificate Grades
    Route::middleware('permission:mengelola certificate grades')->group(function () {
        Route::apiResource('certificate-grades', CertificateGradeController::class);
        Route::get('certificate-grades/certificate/{certificateId}', [CertificateGradeController::class, 'getByCertificateId']);
        Route::get('certificate-grades/grade/{gradeId}', [CertificateGradeController::class, 'getByGradeId']);
    });
    
    // Student Attendances
    Route::middleware('permission:mengelola student attendances')->group(function () {
        Route::apiResource('student-attendances', StudentAttendanceController::class);
        Route::get('student-attendances/student/{studentId}', [StudentAttendanceController::class, 'getByStudent']);
        Route::get('student-attendances/class-room/{classRoomId}', [StudentAttendanceController::class, 'getByClassRoom']);
        Route::get('student-attendances/teacher/{teacherId}', [StudentAttendanceController::class, 'getByTeacher']);
        Route::get('student-attendances/date/{date}', [StudentAttendanceController::class, 'getByDate']);
        Route::get('student-attendances/date-range/{startDate}/{endDate}', [StudentAttendanceController::class, 'getByDateRange']);
        Route::get('student-attendances/status/{status}', [StudentAttendanceController::class, 'getByStatus']);
        Route::get('student-attendances/summary/{studentId}', [StudentAttendanceController::class, 'getStudentAttendanceSummary']);
    });
    // Leaves
    Route::middleware('permission:mengelola leaves')->group(function () {
        Route::apiResource('leaves', LeaveController::class);
        Route::patch('leaves/{id}/status', [LeaveController::class, 'updateStatus']);
    });
    // Notifications
    Route::middleware('permission:mengelola notifications')->group(function () {
        Route::apiResource('notifications', NotificationController::class);
        Route::patch('notifications/{id}/status', [NotificationController::class, 'updateStatus']);
    });
    // Article
    Route::middleware('permission:mengelola articles')->group(function () {
        Route::apiResource('articles', ArticleController::class);
        Route::patch('articles/{id}/status', [ArticleController::class, 'updateStatus']);
    });
    // Job Vacancies
    Route::middleware('permission:mengelola job vacancies')->group(function () {
        Route::apiResource('job-vacancies', JobVacancyController::class);
        Route::patch('job-vacancies/{id}/status', [JobVacancyController::class, 'updateStatus']);
    });
    // Job Applications
    Route::middleware('permission:mengelola jobApplications')->group(function () {
        Route::apiResource('job-applications', JobApplicationController::class);
        Route::patch('job-applications/{id}/status', [JobApplicationController::class, 'updateStatus']);
    // Testimonials
    Route::middleware('permission:mengelola testimonials')->group(function () {
        Route::apiResource('testimonials', TestimonialController::class);
        Route::get('testimonials/by-name', [TestimonialController::class, 'getByName']);
    });

});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
