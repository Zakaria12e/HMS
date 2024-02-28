<script setup>

import axios from 'axios';
import { onMounted , ref} from 'vue';

const users = ref([]);



const getUsers = () => {
    axios.get('/api/users')
    .then((response) => {
        users.value = response.data;
    })
};




onMounted(() => {
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





            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>

                                <th style="width: 10px">id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Appointments</th>
                                <th>Options</th>

                            </tr>
                        </thead>

                        <tbody v-if="users.length > 0">

                            <tr v-for="( user , index ) in users" :key="user.id">
                                <td>{{ index + 1}}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td class="centered-cell">
                                    <span class="appointment-count">{{ user.appointment_count }}</span>
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
    </div>

</template>
