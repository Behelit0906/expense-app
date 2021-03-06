let myChart = null;

async function draw_chart() {  
    const response =  await get_data(domain + '/api/chart-data');

    const labels = [];
    const colors = [];
    const transactions = [];

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

    if(myChart != null){
        myChart.destroy();
    }

    myChart = new Chart(
        document.getElementById('myChart').getContext("2d"),
        config
    );
  
}

draw_chart();







