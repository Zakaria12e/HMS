<script setup>
import axios from 'axios';
import { onMounted , ref} from 'vue';
import $ from 'jquery';

const departments = ref([]);
const updating = ref(false);
const formValues = ref();
const doctors = ref({'data':[]});



const getDoctors = async () => {
  try {
    const response = await axios.get('/api/doctors');
    doctors.value = response.data;
  } catch (error) {
    console.error('Error fetching doctors:', error);
  }
};


const getDepartments = () => {
    axios.get('/api/departments')
    .then((response) => {
        users.value = response.data;
    })
};

const addDepartment = () =>{

updating.value = false;
$('#createDepartmentModal').modal('show');

};


const saveDepartment = () => {
    if (updating.value) {
        // Update department logic
    } else {
        axios.post('/api/departments', formValues)
        .then((response) => {

            $('#createDepartmentModal').modal('hide');
            getDepartments();
        })
        .catch((error) => {
            // Handle error
        });
    }
};




onMounted(() => {
    getDepartments();
    getDoctors();
});

</script>


<template>

    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Departments</h1>
        </div>
        <div class="col-sm-6">

        </div>
        </div>
        </div>
        </div>

        <div class="modal fade" id="createDepartmentModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <h5 class="modal-title" id="staticBackdropLabel">
                          <span v-if="updating">Update Department</span>
                          <span v-else>Add New Department</span>
                      </h5>


                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>


                    <form :initial-values="formValues">
                    <div class="modal-body" style="display: flex; flex-wrap: wrap; gap: 20px;">

                            <div class="form-group" style="flex: 1;">
                                <label for="name">Name</label>
                                <input v-model="form.name" type="text" class="form-control" id="name" aria-describedby="nameHelp" placeholder="Enter name" required>

                            </div>
                            <div class="form-group">
                              <label for="email">Description</label>
                              <input v-model="form.Description" type="text" class="form-control" id="Description" aria-describedby="DescriptionHelp" placeholder="Enter Description" required>

                          </div>
                          <div class="form-group">
                            <label for="head_of_department">Chef du Département</label>
                            <select v-model="form.head_of_department_id" class="form-control" id="head_of_department" required>
                                <option value="" disabled>Select Chef du Département</option>
                                <option v-for="doctor in doctors" :key="doctor.id" :value="doctor.id">{{ doctor.name }}</option>
                            </select>
                        </div>
                      </div>

                        </form>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button @click="saveDepartment" type="button" class="btn btn-purple">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">



                <div class="d-flex justify-content-between">

                    <button @click="addDepartment" type="button" class="mb-3 btn btn-purple">
                      <i class="fa fa-plus-circle mr-1"></i>
                      Add Department
                    </button>

                  </div>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>

                                    <th style="width: 10px">id</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Doctors</th>
                                    <th>Options</th>

                                </tr>
                            </thead>

                            <tbody v-if="departments.length > 0">

                                <tr v-for="( department , index ) in departments" :key="department.id">
                                    <td>{{ index + 1}}</td>
                                    <td>{{ department.name }}</td>
                                    <td>{{ department.description }}</td>
                                    <td class="centered-cell">
                                        <span class="appointment-count">{{ department.doctors_count }}</span>
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
