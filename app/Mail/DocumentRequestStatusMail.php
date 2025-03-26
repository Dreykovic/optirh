<?php

namespace App\Mail;

use App\Models\OptiHr\DocumentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentRequestStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $receiverName;
    public $documentRequest;
    public $documentType;
    public $status;
    public $comment;
    public $url;

    public function __construct($data)
    {
        $this->receiverName = $data['receiverName'];
        $this->documentRequest = $data['documentRequest'];
        $this->documentType = $data['documentType'];
        $this->status = $data['status'];
        $this->comment = $data['comment'] ?? null;
        $this->url = $data['url'];
    }

    public function build()
    {
        return $this->subject('Statut de votre demande de document ' . $this->documentType)
            ->view("modules.opti-hr.emails.document-request-status");
    }
}