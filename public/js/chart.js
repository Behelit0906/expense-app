
var labels = [];
var colors = [];
var transactions = [];


async function draw_chart() {  
    const response =  await get_data('http://your-expenses.com/api/chart-data');

    response.forEach(element => {
        labels.push(element['name']);
        colors.push(element['color']);
        transactions.push(element['transactions']);
    });

    const data = {
        labels: labels,
        datasets: [{
          backgroundColor: colors, 
          borderWidth: 2,
          data: transactions,
        }]
    };
    
    const config = {
        type: 'bar',
        data: data,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                },
            },
            plugins: {
                legend: {
                    display: false,
                }
            },
            borderWidth: 5,
        },   
    };

    
  const myChart = new Chart(
    document.getElementById('myChart'),
    config
  );
  
}

draw_chart();







