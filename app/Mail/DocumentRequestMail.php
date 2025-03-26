<?php

namespace App\Mail;

use App\Models\OptiHr\DocumentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $receiverName;
    public $requesterName;
    public $documentType;
    public $dateOfApplication;
    public $startDate;
    public $endDate;
    public $reasons;
    public $url;

    public function __construct($data)
    {
        $this->receiverName = $data['receiverName'];
        $this->requesterName = $data['requesterName'];
        $this->documentType = $data['documentType'];
        $this->dateOfApplication = $data['dateOfApplication'];
        $this->startDate = $data['startDate'] ?? null;
        $this->endDate = $data['endDate'] ?? null;
        $this->reasons = $data['reasons'] ?? null;
        $this->url = $data['url'];
    }

    public function build()
    {
        return $this->subject('Nouvelle demande de document')
            ->view("modules.opti-hr.emails.document-request-created");
    }
}