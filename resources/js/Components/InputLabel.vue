<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="my-2  block w-full  text-secondary font-semibold">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
