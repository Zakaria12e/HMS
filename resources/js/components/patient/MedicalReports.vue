<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const medicalReports = ref([]);
const userId = ref(null);
const currentReportIndex = ref(0);

const fetchMedicalReports = async () => {
    try {
        const response = await axios.get(`/api/patient/medical-reports?userId=${userId.value}`);
        medicalReports.value = response.data;
    } catch (error) {
        console.error('Error fetching medical reports:', error);
    }
};

const previousReport = () => {
    if (currentReportIndex.value > 0) {
        currentReportIndex.value--;
    }
};

const nextReport = () => {
    if (currentReportIndex.value < medicalReports.value.length - 1) {
        currentReportIndex.value++;
    }
};

onMounted(async () => {
    userId.value = document.getElementById('app').dataset.userId;
    await fetchMedicalReports();
});
</script>

<template>
    <div class="container mt-4">
        <div v-if="medicalReports.length === 0">
            No medical reports available.
        </div>
        <div v-else>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">{{ medicalReports[currentReportIndex].title }}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Diagnosis:</strong> {{ medicalReports[currentReportIndex].diagnosis }}</p>
                            <p><strong>Medications:</strong> {{ medicalReports[currentReportIndex].medications }}</p>
                            <p><strong>Recommendations:</strong> {{ medicalReports[currentReportIndex].recommendations }}</p>
                            <p><strong>Symptoms:</strong> {{ medicalReports[currentReportIndex].symptoms }}</p>
                            <p><strong>Doctor Name:</strong> {{ medicalReports[currentReportIndex].appointment.doctor.name }}</p>
                            <p><strong>Service:</strong> {{ medicalReports[currentReportIndex].appointment.service }}</p>
                            <p><strong>Description:</strong> {{ medicalReports[currentReportIndex].appointment.description }}</p>
                            <p><strong>Appointment Date:</strong> {{ medicalReports[currentReportIndex].appointment.appointment_date }}</p>
                            <p><strong>Start Time:</strong> {{ medicalReports[currentReportIndex].appointment.start_time }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-3" v-if="medicalReports.length > 1">
                <button @click="previousReport" class="btn btn-primary mr-2" :disabled="currentReportIndex === 0" style="margin-right: 10px;">Previous</button>
                <button @click="nextReport" class="btn btn-primary" :disabled="currentReportIndex === medicalReports.length - 1" style="margin-left: 10px;">Next</button>
            </div>




        </div>
    </div>
</template>
