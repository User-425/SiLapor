<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\LaporanKerusakan;

class StatusChangedNotification extends Notification
{
    use Queueable;
    protected $report;
    protected $oldStatus;
    protected $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(LaporanKerusakan $report, $oldStatus, $newStatus)
    {
        $this->report = $report;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
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
        $statusLabel = str_replace('_', ' ', ucwords($this->newStatus));
        
        return (new MailMessage)
                    ->subject('Status Laporan Kerusakan Berubah')
                    ->line("Status laporan kerusakan Anda telah berubah menjadi: $statusLabel")
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
        $statusLabel = str_replace('_', ' ', ucwords($this->newStatus));
        
        return [
            'id_laporan' => $this->report->id_laporan,
            'title' => 'Status Laporan Berubah',
            'message' => "Laporan kerusakan pada $fasilitasName di $ruangName sekarang berstatus: $statusLabel",
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'url_foto' => $this->report->url_foto
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
            'id_laporan' => $this->report->id_laporan,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus
        ];
    }
}
