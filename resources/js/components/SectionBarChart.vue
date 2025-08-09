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
import ChartDataLabels from "chartjs-plugin-datalabels";

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

const total = props.sectionData.length || 1;
const le7 = props.sectionData.filter((emp) => emp.DAYS <= 7).length;
const gt7 = props.sectionData.filter((emp) => emp.DAYS > 7).length;

const le7Percent = (le7 / total) * 100;
const gt7Percent = 100 - le7Percent;

const chartData = {
  labels: [""], // ✅ ให้เหลือ label เดียว
  datasets: [
    {
      label: "Cleaned in 7 Days",
      data: [Math.round(le7Percent)],
      backgroundColor: "#4ade80",
      stack: "stack1",
      barThickness: 50, // กำหนดความหนาของแท่ง
      maxBarThickness: 60, // กำหนดความหนาสูงสุด
    },
    {
      label: "Over 7 Days",
      data: [Math.round(gt7Percent)],
      backgroundColor: "#f87171",
      stack: "stack1",
      barThickness: 50, // กำหนดความหนาของแท่ง
      maxBarThickness: 60, // กำหนดความหนาสูงสุด
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
      font: { size: 20, weight: "bold" },
    },
    datalabels: {
      color: "#ffffff",
      font: {
        size: 16,
        weight: "bold",
      },
      anchor: "center",
      align: (context) => {
        // ✅ ถ้าครบ 100% ให้อยู่ตรงกลาง
        return context.dataset.data[context.dataIndex] === 100 ? "center" : "end";
      },
      clamp: true,
      formatter: (value, context) => {
        // ✅ แสดงเฉพาะแท่งสีเขียว (datasetIndex = 0)
        if (context.datasetIndex === 0) {
          return `${le7} / ${total}`;
        }
        return ""; // ไม่แสดงอะไรในแท่งสีแดง
      },
    },
  },
  scales: {
    x: {
      stacked: true,
      max: 100,
      ticks: { display: false },
      grid: { display: false },
    },
    y: {
      stacked: true,
      ticks: { display: false },
      grid: { display: false },
    },
  },
};
</script>
