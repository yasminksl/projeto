<template>
  <thead class="bg-gray-800">
    <tr>
      <th :class="props.class">ID</th>
      <th :class="props.class">
        Nome do
        Cliente
      </th>
      <th :class="props.class">
        Data de
        Criação
      </th>
      <th
        v-if="showScheduledDateColumn"
        :class="props.class"
      >
        Data de
        Agendamento
      </th>
      <th
        v-if="showDeliveryDateColumn"
        :class="props.class"
      >
        Data de
        Entrega
      </th>
      <th :class="props.class">
        Status da
        Entrega
      </th>
    </tr>
  </thead>
</template>

<script setup>

let props = defineProps({
    showScheduledDateColumn: Boolean,
    showDeliveryDateColumn: Boolean,
    class: {
        type: String,
        default: 'px-4 py-2 text-left text-xs font-medium text-white uppercase tracking-wider',
    },
})

</script>
