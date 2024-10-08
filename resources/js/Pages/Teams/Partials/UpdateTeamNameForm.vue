<script setup lang="ts">
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
  team: {
    name: string;
    owner: {
      name: string;
      email: string;
      profile_photo_url: string;
    };
  };
  permissions: {
    canUpdateTeam: boolean;
  };
}>();

const form = useForm({
  name: props.team.name,
});

const updateTeamName = () => {
  form.put(route('teams.update', props.team), {
    errorBag: 'updateTeamName',
    preserveScroll: true,
  });
};
</script>

<template>
  <FormSection @submitted="updateTeamName">
    <template #title> Team Name </template>

    <template #description> The team's name and owner information. </template>

    <template #form>
      <!-- Team Owner Information -->
      <div class="col-span-6">
        <InputLabel value="Team Owner" />

        <div class="mt-2 flex items-center">
          <img
            class="h-12 w-12 object-cover"
            :src="team.owner.profile_photo_url"
            :alt="team.owner.name"
          />

          <div class="ms-4 leading-tight">
            <div class="text-neutral-900 dark:text-white">
              {{ team.owner.name }}
            </div>
            <div class="text-sm text-neutral-700 dark:text-neutral-300">
              {{ team.owner.email }}
            </div>
          </div>
        </div>
      </div>

      <!-- Team Name -->
      <div class="col-span-6 sm:col-span-4">
        <InputLabel for="name" value="Team Name" />

        <TextInput
          id="name"
          v-model="form.name"
          type="text"
          class="mt-1 block w-full"
          :disabled="!permissions.canUpdateTeam"
        />

        <InputError :message="form.errors.name" class="mt-2" />
      </div>
    </template>

    <template v-if="permissions.canUpdateTeam" #actions>
      <ActionMessage :on="form.recentlySuccessful" class="me-3">
        Saved.
      </ActionMessage>

      <PrimaryButton
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        Save
      </PrimaryButton>
    </template>
  </FormSection>
</template>
