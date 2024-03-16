<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const medicalReports = ref([]);
const userId = ref(null);

onMounted(async () => {
    userId.value = document.getElementById('app').dataset.userId;
    try {
        const response = await axios.get(`/api/patient/medical-reports?userId=${userId.value}`);
        medicalReports.value = response.data;
    } catch (error) {
        console.error('Error fetching medical reports:', error);
    }
});
</script>


<template>

    <div>
        <div v-if="medicalReports.length === 0">
            <p>No medical reports available.</p>
        </div>
        <div v-else>
            <div v-for="report in medicalReports" :key="report.id">
                <h2>{{ report.title }}</h2>
                <p>Diagnosis: {{ report.diagnosis }}</p>
                <p>Medications: {{ report.medications }}</p>
                <p>Recommendations: {{ report.recommendations }}</p>
                <p>Symptoms: {{ report.symptoms }}</p>
                <p>Appointment ID: {{ report.appointment_id }}</p>
                <p>Doctor Name: {{ report.appointment.doctor.name }}</p>
                <p>Service: {{ report.appointment.service }}</p>
                <p>Description: {{ report.appointment.description }}</p>
                <p>Appointment Date: {{ report.appointment.appointment_date }}</p>
                <p>Start Time: {{ report.appointment.start_time }}</p>
            </div>
        </div>
    </div>

</template>
