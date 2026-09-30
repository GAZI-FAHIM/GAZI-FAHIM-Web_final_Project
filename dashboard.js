fetch("connect_php/get_dashboard.php")
  .then((response) => response.json())
  .then((data) => {
    const {
      crimeStats,
      safetyData,
      safetyChecks,
      featureUsage,
      crimeTypes,
      responseData,
      notifEngage,
      bubbleData,
    } = data;

    // === FILTER TOP 5 ===
    const topCrimeStats = crimeStats
      .sort((a, b) => b.incident_count - a.incident_count)
      .slice(0, 5);

    const topSafetyData = safetyData
      .sort((a, b) => b.value - a.value)
      .slice(0, 5);

    const topFeatureUsage = featureUsage
      .sort((a, b) => b.usage_count - a.usage_count)
      .slice(0, 5);

    const topResponseData = responseData
      .sort((a, b) => b.response_rating - a.response_rating)
      .slice(0, 5);

    const topBubbleData = bubbleData.slice(0, 5); // Assuming preprocessed for bubble chart
    const topNotifEngage = {
      labels: notifEngage.labels.slice(0, 5),
      datasets: notifEngage.datasets.map((ds) => ({
        ...ds,
        data: ds.data.slice(0, 5),
      })),
    };

    // === CHART 1: CRIME BAR CHART ===
    new Chart(document.getElementById("crimeChart"), {
      type: "bar",
      data: {
        labels: topCrimeStats.map((stat) => stat.area),
        datasets: [
          {
            label: "Incidents",
            data: topCrimeStats.map((stat) => stat.incident_count),
            backgroundColor: "#dc3545",
            borderWidth: 1,
          },
          {
            label: "Crime Index",
            data: topCrimeStats.map((stat) => stat.crime_index),
            backgroundColor: "#ffc107",
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          tooltip: {
            callbacks: {
              label: (ctx) =>
                ctx.dataset.label === "Crime Index"
                  ? `Index: ${ctx.raw}`
                  : `Incidents: ${ctx.raw}`,
            },
          },
          title: {
            display: true,
            text: "Top 5 Crime-Prone Areas in Dhaka",
            font: { size: 18 },
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            title: { display: true, text: "Value" },
          },
        },
      },
    });

    // === CHART 2: SAFETY PIE/DOUGHNUT CHART ===
    new Chart(document.getElementById("areaSafetyPie"), {
      type: "doughnut",
      data: {
        labels: topSafetyData.map((s) => `${s.category}: ${s.label}`),
        datasets: [
          {
            label: "Safety Distribution",
            data: topSafetyData.map((s) => s.value),
            backgroundColor: [
              "#28a745",
              "#ffc107",
              "#dc3545",
              "#17a2b8",
              "#6610f2",
            ],
            hoverOffset: 10,
          },
        ],
      },
      options: {
        plugins: {
          title: {
            display: true,
            text: "Top 5 Safety Factors by Score",
            font: { size: 18 },
          },
          tooltip: {
            callbacks: {
              label: (ctx) =>
                `${ctx.label} — Score: ${ctx.raw}, Weight: ${
                  topSafetyData[ctx.dataIndex].weightage_percent
                }%`,
            },
          },
          legend: {
            position: "bottom",
            labels: { usePointStyle: true, boxWidth: 10 },
          },
        },
      },
    });

    // === CHART 3: SAFETY CHECK LINE CHART (All Days) ===
    const safetyCheckMap = {};
    safetyChecks.forEach((entry) => {
      const day = entry.check_day;
      safetyCheckMap[day] = (safetyCheckMap[day] || 0) + 1;
    });
    const dayLabels = Object.keys(safetyCheckMap);
    const checkCounts = Object.values(safetyCheckMap);

    new Chart(document.getElementById("routeCheckLine"), {
      type: "line",
      data: {
        labels: dayLabels,
        datasets: [
          {
            label: "Safety Checks",
            data: checkCounts,
            fill: true,
            borderColor: "#007bff",
            backgroundColor: "rgba(0, 123, 255, 0.2)",
            tension: 0.4,
            pointRadius: 5,
          },
        ],
      },
      options: {
        plugins: {
          title: {
            display: true,
            text: "Daily Safety Checks Across Dhaka",
            font: { size: 18 },
          },
          tooltip: {
            callbacks: { label: (ctx) => ` ${ctx.raw} checks` },
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            title: { display: true, text: "Check Count" },
          },
        },
      },
    });

    // === CHART 4: FEATURE USAGE RADAR CHART ===
    new Chart(document.getElementById("featureRadar"), {
      type: "radar",
      data: {
        labels: topFeatureUsage.map((f) => f.feature),
        datasets: [
          {
            label: "Usage Count",
            data: topFeatureUsage.map((f) => f.usage_count),
            fill: true,
            backgroundColor: "rgba(40,167,69,0.2)",
            borderColor: "rgba(40,167,69,1)",
            pointBackgroundColor: "rgba(40,167,69,1)",
          },
        ],
      },
      options: {
        plugins: {
          title: {
            display: true,
            text: "Top 5 Used SafeWay Features",
            font: { size: 18 },
          },
          legend: { display: false },
        },
        scales: {
          r: {
            angleLines: { display: true },
            suggestedMin: 0,
            suggestedMax:
              Math.max(...topFeatureUsage.map((f) => f.usage_count)) + 5,
            pointLabels: { font: { size: 14 } },
          },
        },
      },
    });

    // === CHART 5: CRIME TYPE TREND LINE CHART (All Data) ===
    // Calculate total incidents per week
    const weekTotals = crimeTypes.labels.map((week, i) => {
      let total = 0;
      for (const dataset of crimeTypes.datasets) {
        total += dataset.data[i];
      }
      return { week, total };
    });

    // Sort weeks by total descending and take top 7
    const topWeeks = weekTotals
      .sort((a, b) => b.total - a.total)
      .slice(0, 7)
      .map((w) => w.week);

    // Filter labels to top 7 weeks
    const filteredLabels = topWeeks;

    // Filter each dataset's data to only include top 7 weeks in correct order
    const filteredDatasets = crimeTypes.datasets.map((ds) => {
      return {
        ...ds,
        data: topWeeks.map((week) => {
          const index = crimeTypes.labels.indexOf(week);
          return ds.data[index];
        }),
      };
    });

    // Now create the chart with filtered data
    new Chart(document.getElementById("crimeTypeTrend"), {
      type: "line",
      data: {
        labels: filteredLabels,
        datasets: filteredDatasets,
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: "Top 7 Weeks of Crime Trends in Dhaka",
            font: { size: 18 },
          },
        },
      },
    });

    // === CHART 6: EMERGENCY RESPONSE BAR CHART ===
    new Chart(document.getElementById("responseBar"), {
      type: "bar",
      data: {
        labels: topResponseData.map((r) => `${r.service_type} - ${r.area}`),
        datasets: [
          {
            label: "Avg. Response Time (mins)",
            data: topResponseData.map((r) => r.average_response_time_min),
            backgroundColor: "#3498db",
          },
          {
            label: "Service Rating",
            data: topResponseData.map((r) => r.response_rating),
            backgroundColor: "#2ecc71",
          },
        ],
      },
      options: {
        indexAxis: "y",
        plugins: {
          title: {
            display: true,
            text: "Top 5 Emergency Response Performers",
            font: { size: 18 },
          },
        },
        scales: {
          x: {
            beginAtZero: true,
            title: { display: true, text: "Value" },
          },
        },
      },
    });

    // === CHART 7: NOTIFICATION INTERACTION STACKED BAR ===
    new Chart(document.getElementById("notifEngage"), {
      type: "bar",
      data: {
        labels: topNotifEngage.labels,
        datasets: topNotifEngage.datasets,
      },
      options: {
        plugins: {
          title: {
            display: true,
            text: "Top 5 Notification Engagement Types",
            font: { size: 18 },
          },
          tooltip: { mode: "index", intersect: false },
          legend: { position: "bottom" },
        },
        scales: {
          x: { stacked: true },
          y: {
            stacked: true,
            beginAtZero: true,
            title: { display: true, text: "Count" },
          },
        },
      },
    });

    // === CHART 8: FEEDBACK BUBBLE CHART (Top 5) ===
    new Chart(document.getElementById("bubbleFeedback"), {
      type: "bubble",
      data: {
        datasets: topBubbleData,
      },
      options: {
        plugins: {
          title: {
            display: true,
            text: "Top 5 Feedbacks (X: Positive, Y: Safety, R: Traffic)",
            font: { size: 18 },
          },
          tooltip: {
            callbacks: {
              label: (ctx) => {
                const val = ctx.raw;
                return `${ctx.dataset.label}: Feedback ${val.x}%, Safety ${val.y}%, Traffic Score: ${val.r}`;
              },
            },
          },
          legend: { position: "bottom" },
        },
        scales: {
          x: {
            title: { display: true, text: "Feedback Positivity (%)" },
            min: 0,
            max: 100,
          },
          y: {
            title: { display: true, text: "Safety Score (%)" },
            min: 0,
            max: 100,
          },
        },
      },
    });
  })
  .catch((error) => console.error("Error loading dashboard data:", error));
