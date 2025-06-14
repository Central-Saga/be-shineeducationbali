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
use App\Http\Controllers\BankAccountController;
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
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionDetailController;
use App\Http\Controllers\GradeCategoryController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentAttendanceController;
use App\Http\Controllers\TeacherAttendanceController;
use App\Http\Controllers\StudentQuotaController;
use App\Http\Controllers\AssetController;

// Public routes with throttling
Route::middleware(['throttle:6,1'])->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
    Route::middleware('permission:mengelola classes')->group(function () {
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

    // Teacher Attendances
    Route::middleware('permission:mengelola teacher attendances')->group(function () {
        Route::apiResource('teacher-attendances', TeacherAttendanceController::class);
        Route::post('teacher-attendances/check-in', [TeacherAttendanceController::class, 'checkIn']);
        Route::post('teacher-attendances/check-out', [TeacherAttendanceController::class, 'checkOut']);
        Route::get('teacher-attendances/teacher-stats/{teacherId}', [TeacherAttendanceController::class, 'teacherStats']);
        Route::get('teacher-attendances/class-room-stats/{classRoomId}', [TeacherAttendanceController::class, 'classRoomStats']);
    });

    // Student Quotas
    Route::middleware('permission:mengelola student quotas')->group(function () {
        Route::apiResource('student-quotas', StudentQuotaController::class);
        Route::post('student-quotas/{id}/use-session', [StudentQuotaController::class, 'useSession']);
        Route::post('student-quotas/{id}/add-paid', [StudentQuotaController::class, 'addPaidSessions']);
        Route::post('student-quotas/{id}/add-accumulated', [StudentQuotaController::class, 'addAccumulatedSessions']);
        Route::get('student-quotas/student-stats/{studentId}', [StudentQuotaController::class, 'studentStats']);
        Route::get('student-quotas/program-stats/{programId}', [StudentQuotaController::class, 'programStats']);
    });

    // Assets
    Route::middleware('permission:mengelola assets')->group(function () {
        Route::apiResource('assets', AssetController::class);
        Route::get('assets/{modelType}/{modelId}', [AssetController::class, 'getAssets']);
        Route::post('assets/{modelType}/{modelId}', [AssetController::class, 'uploadAsset']);
        Route::post('assets/{modelType}/{modelId}/multiple', [AssetController::class, 'uploadMultipleAssets']);
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
        
        // Get notifications by type
        Route::get('notifications/type/{type}', [NotificationController::class, 'getNotificationsByType']);
        Route::get('notifications/type/payment', [NotificationController::class, 'findByTypePayment']);
        Route::get('notifications/type/leave', [NotificationController::class, 'findByTypeLeave']);
        Route::get('notifications/type/student', [NotificationController::class, 'findByTypeStudent']);
        Route::get('notifications/type/teacher', [NotificationController::class, 'findByTypeTeacher']);
        Route::get('notifications/type/assignment', [NotificationController::class, 'findByTypeAssignment']);
        Route::get('notifications/type/attendance', [NotificationController::class, 'findByTypeAttendance']);
        Route::get('notifications/type/grade', [NotificationController::class, 'findByTypeGrade']);
        
        // Get notifications by status
        Route::get('notifications/status/read', [NotificationController::class, 'findByStatusRead']);
        Route::get('notifications/status/unread', [NotificationController::class, 'findByStatusUnread']);
        
        // Get notifications by user
        Route::get('notifications/user/{userId}', [NotificationController::class, 'getNotificationsByUser']);
        
        // Get notifications by date
        Route::get('notifications/today', [NotificationController::class, 'getTodayNotifications']);
        Route::get('notifications/date-range/{startDate}/{endDate}', [NotificationController::class, 'getNotificationsByDateRange']);
        
        // Mark notifications as read
        Route::patch('notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
        Route::patch('notifications/mark-all-as-read/{userId}', [NotificationController::class, 'markAllAsRead']);
    });

    // Article
    Route::middleware('permission:mengelola articles')->group(function () {
        Route::apiResource('articles', ArticleController::class);
        Route::patch('articles/{id}/status', [ArticleController::class, 'updateStatus']);
    });

    // Job Vacancies
    Route::middleware('permission:mengelola jobvacancies')->group(function () {
        Route::apiResource('job-vacancies', JobVacancyController::class);
        Route::patch('job-vacancies/{id}/status', [JobVacancyController::class, 'updateStatus']);
    });

    // Job Applications
    Route::middleware('permission:mengelola jobapplications')->group(function () {
        Route::apiResource('job-applications', JobApplicationController::class);
        Route::patch('job-applications/{id}/status', [JobApplicationController::class, 'updateStatus']);
    });

    // Testimonials
    Route::middleware('permission:mengelola testimonials')->group(function () {
        Route::apiResource('testimonials', TestimonialController::class);
        Route::get('testimonials/by-name', [TestimonialController::class, 'getByName']);
    });

    // Bank Accounts
    Route::middleware('permission:mengelola bankaccounts')->group(function () {
        Route::apiResource('bank-accounts', BankAccountController::class);
        Route::patch('bank-accounts/{id}/status', [BankAccountController::class, 'updateStatus']);
    });

    // Transactions
    Route::middleware('permission:mengelola transactions')->group(function () {
        Route::apiResource('transactions', TransactionController::class);
        Route::patch('transactions/{id}/status', [TransactionController::class, 'updateStatus']);
    });

    // Transaction Details
    Route::middleware('permission:mengelola transactionDetails')->group(function () {
        Route::apiResource('transaction-details', TransactionDetailController::class);
        Route::patch('transaction-details/{id}/type', [TransactionDetailController::class, 'updateType']);
    });
});

// User status check
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
