<script setup>
import axios from 'axios';
import { ref, onMounted, reactive } from 'vue';
import { useToastr } from '/resources/js/toastr.js';
import $ from 'jquery';

const appointments = ref([]);
const toastr = useToastr();
const doctorId = ref(null);
const selectedStatus = ref(null);
const AppointmentId = ref(null);

const form = reactive({
  date: '',
  duedate: '',
  description: '',
  totalAmount: '',
  appointmentId: '',
});

const getAppointments = async (status = null, page = 1) => {
  try {
    const response = await axios.get('/api/doctor/appointments', {
      params: { page, doctor_id: doctorId.value, status }
    });
    appointments.value = response.data;
  } catch (error) {
    toastr.error('Failed to fetch appointments.');
  }
};

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




const filterAppointments = (status) => {
  selectedStatus.value = status;
  getAppointments(selectedStatus.value);
};

const appointmentCounts = ref({});

const fetchAppointmentCounts = async () => {
  try {

    const response = await axios.get('/api/appointment-counts', {
      params: {doctor_id: doctorId.value }
    });
    appointmentCounts.value = response.data;
  } catch (error) {
    toastr.error('Failed to fetch appointment counts.');
  }
};


const newStatus = ref('');

const modifyStatus = async (appointmentId, newStatus) => {
  try {
    const response = await axios.put(`/api/changeStatusofAppointments/${appointmentId}`, {
      status: newStatus
    });
    if (response.status === 200) {
      toastr.success('Status modified successfully');
      await getAppointments(selectedStatus.value);
      await fetchAppointmentCounts();
    } else {
      console.error('Unexpected response status:', response.status);
      toastr.error('Error modifying status');
    }
  } catch (error) {
    console.error('Error modifying status:', error);
    toastr.error('Error modifying status');
  }
};



const medicalForm = ref({
  title: '',
  diagnosis: '',
  medications: '',
  recommendations: '',
  symptoms: ''
});

const showModal = (appointment) => {
  AppointmentId.value = appointment.id;
  $('#createMedicalReportModal').modal('show');
};

const createMedicalReport = async () => {
  try {
    const response = await axios.post('/api/medical-reports', {
      title: medicalForm.value.title,
      diagnosis: medicalForm.value.diagnosis,
      medications: medicalForm.value.medications,
      recommendations: medicalForm.value.recommendations,
      symptoms: medicalForm.value.symptoms,
      appointment_id: AppointmentId.value
    });
    if (response.status === 200) {
      toastr.success('Medical report saved successfully');
      clearMedicalForm();
      $('#createMedicalReportModal').modal('hide');
    } else {
      console.error('Unexpected response status:', response.status);
      toastr.error('Error saving medical report');
    }
  } catch (error) {
    console.error('Error saving medical report:', error);
    toastr.error('Error saving medical report');
  }
};

const clearMedicalForm = () => {
  medicalForm.value.title = '';
  medicalForm.value.diagnosis = '';
  medicalForm.value.medications = '';
  medicalForm.value.recommendations = '';
  medicalForm.value.symptoms = '';
};


const showDescriptionModal = (description) => {

    form.description = description;

    $('#descriptionModal').modal('show');
};


