<?php

namespace App\DTO\Response;

// Formatage de la réponse
class HttpResponse
{
    public bool $success = false;
    public int $status = 500;
    public $messages;
    public $data;

    public function __construct($status, $data = null, $messages = null)
    {
        $this->status = $status;
        $this->messages = $messages;
        $this->data = $data;

        if ($status >= 200 && $status < 300)
            $this->success = true;

        if ($messages == null) {
            switch ($status) {
                case 400:
                    $messages = ["Requête incorrecte"];
                    break;
                case 401:
                    $messages = ["Non autorisé"];
                    break;
                case 403:
                    $messages = ["Interdit"];
                    break;
                case 404:
                    $messages = ["Non trouvé"];
                    break;
                case 500:
                    $messages = ["Erreur interne du serveur"];
                    break;
                default:
                    $messages = [];
                    break;
            }

            $this->messages = $messages;
        }
    }
}
