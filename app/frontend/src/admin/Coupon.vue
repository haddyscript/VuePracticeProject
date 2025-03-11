<template> <br><br>
  <div class="app-wrapper">
    <div class="container mt-5">
      <div class="header">
        <h2>Coupon Management</h2>
        <button class="btnadd" @click="openModal()">
          <i class="fas fa-plus"></i> Add Coupon
        </button>
      </div>

      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Code</th>
              <th>Discount Type</th>
              <th>Value</th>
              <th>Usage Limit</th>
              <th>Used</th>
              <th>Min Order</th>
              <th>Start Date</th>
              <th>Expiry Date</th>
              <th>Active</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="coupon in coupons" :key="coupon.id">
              <td>{{ coupon.id }}</td>
              <td>{{ coupon.code }}</td>
              <td>{{ coupon.discount_type }}</td>
              <td> {{ coupon.discount_type === 'fixed' ? coupon.discount_value : (Number(coupon.discount_value).toFixed(0) + '%') }} </td>
              <td>{{ coupon.usage_limit }}</td>
              <td>{{ coupon.used_count == 'null' ? 0 : coupon.used_count || 0 }}</td>
              <td>{{ coupon.min_order_amount || 'N/A' }}</td>
              <td>{{ formatDate(coupon.start_date) }}</td>
              <td>{{ formatDate(coupon.expiry_date) }}</td>
              <td>
                <label class="switch">
                  <input type="checkbox" v-model="coupon.is_active" :true-value="1" :false-value="0" />
                  <span class="slider"></span>
                </label>
              </td>
              <td>
                <button class="btn btn-sm btn-warning" @click="openModal(coupon, edit = true)">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger ml-2" @click="deleteCoupon(coupon.id)">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal -->
      <div v-if="showModal" class="modal-overlay">
        <div class="modal-container">
          <div class="modal-header">
            <h5>{{ editingCoupon.id ? 'Edit Coupon' : 'Add Coupon' }}</h5>
            <button class="close-btn" @click="closeModal()">&times;</button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveCoupon">
              <div class="form-group">
                <label>Code</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="clear-btn" @click="clear()">Clear</a>
                <input hidden v-model="editingCoupon.id" type="number" placeholder="ID" class="form-control"  />
                <input v-model="editingCoupon.code" type="text" class="form-control" required />
                <button type="button" class="generate-btn mt-2" @click="generateCouponCode">Generate Code</button>
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
                <label>Start Date</label>
                <input v-model="editingCoupon.start_date" type="date" class="form-control" required />
              </div>
              <div class="form-group">
                <label>Expiry Date</label>
                <input v-model="editingCoupon.expiry_date" type="date" class="form-control" required />
              </div>
              <div class="form-group">
                <label>Active</label>
                <label class="switch">
                  <input type="checkbox" v-model="editingCoupon.is_active" :true-value="1" :false-value="0" />
                  <span class="slider"></span>
                </label> 
              </div>
              <button type="submit" class="btn btn-success w-100">Save</button>
            </form>
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
        editingCoupon: { is_active: 0},
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
      openModal(coupon, edit) {
        this.showModal = true;
        if(edit == true){
            this.editingCoupon = coupon;
        }
      },
      closeModal() {
        this.showModal = false;
      },
      clear(){
        this.editingCoupon = {};
      },
      generateCouponCode() {
          const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
          let randomCode = '';
          for (let i = 0; i < 6; i++) {
            randomCode += characters.charAt(Math.floor(Math.random() * characters.length));
          }
          this.editingCoupon.code = randomCode; 
      },
      async saveCoupon() {
        
        try{
            var data = this.editingCoupon;
            console.log('Data to send' , data);
            
            const response = data.id != null ? await apiRequest.editCoupon(data) : await apiRequest.addCoupon(data);

            if(response.data.success == "true"){
                this.editingCoupon = {};
                showAlert("success", "Success!", response.data.message);
            }else{
                showAlert("error", "Oops!", response.data.message);
                this.editingCoupon = data;
            }
        }catch(error){
          console.error('Catch error ' , error);
        }
        this.closeModal();
        this.fetchCoupons();
      },
      async deleteCoupon(id) {
        if (confirm('Are you sure?')) {
          try{
            const formData = new FormData();
            formData.append('id', id);
            const response = await apiRequest.deleteCoupon(formData);
            if(response.data.success == "true"){
                showAlert("success", "Success!", response.data.message);
                this.fetchCoupons();
            }else{
                showAlert("error", "Oops!", response.data.message);
            }
          }catch(error){
            console.error('Catch error ' , error);
          }
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
/* Admin Table */
.table {
  border-collapse: collapse;
  width: 100%;
  background: #fff;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.table thead {
  background: #007bff;
  color: #fff;
}

.table th,
.table td {
  padding: 10px;
  text-align: center;
  font-size: 13px;
}

.table tbody tr:hover {
  background: #f5f5f5;
}

/* Header */
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

/* Buttons */
.btnadd {
  border-radius: 5px;
  color: white !important;
  background-color: #28a745 !important;
  border : 1px solid #28a745 !important;
}

.btn{
  border-radius: 5px;
}
.btn-sm i {
  margin-right: 3px;
}

/* Modal */
.modal-overlay {
  background: rgba(0, 0, 0, 0.5);
  position: fixed;
  top: 5mm;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-container {
  background: #fff;
  padding: 20px;
  width: 400px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
  position: relative;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #ddd;
  padding-bottom: 10px;
  margin-bottom: 10px;
}

.close-btn {
  background: none;
  border: none;
  font-size: 18px;
  cursor: pointer;
}
.clear-btn{
  background: none;
  border: none;
  font-size: 12px;
  cursor: pointer;
  color: red;
}
.generate-btn{
  background: green;
  color: white;
  font-size: 11px;
  border: none;
  padding: 5px 10px;
  border-radius: 5px;
  cursor: pointer;
}
/* Toggle Switch */
.switch {
  position: relative;
  display: inline-block;
  width: 40px;
  height: 20px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

label{
  font-size: 14px;
  font-weight: bold;
  color: black;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  border-radius: 20px;
  transition: 0.4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 14px;
  width: 14px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  border-radius: 50%;
  transition: 0.4s;
}

input:checked + .slider {
  background-color: #28a745;
}

input:checked + .slider:before {
  transform: translateX(20px);
}
</style>