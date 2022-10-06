<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CadastroUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userid;
    public $pwd;
    public $email;

      /**
     * The order instance.
     *
     * @var \App\Models\User
     * */

      /**
     * Create a new message instance.
     *
     * * @param  \App\Models\User  $user
     * 
     * @return void
     */
    public function __construct($userid, $pwd)
    {
        // Seleciona o usuário
        $usr = User::select('users.id', 'name', 'email')
        ->where('users.id', '=', $userid)
        ->first();
        // Atribui às variáveis
        $this->user = $usr['name'];
        $this->pwd = $pwd;
        $this->email = $usr['email'];
    }
    

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         //$url = url('/password/reset/' . $this->token.'?email='.$this->email);// arrumar esse link que nem mexi (se for usar essa classe)
        $minutos = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');
        
        $this->subject('Novo cadastro');
        return $this->view('mail.CadastroUser_mail', [
            'user' => $this->user,
            'pwd' => $this->pwd,
            'email' => $this->email,
            ]);
    }
}
