let navbar = document.querySelector('.header .flex .navbar');
let userBox = document.querySelector('.header .flex .account-box');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   userBox.classList.remove('active');
}

document.querySelector('#user-btn').onclick = () =>{
   userBox.classList.toggle('active'); 
   navbar.classList.remove('active');
}

window.onscroll = () =>{
   navbar.classList.remove('active');
   userBox.classList.remove('active');
}

// Chart.js for admin dashboard
if (document.getElementById('adminMainChart')) {
  const ctx = document.getElementById('adminMainChart').getContext('2d');
  const adminMainChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Pending', 'Completed', 'Orders', 'Products', 'Users', 'Admins', 'Accounts', 'Messages'],
      datasets: [{
        label: 'Count',
        data: [
          parseInt(document.querySelector('.card-pending .card-info h3').textContent.replace(/[^\d]/g, '')),
          parseInt(document.querySelector('.card-completed .card-info h3').textContent.replace(/[^\d]/g, '')),
          parseInt(document.querySelector('.card-orders .card-info h3').textContent.replace(/[^\d]/g, '')),
          parseInt(document.querySelector('.card-products .card-info h3').textContent.replace(/[^\d]/g, '')),
          parseInt(document.querySelector('.card-users .card-info h3').textContent.replace(/[^\d]/g, '')),
          parseInt(document.querySelector('.card-admins .card-info h3').textContent.replace(/[^\d]/g, '')),
          parseInt(document.querySelector('.card-accounts .card-info h3').textContent.replace(/[^\d]/g, '')),
          parseInt(document.querySelector('.card-messages .card-info h3').textContent.replace(/[^\d]/g, ''))
        ],
        backgroundColor: [
          '#ff9800', '#22c55e', '#3b82f6', '#d946ef', '#0284c7', '#7c3aed', '#64748b', '#f43f5e'
        ],
        borderRadius: 8,
        borderWidth: 0
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        title: { display: true, text: 'Admin Overview', color: '#222b45', font: { size: 18, weight: 'bold' } }
      },
      scales: {
        y: { beginAtZero: true, ticks: { color: '#222b45', font: { size: 14 } } },
        x: { ticks: { color: '#6b7a90', font: { size: 13 } } }
      }
    }
  });
}