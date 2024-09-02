<template>
    <div class="mt-4 md:mt-6 flex flex-col w-full bg-gray-100 rounded p-3" v-if="orderComments">
        <div class="flex flex-col md:flex-row justify-start items-start md:items-center md:space-x-6 xl:space-x-8">
            <p class="text-normal md:text-normal font-semibold leading-6 xl:leading-5 text-gray-800">
                Comentários/Anotações do pedido:
            </p>
        </div>
        <div class="flex flex-col space-y-2 mt-2">
            <p class="text-normal leading-none text-gray-800">
                {{ orderComments }}
            </p>
        </div>
    </div>
</template>

<script setup>

let props = defineProps({
    orderComments: String
})

</script>
