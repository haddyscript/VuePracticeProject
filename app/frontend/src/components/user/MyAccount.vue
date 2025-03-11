<template>
    <!-- End Hero Section -->
		<div v-if="isLoading" class="loader-container">
			<div class="loader"></div>
		</div>

    <div v-else class="customer-account">
      <div class="account-header">
        <div class="profile-section">
          <img :src=" user.profile_picture ? getProductImage(user.profile_picture) : previewImage"  alt="Profile Picture" class="profile-pic" />
          <div class="user-info">
            <h2>{{ user.first_name }} {{ user.last_name }}</h2>
            <p>{{ user.email }}</p>
            <input type="file" @change="onFileChange" accept="image/*" class="file-input" />
            <button @click="uploadProfilePicture" :disabled="!selectedFile">Upload</button>
          </div>
        </div>
      </div>
      
      <div class="account-content">
        <div class="menu">
          <button :class="{ active: activeTab === 'orders' }" @click="activeTab = 'orders'">My Orders</button>
          <button :class="{ active: activeTab === 'wishlist' }" @click="activeTab = 'wishlist'">Wishlist</button>
          <button :class="{ active: activeTab === 'settings' }" @click="activeTab = 'settings'">Account Settings</button>
        </div>
        
        <div class="tab-content">
          
          <div v-if="activeTab === 'orders'" class="orders-section">
            <h3>My Orders</h3>
            <table class="order-table">
              <thead>
                <tr>
                  <th>Order #</th>
                  <th>Status</th>
                  <th>Total (PHP)</th>
                  <th>Payment Reference</th>
                  <th>Coupon Code</th>
                  <th>Address</th>
                  <th>Mode of Payment</th>
                  <th>Ordered At</th>
                  <th>Products</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="order in orders">
                  <td><strong>{{ order.id }}</strong></td>
                  <td>
                    <div class="dropdown">
                      <button
                        class="status-btn"
                        :class="statusClass(order.is_paid)"
                        :disabled="order.is_paid == 1"
                      >
                        {{ statusText(order.is_paid) }}
                      </button>
                      <div v-if="order.is_paid !== 1" class="dropdown-content">
                        <button @click="updateStatus(1)" class="paid">Paid</button>
                        <button @click="updateStatus(2)" class="cancel">Cancel</button>
                      </div>
                    </div>
                  </td>
                  <td><strong>{{ order.order_total }}</strong></td>
                  <td><strong>{{ order.payment_reference }}</strong></td>
                  <td><strong>{{ order.coupon_code !== 'null' ? order.coupon_code : 'N/A' }}</strong></td>
                  <td>{{ order.address }}, {{ order.state_country }}, {{ order.postal_zip }}</td>
                  <td>{{ getPaymentMethod(order.mode_of_payment) }}</td>
                  <td>{{ formatDate(order.created_at) }}</td>
                  <td>
                    <table class="product-table">
                      <thead>
                        <tr>
                          <th>Image</th>
                          <th>Product Name</th>
                          <th>Price (PHP)</th>
                          <th>Quantity</th>
                          <th>Subtotal (PHP)</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="product in order.product_details" :key="product.id">
                          <td>
                            <img 
                              :src="getProductImage(product.image_data[0])" 
                              class="product-thumbnail" 
                              alt="Product Image"
                            />
                          </td>
                          <td><strong>{{ product.product_name }}</strong></td>
                          <td>{{ product.product_price }}</td>
                          <td>{{ product.quantity }}</td>
                          <td><strong>{{ product.total_price }}</strong></td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <br><br>
          
          <div v-if="activeTab === 'wishlist'" class="wishlist-section">
            <h3>Wishlist</h3>
            <div v-for="item in wishlist" :key="item.id" class="wishlist-item">
              <div class="item-details">
                <p><strong>{{ item.name }}</strong></p>
                <p>Price: <strong>{{ item.price }} PHP</strong></p>
              </div>
            </div>
          </div>
          
          <div v-if="activeTab === 'settings'" class="settings-section">
            <div class="settings-section">
            <h3>Account Settings</h3>
            
            <div class="form-grid">
                <div class="form-group">
                <label for="first_name">First Name:</label>
                <input id="first_name" v-model="user.first_name" class="input-field" />
                </div>

                <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input id="last_name" v-model="user.last_name" class="input-field" />
                </div>

                <div class="form-group">
                <label for="email">Email:</label>
                <input id="email" v-model="user.email" class="input-field" />
                </div>

                <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" v-model="user.gender" class="input-field">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                </div>

                <div class="form-group">
                <label for="date_of_birth">Date of Birth:</label>
                <input type="date" id="date_of_birth" v-model="user.date_of_birth" class="input-field" />
                </div>

                <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input id="phone_number" v-model="user.phone_number" @input="formatPhoneNumber" class="input-field" maxlength="13" />
                </div>

                <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" v-model="user.address" class="input-field"></textarea>
                </div>

                <div class="form-group">
                <label for="city">City:</label>
                <input id="city" v-model="user.city" class="input-field" />
                </div>

                <div class="form-group">
                <label for="state">State:</label>
                <input id="state" v-model="user.state" class="input-field" />
                </div>

                <div class="form-group">
                    <select v-model="selectedCountry" class="form-control" :required="true" @change="updateCountry">
                      <option value="">Select a country</option>
                      <option v-for="place in countries" :key="place.id" :value="place.name">
                        {{ place.name }}
                      </option>
                    </select>
                </div>

                <div class="form-group">
                <label for="postal_code">Postal Code:</label>
                <input id="postal_code" v-model="user.postal_code" class="input-field" />
                </div>
            </div>

            <button class="save-button" @click="updateProfile()">Save Changes</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import apiRequest from '@/services/apiService';
  import showAlert from '@/services/swalAlert';
  export default {
    data() {
      return {
        countries: [],
        selectedCountry: '',
        isLoading: true,
        activeTab: 'settings',
        user: [],
        orders: [],
        wishlist: [
          { id: 1, name: 'Product 1', price: 300 },
          { id: 2, name: 'Product 2', price: 450 }
        ],
        profileImage: "https://via.placeholder.com/150",
        selectedFile: null, 
        previewImage: null, 
      };
    },
    mounted() {
      this.getUser();
      this.getMyOrders();
      this.getCountry();
      setTimeout(() => {
			this.isLoading = false; 
		}, 1000);
    },
    methods: {
      statusText(status) {
          switch (status) {
            case 0:
              return "Pending";
            case 1:
              return "Paid";
            case 2:
              return "Cancelled";
            default:
              return "Unknown";
          }
      },
      statusClass(status) {
        return {
          pending: status === 0,
          paid: status === 1,
          cancel: status === 2,
        };
      },
      onFileChange(event) {
        const file = event.target.files[0];
        if (file) {
          this.selectedFile = file;

          const reader = new FileReader();
          reader.onload = (e) => {
            this.previewImage = e.target.result;
          };
          reader.readAsDataURL(file);
        }
      },
      async uploadProfilePicture() {
        if (!this.selectedFile) {
          alert("Please select a file first.");
          return;
        }
        const user = JSON.parse(localStorage.getItem('user'));
        if (!user) return;
        const formData = new FormData();
        formData.append("user_id", user.id); 
        formData.append("profile_picture", this.selectedFile);
        const file_input =  document.getElementsByClassName("file-input");
        try {
          const response = await apiRequest.updateProfilePicture(formData);

          if (response.data.success === "true") {
              showAlert("success", "Success!", response.data.message);
              this.previewImage = null;
              file_input[0].value = "";
              this.getUser();
          } else {
             showAlert("error", "Oops!", response.data.message);
          }
        } catch (error) {
          console.error(error);
          alert("An error occurred while uploading.");
        }
      },
      getProductImage(product) {
        return 'data:image/jpeg;base64,' +product || '/images/default-product.png';
      },
      getPaymentMethod(mode) {
        const methods = {
          1: "Cash On Delivery",
          2: "Bank Transfer",
          3: "Pay Pal",
          4: "Gcash"
        };
        return methods[mode] || "Unknown";
      },
      formatDate(date) {
        return new Date(date).toLocaleString("en-PH", { timeZone: "Asia/Manila" });
      },
        async getCountry(){
			try{
				const response = await apiRequest.getCountry();
				if(response.data != undefined){
					this.countries = response.data.data;
				}
			}catch(error){
				console.log(error);
			}
		},
        formatPhoneNumber() {
            let value = this.user.phone_number.replace(/\D/g, ''); 

            if (value.length > 4) {
            value = value.substring(0, 4) + ' ' + value.substring(4);
            }
            if (value.length > 8) {
            value = value.substring(0, 8) + ' ' + value.substring(8);
            }
            if (value.length > 13) {
            value = value.substring(0, 13); 
            }

            this.user.phone_number = value;
        },
        async getUser() {
            try {
                const response = await apiRequest.getUser();
                console.log('User : ', response);
                if (response.data) {
                    this.user = response.data;
                    this.selectedCountry = this.user.country;
                }
            } catch (error) {
                console.log(error);
            }
        },
        async getMyOrders() {
            try {
                const user = JSON.parse(localStorage.getItem('user'));
                if (!user) return;

                const formData = new FormData();
                formData.append('user_id', user.id);

                const response = await apiRequest.getMyOrders(formData);
                console.log('Orders : ', response);
                if (response.data) {
                    this.orders = response.data.orders;
                }
            } catch (error) {
                console.log(error);
            }
        },

      prepareUserFormData() {
          const formData = new FormData();
          formData.append('user_id', this.user.id);
          formData.append('first_name', this.user.first_name ?? '');
          formData.append('last_name', this.user.last_name ?? '');
          formData.append('email', this.user.email ?? '');
          formData.append('gender', this.user.gender ?? '');
          formData.append('date_of_birth', this.user.date_of_birth ?? '');
          formData.append('phone_number', this.user.phone_number ?? '');
          formData.append('address', this.user.address ?? '');
          formData.append('city', this.user.city ?? '');
          formData.append('state', this.user.state ?? '');
          formData.append('country', this.selectedCountry ?? '');
          formData.append('postal_code', this.user.postal_code ?? '');

          return formData;
      },

      async updateProfile() {
            try {
                const response = await apiRequest.updateUser(this.prepareUserFormData());
                if (response.data?.success === 'true') {
                    this.user = response.data.user;
                    showAlert("success", "Nice!", response.data.message);
                } else {
                    showAlert("error", "Oops!", response.data.message);
                }
            } catch (error) {
                console.log(error);
            }
        },

    }
  };
  </script>
  
  <style scoped>
  .form-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: space-between;
    }

    .form-group {
        width: 48%; 
        display: flex;
        flex-direction: column;
    }

    .input-field {
        width: 100%;
    }


  .customer-account {
    width: 90%;
    max-width: 900px;
    margin: auto;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
    font-family: 'Arial', sans-serif;
  }
  .account-header {
  text-align: center;
  padding: 20px;
}

