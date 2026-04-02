<?php

declare(strict_types=1);

namespace Axn\Notifier\Concerns;

use Axn\Notifier\Notify;
use Illuminate\Support\Collection;

trait CanGroupMessagesByType
{
    /**
     * Macro collection pour grouper les messages par type.
     */
    public static function groupMessagesByType(Collection $messages): Collection
    {
        $grouped = [
            Notify::INFO => null,
            Notify::SUCCESS => null,
            Notify::WARNING => null,
            Notify::ERROR => null,
        ];

        $messages
            ->groupBy('type')
            ->each(function (Collection $messages, $type) use (&$grouped): void {
                if (\array_key_exists($type, $grouped)) {
                    static::groupMessagesOfSameType($messages, $grouped[$type]);
                }
            });

        return collect(array_filter($grouped));
    }

    private static function groupMessagesOfSameType(Collection $messages, ?array &$messagesType): void
    {
        if ($messages->isEmpty()) {
            return;
        }

        $messagesFormat = config('notifier.group_messages_format');
        $titleFormat = config('notifier.group_title_format');
        $messageFormat = config('notifier.group_message_format');

        $firstMessage = $messages->first();
        $messageId = $firstMessage['id'];
        $messageType = $firstMessage['type'];
        $typeOrder = $firstMessage['type_order'];

        $messages->each(function (array $message) use (&$messagesType, $messageId, $messageType, $typeOrder, $titleFormat, $messageFormat): void {
            $messagesType = [
                'id' => $messageId,
                'type' => $messageType,
                'message' => self::formatGroupedMessages($messagesType, $message, $titleFormat, $messageFormat),
                'title' => null,
                'delay' => $message['delay'],
                'type_order' => $typeOrder,
            ];
        });

        $messagesType['message'] = \sprintf($messagesFormat, $messagesType['message']);
    }

    private static function formatGroupedMessages(?array &$messagesType, array $message, string $titleFormat, string $messageFormat): string
    {
        $title = empty($message['title']) ? '' : \sprintf($titleFormat, $message['title']);

        if (empty($messagesType['message'])) {
            return \sprintf($messageFormat, $title, $message['message']);
        }

        return $messagesType['message'].\sprintf($messageFormat, $title, $message['message']);
    }
}
