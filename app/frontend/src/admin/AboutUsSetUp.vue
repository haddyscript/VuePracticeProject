<template>
    <br>
    <div class="container py-5">
      <!-- Header Section -->
      <div class="row mb-4">
        <div class="col">
          <h2 class="text-center">About Us Page Setup</h2>
          <p class="text-center text-muted">Manage the details of the "About Us" page displayed to users.</p>
        </div>
      </div>
  
      <!-- Form Section to Add About Us Details -->
      <div class="row mb-5">
        <div class="col-lg-6 offset-lg-3">
          <div class="card shadow">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0 text-white">Add About Us Details</h5>
            </div>
            <div class="card-body">
              <form @submit.prevent="submitForm">
                <div class="mb-3">
                  <label for="title" class="form-label">Page Title</label>
                  <input type="text" hidden  v-model="aboutUs.id" placeholder="ID" class="form-control" />
                  <input type="text" id="title" v-model="aboutUs.title" class="form-control" required />
                </div>
                <div class="mb-3">
                  <label for="description" class="form-label">Page Description</label>
                  <textarea id="description" v-model="aboutUs.description" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100 text-white">Save Details</button>
              </form>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Table Section to Display About Us Data -->
      <div class="row">
        <div class="col">
          <div class="card shadow ml-6">
            <div class="card-header bg-success text-white">
              <h5 class="mb-0 text-white">Existing About Us Page Details</h5>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Count</th>
                    <th>Page</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Created</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in aboutUsList" :key="index">
                        <td>{{ (pagination.current_page - 1) * pagination.per_page + index + 1 }}</td>
                        <td>{{ item.page == 'about_us' ? 'About Us' : item.page }}</td>
                        <td>{{ item.title }}</td>
                        <td>{{ item.description }}</td>
                        <td>{{ new Date(item.created_at).toLocaleString('en-US', { month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true }) }}</td>
                        <td>
                        <button class="btn btn-warning btn-sm" @click="editItem(item)">Edit</button>
                        <button class="btn btn-danger btn-sm" @click="deleteItem(item)">Delete</button>
                        </td>
                    </tr>
                </tbody>    
              </table>
              <nav class="app-pagination mt-5" v-if="pagination.total > 0">
                  <ul class="pagination justify-content-center">
                      <li class="page-item" :class="{ 'disabled': pagination.current_page === 1 }">
                          <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1)">Previous</a>
                      </li>
                      <li 
                          class="page-item" 
                          v-for="page in pagination.last_page" 
                          :key="page" 
                          :class="{ 'active': pagination.current_page === page }">
                          <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
                      </li>
                      <li class="page-item" :class="{ 'disabled': pagination.current_page === pagination.last_page }">
                          <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page + 1)">Next</a>
                      </li>
                  </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>

<script>

import apiRequest from '@/services/apiService';
import swalAlert from "@/services/swalAlert";

export default {
  data() {
    return {
      aboutUs: {
        id: '',
        page: 'about_us',
        title: '',
        description: ''
      },
      aboutUsList: [],
      pagination: {}
    };
  },
  mounted() {
    this.fetchAboutUsDetails({ page: 'about_us' });
  },
  methods: {
    async submitForm() {
      try{
        const response = this.aboutUs.id != '' ? await apiRequest.editPages(this.aboutUs) : await apiRequest.addAboutUsDetails(this.aboutUs);
        if(response.data.success == "true"){
            swalAlert("success", "Success!", response.data.message);
            this.clearForm();
            this.fetchAboutUsDetails({ page: 'about_us' });
        }else{
            swalAlert("error", "Oops!", response.data.message);
        }
      }catch(error){
        console.error('Catch error ' , error);
      }
    },
    clearForm() {
      this.aboutUs.id = '';
      this.aboutUs.title = '';
      this.aboutUs.description = '';
    },
    async fetchAboutUsDetails(params) {
      try {
        const response = await apiRequest.getCertainPage(params);
        if (response.data.success == "true") {
            this.aboutUsList = response.data.page || [];
            this.pagination = response.data.pagination;
        }
      } catch (error) {
        console.error('Error fetching about us details:', error);
      }
    },
    changePage(page) {
          this.fetchAboutUsDetails({ page: 'about_us'  ,paginate: page });
     },
    editItem(item){
        this.editItemParams({ item : item.id });
    },
    async editItemParams(item) {    
        try{
            const response = await apiRequest.getCertainPageDetailsToEdit(item);
            if (response.data.success == "true") {
                    this.aboutUs.id = response.data.page.id;
                    this.aboutUs.title = response.data.page.title;
                    this.aboutUs.description = response.data.page.description;
            }
            else{
                swalAlert("error", "Oops!", response.data.message);
            }
        }catch(error){
            console.error(error);
        }
      
    },
    deleteItem(item){
        this.deleteItemParams({ data_id : item.id });
    },
    async deleteItemParams(item) {
        try{
            const response = await apiRequest.deletePageDetail(item);
            if (response.data.success == "true") {
                swalAlert("success", "Success!", response.data.message);
                this.fetchAboutUsDetails({ page: 'about_us' });
            }else{
                swalAlert("error", "Oops!", response.data.message);
            }
        }catch(error){
            console.error(error);
        }
    }
  }
};
</script>

<style scoped>
.row{
  margin-left : 20%;
}
.container {
  max-width: 1200px;
}

.card {
  border-radius: 10px;
}

.card-header {
  font-size: 18px;
}

.table th {
  text-align: center;
}

.table td {
  vertical-align: middle;
}

.table td button {
  margin: 0 5px;
}

@media (max-width: 768px) {
  .container {
    padding: 20px;
  }
}
</style>