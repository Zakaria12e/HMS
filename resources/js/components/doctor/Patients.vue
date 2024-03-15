<script setup>

import axios from 'axios';
import { onMounted , ref , watch } from 'vue';
import { debounce } from 'lodash';
import $ from 'jquery';

const users = ref([]);
const doctorId = ref(null);
const selectedUserId = ref(null);
const medicalReports = ref([]);


const getUsers = () => {
    axios.get('/api/doctor/getusers', {
        params: {
            doctor_id: doctorId.value
        }
    })
    .then((response) => {
        users.value = response.data;
    })
    .catch((error) => {
        console.error('Failed to fetch users:', error);
    });
};

const searchQuery = ref(null);

const search = () => {

    axios.get('/api/doctor/patients/search',{

        params: {
            query: searchQuery.value,doctor_id: doctorId.value
        }
    })
    .then(response => {

        users.value = response.data;
    })
    .catch(error => {

        console.log(error);
    })

};

watch(searchQuery, debounce(() => {

search();
}, 300));


const currentReportIndex = ref(0);

const showModal = (userId) => {
    selectedUserId.value = userId;
    $('#createMedicalReportModal').modal('show');
    fetchMedicalReports(doctorId.value, userId);
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

const fetchMedicalReports = (doctorId, userId) => {
    axios.get(`/api/medical-reports`, {
        params: {
            doctor_id: doctorId,
            user_id: userId
        }
    })
    .then(response => {

        console.log('Medical reports:', response.data);
        medicalReports.value = response.data;
    })
    .catch(error => {
        console.error('Failed to fetch medical reports:', error);
    });
};


onMounted(() => {
    doctorId.value = parseInt(document.getElementById('app').getAttribute('data-user-id'));
   getUsers();
});
</script>

<template>
    <div class="content-header">
        <div class="container-fluid">

            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Patients</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Patients</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createMedicalReportModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-purple text-white">
                    <h5 class="modal-title" id="staticBackdropLabel">Medical Reports</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="modal-body">
                    <div v-if="medicalReports.length > 0">
                        <div class="card">
                            <div class="card-body">
                                <h6>Title: {{ medicalReports[currentReportIndex].title }}</h6>
                                <p>Diagnosis: {{ medicalReports[currentReportIndex].diagnosis }}</p>
                                <p>Medications: {{ medicalReports[currentReportIndex].medications }}</p>
                                <p>Recommendations: {{ medicalReports[currentReportIndex].recommendations }}</p>
                                <p>Symptoms: {{ medicalReports[currentReportIndex].symptoms }}</p>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <button @click="previousReport" class="btn btn-primary mr-2" :disabled="currentReportIndex === 0">Previous</button>
                            <button @click="nextReport" class="btn btn-primary" :disabled="currentReportIndex === medicalReports.length - 1">Next</button>
                        </div>
                    </div>
                    <div v-else>
                        <p>No medical reports available.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
            <div class="d-flex justify-content-end mb-3">
                <div>

                  <input type="text" v-model="searchQuery" class="form-control" placeholder="Search..."/>

                </div>
              </div>

            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>

                                <th style="width: 10px">id</th>
                                <th>Name</th>
                                <th>Contact Number</th>
                                <th>Email</th>
                                <th>Options</th>

                            </tr>
                        </thead>

                        <tbody>

                            <tr v-for="( user , index ) in users" :key="user.id">
                                <td>{{ user.id}}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.contact_number }}</td>
                                <td>{{ user.email }}</td>
                                <td>

                                    <button @click="showModal(user.id)" type="button" class="btn btn-primary" data-toggle="modal" data-target="#createMedicalReportModal">
                                       Medicals Reports
                                      </button>
                                </td>

                            </tr>
                        </tbody>


                    </table>
                </div>
            </div>


        </div>
    </div>

</template>
