<template>
    <div class="markdown" v-html="renderedMarkdown"></div>
</template>

<script setup>
import markdownit from 'markdown-it'
import hljs from 'highlight.js' //
import 'highlight.js/styles/panda-syntax-dark.css'
import {computed} from "vue";

const md = markdownit({
    breaks:       true,
    linkify:      true,
    highlight: function (str, lang) {
        if (lang && hljs.getLanguage(lang)) {
            try {
                return hljs.highlight(str, { language: lang }).value;
            } catch (__) {}
        }

        return ''; // use external default escaping
    }
})
    .disable(['heading'])


const props = defineProps({
    markdown: {
        type: String,
        required: true,
    },
})

const renderedMarkdown = computed(() => {
    return md.render(props.markdown);
})
</script>
