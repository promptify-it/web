<script setup lang="ts">
import { useTheme } from '@/composables/use-theme';
import * as monaco from 'monaco-editor';
import { onMounted, ref, watch } from 'vue';
import '../worker';

const props = withDefaults(
  defineProps<{
    defaultValue?: string;
    modelValue: string;
  }>(),
  {
    modelValue: '',
  },
);

const emit = defineEmits<{
  (event: 'update:modelValue', value: string): void;
}>();
const { theme, init } = useTheme();

const editor = ref();

onMounted(async () => {
  init();

  const monacoEditor = monaco.editor.create(editor.value, {
    value: props.modelValue,
    language: 'json',
    theme: theme.value == 'light' ? 'vs' : 'vs-dark',
    minimap: {
      enabled: false,
    },
  });

  watch(
    () => theme.value,
    () => {
      monaco.editor.setTheme(theme.value == 'light' ? 'vs' : 'vs-dark');
    },
  );

  monacoEditor.onDidChangeModelContent(() => {
    const value = monacoEditor.getValue();

    emit('update:modelValue', value);
  });

  monaco.languages.json.jsonDefaults.setDiagnosticsOptions({
    validate: true,
    schemas: [
      {
        uri: 'http://json-schema.org/draft-07/schema#',
        fileMatch: ['*'],
        schema: {
          type: 'object',
          properties: {
            signature: {
              type: 'string',
            },
            description: {
              type: 'string',
            },
            root: {
              type: 'object',
              properties: {
                nodes: {
                  type: 'array',
                  items: {
                    type: 'object',
                    properties: {
                      type: {
                        type: 'string',
                        enum: [
                          'text-input',
                          'assign',
                          'file-template',
                          'shell',
                        ],
                      },
                      content: {
                        type: 'object',
                        properties: {},
                        allOf: [
                          {
                            if: {
                              properties: {
                                type: {
                                  const: 'text-input',
                                },
                              },
                            },
                            then: {
                              properties: {
                                label: {
                                  type: 'string',
                                },
                                value: {
                                  type: 'string',
                                },
                              },
                              required: ['label', 'value'],
                            },
                          },
                        ],
                      },
                    },
                    required: ['type', 'content'],
                  },
                },
              },
              required: ['nodes'],
            },
          },
          required: ['signature', 'description', 'root'],
        },
      },
    ],
  });
});
</script>

<template>
  <div id="editor" ref="editor"></div>
</template>

<style>
.monaco-editor .margin,
.sticky-widget,
.monaco-editor-background {
  @apply dark:!bg-gray-900;
  /* --vscode-editor-background: rgb(17 24 39 / var(--tw-bg-opacity)) !important; */
}

#root {
  @apply rounded-sm !border dark:border-gray-800;
}
</style>
