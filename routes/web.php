<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AtividadeController;
use App\Http\Controllers\ConviteController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\EscolaController;
use App\Http\Controllers\Admin\CursoController;
use App\Http\Controllers\Admin\DisciplinaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AtividadeController as AdminAtividadeController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\EmailVerificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Rotas de autenticação (apenas para utilizadores não autenticados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/register/verify', [AuthController::class, 'showVerifyForm'])->name('register.verify');
    Route::post('/register/verify', [AuthController::class, 'verifyCode'])->name('register.verify');
    
    // Rotas de recuperação de palavra-passe
    Route::get('/password/forgot', [PasswordResetController::class, 'showRequestForm'])->name('password.request');
    Route::post('/password/forgot', [PasswordResetController::class, 'sendCode'])->name('password.forgot');
    Route::get('/password/reset', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [PasswordResetController::class, 'resetPassword'])->name('password.reset');
    
    // Rotas de verificação de email
    Route::get('/email/verify/{token}', [EmailVerificationController::class, 'verify'])->name('email.verify');
    Route::get('/email/resend', [EmailVerificationController::class, 'showResendForm'])->name('email.resend.show');
    Route::post('/email/resend', [EmailVerificationController::class, 'resend'])->name('email.resend');
});

// Rotas protegidas (apenas para utilizadores autenticados)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Perfil
    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/perfil/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::put('/perfil/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    
    // Atividades
    Route::get('/atividades', [AtividadeController::class, 'index'])->name('atividades.index');
    Route::get('/atividades/create', [AtividadeController::class, 'create'])->name('atividades.create');
    Route::post('/atividades', [AtividadeController::class, 'store'])->name('atividades.store');
    Route::get('/atividades/{id}', [AtividadeController::class, 'show'])->name('atividades.show');
    Route::get('/atividades/{id}/edit', [AtividadeController::class, 'edit'])->name('atividades.edit');
    Route::put('/atividades/{id}', [AtividadeController::class, 'update'])->name('atividades.update');
    Route::delete('/atividades/{id}', [AtividadeController::class, 'destroy'])->name('atividades.destroy');
    Route::post('/atividades/{id}/archive', [AtividadeController::class, 'archive'])->name('atividades.archive');
    Route::post('/atividades/{id}/approve', [AtividadeController::class, 'approve'])->name('atividades.approve');
    Route::post('/atividades/{id}/reject', [AtividadeController::class, 'reject'])->name('atividades.reject');
    
    // Convites
    Route::get('/convites', [ConviteController::class, 'index'])->name('convites.index');
    Route::get('/atividades/{eventoId}/convites/create', [ConviteController::class, 'create'])->name('convites.create');
    Route::post('/atividades/{eventoId}/convites', [ConviteController::class, 'store'])->name('convites.store');
    Route::post('/convites/{id}/accept', [ConviteController::class, 'accept'])->name('convites.accept');
    Route::post('/convites/{id}/reject', [ConviteController::class, 'reject'])->name('convites.reject');
    Route::delete('/convites/{id}', [ConviteController::class, 'destroy'])->name('convites.destroy');
    
    // Área de Administração (apenas para admins)
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Gestão Académica
        Route::resource('escolas', EscolaController::class);
        Route::resource('cursos', CursoController::class);
        Route::resource('disciplinas', DisciplinaController::class);
        
        // Gestão de Utilizadores e Atividades
        Route::resource('users', UserController::class);
        Route::resource('atividades', AdminAtividadeController::class);
    });
});
