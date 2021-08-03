<?php

namespace App\Twig;

use App\Repository\ContactMessageRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NotificationsExtension extends AbstractExtension
{
    private ContactMessageRepository $contactMessageRepository;

    /**
     * @param ContactMessageRepository $contactMessageRepository
     */
    public function __construct(ContactMessageRepository $contactMessageRepository)
    {
        $this->contactMessageRepository = $contactMessageRepository;
    }

    public function getFunctions() {
        return [
          new TwigFunction('get_messages', [$this, 'getMessages']),
        ];
    }

    public function getMessages() {
        $messages = $this->contactMessageRepository->findAll();
        $notReadedMessages = [];
        foreach ($messages as $message) {
            if(!$message->getIsRead()) {
                $notReadedMessages[] = $message;
            }
        }
        return $notReadedMessages;
    }
}