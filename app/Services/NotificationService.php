<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\FcmToken;
use App\Models\User; // Changed: Only User model needed

class NotificationService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/firebase-service-account.json'));
            
        $this->messaging = $factory->createMessaging();
    }

    public function sendToUser($userId, $userType, $notificationData)
    {
        // Always use User::class since all users are in the users table
        $token = FcmToken::where('tokenable_id', $userId)
            ->where('tokenable_type', User::class) // Simplified
            ->first();

        if (!$token) {
            \Log::warning("No FCM token found for user $userId");
            return false;
        }

        $notificationData = array_merge($this->getNotificationDefaults(), $notificationData);

        $notification = Notification::create(
            $notificationData['title'],
            $notificationData['body']
        );

        $message = CloudMessage::withTarget('token', $token->token)
            ->withNotification($notification)
            ->withData($notificationData)
            ->withHighPriority();

        try {
            $this->messaging->send($message);
            return true;
        } catch (\Kreait\Firebase\Exception\Messaging\InvalidMessage $e) {
            $token->delete();
            \Log::warning("Deleted invalid FCM token for user $userId");
            return false;
        } catch (\Exception $e) {
            \Log::error("FCM Error for user $userId: " . $e->getMessage());
            return false;
        }
    }

    public function sendToUsers(array $userIds, $userType, $notificationData)
    {
        // Always use User::class
        $tokens = FcmToken::whereIn('tokenable_id', $userIds)
            ->where('tokenable_type', User::class) // Simplified
            ->pluck('token')
            ->toArray();

        if (empty($tokens)) {
            \Log::warning("No FCM tokens found for users: " . implode(', ', $userIds));
            return false;
        }

        $notificationData = array_merge($this->getNotificationDefaults(), $notificationData);

        $message = CloudMessage::new()
            ->withNotification(Notification::create(
                $notificationData['title'],
                $notificationData['body']
            ))
            ->withData($notificationData);

        try {
            $this->messaging->sendMulticast($message, $tokens);
            return true;
        } catch (\Exception $e) {
            \Log::error("FCM Multicast Error: " . $e->getMessage());
            return false;
        }
    }

    protected function getNotificationDefaults()
    {
        return [
            'title' => 'New Notification',
            'body' => 'You have a new notification',
            'sound' => 'default',
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
        ];
    }
}