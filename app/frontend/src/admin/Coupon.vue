<template>
<div class="app-wrapper">
    <div class="container mt-5">
      <h2>Coupon Management</h2>
      
      <button class="btn btn-primary mb-3" @click="openModal()">Add Coupon</button>
      
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Code</th>
            <th>Discount Type</th>
            <th>Value</th>
            <th>Min Order</th>
            <th>Max Discount</th>
            <th>Start Date</th>
            <th>Expiry Date</th>
            <th>Active</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="coupon in coupons">
            <td>{{ coupon.id }}</td>
            <td>{{ coupon.code }}</td>
            <td>{{ coupon.discount_type }}</td>
            <td>{{ coupon.discount_value }}</td>
            <td>{{ coupon.min_order_amount || 'N/A' }}</td>
            <td>{{ coupon.max_discount_amount || 'N/A' }}</td>
            <td>{{ formatDate(coupon.start_date) }}</td>
            <td>{{ formatDate(coupon.expiry_date) }}</td>
            <td> <input type="checkbox" v-model="coupon.is_active" :true-value="1" :false-value="0" /> </td>
            <td>
              <button class="btn btn-warning btn-sm" @click="openModal(coupon)">Edit</button>
              <button class="btn btn-danger btn-sm ml-2" @click="deleteCoupon(coupon.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
  
      <!-- Modal -->
      <div v-if="showModal" class="modal show d-block" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">{{ editingCoupon.id ? 'Edit Coupon' : 'Add Coupon' }}</h5>
              <button type="button" class="close" @click="closeModal">&times;</button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="saveCoupon">
                <div class="form-group">
                  <label>Code</label>
                  <input v-model="editingCoupon.code" type="text" class="form-control" required />
                </div>
                <div class="form-group">
                  <label>Discount Type</label>
                  <select v-model="editingCoupon.discount_type" class="form-control" required>
                    <option value="percentage">Percentage</option>
                    <option value="fixed">Fixed</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Discount Value</label>
                  <input v-model="editingCoupon.discount_value" type="number" class="form-control" required />
                </div>
                <div class="form-group">
                  <label>Used Limit</label>
                  <input v-model="editingCoupon.usage_limit" type="number" class="form-control" required />
                </div>
                <div class="form-group">
                  <label>Min Order Amount</label>
                  <input v-model="editingCoupon.min_order_amount" type="number" class="form-control" />
                </div>
                <div class="form-group">
                  <label>Max Discount Amount</label>
                  <input v-model="editingCoupon.max_discount_amount" type="number" class="form-control" />
                </div>
                <div class="form-group">
                  <label>Start Date</label>
                  <input v-model="editingCoupon.start_date" type="date" class="form-control" required />
                </div>
                <div class="form-group">
                  <label>Expiry Date</label>
                  <input v-model="editingCoupon.expiry_date" type="date" class="form-control" required />
                </div>
                <div class="form-group">
                  <label>
                    <input type="checkbox" v-model="editingCoupon.is_active" :true-value="1" :false-value="0" />
                      Active
                  </label>
                </div>
                <button type="submit" class="btn btn-success">Save</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
  </template>
  
  <script>

  import apiRequest from '@/services/apiService';
  import showAlert from "@/services/swalAlert";

  export default {
    data() {
      return {
        coupons: [],
        showModal: false,
        editingCoupon: {},
      };
    },
    mounted() {
      this.fetchCoupons();
    },
    methods: {
      async fetchCoupons() {
        try{
            const response = await apiRequest.getAllCoupon();
            console.log(response);
            if(response.data.success == "true"){
                this.coupons = response.data.coupons;
                console.log("Fetched coupons" ,this.coupons);
            }
        }catch(error){
            console.error('Catch error ' , error);
        }
      },
      openModal() {
        this.showModal = true;
      },
      closeModal() {
        this.showModal = false;
      },
      async saveCoupon() {
        
        try{
            var data = this.editingCoupon;
            console.log('Data to send' , data);
            const response = await apiRequest.addCoupon(data);

            if(response.data.success == "true"){
                this.editingCoupon = {};
                console.log(response.data);
            }else{
                showAlert("error", "Oops!", response.data.message);
                this.editingCoupon = data;
            }
            console.log(response);




        }catch(error){
          console.error('Catch error ' , error);
        }
        this.closeModal();
        this.fetchCoupons();
      },
      async deleteCoupon(id) {
        if (confirm('Are you sure?')) {
          await axios.delete(`/api/coupons/${id}`);
          this.fetchCoupons();
        }
      },
      formatDate(date) {
        return new Date(date).toLocaleDateString();
      }
    },
    mounted() {
      this.fetchCoupons();
    }
  };
  </script>
  
  <style>
  .modal {
    background: rgba(0, 0, 0, 0.5);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  </style>
  