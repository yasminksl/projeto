<template>
    <div class="w-full flex justify-center items-center mt-6">
        <button @click="handleClick"
            class="hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800 py-5 w-96 md:w-full bg-gray-800 text-base font-medium leading-4 text-white"
            :class="aditionalClass">
            {{ value }}
        </button>
    </div>
</template>

<script setup>

let props = defineProps({
    value: String,
    aditionalClass: String
});

const emit = defineEmits(['click']);

const handleClick = () => {
  emit('click');
};

</script>
