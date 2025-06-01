<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\LaporanKerusakan;

class FeedbackRequestNotification extends Notification
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
                    ->subject('Permintaan Feedback Perbaikan')
                    ->line('Perbaikan untuk laporan kerusakan Anda telah selesai.')
                    ->line('Mohon berikan feedback Anda tentang hasil perbaikan.')
                    ->action('Berikan Feedback', url('/laporan/' . $this->report->id_laporan . '/feedback'))
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
            'title' => 'Permintaan Feedback',
            'message' => "Perbaikan pada $fasilitasName di $ruangName telah selesai. Mohon berikan feedback Anda!",
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
            'id_laporan' => $this->report->id_laporan
        ];
    }
}
