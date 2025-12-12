<template>
    <div>
        <ul>
            <li
                class="me-11 flex max-w-lg gap-x-2 sm:gap-x-4"
                v-if="sender && (message.body || message.attachments)"
            >
                <img
                    :src="message.user.profile.avatar"
                    :alt="message.user.first_name"
                />
                <div class="flex flex-col">
                    <div
                        class="text-primary-foreground rounded-xl bg-primary/10 p-3 text-sm"
                    >
                        {{ message.user.first_name }} -
                        <time>{{ message.created_at }}</time>
                        <div>
                            {{ message.body }}
                        </div>
                    </div>
                </div>
                <div
                    v-if="message.attachments && message.attachments.length > 0"
                    class="mt-2 flex flex-col gap-y-2"
                >
                    <div
                        v-for="(attachment, index) in message.attachments"
                        :key="index"
                        class="rounded-lg border border-primary/20 p-2"
                    >
                        <a
                            :href="attachment.url"
                            target="_blank"
                            class="text-primary underline"
                        >
                            {{ attachment.filename }}
                        </a>
                    </div>
                </div>
            </li>
            <li
                v-if="!sender && (message.body || message.attachments)"
                class="ms-auto flex max-w-lg justify-end gap-x-2 sm:gap-x-4"
            ></li>
        </ul>
    </div>
</template>

<script setup lang="ts">
const props = defineProps<{
    message: any;
    sender: any;
}>();
</script>

<style scoped></style>
