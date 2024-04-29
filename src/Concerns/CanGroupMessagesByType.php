<?php

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
        $infoMessages = null;
        $successMessages = null;
        $warningMessages = null;
        $errorMessages = null;

        $messages
            ->groupBy('type')
            ->each(function ($messages, $type) use (&$infoMessages, &$successMessages, &$warningMessages, &$errorMessages) {
                if ($type == Notify::INFO) {
                    static::groupMessagesOfSameType($messages, $infoMessages);
                } elseif ($type == Notify::SUCCESS) {
                    static::groupMessagesOfSameType($messages, $successMessages);
                } elseif ($type == Notify::WARNING) {
                    static::groupMessagesOfSameType($messages, $warningMessages);
                } elseif ($type == Notify::ERROR) {
                    static::groupMessagesOfSameType($messages, $errorMessages);
                }
            });

        return collect()
            ->when(\is_array($infoMessages), function ($groupedMessages) use ($infoMessages) {
                return $groupedMessages->push($infoMessages);
            })
            ->when(\is_array($successMessages), function ($groupedMessages) use ($successMessages) {
                return $groupedMessages->push($successMessages);
            })
            ->when(\is_array($warningMessages), function ($groupedMessages) use ($warningMessages) {
                return $groupedMessages->push($warningMessages);
            })
            ->when(\is_array($errorMessages), function ($groupedMessages) use ($errorMessages) {
                return $groupedMessages->push($errorMessages);
            });
    }

    private static function groupMessagesOfSameType(Collection $messages, ?array &$messagesType)
    {
        static $messagesFormat = null;

        if ($messages->isEmpty()) {
            return;
        }

        if (\is_null($messagesFormat)) {
            $messagesFormat = config('notifier.group_messages_format');
        }

        $firstMessage = $messages->first();
        $messageId = $firstMessage['id'];
        $messageType = $firstMessage['type'];
        $typeOrder = $firstMessage['type_order'];

        $messages->each(function ($message) use (&$messagesType, $messageId, $messageType, $typeOrder) {
            $messagesType = [
                'id' => $messageId,
                'type' => $messageType,
                'message' => self::formatGroupedMessages($messagesType, $message),
                'title' => null,
                'delay' => $message['delay'],
                'type_order' => $typeOrder,
            ];
        });

        $messagesType['message'] = sprintf($messagesFormat, $messagesType['message']);
    }

    private static function formatGroupedMessages(?array &$messagesType, array $message): string
    {
        static $titleFormat = null;
        static $messageFormat = null;

        if (\is_null($titleFormat)) {
            $titleFormat = config('notifier.group_title_format');
            $messageFormat = config('notifier.group_message_format');
        }

        $title = ! empty($message['title']) ? sprintf($titleFormat, $message['title']) : '';

        if (empty($messagesType['message'])) {
            return sprintf($messageFormat, $title, $message['message']);
        }

        return $messagesType['message'].sprintf($messageFormat, $title, $message['message']);
    }
}
