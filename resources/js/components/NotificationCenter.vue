<template>
    <DropdownMenu >
    <DropdownMenuTrigger as-child>
        <Button variant="ghost" size="icon" class="relative">
            <Bell class="w-5 h-5" />
            <span
                v-if="unreadCount > 0"
                class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-primary-content bg-primary rounded-full"
            >
                {{ unreadCount }}
            </span>
        </Button>
    </DropdownMenuTrigger>
    <DropdownMenuContent align="end" class="w-[400px] p-0 bg-base-100">
        <div class="p-4 border-b border-accent">
        <div class="flex items-center justify-between">
        <h3 class="font-medium">Notifications</h3>
        <div class="flex items-center gap-2">
        <Button v-if="unreadCount > 0" variant="ghost" size="sm" @click="markAllAsRead">Mark all as read</Button>
        <Button variant="ghost" size="icon" @click="handleOpenSettings" class="w-8h-8"></Button>
            <SettingsIcon class="w-4 h-4" />
        </div>
        </div>
        </div>
        <Tabs default-value="unread" class="w-full">
        <div class="px-4 pt-2">
        <TabsList clas="grid w-full grid-cols-2">
        <TabsTrigger value="unread" class="relative">Unread
        <Badge v-if="unreadCount > 0" class="ml-2 bg-info text-info-content text-xs px-1.5 py-0 hover:bg-info/50 ">{{ unreadCount }}</Badge>
        </TabsTrigger>
        <TabsTrigger value="all">All</TabsTrigger>
        </TabsList>
        </div>
        <TabsContent value="unread" class="m-0">
        <ScrollArea class="h-[400px]">
        <div v-if="unreadNotifications.length === 0" class="flex flex-col items-center justify-center py-12 text-center">
        <div class="w-16 h-16 bg-success rounded-full flex items-center justify-center mb-3">
        <CheckCircle2  color="var(--color-success-content)" class="w-8 h-8 "/>
        </div>
        <p>All caught up!</p>
        <p class="text-sm">No unread notifications</p>
        </div>
        <div v-else class="flex flex-col">
        <button
                v-for="notification in unreadNotifications"
                :key="notification.id"
                @click="markAsRead(notification.id)"
                :class="[
                  'w-full text-left p-4 hover:bg-slate-50 transition-colors border-b border-slate-100',
                  !notification.read ? 'bg-blue-50' : ''
                ]"
              >
                <div class="flex gap-3">
                  <div
                    :class="[
                      'w-10 h-10 rounded-full flex items-center justify-center shrink-0',
                      notification.iconColor
                    ]"
                  >
                    <component :is="notification.icon" class="w-5 h-5" />
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2 mb-1">
                      <h4
                        :class="[
                          'text-sm font-medium',
                          !notification.read ? 'text-slate-900' : 'text-slate-700'
                        ]"
                      >
                        {{ notification.title }}
                      </h4>
                      <div
                        v-if="!notification.read"
                        class="w-2 h-2 bg-blue-600 rounded-full shrink-0 mt-1"
                      />
                    </div>
                    <p class="text-sm text-slate-600 mb-1">{{ notification.message }}</p>
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                      <span>{{ notification.course }}</span>
                      <span>•</span>
                      <span>{{ notification.timestamp }}</span>
                    </div>
                  </div>
                </div>
              </button>
            </div>
          </ScrollArea>
        </TabsContent>

        <TabsContent value="all" class="m-0">
          <ScrollArea class="h-[400px]">
            <div class="flex flex-col">
              <button
                v-for="notification in notifications"
                :key="notification.id"
                @click="markAsRead(notification.id)"
                :class="[
                  'w-full text-left p-4 hover:bg-slate-50 transition-colors border-b border-slate-100',
                  !notification.read ? 'bg-blue-50' : ''
                ]"
              >
                <div class="flex gap-3">
                  <div
                    :class="[
                      'w-10 h-10 rounded-full flex items-center justify-center shrink-0',
                      notification.iconColor
                    ]"
                  >
                    <component :is="notification.icon" class="w-5 h-5" />
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2 mb-1">
                      <h4
                        :class="[
                          'text-sm font-medium',
                          !notification.read ? 'text-slate-900' : 'text-slate-700'
                        ]"
                      >
                        {{ notification.title }}
                      </h4>
                      <div
                        v-if="!notification.read"
                        class="w-2 h-2 bg-blue-600 rounded-full shrink-0 mt-1"
                      />
                    </div>
                    <p class="text-sm text-slate-600 mb-1">{{ notification.message }}</p>
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                      <span>{{ notification.course }}</span>
                      <span>•</span>
                      <span>{{ notification.timestamp }}</span>
                    </div>
                  </div>
                </div>
              </button>
            </div>
          </ScrollArea>
        </TabsContent>
      </Tabs>

      <div class="p-3 border-t border-accent bg-base-100">
        <Button
          variant="ghost"
          class="w-full text-sm"
          @click="handleOpenSettings"
        >
          Notification Settings
        </Button>
      </div>
    </DropdownMenuContent>
  </DropdownMenu>
</template>

<script setup lang="ts">
    import {ref, computed} from 'vue';
    import{Bell, CheckCircle2, Settings as SettingsIcon} from 'lucide-vue-next';
    import {Button} from '@/components/ui/button';
    import {Badge} from '@/components/ui/badge';
    import {DropdownMenu, DropdownMenuContent, DropdownMenuTrigger} from '@/components/ui/dropdown-menu';
    import {ScrollArea} from '@/components/ui/scroll-area';
    import {Tabs, TabsContent, TabsList, TabsTrigger} from '@/components/ui/tabs';

    interface Notification {
        id: string;
        type: string;
        title: string;
        message: string;
        course?: string;
        timestamp: string;
        read: boolean;
        icon: any;
        iconColor: string;
        priority: 'high' | 'medium' | 'low' | 'critical';
    }

    const props = defineProps<{onOpenSettings?: ()=> void;}>();

    const emit = defineEmits<{
        (e: 'openSettings'): void;
    }>();

    const notifications = ref<Notification[]>([]);

    const unreadNotifications = computed(()=> notifications.value.filter((n)=> !n.read));
    const unreadCount = computed(()=> unreadNotifications.value.length);

 const markAsRead = (id: string) => {
        const notification = notifications.value.find((n) => n.id === id);
        if (notification) {
            notification.read = true;
        }
    };

    const markAllAsRead = () => {
        notifications.value.forEach((n) => (n.read = true));
    };

    const handleOpenSettings = () => {
        if (props.onOpenSettings) 
            props.onOpenSettings();
      
            emit('openSettings');
        
    };

</script>

<style scoped>

</style>