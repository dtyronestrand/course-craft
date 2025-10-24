<template>
    <section v-if="editor" class="button flex item-center px-8 flex-wrap">
        <button @click="editor.chain().focus().toggleBold().run()"
            :class="{'bg-primary': editor.isActive('bold')}">BOLD</button>
        <button @click="editor.chain().focus().toggleItalic().run()"
            :class="{'bg-primary': editor.isActive('italic')}">ITALICS</button>
        <button @click="editor.chain().focus().toggleUnderline().run()"
            :class="{'bg-primary': editor.isActive('underline')}">Underline</button>
    </section>
    <editor-content :editor="editor" />
</template>

<script setup lang="ts">
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import { onBeforeUnmount } from 'vue'
const props = defineProps({
    modelValue: String,
})

const emit = defineEmits(['update:modelValue'])

const editor = useEditor({
    content: props.modelValue,
    onUpdate: ({editor})=>{
        emit('update:modelValue', editor.getHTML())
    },
    extensions: [
        StarterKit,
    ],
    editorProps: {
        attributes: {
            class: 'min-h-[12rem] max-h-[12rem] overflow-y-auto prose prose-sm sm:prose lg:prose-lg xl:prose-2xl m-5 focus:outline-none bg-base-300 border p-4'
        },
    },
 
})

onBeforeUnmount(() => {
    editor.value?.destroy()
})
</script>

<style scoped>
button {
    border: 2px solid var(--color-primary);
    padding: 0.5rem 1rem;
  
}
</style>