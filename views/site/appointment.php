<?php

use hail812\adminlte\widgets as Widget;
use yii\bootstrap4\Modal;
use yii\helpers\Html;

$this->title = 'Appointment';
$this->params['breadcrumbs'] = [['label' => $this->title]];
?>

<!-- Full Page Calendar -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10">
            <div id="calendar"></div>
        </div>

        <!-- Status Legends & New Appointment Button -->
        <div class="col-lg-2">
            <button class="btn btn-primary w-100 mb-3" data-toggle="modal" data-target="#newAppointmentModal">
                + New Appointment
            </button>
            <div class="card">
                <div class="card-header bg-dark text-white">STATUS</div>
                <div class="card-body text-right">
                    <p><span class="col-12 badge badge-warning">Pending</span></p>
                    <p><span class="col-12 badge badge-danger">Cancelled</span></p>
                    <p><span class="col-12 badge badge-info">Rescheduled</span></p>
                    <p><span class="col-12 badge badge-success">Confirmed</span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for New Appointment -->
<?php
Modal::begin([
    'id' => 'newAppointmentModal',
    'title' => '<h5>New Appointment</h5>',
    'size' => 'modal-md',
    'dialogOptions' => ['class' => 'modal-dialog-centered'],
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false]
]);
?>
<div class="form-group">
    <label for="appointmentDate">Date & Time</label>
    <input type="datetime-local" class="form-control" id="appointmentDate">
</div>
<div class="form-group">
    <label for="appointmentType">Appointment Type</label>
    <select class="form-control" id="appointmentType">
        <option value="" disabled selected>-- Select Appointment Type --</option>
        <option value="meeting">Meeting</option>
        <option value="services-request">Services Request</option>
        <option value="services-repair">Services Repair</option>
        <option value="consultation">Consultation</option>
        <option value="others">Others...</option>
    </select>
</div>
<div class="form-group">
    <label for="appointmentStatus">Status</label>
    <select class="form-control" id="appointmentStatus">
        <option value="" disabled selected>-- Select Appointment Status --</option>
        <option value="pending">Pending</option>
        <option value="cancelled">Cancelled</option>
        <option value="rescheduled">Rescheduled</option>
        <option value="confirmed">Confirmed</option>
    </select>
</div>
<div class="form-group text-right">
    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
    <button id="btn_create" type="button" class="btn btn-success">Create</button>
</div>
<?php Modal::end(); ?>

<!-- Modal for Viewing Appointments -->
<?php
Modal::begin([
    'id' => 'viewAppointmentsModal',
    'title' => '<h5>Appointments on Selected Day</h5>',
    'size' => 'modal-md',
    'dialogOptions' => ['class' => 'modal-dialog-centered'],
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false]
]);
?>
<div id="appointmentsList">
</div>
<?php Modal::end(); ?>

<!-- Modal for Confirming New Appointment -->
<?php
Modal::begin([
    'id' => 'confirmAppointmentModal',
    'title' => '<h5>Are you sure you want to create this appointment?</h5>',
    'size' => 'modal-md',
    'dialogOptions' => ['class' => 'modal-dialog-centered'],
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false]
]);
?>
<div id="confirmAppointmentList"></div>
<div class="form-group text-right">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Go Back</button>
    <button id="btn_confirm" type="button" class="btn btn-success">Confirm</button>
</div>
<?php Modal::end(); ?>

<!-- Include FullCalendar CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!--jQuery-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script> <!--moment-->

<script>
    var currentDate = new Date();
    var formattedDate = currentDate.toISOString().slice(0, 16); // Format as "YYYY-MM-DDTHH:MM"
    $('#appointmentDate').attr('min', formattedDate); // Set min attribute to the input
    var calendar = new FullCalendar.Calendar($('#calendar')[0], {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'title',
            center: '',
            right: 'dayGridMonth,dayGridWeek'
        },
        footerToolbar: {
            center: 'prev,today,next'
        },
        height: 'auto',
        dateClick: function(info) {
            // Clear any previous appointments
            $('#appointmentsList').html('<p>Loading...</p>'); // Loading message
            var date = info;
            var dateStr = info.dateStr;

            // Fetch the appointments for the clicked date
            $.ajax({
                url: '<?= yii\helpers\Url::to(['site/get-appointments-for-date']) ?>',
                type: 'GET',
                data: {
                    date: dateStr
                },
                success: function(response) {
                    var listHtml = '';
                    if (response.status === 'success') {
                        if (response.appointments && response.appointments.length > 0) {
                            // Generate the list with Update and Delete buttons beside the info
                            listHtml = response.appointments.map(function(appt) {
                                return `
                            <div class="appointment-item d-flex align-items-center" id="appointment-${appt.id}">
                                <p class="mr-3"><strong>Type:</strong> ${appt.title} - <strong>Time:</strong> ${appt.time} - <strong>Status:</strong> ${appt.status}</p>
                                <button class="btn btn-primary btn-sm update-btn mr-2" data-id="${appt.id}" data-type="${appt.title}" data-time="${appt.time}">Update</button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${appt.id}">Delete</button>
                            </div>
                        `;
                            }).join('');
                        } else {
                            listHtml = '<p>No appointments for this date.</p>';
                        }
                    } else {
                        listHtml = '<p>Error loading appointments.</p>';
                    }
                    $('#appointmentsList').html(listHtml); // Update modal content
                    $('#viewAppointmentsModal').modal('show'); // Show modal
                },
                error: function(response) {
                    $('#appointmentsList').html('<p>Error loading appointments.</p>');
                }
            });
        },


        events: [] // Empty initially, will be populated dynamically
    });

    function readAppointments() {
        $.ajax({
            url: '<?= yii\helpers\Url::to(['site/get-appointment']) ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'empty') {
                    console.log("No Appointments Found");
                    return;
                } else if (response.status === 'success') {
                    let appointments = response.appointments;
                    console.log("READ APPOINTMENTS");
                    console.log(appointments);

                    // Ensure the calendar is initialized before manipulating it
                    if (!calendar) {
                        console.error('FullCalendar not initialized');
                        return;
                    }

                    // Clear existing events on the calendar
                    calendar.getEvents().forEach(function(event) {
                        event.remove();
                    });

                    // Add events to the calendar
                    appointments.forEach(function(appt) {
                        calendar.addEvent({
                            id: appt.id,
                            title: appt.title,
                            start: appt.start,
                            color: appt.color,
                            textColor: appt.textColor,
                        });
                    });

                    console.log("Appointments loaded successfully.");
                } else if (response.status === 'empty') {
                    console.log("EMPTY RESPONSE: " + response.message);
                    calendar.getEvents().forEach(event => event.remove()); // Clear events if empty
                } else {
                    alert("Unexpected response: " + JSON.stringify(response));
                }
            },
            error: function(response) {
                alert("Error (AJAX): " + JSON.stringify(response));
            }
        });
    }
    $(document).ready(function() {
        // Initialize FullCalendar


        calendar.render(); // Render the calendar

        // Load dynamic appointments
        readAppointments();


    });
