<!-- components/SectionBarChart.vue -->
<template>
  <Bar :data="chartData" :options="chartOptions" />
</template>

<script setup>
import { Bar } from "vue-chartjs";
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from "chart.js";

import ChartDataLabels from "chartjs-plugin-datalabels"; // ✅ import plugin
import { display } from "@primeuix/themes/aura/inplace";

// ✅ register plugin
ChartJS.register(
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
  ChartDataLabels
);

const props = defineProps({
  sectionName: String,
  sectionData: Array,
});

const total = props.sectionData.length;
const le7 = props.sectionData.filter((emp) => emp.DAYS <= 7).length;
const gt7 = props.sectionData.filter((emp) => emp.DAYS > 7).length;

const chartData = {
  labels: ["จำนวนคนที่ทำความสะอาดรองเท้า", "จำนวนคนที่ยังไม่ได้ทำความสะอาดรองเท้า"],
  datasets: [
    {
      //   label: `Section: ${props.sectionName}`,
      data: [le7, gt7],
      backgroundColor: ["#4ade80", "#f87171"],
      barThickness: 30,
    },
  ],
};

const chartOptions = {
  indexAxis: "y",
  responsive: true,
  plugins: {
    legend: { display: false },
    title: {
      display: true,
      text: `Section: ${props.sectionName}`,
      color: "#ffffff",
      font: { size: 16 },
    },
    datalabels: {
      color: "#ffffff",
      font: {
        size: 18,
        weight: "bold",
      },
      anchor: "center",
      align: (context) => {
        const dataset = context.dataset;
        const value = dataset.data[context.dataIndex];
        const total = le7 + gt7;
        const percent = (value / total) * 100;

        return percent === 100 ? "center" : "end";
      },
      formatter: (value, ctx) => {
        const total = le7 + gt7;
        const percent = ((value / total) * 100).toFixed(1);
        return `${percent}%`;
      },
    },
  },
  scales: {
    x: {
      min: 0,
      max: total,
      beginAtZero: true,
      ticks: {
        stepSize: 1,
        precision: 0,
        color: "#ffffff",
        display: false,
      },
      grid: {
        display: false,
      },
    },
    y: {
      ticks: {
        color: "#ffffff",
        display: false,
      },
      grid: {
        display: false,
      },
    },
  },
};
</script>
