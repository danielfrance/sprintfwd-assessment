import './bootstrap';

import Alpine from "alpinejs";
import { Chart, registerables } from "chart.js";
Chart.register(...registerables);

window.Alpine = Alpine;

Alpine.data("chartData", chartData);
Alpine.data("recordData", recordData);
Alpine.start();

function chartData() {
    return {
        loadData() {
            fetch("/api-calls")
                .then((response) => response.json())
                .then((data) => {
                    this.initChart("projectsChart", data.project);
                    this.initChart("membersChart", data.member);
                    this.initChart("teamsChart", data.team);
                });
        },
        initChart(chartId, data) {
            const labels = data.map((item) => item.method);
            const dataPoints = data.map((item) => item.total);

            const ctx = document.getElementById(chartId);
            const chartConfig = {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: "",
                            backgroundColor: "hsl(252, 82.9%, 67.8%)",
                            borderColor: "hsl(252, 82.9%, 67.8%)",
                            data: dataPoints,
                            minBarLength: 5,
                        },
                    ],
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                        },
                    },
                    plugins: {
                        title: {
                            display: false,
                        },
                        legend: {
                            display: false,
                        },
                    },
                },
            };

            new Chart(ctx, chartConfig);
        },
    };
}

function recordData() {
    return {
        projects: 0,
        members: 0,
        teams: 0,

        init() {
            fetch("/dashboard-data")
                .then((response) => response.json())
                .then((data) => {
                    this.projects = data.projects;
                    this.members = data.members;
                    this.teams = data.teams;
                });
        },
    };
}

