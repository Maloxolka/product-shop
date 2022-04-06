<?php

declare(strict_types=1);

namespace App\Formatters;

use App\Components\Formatters\BaseFormatter;
use App\Entity\Message;

class MessageFormatter extends BaseFormatter
{
    /**
     * @param Message[] $messages
     */
    public function formatArray(array $messages): array
    {
        $result = [];

        foreach ($messages as $message) {
            $result[] = $this->format($message);
        }

        return $result;
    }

    public function format(Message $message): array
    {
        return [
            'id' => $message->id,
            'name' => $message->name,
            'contacts' => $message->contacts,
            'subject' => $message->subject,
            'body' => $message->body,
            'file_link' => [
                'url' => $message->file_link,
                'default' => null,
            ],
        ];
    }
}
