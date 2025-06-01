<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\LaporanKerusakan;

class NewReportNotification extends Notification
{
    use Queueable;
    protected $report;

    /**
     * Create a new notification instance.
     */
    public function __construct(LaporanKerusakan $report)
    {
        $this->report = $report;
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
            ->subject('Laporan Kerusakan Baru')
            ->line('Laporan kerusakan baru telah dibuat.')
            ->action('Lihat Laporan', url('/laporan/' . $this->report->id_laporan))
            ->line('Terima kasih telah menggunakan SiLapor!');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        $fasilitasName = $this->report->fasilitasRuang->fasilitas->nama_fasilitas ?? 'Fasilitas';
        $ruangName = $this->report->fasilitasRuang->ruang->nama_ruang ?? 'Ruang';
        
        return [
            'id_laporan' => $this->report->id_laporan,
            'title' => 'Laporan Kerusakan Baru',
            'message' => "Laporan kerusakan pada $fasilitasName di $ruangName",
            'created_by' => $this->report->pengguna->nama_lengkap,
            'url_foto' => $this->report->url_foto,
            'status' => $this->report->status
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
            'id_laporan' => $this->report->id_laporan
        ];
    }
}