<script setup lang="ts">
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
});

const createTeam = () => {
  form.post(route('teams.store'), {
    errorBag: 'createTeam',
    preserveScroll: true,
  });
};
</script>

<template>
  <FormSection @submitted="createTeam">
    <template #title> Team Details </template>

    <template #description>
      Create a new team to collaborate with others on projects.
    </template>

    <template #form>
      <div class="col-span-6">
        <InputLabel value="Team Owner" />

        <div class="mt-2 flex items-center">
          <img
            class="h-12 w-12 object-cover"
            :src="$page.props.auth.user.profile_photo_url"
            :alt="$page.props.auth.user.name"
          />

          <div class="ms-4 leading-tight">
            <div class="text-neutral-900 dark:text-white">
              {{ $page.props.auth.user.name }}
            </div>
            <div class="text-sm text-neutral-700 dark:text-neutral-300">
              {{ $page.props.auth.user.email }}
            </div>
          </div>
        </div>
      </div>

      <div class="col-span-6 sm:col-span-4">
        <InputLabel for="name" value="Team Name" />
        <TextInput
          id="name"
          v-model="form.name"
          type="text"
          class="mt-1 block w-full"
          autofocus
        />
        <InputError :message="form.errors.name" class="mt-2" />
      </div>
    </template>

    <template #actions>
      <PrimaryButton
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        Create
      </PrimaryButton>
    </template>
  </FormSection>
</template>
