<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Tugas;

class TechnicianAssignedNotification extends Notification
{
    use Queueable;
    protected $tugas;

    /**
     * Create a new notification instance.
     */
    public function __construct(Tugas $tugas)
    {
        $this->tugas = $tugas;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Anda Ditugaskan Perbaikan Baru')
                    ->line('Anda telah ditugaskan untuk menangani perbaikan.')
                    ->line('Detail Tugas: ' . $this->tugas->deskripsi)
                    ->line('Deadline: ' . $this->tugas->deadline)
                    ->action('Lihat Tugas', url('/teknisi/tugas/' . $this->tugas->id_tugas))
                    ->line('Terima kasih atas kerjasamanya!');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        $laporan = $this->tugas->laporan;
        $fasilitas = $laporan->fasilitasRuang->fasilitas->nama_fasilitas ?? 'Fasilitas';
        $ruang = $laporan->fasilitasRuang->ruang->nama_ruang ?? 'Ruang';
        
        return [
            'id_tugas' => $this->tugas->id_tugas,
            'id_laporan' => $laporan->id_laporan,
            'title' => 'Tugas Perbaikan Baru',
            'message' => "Anda ditugaskan untuk memperbaiki $fasilitas di $ruang",
            'deskripsi' => $this->tugas->deskripsi,
            'deadline' => $this->tugas->batas_waktu->format('d/m/Y'),
            'prioritas' => $this->tugas->prioritas
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id_tugas' => $this->tugas->id_tugas,
            'id_laporan' => $this->tugas->id_laporan
        ];
    }
}
