<?php

namespace App\Mail;

use App\Models\Blog;
use App\Models\Post;
use App\Models\Qna;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QnaCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $qna;
    protected $blog;
    protected $post;

    public function __construct(Qna $qna)
    {
        $this->qna = $qna;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.qnas.created')
            ->subject("[예산 : {$this->qna->budget}만원] {$this->qna->company}로부터 문의가 도착하였습니다.")
            ->with([ "qna" => $this->qna]);
    }
}
