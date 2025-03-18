<?php
// Include your database connection file here
include 'db_connect.php';

// Query to get the count of members by political party
$query = $db->query("SELECT party, COUNT(id) AS count FROM horMembers GROUP BY party");
$partyCounts = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parliament Chart</title>
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <style>
    .dot {
        stroke: #fff;
        stroke-width: 1.5px;
    }
    .tooltip {
        position: absolute;
        text-align: center;
        width: 100px;
        height: 30px;
        padding: 5px;
        font: 12px sans-serif;
        background: lightsteelblue;
        border: 1px solid #333;
        border-radius: 5px;
        pointer-events: none;
        visibility: hidden;
    }
</style>
</head>
<body>
    <div style="text-align: center;">
        <h2>House of Representatives Members by Political Party</h2>
        <svg id="parliament-chart" width="800" height="600"></svg>
        <div class="tooltip" id="tooltip"></div>
    </div>

    <script>
    // PHP data passed to JavaScript
    const data = <?php echo json_encode($partyCounts); ?>;

    // Sample colors for different parties (customize as needed)
    const colors = d3.scaleOrdinal()
        .domain(data.map(d => d.party))
        .range(["#1f77b4", "#ff7f0e", "#2ca02c", "#d62728", "#9467bd", "#8c564b"]);

    // Calculate the total number of members and layout settings
    const totalMembers = data.reduce((sum, d) => sum + parseInt(d.count), 0);
    const radius = 200;
    const svg = d3.select("#parliament-chart");
    const g = svg.append("g").attr("transform", `translate(300, 250)`);

    // Tooltip
    const tooltip = d3.select("#tooltip");

    // Generate dots in a half-circle with tooltip
    let currentAngle = 180; // Starting angle (top of the half-circle)

    data.forEach(d => {
        const partyMembers = parseInt(d.count);
        const anglePerMember = 180 / totalMembers; // Half-circle: 180 degrees total

        for (let i = 0; i < partyMembers; i++) {
            const angle = currentAngle + i * anglePerMember;
            const x = Math.cos(angle * (Math.PI / 180)) * radius;
            const y = Math.sin(angle * (Math.PI / 180)) * radius;

            g.append("circle")
                .attr("cx", x)
                .attr("cy", y)
                .attr("r", 8)
                .attr("fill", colors(d.party))
                .attr("class", "dot")
                .on("mouseover", () => {
                    tooltip.style("visibility", "visible")
                           .text(`${d.party}: ${d.count} members`);
                })
                .on("mousemove", (event) => {
                    tooltip.style("top", (event.pageY - 30) + "px")
                           .style("left", (event.pageX + 5) + "px");
                })
                .on("mouseout", () => {
                    tooltip.style("visibility", "hidden");
                });
        }

        currentAngle += partyMembers * anglePerMember; // Adjust for next party
    });
</script>
</body>
</html>
