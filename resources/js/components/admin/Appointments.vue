<script setup>
import axios from 'axios';
import { ref, onMounted, watchEffect , reactive} from 'vue';
import { debounce } from 'lodash';
import { useToastr } from '/resources/js/toastr.js';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
import $ from 'jquery';



const appointments = ref([]);
const toastr = useToastr();
const cachedUserNames = {};
const selectedStatus = ref(null);
const filteredAppointments = ref([]);
const doctors = ref({'data':[]});
const selectedDoctorId = ref(null);
const selectedAppointmentId = ref(null);



const getCountByStatus = (status) => {
  if (!status) {
    return appointments.value.length;
  }
  return appointments.value.filter(appointment => appointment.status === status).length;
};





const filterAppointments = (status) => {
  selectedStatus.value = status;
  getAppointments();
};






const getUsers = async (userIds) => {
  try {
    const response = await axios.post('/api/users/batch', { userIds });
    return response.data;
  } catch (error) {
    console.error('Error fetching users:', error);
    return [];
  }
};








const fetchUserNames = async (userIds) => {
  try {
    const response = await getUsers(userIds);
    const userNames = response.data;

    if (userNames && userNames.data && typeof userNames.data === 'object') {
      for (const [ userId, Name ] of Object.entries(userNames.data)) {
        cachedUserNames[userId] = Name;
      }
    }
  } catch (error) {
    console.error('Error fetching user names:', error);
  }
};










const getAppointments = debounce(async (page = 1) => {
  try {
    let response;

    if (selectedStatus.value === null) {
      response = await axios(`/api/appointments?page=${page}`);
    } else {
      response = await axios('/api/appointments', {
        params: { status: selectedStatus.value }
      });
    }

    appointments.value = response.data.data;


  } catch (error) {
    console.error('Error fetching appointments:', error);
  }
}, 100);








const deleteAppointment = async (appointment) => {
    try {
        const response = await axios.delete(`/api/appointments/${appointment.id}`);

        if (response.status === 200) {

            getAppointments();
            toastr.success('Appointment deleted successfully');
        } else {

            console.error('Unexpected response status:', response.status);
            toastr.error('Error deleting appointment');
        }
    } catch (error) {
        console.error('Error deleting appointment:', error);
        toastr.error('Error deleting appointment');
    }
};







const getDoctors = async () => {
  try {
    const response = await axios.get('/api/doctors');
    doctors.value = response.data;
  } catch (error) {
    console.error('Error fetching doctors:', error);
  }
};









watchEffect(() => {
  filteredAppointments.value = selectedStatus.value
    ? appointments.value.filter(appointment => appointment.status === selectedStatus.value)
    : appointments.value;
});

onMounted(() => {
  getAppointments();
});



</script>




<template>


        <main class="content-wrap">
            <header class="content-head">
                <h1>Appointments</h1>

            </header>


        <div class="content">



                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-flex justify-content-between mb-2">
                            <div class="btn-group">
                                <button @click="filterAppointments(null)" type="button" class="btn btn-default">
                                  <span class="mr-1">All</span>
                                  <span class="badge badge-pill badge-info"> {{ getCountByStatus(null) }}</span>
                                </button>
                                <button @click="filterAppointments('Planifié')" type="button" class="btn btn-default">
                                  <span class="mr-1">Planifié</span>
                                  <span class="badge badge-pill badge-purple">{{ getCountByStatus('Planifié') }}</span>
                                </button>

                                <button @click="filterAppointments('Confirmé')" type="button" class="btn btn-default">
                                  <span class="mr-1">Confirmé</span>
                                  <span class="badge badge-pill badge-success">{{ getCountByStatus('Confirmé') }}</span>
                                </button>

                                <button @click="filterAppointments('Fermé')" type="button" class="btn btn-default">
                                  <span class="mr-1">Fermé</span>
                                  <span class="badge badge-pill badge-danger">{{ getCountByStatus('Fermé') }}</span>
                                </button>
                              </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                      <tr>
                                        <th scope="col">id</th>
                                        <th scope="col">patient</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Doctor</th>
                                        <th scope="col">Options</th>
                                      </tr>
                                    </thead>
                                    <tbody v-if="appointments.length > 0">
                                        <tr v-for="(appointment, index) in filteredAppointments" :key="appointment.id">
                                          <td>{{ index + 1 }}</td>
                                          <td>{{ appointment.patient.name }}</td>
                                          <td>{{ appointment.appointment_date }}</td>
                                          <td>{{ appointment.title }}</td>
                                          <td>
                                            <span v-if="appointment.status === 'Confirmé'" class="badge badge-success">{{ appointment.status }}</span>
                                            <span v-else-if="appointment.status === 'Annulé'" class="badge badge-warning">{{ appointment.status }}</span>
                                            <span v-else-if="appointment.status === 'Fermé'" class="badge badge-danger">{{ appointment.status }}</span>
                                            <span v-else-if="appointment.status === 'Planifié'" class="badge badge-purple">{{ appointment.status }}</span>
                                            <span v-else class="badge badge-secondary">{{ appointment.status }}</span>
                                          </td>
                                          <td> {{appointment.doctor.name}}</td>
                                          <td>


                                            <a href="#" @click.prevent="deleteAppointment(appointment)"><i class="fa fa-trash text-danger ml-3"></i></a>
                                          </td>
                                        </tr>
                                      </tbody>
                                      <tbody v-else>
                                        <td colspan="7" class="text-center">No result found</td>
                                    </tbody>
                                  </table>
                            </div>
                        </div>

                        <Bootstrap4Pagination :data="appointments" @pagination-change-page="getAppointments" />

                    </div>

                </div>

        </div>
    </main>
</template>
