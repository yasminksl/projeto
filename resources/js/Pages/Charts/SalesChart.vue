<template>
  <div class="flex justify-center">
    <canvas id="myChart" style="max-width: 600px; height: 400px;" />
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import Chart from 'chart.js/auto'

let props = defineProps({
    sales: Array,
})

onMounted(() => {
    const getMonthName = (monthIndex) => {
        return new Date(new Date().getFullYear(), monthIndex)
            .toLocaleString('pt-BR', { month: 'long' })
            .charAt(0).toUpperCase() +
            new Date(new Date().getFullYear(), monthIndex)
                .toLocaleString('pt-BR', { month: 'long' })
                .slice(1)
    }

    const getLastFourMonths = () => {
        const currentMonth = new Date().getMonth()
        let months = []

        for (let i = 3; i >= 0; i--) {
            let monthIndex = (currentMonth - i + 12) % 12
            months.push(monthIndex)
        }
        return months
    }

    const labels = getLastFourMonths().map(monthIndex => getMonthName(monthIndex))

    const salesData = getLastFourMonths().map(monthIndex => {
        const saleForMonth = props.sales.find(sale => sale.month - 1 === monthIndex)
        return saleForMonth ? saleForMonth.total_sales : 0
    })

    const ctx = document.getElementById('myChart')
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: '',
                data: salesData,
                backgroundColor: 'rgba(55, 65, 81, 1)',
                borderColor: 'rgba(31, 41, 55, 1)',
                borderWidth: 5,
                fill: 'origin',
            }],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
            },
        },
    })
})
</script>
