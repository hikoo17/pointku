<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ExceptionMessages
{
    /**
     * Tentukan kode status HTTP yang sesuai untuk sebuah exception.
     */
    public static function status(Throwable $e): int
    {
        if ($e instanceof ValidationException) {
            return Response::HTTP_UNPROCESSABLE_ENTITY;
        }

        if ($e instanceof AuthenticationException) {
            return Response::HTTP_UNAUTHORIZED;
        }

        if ($e instanceof AuthorizationException) {
            return Response::HTTP_FORBIDDEN;
        }

        if ($e instanceof ModelNotFoundException) {
            return Response::HTTP_NOT_FOUND;
        }

        if ($e instanceof HttpException) {
            return $e->getStatusCode();
        }

        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    /**
     * Terjemahkan exception menjadi pesan ramah yang bisa dipahami
     * pengguna non-teknis. Pesan kustom (sudah berbahasa Indonesia) akan
     * dipertahankan, sedangkan pesan bawaan framework diterjemahkan.
     */
    public static function message(Throwable $e): string
    {
        if ($e instanceof ValidationException) {
            return 'Terdapat kesalahan pada data yang Anda kirimkan. Silakan periksa kembali.';
        }

        if ($e instanceof AuthenticationException) {
            return 'Sesi Anda telah berakhir. Silakan masuk kembali.';
        }

        if ($e instanceof AuthorizationException) {
            return 'Akses ditolak. Anda tidak memiliki izin untuk melakukan tindakan ini.';
        }

        if ($e instanceof ModelNotFoundException) {
            return 'Data yang Anda cari tidak ditemukan atau sudah dihapus.';
        }

        if ($e instanceof HttpException) {
            $code = $e->getStatusCode();
            $message = trim((string) $e->getMessage());

            // Gunakan pesan kustom apabila sudah disetel (biasanya sudah
            // berbahasa Indonesia). Hanya ganti pesan bawaan framework.
            $generic = [
                400 => 'Bad Request',
                401 => 'Unauthorized',
                403 => 'Forbidden',
                404 => 'Not Found',
                419 => 'Page Expired',
                429 => 'Too Many Requests',
                500 => 'Server Error',
                503 => 'Service Unavailable',
            ];

            if ($message === '' || (isset($generic[$code]) && strcasecmp($message, $generic[$code]) === 0)) {
                return self::friendlyForCode($code);
            }

            return $message;
        }

        return 'Terjadi kesalahan pada server. Silakan coba beberapa saat lagi.';
    }

    public static function friendlyForCode(int $code): string
    {
        return match ($code) {
            Response::HTTP_BAD_REQUEST => 'Permintaan tidak dapat diproses. Silakan periksa kembali data yang dikirimkan.',
            Response::HTTP_UNAUTHORIZED => 'Sesi Anda telah berakhir. Silakan masuk kembali.',
            Response::HTTP_FORBIDDEN => 'Akses ditolak. Anda tidak memiliki izin untuk melakukan tindakan ini.',
            Response::HTTP_NOT_FOUND => 'Halaman atau data yang Anda tuju tidak ditemukan.',
            Response::HTTP_METHOD_NOT_ALLOWED => 'Metode yang digunakan tidak diizinkan.',
            419 => 'Halaman telah kedaluwarsa. Silakan muat ulang dan coba lagi.',
            Response::HTTP_UNPROCESSABLE_ENTITY => 'Terdapat kesalahan pada data yang dikirimkan. Silakan periksa kembali.',
            Response::HTTP_TOO_MANY_REQUESTS => 'Terlalu banyak permintaan. Silakan tunggu beberapa saat dan coba lagi.',
            Response::HTTP_INTERNAL_SERVER_ERROR => 'Terjadi kesalahan pada server. Silakan coba beberapa saat lagi.',
            Response::HTTP_SERVICE_UNAVAILABLE => 'Layanan sedang tidak tersedia. Silakan coba beberapa saat lagi.',
            Response::HTTP_GATEWAY_TIMEOUT => 'Layanan terlalu lama merespons. Silakan coba beberapa saat lagi.',
            default => 'Terjadi kesalahan. Silakan coba beberapa saat lagi.',
        };
    }
}
