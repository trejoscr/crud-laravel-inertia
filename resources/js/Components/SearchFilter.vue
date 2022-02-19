<template>
  <div class="flex search-input-container">
    <div class="flex w-full">
      <input class="mr-4 relative px-6 py-2 w-full rounded-lg focus:shadow-outline" autocomplete="off" type="text" name="search" placeholder="Search…" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" />
    </div>
    <button class="flex-sheink bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md" type="button" @click="$emit('reset')">Reset</button>
  </div>
</template>

<script>
export default {
  props: {
    modelValue: String,
    maxWidth: {
      type: Number,
      default: 300,
    },
  },
  emits: ['update:modelValue', 'reset'],
}
</script>