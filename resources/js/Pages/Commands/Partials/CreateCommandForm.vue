<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import MonacoEditor from '@/Components/MonacoEditor.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  definition: `{
    "signature": "",
    "description": "",
    "root": {
        "nodes": [
            {
                "type": "text-input",
                "content": {
                    "key": "NAME",
                    "label": "Name",
                    "placeholder": "Enter your name"
                }
            },
            {
                "type": "shell",
                "content": {
                    "script": "echo $NAME"
                }
            }
        ]
    }
}`,
});

const createCommand = () => {
  form.post(route('command.store'), {
    errorBag: 'createCommand',
    preserveScroll: true,
    onBefore: () => {
      form.definition = JSON.stringify(JSON.parse(form.definition), null, 2);
    },
  });
};
</script>

<template>
  <form class="grid gap-4" @submit.prevent="createCommand">
    <div class="col-span-6 sm:col-span-4">
      <MonacoEditor
        id="root"
        v-model="form.definition"
        class="h-[60vh] w-full"
      />

      <InputError :message="form.errors.definition" class="mt-2" />
    </div>

    <PrimaryButton
      :class="{ 'opacity-25': form.processing }"
      :disabled="form.processing"
    >
      Create
    </PrimaryButton>
  </form>
</template>
