<?php

use hail812\adminlte\widgets as Widget;

$this->title = 'Reports';
$this->params['breadcrumbs'] = [['label' => $this->title]];
?>

<div class="container-fluid">
    <!-- <?= Yii::$app->session->setFlash('success'); ?> -->
    <!-- <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="row">
            <div class="col-lg-12">
                <?= Widget\Alert::widget([
                    'type' => 'success',
                ]) ?>
            </div>
        </div>
    <?php endif; ?> -->

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
            <?= Widget\SmallBox::widget([
                'title' => '150',
                'text' => 'Total Clients',
                'icon' => 'fas fa-users',
            ]) ?>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
            <?php $smallBox = Widget\SmallBox::begin([
                'title' => '150',
                'text' => 'Total Appointments',
                'icon' => 'fas fa-calendar',
                'theme' => 'success'
            ]) ?>
            <!-- RIBBON -->
            <!-- <?= Widget\Ribbon::widget([
                        'id' => $smallBox->id . '-ribbon',
                        'text' => 'Ribbon',
                        'theme' => 'warning',
                        'size' => 'lg',
                        'textSize' => 'lg'
                    ]) ?> -->
            <?php Widget\SmallBox::end() ?>
        </div>
        <!-- LOADING -->
        <!-- <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <?= Widget\SmallBox::widget([
                'title' => '44',
                'text' => 'User Registrations',
                'icon' => 'fas fa-user-plus',
                'theme' => 'gradient-success',
                'loadingStyle' => true
            ]) ?>
        </div> -->
    </div>
</div>
<div class="row">
    <!-- Clients Overview (Larger Section) -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Clients Overview</h3>
            </div>
            <div class="card-body" style="height: 500px;">
                <canvas id="clientsChart" style="width: 100% !important; height: 100% !important;"></canvas>
            </div>
        </div>
    </div>

    <!-- Activity Section (Smaller Section) -->
    <div class="col-lg-4">
        <div class="card bg-white text-dark shadow-sm rounded-lg">
            <div class="card-header border-0 bg-white">
                <h5 class="card-title mb-0">Activity</h5>
            </div>
            <div class="card-body text-center">
                <div class="position-relative d-flex justify-content-center">
                    <canvas id="activityChart" style="max-width: 180px; height: 180px;"></canvas>
                    <div class="position-absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%);">
                        <h4 class="mb-0 text-dark">+13%</h4>
                        <small class="text-muted">Since last week</small>
                    </div>
                </div>
                <div class="row mt-3 small">
                    <div class="col-6 text-left">
                        <p class="mb-1"><span style="color: #FFC107;">●</span> Pending: <strong>452</strong></p>
                        <p class="mb-1"><span style="color: #DC3545;">●</span> Cancelled: <strong>715</strong></p>
                    </div>
                    <div class="col-6 text-left">
                        <p class="mb-1"><span style="color: #17A2B8;">●</span> Rescheduled: <strong>412</strong></p>
                        <p class="mb-1"><span style="color: #28A745;">●</span> Confirmed: <strong>128</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Clients Overview Line Chart
    var ctxClients = document.getElementById('clientsChart').getContext('2d');
    var clientsChart = new Chart(ctxClients, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            datasets: [{
                label: 'Clients',
                data: [50, 80, 60, 90, 120],
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Activity Doughnut Chart
    var ctxActivity = document.getElementById('activityChart').getContext('2d');
    var activityChart = new Chart(ctxActivity, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Cancelled', 'Rescheduled', 'Confirmed'],
            datasets: [{
                data: [452, 715, 412, 128],
                backgroundColor: ['#FFC107', '#DC3545', '#17A2B8', '#28A745'],
                borderWidth: 2,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
<!-- Table Section -->
<div class="container mt-4">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="bg-dark text-white">
                <tr>
                    <th colspan="2">
                        <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm active">Today</button>
                            <button class="btn btn-outline-primary btn-sm">Week</button>
                            <button class="btn btn-outline-primary btn-sm">Month</button>
                            <button class="btn btn-outline-primary btn-sm">Year</button>
                        </div>
                    </th>
                    <th colspan="2" class="text-end">
                        <input type="date" class="form-control form-control-sm w-auto d-inline" id="reportDate">
                    </th>
                </tr>
                <tr>
                    <th>Date</th>
                    <th>Reports</th>
                    <th>Appointment Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-light">
                    <td>mm/dd/yyyy</td>
                    <td>Czaira</td>
                    <td>Meeting</td>
                    <td>
                        <button class="btn btn-outline-warning btn-sm">👁 View</button>
                        <button class="btn btn-outline-secondary btn-sm">⬇ Download</button>
                    </td>
                </tr>
                <tr class="bg-light">
                    <td>mm/dd/yyyy</td>
                    <td>Beatriz</td>
                    <td>Consultation</td>
                    <td>
                        <button class="btn btn-outline-warning btn-sm">👁 View</button>
                        <button class="btn btn-outline-secondary btn-sm">⬇ Download</button>
                    </td>
                </tr>
                <tr class="bg-light">
                    <td>mm/dd/yyyy</td>
                    <td>Winston</td>
                    <td>Service Repair</td>
                    <td>
                        <button class="btn btn-outline-warning btn-sm">👁 View</button>
                        <button class="btn btn-outline-secondary btn-sm">⬇ Download</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap 5 CSS (Required if not already included) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<script>
    // Set default date in the calendar input
    document.getElementById("reportDate").valueAsDate = new Date();
</script>