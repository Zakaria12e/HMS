<script setup>

import { ref, onMounted } from 'vue';
import axios from 'axios';

const userId = ref(null);
const appointments = ref([]);

const getAppointments = async () => {
    try {
        // Fetch appointments for the logged-in user
        const response = await axios.get(`/api/Myappointments?userId=${userId.value}`);
        appointments.value = response.data;
    } catch (error) {
        console.error('Error fetching appointments:', error);
    }
};


const cancelAppointment = async (appointmentId) => {
    try {

        await axios.put(`/api/Myappointments/${appointmentId}`, { status: 'Annulé' });

        getAppointments();
    } catch (error) {
        console.error('Error canceling appointment:', error);
    }
};

onMounted(() => {

    userId.value = document.getElementById('app').dataset.userId;


    getAppointments();
});

</script>

<template>

    <div class="container mt-5 mb-5">
        <h2 class="mb-4">My Appointments</h2>
        <div class="row">

            <div v-if="appointments.length === 0" class="text-center" style="margin: 150px 0;">
                <p>No appointments available.</p>
            </div>
            <div v-else class="row">

                <div v-for="appointment in appointments" :key="appointment.id" class="col-md-4 mb-4">
                <div class="card">

                    <div class="card-body">
                        <h5 class="card-title"><strong>{{ appointment.title }}</strong></h5>
                        <p class="card-text"><strong>Date:</strong> {{ appointment.appointment_date }}</p>
                        <p class="card-text"><strong>Time:</strong> {{ appointment.start_time }}</p>
                        <p class="card-text"><strong>Doctor:</strong> {{ appointment.doctor.name }}</p>
                        <p class="card-text"><strong>Status: </strong><span v-if="appointment.status === 'Confirmé'" class="badge badge-success">{{ appointment.status }}</span>
                        <span v-else-if="appointment.status === 'Annulé'" class="badge badge-warning">{{ appointment.status }}</span>
                        <span v-else-if="appointment.status === 'Fermé'" class="badge badge-danger">{{ appointment.status }}</span>
                        <span v-else-if="appointment.status === 'Planifié'" class="badge badge-purple">{{ appointment.status }}</span>
                        <span v-else class="badge badge-secondary">{{ appointment.status }}</span></p>
                        <p class="card-text"><strong>Description:</strong> {{ appointment.description }}</p>
                        <p class="card-text"><strong>Service</strong> {{ appointment.service }}</p>

                        <div class="text-right">
                            <button v-if="appointment.status !== 'Annulé'" @click="cancelAppointment(appointment.id)" class="btn btn-danger">Cancel</button>
                        </div>



                    </div>
                </div>
            </div></div>

        </div>
    </div>

</template>
