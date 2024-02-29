<script setup>
import axios from 'axios';
import { ref, onMounted, reactive } from 'vue';
import { useToastr } from '/resources/js/toastr.js';
import $ from 'jquery';

const toastr = useToastr();
const departments = ref({ 'data': [] });
const updating = ref(false);
const formValues = ref();

const form = reactive({
  name: '',
  description: '',
  chef_id: null,
  doctors: [],
});





const getDepartments = () => {
  axios.get('/api/departments')
    .then(async (response) => {
      const departmentsData = response.data;
      const departmentsWithUsers = await Promise.all(departmentsData.map(async (department) => {
        if (department.chef_id !== null) {
          const userResponse = await axios.get(`/api/users/${department.chef_id}`);
          department.chef_name = userResponse.data.name;
        } else {
          department.chef_name = null;
        }
        return department;
      }));

      departments.value = departmentsWithUsers;
    })
    .catch((error) => {
      console.error('Error fetching departments:', error);
    });
};











const updateDepartment = (department) => {
  updating.value = true;

 getDoctors();

  formValues.value = {
    id: department.id,
    name: department.name,
    description: department.description,
    chef_id: department.chef_id,
  };

  form.name = formValues.value.name;
  form.description = formValues.value.description;
  form.chef_id = formValues.value.chef_id;

  $('#createDepartmentModal').modal('show');

  $('#createDepartmentModal').on('hidden.bs.modal', function () {
    clearForm();
  });
};







const deleteDepartment = async (department) => {
  try {
    const confirmDelete = confirm('Are you sure you want to delete this department?');

    if (!confirmDelete) {
      return;
    }

    const response = await axios.delete(`/api/departments/${department.id}`);

    getDepartments();
    toastr.success('Department deleted successfully');
  } catch (error) {
    console.error('Error deleting department:', error);
  }
};




const getDoctors = async () => {
  try {
    const response = await axios.get('/api/doctors/getDoctorsForDepartments');
    form.doctors = response.data;
    console.log(form.doctors);
  } catch (error) {
    console.error('Error fetching doctors:', error);
  }
};





const addDepartment = () => {
  updating.value = false;
  getDoctors();
  $('#createDepartmentModal').modal('show');
};






const saveDepartment = async () => {
  try {
    const requestData = {
      name: form.name,
      description: form.description,
      chef_id: form.chef_id,
    };

    if (updating.value) {
      await axios.put('/api/departments/' + formValues.value.id, requestData);
      getDepartments();
      toastr.success('Department updated successfully');
    }else {
      const response = await axios.post('/api/departments', requestData);
      const newDepartment = response.data;


      departments.value.data = departments.value.data || [];
      departments.value.data.push(newDepartment);
      getDepartments();
      toastr.success('Department added successfully');
    }

    clearForm();
    $('#createDepartmentModal').modal('hide');


  } catch (error) {
    console.error('Error creating/updating department:', error);
  }
};







const clearForm = () => {
  form.name = '';
  form.description = '';
  form.chef_id = null;
};


onMounted(() => {
  getDepartments();
});
</script>

<template>
    <div>
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Departments</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Departments</li>
              </ol>
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


          <div class="modal fade" id="createDepartmentModal" data-backdrop="static" tabindex="-1" role="dialog"
               aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                  <div class="modal-body" style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <div class="form-group" style="flex: 1;">
                      <label for="name">Name</label>
                      <input v-model="form.name" type="text" class="form-control" id="name" aria-describedby="nameHelp" placeholder="Enter name" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                      <label for="description">Description</label>
                      <input v-model="form.description" type="text" class="form-control" id="description" aria-describedby="descriptionHelp" placeholder="Enter Description" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="description">Head of Department</label>
                        <select v-model="form.chef_id" class="form-control">
                        <option v-for="doctor in form.doctors.data" :key="doctor.id" :value="doctor.doctor_id"> {{ doctor.name }}</option>
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

          <!-- Department Table -->
          <div class="card">
            <div class="card-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th style="width: 10px">ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Head of Department</th>
                    <th>Options</th>
                  </tr>
                </thead>
                <tbody v-if="departments.length > 0">
                  <tr v-for="department in departments" :key="department.id">
                    <td>{{ department.id }}</td>
                    <td>{{ department.name }}</td>
                    <td>{{ department.description }}</td>
                    <td>{{ department.chef_id ? department.chef_name : 'No Head of Department' }}</td>
                    <td>
                        <a href="#" @click="updateDepartment(department)"> <i class="fa fa-edit" style="color: #9528b8;"></i> </a>
                        <a href="#" @click="deleteDepartment(department)"> <i class="fa fa-trash text-danger ml-3"></i> </a>
                    </td>
                  </tr>
                </tbody>
                <tbody v-else>
                  <td colspan="5" class="text-center">No result found</td>
                </tbody>
              </table>
            </div>
          </div>


        </div>
      </div>
    </div>
  </template>
