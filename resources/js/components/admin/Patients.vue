<script setup>

import axios from 'axios';
import { onMounted , ref , watch} from 'vue';
import { debounce } from 'lodash';

const users = ref([]);



const getUsers = () => {
    axios.get('/api/users')
    .then((response) => {
        users.value = response.data;
    })
};

const searchQuery = ref(null);

const search = () => {

    axios.get('/api/patients/search',{

        params: {
            query: searchQuery.value
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
   getUsers();
});
</script>


<template>

    <main class="content-wrap">
        <header class="content-head">
            <h1>Patients</h1>

        </header>


    <div class="content">


        <div class="d-flex justify-content-end mb-3">
            <div>
                <input type="text" v-model="searchQuery" class="form-control" placeholder="Search...">
            </div>
        </div>



            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>

                                <th style="width: 10px">id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact Number</th>
                                <th>Appointments</th>
                                <th>Options</th>

                            </tr>
                        </thead>

                        <tbody v-if="users.length > 0">

                            <tr v-for="( user , index ) in users" :key="user.id">
                                <td>{{ index + 1}}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.contact_number }}</td>
                                <td class="centered-cell">
                                    <span class="appointment-count">{{ user.appointments_count }}</span>
                                   </td>
                                <td>

                                </td>

                            </tr>
                        </tbody>
                        <tbody v-else>
                            <td colspan="8" class="text-center">No result found</td>
                        </tbody>


                    </table>
                </div>
            </div>



    </div>
</main>
</template>
