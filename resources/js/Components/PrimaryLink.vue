<script>
    import { Link } from "@inertiajs/vue3";
    export default{
        components : {
            Link
        },
        props : {
            href : String,
        }
    }
</script>

<template>
    <a :href="href"
        class="text-white text-center font-semibold rounded-3xl px-4 py-2 mt-6 w-full block bg-primary"
    >
        <slot />
    </a>
</template>
