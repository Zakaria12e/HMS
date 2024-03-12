<script setup>

import { ref, onMounted } from 'vue';
import axios from 'axios';



const userId = ref(null);
const appointmentCount = ref(0);
const workingHours = ref([]);


const fetchAppointmentCount = async () => {
    try {

        const response = await axios.get(`/api/appointments/count?doctor_id=${userId.value}`);


        appointmentCount.value = response.data.count;
    } catch (error) {
        console.error('Error fetching appointment count:', error);
    }
};

const patientCount = ref(0);

const fetchPatientCount = async () => {
    try {
        const response = await axios.get(`/api/appointments/patient-count/${userId.value}`);
        patientCount.value = response.data.count;
    } catch (error) {
        console.error('Error fetching patient count:', error);
    }
};

const fetchWorkingHours = async () => {
    try {
        const response = await axios.get(`/api/doctor/working-hours/${userId.value}`);
        workingHours.value = response.data;
    } catch (error) {
        console.error('Error fetching working hours:', error);
    }
};




onMounted(() => {

    userId.value = parseInt(document.getElementById('app').getAttribute('data-user-id'));
    fetchAppointmentCount();
    fetchPatientCount();
    fetchWorkingHours();

});


</script>


<template>


    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">DocDashboard</h1>
        </div>
        <div class="col-sm-6">

        </div>
        </div>
        </div>
        </div>


        <div class="content">
        <div class="container-fluid">

            <div class="row">


                <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-calendar-alt"></i></span>
                <div class="info-box-content">
                <span class="info-box-text">Appointments</span>
                <span class="info-box-number">{{ appointmentCount }}</span>
                </div>

                </div>

                </div>



                <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <div class="row align-items-center">
                        <div class="col">
                            <span class="info-box-text">Patients</span>
                        </div>

                    </div>
                    <span class="info-box-number">{{ patientCount }}</span>
                </div>


                </div>

                </div>

                </div>


        </div>
        </div>

        <div class="content">
            <div class="container-fluid">
              <div class="row">
                <div class="col-md-6">
                  <h3>Working Hours</h3>
                  <ul class="list-group">
                    <li class="list-group-item" v-for="(hours, index) in workingHours" :key="index">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <span class="fw-bold">{{ hours.day }}</span>: {{ hours.start_time }} - {{ hours.end_time }}
                        </div>
                        <div>
                          <!-- Add any additional controls or buttons here -->
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>



    </template>