const deleteAppointment = async (appointment) => {
    try {
        const response = await axios.delete(`/api/doctorAppointments/${appointment.id}`);

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


onMounted(() => {
  doctorId.value = parseInt(document.getElementById('app').getAttribute('data-user-id'));
  getAppointments(selectedStatus.value);
  fetchAppointmentCounts(doctorId.value);


});


</script>




<template>

    <main class="content-wrap">
        <header class="content-head">
            <h1>Appointments</h1>

        </header>


        <div class="content">

                <div class="modal fade" id="createMedicalReportModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-purple text-white">
                                <h5 class="modal-title" id="staticBackdropLabel">Create Medical Report</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="createMedicalReport">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input v-model="medicalForm.title" type="text" class="form-control" id="title" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="diagnosis">Diagnosis</label>
                                        <textarea v-model="medicalForm.diagnosis" class="form-control" id="diagnosis" rows="2" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="medications">Medications</label>
                                        <textarea v-model="medicalForm.medications" class="form-control" id="medications" rows="2" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="recommendations">Recommendations</label>
                                        <textarea v-model="medicalForm.recommendations" class="form-control" id="recommendations" rows="2" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="symptoms">Symptoms</label>
                                        <textarea v-model="medicalForm.symptoms" class="form-control" id="symptoms" rows="2" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-purple">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="createInvoiceModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-purple text-white">
                                <h5 class="modal-title" id="staticBackdropLabel">Create Invoice</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="date" class="form-label">Date:</label>
                                            <input type="date" id="date" v-model="form.date" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="dueDate" class="form-label">Due Date:</label>
                                            <input type="date" id="dueDate" v-model="form.dueDate" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description of Services:</label>
                                        <textarea id="description" v-model="form.description" class="form-control" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="totalAmount" class="form-label">Total Amount:</label>
                                        <input type="text" id="totalAmount" v-model="form.totalAmount" class="form-control" required>
                                    </div>
                                    <input type="hidden" id="appointmentId" v-model="form.appointmentId">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button @click="saveInvoice" type="button" class="btn btn-purple">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <!-- Description Modal -->
<div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog" aria-labelledby="descriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionModalLabel">Description</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                {{ form.description }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-flex justify-content-between mb-2">
                            <div class="btn-group">
                                <button @click="filterAppointments(null)" type="button" class="btn btn-default">
                                    <span class="mr-1">All</span>
                                    <span class="badge badge-pill badge-info">{{
                                        (!isNaN(appointmentCounts.Planifié) ? appointmentCounts.Planifié : 0) +
                                        (!isNaN(appointmentCounts.Confirmé) ? appointmentCounts.Confirmé : 0) +
                                        (!isNaN(appointmentCounts.Fermé) ? appointmentCounts.Fermé : 0)
                                      }}</span>
                                </button>
                                <button @click="filterAppointments('Planifié')" type="button" class="btn btn-default">
                                    <span class="mr-1">Planifié</span>
                                    <span class="badge badge-pill badge-purple">{{ appointmentCounts.Planifié || 0 }}</span>
                                </button>
                                <button @click="filterAppointments('Confirmé')" type="button" class="btn btn-default">
                                    <span class="mr-1">Confirmé</span>
                                    <span class="badge badge-pill badge-success">{{ appointmentCounts.Confirmé || 0 }}</span>
                                </button>
                                <button @click="filterAppointments('Fermé')" type="button" class="btn btn-default">
                                    <span class="mr-1">Fermé</span>
                                    <span class="badge badge-pill badge-danger">{{ appointmentCounts.Fermé || 0 }}</span>
                                </button>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">id</th>
                                                <th scope="col">patient</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Time</th>
                                                <th scope="col">Title</th>
                                                <th scope="col">Description</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Options</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="appointments.length > 0">
                                            <tr v-for="(appointment, index) in appointments" :key="appointment.id">
                                                <td>{{ index + 1 }}</td>
                                                <td>{{ appointment.patient_name }}</td>
                                                <td>{{ appointment.appointment_date }}</td>
                                                <td>{{ appointment.start_time }}</td>
                                                <td>{{ appointment.title }}</td>
                                                <td>
                                                    <button @click="showDescriptionModal(appointment.description)" class="btn btn-sm ml-2">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <span v-if="appointment.status === 'Confirmé'" class="badge badge-success">{{ appointment.status }}</span>
                                                    <span v-else-if="appointment.status === 'Annulé'" class="badge badge-warning">{{ appointment.status }}</span>
                                                    <span v-else-if="appointment.status === 'Fermé'" class="badge badge-danger">{{ appointment.status }}</span>
                                                    <span v-else-if="appointment.status === 'Planifié'" class="badge badge-purple">{{ appointment.status }}</span>
                                                    <span v-else class="badge badge-secondary">{{ appointment.status }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex align-items-center">
                                                        <div class="col-sm-3 mr-2">
                                                            <select v-model="appointment.newStatus" class="form-control form-control-sm mr-2">
                                                                <option value="" disabled>Select status</option>
                                                                <option value="Planifié" :selected="appointment.status === 'Planifié'">Planifié</option>
                                                                <option value="Confirmé" :selected="appointment.status === 'Confirmé'">Confirmé</option>
                                                                <option value="Fermé" :selected="appointment.status === 'Fermé'">Fermé</option>
                                                            </select>
                                                        </div>
                                                        <div class="btn-group">
                                                            <button @click="modifyStatus(appointment.id, appointment.newStatus)" type="button" class="btn btns btn-success mr-2" :disabled="appointment.status === 'Annulé' || appointment.status === 'Fermé' || appointment.status === 'Confirmé'">Modify Status</button>
                                                            <button @click="createInvoice(appointment)" class="btn btns btn-purple mr-2" :disabled="appointment.status === 'Annulé' || appointment.status === 'Planifié' || appointment.status === 'Confirmé'">Create Invoice</button>
                                                            <button @click="showModal(appointment)" type="button" class="btn btns btn-primary mr-2" data-toggle="modal" data-target="#createMedicalReportModal" :disabled="appointment.status === 'Annulé'">Medical Report</button>
                                                            <a v-if="appointment.status == 'Annulé'" href="#" @click.prevent="deleteAppointment(appointment)"><i class="fa fa-trash text-danger ml-3"></i></a>
                                                          
                                                        </div>
                                                    </div>
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

                </div>

        </div>

        </main>
</template>
<style scoped>
  /* Custom styles for the table */
  .table-responsive {
    overflow-x: auto;
  }

  .table {
    width: 100%;
    border-collapse: collapse;
  }

  .table th,
  .table td {
    padding: 8px;
    text-align: center;
    vertical-align: middle;
  }

  .table th {
    background-color: #cecece;
    color: #ffffff;
    border: 1px solid #dee2e6;
  }

  .btns {
    padding: 4px 2px;
    font-size: 14px;
    border-radius: 6px;
  }


</style>