.profile-section {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.profile-pic {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #ccc;
}

.file-input {
  margin-top: 10px;
}

button {
  margin-top: 10px;
  padding: 10px;
  background-color: #28a745;
  color: white;
  border: none;
  cursor: pointer;
  border-radius: 5px;
}

button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}
  .menu {
    display: flex;
    justify-content: space-around;
    margin: 20px 0;
  }
  .menu button {
    padding: 6px 12px;
    border: none;
    background: #3b5d50;
    color: white;
    font-weight: bold;
    font-size: 12px;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s;
  }
  .menu button:hover, .menu button.active {
    background: #2e4a3d;
  }
  .tab-content {
    background: #f8f8f8;
    padding: 20px;
    border-radius: 8px;
  }
  .orders-section{
    width: 130vh;
  }
  .orders-section, .wishlist-section, .settings-section {
    padding: 15px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
  }
  .order-item, .wishlist-item {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    display: flex;
    justify-content: space-between;
  }
  .order-item:last-child, .wishlist-item:last-child {
    border-bottom: none;
  }
  .input-field , select {
    width: 100%;
    padding: 6px;
    margin: 6px 0;
    border: 1px solid #3b5d50;
    border-radius: 5px;
    font-size: 12px;
  }
  .save-button {
    padding: 6px 12px;
    background: #3b5d50;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    font-size: 12px;
    cursor: pointer;
    transition: background 0.3s;
  }
  .save-button:hover {
    background: #2e4a3d;
  }



  .loader-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh; /* Full page */
  /* background-color: rgba(255, 255, 255, 0.8); */
  background-color: white;
  position: fixed;
  width: 100%;
  top: 0;
  left: 0;
  z-index: 999;
}

.loader {
  border: 5px solid #f3f3f3;
  border-top: 5px solid #3b5d50;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.order-table, .product-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}

.order-table th, .order-table td,
.product-table th, .product-table td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: center;
  background-color: white;
}

.order-table th, .product-table th {
  background-color: #f4f4f4;
}

.product-thumbnail {
  max-width: 100px;
  height: auto;
  display: block;
  margin: 0 auto;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.status-btn {
  padding: 5px 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.status-btn.pending {
  background-color: yellow;
  color: black;
}

.status-btn.paid {
  background-color: green;
  color: white;
  cursor: not-allowed;
}

.status-bt.cancel {
  background-color: red !important;
  color: white;
}
.dropdown-content.cancel{
  background-color: red !important;
  color: white;
}

.dropdown-content {
  display: none;
  position: absolute;
  background: white;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
  z-index: 1;
}

.dropdown:hover .dropdown-content {
  display: block;
}

.dropdown-content button {
  display: block;
  width: 100%;
  padding: 5px;
  border: none;
  cursor: pointer;
  text-align: left;
}

.dropdown-content button:hover {
  background-color: lightgray;
}
  </style>
  