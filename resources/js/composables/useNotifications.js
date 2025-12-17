import axios from 'axios';
import { onMounted, onUnmounted, ref } from 'vue';

export function useNotifications() {
    const notifications = ref([]);
    const unreadCount = ref(0);

    const fetchNotifications = async () => {
        try {
            const response = await axios.get('/notifications');
            notifications.value = response.data;
            unreadCount.value = response.data.filter((n) => !n.read_at).length;
        } catch (error) {
            console.error('Failed to fetch notifications:', error);
        }
    };

    const markAsRead = async (notificationId) => {
        try {
            await axios.post(`/notifications/${notificationId}/read`);
            const notification = notifications.value.find(
                (n) => n.id === notificationId,
            );
            if (notification) {
                notification.read_at = new Date().toISOString();
                unreadCount.value = Math.max(0, unreadCount.value - 1);
            }
        } catch (error) {
            console.error('Failed to mark notification as read:', error);
        }
    };

    const markAllAsRead = async () => {
        try {
            await axios.post('/notifications/mark-all-read');
            notifications.value.forEach((n) => {
                n.read_at = new Date().toISOString();
            });
            unreadCount.value = 0;
        } catch (error) {
            console.error('Failed to mark all as read:', error);
        }
    };

    const listenForNotifications = () => {
        window.Echo.private(
            `App.Models.User.${window.authUser?.id}`,
        ).notification((notification) => {
            notifications.value.unshift(notification);
            unreadCount.value++;

            // Show browser notification if permitted
            if (
                'Notification' in window &&
                Notification.permission === 'granted'
            ) {
                new Notification(notification.sender_name, {
                    body: notification.body,
                    icon: '/favicon.ico',
                });
            }
        });
    };

    const requestNotificationPermission = async () => {
        if ('Notification' in window && Notification.permission === 'default') {
            await Notification.requestPermission();
        }
    };

    onMounted(() => {
        fetchNotifications();
        listenForNotifications();
        requestNotificationPermission();
    });

    onUnmounted(() => {
        window.Echo.leave(`App.Models.User.${window.authUser?.id}`);
    });

    return {
        notifications,
        unreadCount,
        fetchNotifications,
        markAsRead,
        markAllAsRead,
    };
}
