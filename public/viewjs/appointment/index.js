var calendarEl = document.getElementById('calendar');
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    //Instanciation du calendrier
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'fr',
        initialView: 'dayGridMonth',
        slotDuration: "00:15:00",
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        buttonText: {
            today: "Aujourd'hui",
            month: 'Mois',
            week: 'Semaine',
            day: 'day',
            list: 'Liste'
        },
        eventClick: function(info) {
            console.log(info.event.id)

            var id = info.event.id;
            $.ajax({
                type: "GET",
                url: SITEURL + "/Appointments/getAppointmentsById/" + id,
                success: function(data) {

                    $('#Appointment_id2').val(data.id);
                    $('#patient_id2').val(data.patient_id).change();
                    $('#doctor_id2').val(data.user_id).change();
                    $('#time2').val(data.date);
                    $('#message2').val(data.message);
                    $('#priority2').val(data.priority).change();

                    console.log(data);
                    $('#event-modal-edit').modal('show');
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        },
        events: ROUTEGETAPPOINTMENT,
    });
    calendar.render();


    //AJOUT D'UN NOUVEAU RENDEZ-VOUS
    $('#form-event').on('submit', function(e) {
        e.preventDefault();
        let doctorId = $('#doctor_id').val();
        let patientId = $('#patient_id').val();
        let priority = $('#priority').val();
        let message = $('#message').val();
        let time = $('#time').val();
        // console.log(time);
        $.ajax({
            url: ROUTEAPPOINTMENTSTORE,
            type: "POST",
            data: {
                "_token": TOKENSUBMIT,
                doctorId: doctorId,
                patientId: patientId,
                priority: priority,
                message: message,
                time: time
            },
            success: function(data) {
                calendar.refetchEvents();
                $('#form-event').trigger("reset");
                $('#event-modal').modal('hide');
                toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');
                console.log(data);
            },
            error: function(data) {
                console.log(data);
            },

        });

    });

    //MIS A JOUR D'UN RENDEZ-VOUS
    $('#form-event-edit').on('submit', function(e) {
        e.preventDefault();
        let doctorId = $('#doctor_id2').val();
        let patientId = $('#patient_id2').val();
        let priority = $('#priority2').val();
        let message = $('#message2').val();
        let time = $('#time2').val();
        let id = $('#Appointment_id2').val();
        console.log(id);
        $.ajax({
            url: ROUTEAPPOINTMENTUPDATE,
            type: "POST",
            data: {
                "_token": TOKENUPDATE,
                id: id,
                doctorId: doctorId,
                patientId: patientId,
                priority: priority,
                message: message,
                time: time
            },
            success: function(data) {
                calendar.refetchEvents();
                $('#form-event-edit').trigger("reset");
                $('#event-modal-edit').modal('hide');
                toastr.success("Donnée mis à jour avec succès", 'Mis à jour réussi');
                console.log(data);
            },
            error: function(data) {
                console.log(data);
            },

        });

    });

    //SUPPRESSION
    $('#deleteAppointment').on('click', function(e) {
        e.preventDefault();

        let id = $('#Appointment_id2').val();

        $.ajax({
            url: SITEURL + "/Appointments/delete/" + id,
            type: "get",
            success: function(data) {
                calendar.refetchEvents();
                $('#event-modal-edit').modal('hide');
                toastr.success("Donnée supprimé avec succès", 'Supprimé réussi');
                console.log(data);
            },
            error: function(data) {
                console.log(data);
            },

        });

    });
});

