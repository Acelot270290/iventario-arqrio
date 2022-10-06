<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NovoCadastroNotification extends Notification
{
    use Queueable;
    public $token;
    public $email;
    public $name;
    public $pwd;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $email, $name, $pwd)
    {
        $this->token = $token;
        $this->email = $email;
        $this->name = $name;
        $this->pwd = $pwd;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url('/password/reset/' . $this->token.'?email='.$this->email);// arrumar esse link que nem mexi (se for usar essa classe)
        $minutos = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');
        $nome = $this->name;
        $pwd = $this->pwd;
        return (new MailMessage)
            ->subject('Novo cadastro')
            ->greeting('Olá ' . $nome)
            ->line('Você está recebendo este e-mail porque este e-mail foi cadastrado em nosso sistema.')
            ->line('Seu login é o seu e-mail.')
            ->line('Sua senha atual é ' . $pwd . ' porém recomendamos que mude de senha.')
            ->action('Clique Aqui para Redefinir a senha', $url)
            ->line('Este link de redefinição de senha expirará em: ' . $minutos . 'minutos')
            ->line('Se você não solicitou uma redefinição de senha, nenhuma ação adicional é necessária.')
            ->salutation('Até Breve!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
