<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shoppy - E-commerce Dashboard</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f9fafb;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .sidebar {
      background-color: #ffffff;
      width: 250px;
      min-height: 100vh;
      border-right: 1px solid #dee2e6;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 100;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    
    .main-content {
      margin-left: 250px;
      padding: 25px;
    }
    
    .nav-link {
      color: #6c757d;
      padding: 10px 20px;
      margin: 3px 0;
      border-radius: 5px;
      transition: all 0.3s ease;
    }
    
    .nav-link:hover {
      background-color: #f8f9fa;
      color: #f5588d;
    }
    
    .nav-link.active {
      background-color: #fff2f6;
      color: #f5588d;
      border-left: 3px solid #f5588d;
    }
    
    .nav-link i {
      margin-right: 10px;
      width: 20px;
    }
    
    .sidebar-heading {
      font-size: 0.75rem;
      text-transform: uppercase;
      color: #6c757d;
      font-weight: 600;
      padding: 15px 20px 5px;
      margin-top: 10px;
    }
    
    .stat-card {
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      height: 100%;
    }
    
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .circle-icon {
      width: 55px;
      height: 55px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
    }
    
    .icon-pink {
      background-color: #f5588d;
      color: white;
    }
    
    .icon-blue {
      background-color: #e6f7ff;
      color: #1890ff;
    }
    
    .icon-yellow {
      background-color: #fffbe6;
      color: #faad14;
    }
    
    .icon-red {
      background-color: #fff2f0;
      color: #ff4d4f;
    }
    
    .icon-green {
      background-color: #f6ffed;
      color: #52c41a;
    }
    
    .pink-bg {
      background-color: #f5588d;
      color: white;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(245, 88, 141, 0.2);
    }
    
    .btn-pink {
      background-color: #f5588d;
      color: white;
      border: none;
      border-radius: 8px;
      padding: 8px 20px;
      transition: all 0.3s ease;
    }
    
    .btn-pink:hover {
      background-color: #d63384;
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(245, 88, 141, 0.3);
    }
    
    .btn-outline-pink {
      background-color: transparent;
      color: #f5588d;
      border: 1px solid #f5588d;
      border-radius: 8px;
      padding: 8px 20px;
      transition: all 0.3s ease;
    }
    
    .btn-outline-pink:hover {
      background-color: #f5588d;
      color: white;
    }
    
    .pink-text {
      color: #f5588d;
    }
    
    .percentage {
      font-size: 0.85rem;
      font-weight: 500;
      margin-top: 8px;
      display: inline-block;
    }
    
    .percentage-up {
      color: #52c41a;
    }
    
    .percentage-down {
      color: #ff4d4f;
    }
    
    .badge-success {
      background-color: #e6f7ff;
      color: #52c41a;
    }
    
    .settings-btn {
      position: fixed;
      bottom: 30px;
      right: 30px;
      width: 55px;
      height: 55px;
      border-radius: 50%;
      background-color: #f5588d;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 15px rgba(245, 88, 141, 0.3);
      border: none;
      transition: all 0.3s ease;
    }
    
    .settings-btn:hover {
      transform: rotate(30deg) scale(1.1);
    }
    
    .chart-container {
      height: 200px;
      margin-bottom: 15px;
    }
    
    .donut-chart {
      max-width: 160px;
      margin: 0 auto;
    }
    
    .brand-logo {
      font-size: 1.5rem;
      font-weight: 700;
      color: #f5588d;
    }
    
    .card-title {
      font-weight: 600;
      color: #343a40;
    }
    
    h3 {
      font-weight: 700;
    }
    
    .section-title {
      font-weight: 700;
      margin-bottom: 20px;
      color: #343a40;
      position: relative;
      display: inline-block;
    }
    
    .section-title::after {
      content: '';
      position: absolute;
      left: 0;
      bottom: -8px;
      height: 3px;
      width: 40px;
      background-color: #f5588d;
      border-radius: 10px;
    }
    
    .revenue-card {
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      background-color: white;
      height: 100%;
    }
    
    .legend-item {
      display: flex;
      align-items: center;
      margin-right: 15px;
      font-size: 0.85rem;
    }
    
    .legend-color {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      margin-right: 6px;
      display: inline-block;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="d-flex align-items-center p-3 border-bottom">
      <i class="fas fa-shopping-bag me-2 pink-text"></i>
      <h4 class="mb-0 brand-logo">Shoppy</h4>
    </div>
    
    <div class="sidebar-heading">Dashboard</div>
    <ul class="nav flex-column px-2">
      <li class="nav-item">
        <a class="nav-link active" href="#">
          <i class="fas fa-shopping-cart"></i> Ecommerce
        </a>
      </li>
    </ul>
    
    <div class="sidebar-heading">Pages</div>
    <ul class="nav flex-column px-2">
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fas fa-file"></i> Orders
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fas fa-users"></i> Employees
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fas fa-user-friends"></i> Customers
        </a>
      </li>
    </ul>
    
    <div class="sidebar-heading">Apps</div>
    <ul class="nav flex-column px-2">
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fas fa-calendar"></i> Calendar
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fas fa-columns"></i> Kanban
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fas fa-edit"></i> Editor
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fas fa-palette"></i> Color-Picker
        </a>
      </li>
    </ul>
    
    <div class="sidebar-heading">Charts</div>
    <ul class="nav flex-column px-2">
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fas fa-chart-line"></i> Line
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fas fa-chart-area"></i> Area
        </a>
      </li>
    </ul>
  </div>
  
  <!-- Main Content -->
  <div class="main-content">
    <!-- Header and Welcome -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="mb-1">Dashboard</h2>
        <p class="text-muted mb-0">Welcome back, Admin</p>
      </div>
      <div class="d-flex">
        <button class="btn btn-outline-pink me-2">
          <i class="fas fa-calendar-alt me-2"></i>March 2025
        </button>
        <button class="btn btn-pink">
          <i class="fas fa-download me-2"></i>Reports
        </button>
      </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4 g-3">
      <div class="col-md-3">
        <div class="stat-card p-3">
          <div class="d-flex justify-content-between mb-2">
            <div>
              <p class="text-muted mb-1">Customers</p>
              <h3>39,354</h3>
            </div>
            <div class="circle-icon icon-blue">
              <i class="fas fa-users"></i>
            </div>
          </div>
          <span class="percentage percentage-down">
            <i class="fas fa-arrow-down me-1"></i>4% vs last month
          </span>
        </div>
      </div>
      
      <div class="col-md-3">
        <div class="stat-card p-3">
          <div class="d-flex justify-content-between mb-2">
            <div>
              <p class="text-muted mb-1">Products</p>
              <h3>4,396</h3>
            </div>
            <div class="circle-icon icon-yellow">
              <i class="fas fa-box"></i>
            </div>
          </div>
          <span class="percentage percentage-up">
            <i class="fas fa-arrow-up me-1"></i>23% vs last month
          </span>
        </div>
      </div>
      
      <div class="col-md-3">
        <div class="stat-card p-3">
          <div class="d-flex justify-content-between mb-2">
            <div>
              <p class="text-muted mb-1">Sales</p>
              <h3>$423.39K</h3>
            </div>
            <div class="circle-icon icon-red">
              <i class="fas fa-chart-line"></i>
            </div>
          </div>
          <span class="percentage percentage-up">
            <i class="fas fa-arrow-up me-1"></i>38% vs last month
          </span>
        </div>
      </div>
      
      <div class="col-md-3">
        <div class="stat-card p-3">
          <div class="d-flex justify-content-between mb-2">
            <div>
              <p class="text-muted mb-1">Refunds</p>
              <h3>$39.35K</h3>
            </div>
            <div class="circle-icon icon-green">
              <i class="fas fa-sync-alt"></i>
            </div>
          </div>
          <span class="percentage percentage-down">
            <i class="fas fa-arrow-down me-1"></i>12% vs last month
          </span>
        </div>
      </div>
    </div>
    
    <!-- Earnings Card -->
    <div class="row mb-4">
      <div class="col-md-12">
        <div class="pink-bg p-4">
          <div class="row">
            <div class="col-md-8">
              <h4 class="mb-3">Total Earnings</h4>
              <div class="d-flex align-items-center mb-3">
                <h2 class="mb-0 me-3">$63,448.78</h2>
                <span class="badge bg-white text-dark px-3 py-2 rounded-pill">
                  <i class="fas fa-arrow-up me-1 text-success"></i>12.5%
                </span>
              </div>
              <p class="mb-4">Compared to $42,580.90 last year</p>
              <div class="d-flex">
                <button class="btn btn-light px-4 me-2">
                  <i class="fas fa-download me-2"></i>Download Report
                </button>
                <button class="btn btn-light px-4">
                  <i class="fas fa-chart-line me-2"></i>View Details
                </button>
              </div>
            </div>
            <div class="col-md-4">
              <!-- Earnings chart -->
              <div class="chart-container">
                <!-- Small bar chart -->
                <svg viewBox="0 0 150 120" style="width: 100%;">
                  <rect x="10" y="70" width="12" height="50" fill="white" opacity="0.6"></rect>
                  <rect x="30" y="60" width="12" height="60" fill="white" opacity="0.7"></rect>
                  <rect x="50" y="40" width="12" height="80" fill="white" opacity="0.8"></rect>
                  <rect x="70" y="50" width="12" height="70" fill="white" opacity="0.7"></rect>
                  <rect x="90" y="30" width="12" height="90" fill="white" opacity="0.9"></rect>
                  <rect x="110" y="25" width="12" height="95" fill="white"></rect>
                  <rect x="130" y="20" width="12" height="100" fill="white"></rect>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Revenue Updates and Charts -->
    <div class="row g-4">
      <!-- Revenue Updates -->
      <div class="col-md-8">
        <div class="revenue-card p-4">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="section-title">Revenue Updates</h4>
            <div class="d-flex">
              <div class="legend-item">
                <span class="legend-color" style="background-color: #343a40;"></span>
                <span>Expense</span>
              </div>
              <div class="legend-item">
                <span class="legend-color" style="background-color: #20c997;"></span>
                <span>Budget</span>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="mb-4">
                <h3>$93,438</h3>
                <div class="d-flex align-items-center">
                  <span class="text-muted">Budget</span>
                  <span class="badge rounded-pill ms-2 py-1 px-2" style="background-color: #e6ffed; color: #52c41a;">25%</span>
                </div>
              </div>
              
              <div class="mb-4">
                <h3>$48,487</h3>
                <div class="d-flex align-items-center">
                  <span class="text-muted">Expense</span>
                  <span class="badge rounded-pill ms-2 py-1 px-2" style="background-color: #fff2f0; color: #ff4d4f;">12%</span>
                </div>
              </div>
              
              <div class="chart-container mb-3">
                <!-- Line chart placeholder -->
                <svg viewBox="0 0 400 120" style="width: 100%; height: 100%;">
                  <defs>
                    <linearGradient id="pinkGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                      <stop offset="0%" style="stop-color:#f5588d;stop-opacity:0.2" />
                      <stop offset="100%" style="stop-color:#f5588d;stop-opacity:0" />
                    </linearGradient>
                  </defs>
                  <!-- Area under the curve -->
                  <path d="M 50,80 L 100,60 L 150,40 L 200,20 L 250,30 L 300,10 L 350,25 L 350,100 L 50,100 Z" fill="url(#pinkGradient)"></path>
                  <!-- Line -->
                  <path d="M 50,80 L 100,60 L 150,40 L 200,20 L 250,30 L 300,10 L 350,25" fill="none" stroke="#f5588d" stroke-width="3"></path>
                  <circle cx="50" cy="80" r="4" fill="#f5588d"></circle>
                  <circle cx="100" cy="60" r="4" fill="#f5588d"></circle>
                  <circle cx="150" cy="40" r="4" fill="#f5588d"></circle>
                  <circle cx="200" cy="20" r="4" fill="#f5588d"></circle>
                  <circle cx="250" cy="30" r="4" fill="#f5588d"></circle>
                  <circle cx="300" cy="10" r="4" fill="#f5588d"></circle>
                  <circle cx="350" cy="25" r="4" fill="#f5588d"></circle>
                </svg>
              </div>
              
              <button class="btn btn-pink">
                <i class="fas fa-download me-2"></i>Download Report
              </button>
            </div>
            
            <div class="col-md-6">
              <!-- Bar chart placeholder -->
              <div class="chart-container mt-2">
                <svg viewBox="0 0 500 300" style="width: 100%; height: 100%;">
                  <g transform="translate(40,20)">
                    <!-- Y-axis labels -->
                    <text x="-30" y="0" text-anchor="end" class="small">400</text>
                    <text x="-30" y="60" text-anchor="end" class="small">300</text>
                    <text x="-30" y="120" text-anchor="end" class="small">200</text>
                    <text x="-30" y="180" text-anchor="end" class="small">100</text>
                    
                    <!-- X-axis labels -->
                    <text x="20" y="210" text-anchor="middle" class="small">Jan</text>
                    <text x="70" y="210" text-anchor="middle" class="small">Feb</text>
                    <text x="120" y="210" text-anchor="middle" class="small">Mar</text>
                    <text x="170" y="210" text-anchor="middle" class="small">Apr</text>
                    <text x="220" y="210" text-anchor="middle" class="small">May</text>
                    <text x="270" y="210" text-anchor="middle" class="small">Jun</text>
                    <text x="320" y="210" text-anchor="middle" class="small">Jul</text>
                    
                    <!-- Horizontal grid lines -->
                    <line x1="0" y1="0" x2="370" y2="0" stroke="#f0f0f0" stroke-width="1"/>
                    <line x1="0" y1="60" x2="370" y2="60" stroke="#f0f0f0" stroke-width="1"/>
                    <line x1="0" y1="120" x2="370" y2="120" stroke="#f0f0f0" stroke-width="1"/>
                    <line x1="0" y1="180" x2="370" y2="180" stroke="#f0f0f0" stroke-width="1"/>
                    
                    <!-- Bars for each month -->
                    <!-- Jan -->
                    <rect x="10" y="160" width="20" height="20" fill="#20c997" rx="2"></rect>
                    <rect x="10" y="140" width="20" height="20" fill="#343a40" rx="2"></rect>
                    
                    <!-- Feb -->
                    <rect x="60" y="150" width="20" height="30" fill="#20c997" rx="2"></rect>
                    <rect x="60" y="120" width="20" height="30" fill="#343a40" rx="2"></rect>
                    
                    <!-- Mar -->
                    <rect x="110" y="140" width="20" height="40" fill="#20c997" rx="2"></rect>
                    <rect x="110" y="100" width="20" height="40" fill="#343a40" rx="2"></rect>
                    
                    <!-- Apr -->
                    <rect x="160" y="130" width="20" height="50" fill="#20c997" rx="2"></rect>
                    <rect x="160" y="70" width="20" height="60" fill="#343a40" rx="2"></rect>
                    
                    <!-- May -->
                    <rect x="210" y="130" width="20" height="50" fill="#20c997" rx="2"></rect>
                    <rect x="210" y="70" width="20" height="60" fill="#343a40" rx="2"></rect>
                    
                    <!-- Jun -->
                    <rect x="260" y="130" width="20" height="50" fill="#20c997" rx="2"></rect>
                    <rect x="260" y="70" width="20" height="60" fill="#343a40" rx="2"></rect>
                    
                    <!-- Jul -->
                    <rect x="310" y="120" width="20" height="60" fill="#20c997" rx="2"></rect>
                    <rect x="310" y="60" width="20" height="60" fill="#343a40" rx="2"></rect>
                  </g>
                </svg>
              </div>
              
              <div class="d-flex justify-content-center small mt-2">
                <div class="legend-item">
                  <span class="legend-color" style="background-color: #20c997;"></span>
                  <span>Budget</span>
                </div>
                <div class="legend-item">
                  <span class="legend-color" style="background-color: #343a40;"></span>
                  <span>Expense</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Right Side Cards -->
      <div class="col-md-4">
        <!-- Monthly Breakdown Card -->
        <div class="card mb-4">
          <div class="card-body">
            <h4 class="section-title">Monthly Breakdown</h4>
            
            <div class="d-flex justify-content-between align-items-center my-3">
              <div>
                <h5 class="mb-0">Web Sales</h5>
                <small class="text-muted">65% of total sales</small>
              </div>
              <h4>$28,568</h4>
            </div>
            
            <div class="progress mb-4" style="height: 10px;">
              <div class="progress-bar" role="progressbar" style="width: 65%; background-color: #f5588d;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            
            <div class="d-flex justify-content-between align-items-center my-3">
              <div>
                <h5 class="mb-0">Store Sales</h5>
                <small class="text-muted">35% of total sales</small>
              </div>
              <h4>$15,350</h4>
            </div>
            
            <div class="progress mb-4" style="height: 10px;">
              <div class="progress-bar" role="progressbar" style="width: 35%; background-color: #20c997;" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>
        
        <!-- Yearly Sales Card -->
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
              <div>
                <h4 class="card-title">$43,246</h4>
                <p class="card-text text-muted small">Yearly sales</p>
              </div>
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary" type="button">
                  <i class="fas fa-ellipsis-v"></i>
                </button>
              </div>
            </div>
            
            <!-- Donut chart -->
            <div class="donut-chart mb-3">
              <svg width="160" height="160" viewBox="0 0 160 160">
                <circle cx="80" cy="80" r="60" fill="none" stroke="#f5588d" stroke-width="18" stroke-dasharray="94.2 282.6" stroke-dashoffset="0"></circle>
                <circle cx="80" cy="80" r="60" fill="none" stroke="#20c997" stroke-width="18" stroke-dasharray="110.25 282.6" stroke-dashoffset="-94.2"></circle>
                <circle cx="80" cy="80" r="60" fill="none" stroke="#6c757d" stroke-width="18" stroke-dasharray="94.2 282.6" stroke-dashoffset="-204.45"></circle>
                <circle cx="80" cy="80" r="60" fill="none" stroke="#dee2e6" stroke-width="18" stroke-dasharray="47.1 282.6" stroke-dashoffset="-298.65"></circle>
                <circle cx="80" cy="80" r="42" fill="white"></circle>
                <text x="80" y="75" text-anchor="middle" font-size="14" font-weight="bold">$43,246</text>
                <text x="80" y="95" text-anchor="middle" font-size="10" fill="#6c757d">Total Sales</text>
              </svg>
            </div>
            
            <div class="row text-center mt-3">
              <div class="col-3">
                <div class="rounded-circle mx-auto mb-1" style="width: 15px; height: 15px; background-color: #f5588d;"></div>
                <div class="small">25%</div>
                <div class="small text-muted">Q1</div>
              </div>
              <div class="col-3">
                <div class="rounded-circle mx-auto mb-1" style="width: 15px; height: 15px; background-color: #20c997;"></div>
                <div class="small">35%</div>
                <div class="small text-muted">Q2</div>
              </div>
              <div class="col-3">
                <div class="rounded-circle mx-auto mb-1" style="width: 15px; height: 15px; background-color: #6c757d;"></div>
                <div class="small">25%</div>
                <div class="small text-muted">Q3</div>
              </div>
              <div class="col-3">
                <div class="rounded-circle mx-auto mb-1" style="width: 15px; height: 15px; background-color: #dee2e6;"></div>
                <div class="small">15%</div>
                <div class="small text-muted">Q4</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Settings Button -->
  <button class="settings-btn">
    <i class="fas fa-cog"></i>
  </button>
  
  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>