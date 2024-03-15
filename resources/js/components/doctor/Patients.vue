<script setup>

import axios from 'axios';
import { onMounted , ref , watch } from 'vue';
import { debounce } from 'lodash';

const users = ref([]);
const doctorId = ref(null);


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


    <div class="content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between">
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
                                <td>{{ index + 1}}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.contact_number }}</td>
                                <td>{{ user.email }}</td>
                                <td></td>

                            </tr>
                        </tbody>


                    </table>
                </div>
            </div>


        </div>
    </div>

</template>
