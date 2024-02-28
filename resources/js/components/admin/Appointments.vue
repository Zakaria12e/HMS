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

const form = reactive({
  date: '',
  duedate: '',
  description: '',
  totalAmount: '',
 appointmentId: '',

});






const createInvoice = (appointment) => {

form.description = appointment.description;
form.date = appointment.appointment_date;
form.appointmentId = appointment.id;
$('#createInvoiceModal').modal('show');
};






const saveInvoice = async () => {
  try {
    const response = await axios.post('/api/invoices', {
      date: form.date,
      dueDate: form.dueDate,
      description: form.description,
      totalAmount: form.totalAmount,
      appointmentId: form.appointmentId
    });

    if (response.status === 200) {
      toastr.success('Invoice saved successfully');
      clearForm();
    } else {
      console.error('Unexpected response status:', response.status);
      toastr.error('Error saving invoice');
    }
  } catch (error) {
    console.error('Error saving invoice:', error);
    toastr.error('Error saving invoice');
  }

  $('#createInvoiceModal').modal('hide');
};





const clearForm = () => {
  form.date = '';
  form.dueDate = '';
  form.description = '';
  form.totalAmount = '';
  form.appointmentId = '';
};




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

    const appointmentsData = response.data.data;

    const userIds = appointmentsData.reduce((ids, appointment) => {
      ids.push(appointment.patient_id, appointment.doctor_id);
      return ids;
    }, []);

    await fetchUserNames(userIds);

    for (const appointment of appointmentsData) {
      appointment.userName = cachedUserNames[appointment.patient_id];
      appointment.doctorName = cachedUserNames[appointment.doctor_id];
    }

    appointments.value = appointmentsData;
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







const assignToDoctor = async (appointmentId) => {
    selectedAppointmentId.value = appointmentId;
    await getDoctors();
$('#assignDoctorModal').modal('show');

};







const assignSelectedDoctor = async () => {
    try {
        const appointmentToUpdate = appointments.value.find(appointment => appointment.id === selectedAppointmentId.value);

        if (!appointmentToUpdate) {
            console.error('Appointment not found');
            return;
        }

        const doctorId = Number(selectedDoctorId.value);

        // Update the appointment's doctor_id
        appointmentToUpdate.doctor_id = doctorId;

        const response = await axios.put('/api/appointments/' + appointmentToUpdate.id, { doctor_id: selectedDoctorId.value });
        selectedDoctorId.value = null;
        getAppointments();
        $('#assignDoctorModal').modal('hide');

        toastr.success('Doctor assigned successfully');
    } catch (error) {
        console.error('Error assigning doctor:', error);
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

    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Appointments</h1>
        </div>
        <div class="col-sm-6">

        </div>
        </div>
        </div>
        </div>


        <div class="content">

            <div class="container-fluid">

                <div class="modal fade" id="createInvoiceModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">

                                <h5 class="modal-title" id="staticBackdropLabel">Create Invoice</h5>

                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>

                            </div>
                                <form>
                                    <div class="modal-body" style="display: flex; flex-wrap: wrap; gap: 20px;">

                                        <div class="mb-3">
                                            <label for="date">Date:</label>
                                            <input type="text" id="date" v-model="form.date" class="form-control" required>
                                          </div>
                                          <div class="mb-3">
                                            <label for="dueDate">Due Date:</label>
                                            <input type="text" id="dueDate" v-model="form.dueDate" class="form-control" required>
                                          </div>
                                          <div class="mb-3">
                                            <label for="description">Description of Services:</label>
                                            <textarea id="description" v-model="form.description" class="form-control" required></textarea>
                                          </div>
                                          <div class="mb-3">
                                            <label for="totalAmount">Total Amount:</label>
                                            <input type="text" id="totalAmount" v-model="form.totalAmount" class="form-control" required>
                                          </div>
                                          <div>
                                            <input type="hidden" id="appointmentId" v-model="form.appointmentId">
                                          </div>

                                       </div>

                                </form>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button @click="saveInvoice" type="button" class="btn btn-purple">Save</button>
                                </div>

                            </div></div></div>

                <div class="modal fade" id="assignDoctorModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">

                                <h5 class="modal-title" id="staticBackdropLabel">Assign To Doctor</h5>


                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>


                              <div class="mt-3 ml-3 mr-3">
                                <div class="mb-3">
                                    <label for="doctorSelect">Select Doctor:</label>
                                    <select v-model="selectedDoctorId" class="form-control" id="doctorSelect" required>

                                        <option v-for="doctor in doctors.data" :key="doctor.doctor_id" :value="doctor.doctor_id">{{ doctor.name }}</option>
                                    </select>
                                </div>

                              </div><br>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-purple" @click="assignSelectedDoctor">Confirm</button>
                              </div>
                                  </div></div></div>
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
                                          <td>{{ appointment.doctor.name }}</td>
                                          <td>

                                            <button @click.prevent="assignToDoctor(appointment.id)" class="btn btn-purple ml-4">Assign to Doctor</button>
                                            <button @click="createInvoice(appointment)" class="btn btn-purple ml-4" :disabled="appointment.status === 'Annulé' || appointment.status === 'Planifié' || appointment.status === 'Fermé'">Create Invoice</button>
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
        </div>
</template>