</script>


<script>
    $(document).ready(function() {
        $('#btn_create').on('click', function() {
            var user_id = <?= Yii::$app->user->isGuest ? 'null' : Yii::$app->user->id; ?>;
            var appointment_date_time = $('#appointmentDate').val();
            var appointment_type = $('#appointmentType').val() ? $('#appointmentType').val().trim() : "";
            var appointment_status = $('#appointmentStatus').val() ? $('#appointmentStatus').val().trim() : "";

            // Clear previous errors
            $('.error-message').remove();
            $('.form-control').removeClass('is-invalid');

            let isValid = true;

            // Validate Date & Time
            if (!appointment_date_time) {
                $('#appointmentDate').addClass('is-invalid')
                    .after('<div class="error-message text-danger">Please select a date and time.</div>');
                isValid = false;
            }

            // Validate Appointment Type
            if (!appointment_type) {
                $('#appointmentType').addClass('is-invalid')
                    .after('<div class="error-message text-danger">Please select an appointment type.</div>');
                isValid = false;
            }

            // Validate Appointment Status
            if (!appointment_status) {
                $('#appointmentStatus').addClass('is-invalid')
                    .after('<div class="error-message text-danger">Please select an appointment status.</div>');
                isValid = false;
            }

            // Stop if validation fails
            if (!isValid) {
                return;
            }

            var parts = appointment_date_time.split("T");
            var scheduled_date = parts[0];
            var scheduled_time = parts[1];
            var formatted_date = moment(appointment_date_time).format('dddd, MMMM Do YYYY');
            var formatted_time = moment(appointment_date_time).format('hh:mm A');

            var appointment_type_text = $('#appointmentType option:selected').text();
            var appointment_status_text = $('#appointmentStatus option:selected').text();

            var appointmentData = {
                user_id: user_id,
                scheduled_date: scheduled_date,
                scheduled_time: scheduled_time,
                appointment_type: appointment_type,
                status: appointment_status
            };

            var appointmentInfo = `
            <p><strong>Date:</strong> ${formatted_date}</p>
            <p><strong>Time:</strong> ${formatted_time}</p>
            <p><strong>Type:</strong> ${appointment_type_text}</p>
            <p><strong>Status:</strong> ${appointment_status_text}</p>
        `;

            $('#confirmAppointmentList').html(appointmentInfo);
            $('#confirmAppointmentModal').modal('show');

            $('#btn_confirm').off('click').on('click', function() {
                $.ajax({
                    url: "<?= yii\helpers\Url::to(['site/create-appointment']) ?>",
                    type: "POST",
                    data: appointmentData,
                    dataType: "json",
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#confirmAppointmentModal').modal('hide');
                            $('#newAppointmentModal').modal('hide');

                            alert("Appointment created successfully.");
                            $('#appointmentDate').val('');
                            $('#appointmentType').val('');
                            $('#appointmentStatus').val('');
                            readAppointments();
                        } else {
                            alert("Error: " + response.errors);
                        }
                    },
                    error: function() {
                        alert("Something went wrong. Please try again.");
                    }
                });
            });
        });
    });


    $(document).on('click', '.update-btn', function() {
        var appointment_id = $(this).data('id');
        console.log("update button clicked", appointment_id);
    });

    $(document).on('click', '.delete-btn', function() {
        var appointment_id = $(this).data('id');
        console.log("APPOINTMENT ID TO BE DELETED", appointment_id);
        $.ajax({
            url: "<?= yii\helpers\Url::to(['/site/delete-appointment']) ?>",
            type: 'POST',
            data: {
                id: appointment_id
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert("appointment deleted successfully.");
                    console.log("success delete");
                    $('#viewAppointmentsModal').modal('hide');
                    readAppointments();
                    calendar.render();
                } else {
                    console.log("not success delete");
                }
            },
            error(response) {
                alert("AN ERROR OCCURED: " + response);
            }
        });
    });
</script>