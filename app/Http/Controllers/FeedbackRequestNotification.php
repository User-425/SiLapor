<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class FeedbackRequestNotification extends Notification
{
    use Queueable;

    protected $laporan;

    public function __construct($laporan)
    {
        $this->laporan = $laporan;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Laporan Selesai - Beri Umpan Balik')
            ->line('Laporan kerusakan Anda (ID: ' . $this->laporan->id_laporan . ') telah selesai.')
            ->action('Beri Umpan Balik', route('umpan_balik.create', $this->laporan->id_laporan))
            ->line('Terima kasih atas kerja samanya!');
    }

    public function toDatabase($notifiable)
    {
        return new DatabaseMessage([
            'message' => 'Laporan kerusakan #' . $this->laporan->id_laporan . ' telah selesai. Silakan beri umpan balik.',
            'url' => route('umpan_balik.create', $this->laporan->id_laporan),
            'laporan_id' => $this->laporan->id_laporan,
        ]);
    }
}