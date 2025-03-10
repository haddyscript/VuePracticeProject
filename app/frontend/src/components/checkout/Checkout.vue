<template>
    <!-- Start Hero Section -->
			<div class="hero">
				<div class="container">
					<div class="row justify-content-between">
						<div class="col-lg-5">
							<div class="intro-excerpt">
								<h1>Checkout</h1>
							</div>
						</div>
						<div class="col-lg-7">
							
						</div>
					</div>
				</div>
			</div>
		<!-- End Hero Section -->
		<div v-if="isLoading" class="loader-container">
			<div class="loader"></div>
		</div>

		<div v-else  class="untree_co-section">
		    <div class="container">
		      <div class="row mb-5">
		        <div class="col-md-12">
		          <div class="border p-4 rounded" role="alert">
		            Returning customer? <a href="#">Click here</a> to login
		          </div>
		        </div>
		      </div>
		      <div class="row">
		        <div class="col-md-6 mb-5 mb-md-0">
		          <h2 class="h3 mb-3 text-black">Billing Details</h2>
		          <div class="p-3 p-lg-5 border bg-white">
		            <div class="form-group">
		              <label for="c_country" class="text-black">Country <span class="text-danger">*</span></label>
					  	<select v-model="selectedCountry" class="form-control" :required="true">
							<option value="">Select a country</option>
							<option v-for="place in countries" :key="place.id" :value="place.name">
								{{ place.name }}
							</option>
						</select>
		            </div>
		            <div class="form-group row">
		              <div class="col-md-6">
		                <label for="c_fname" class="text-black">First Name <span class="text-danger">*</span></label>
		                <input type="text" class="form-control" v-model="user.first_name" id="c_fname" name="c_fname" readonly style="background-color: white;">
		              </div>
		              <div class="col-md-6">
		                <label for="c_lname" class="text-black">Last Name <span class="text-danger">*</span></label>
		                <input type="text" class="form-control" v-model="user.last_name" id="c_lname" name="c_lname" readonly style="background-color: white;">
		              </div>
		            </div>

		            <div class="form-group row">
		              <div class="col-md-12">
		                <label for="c_companyname" class="text-black">Land Mark / Company Name / Building Name </label>
		                <input type="text" class="form-control" id="c_companyname" v-model="landmark_company_building" name="c_companyname">
		              </div>
		            </div>

		            <div class="form-group row">
		              <div class="col-md-12">
		                <label for="c_address" class="text-black">Address <span class="text-danger">*</span></label>
		                <input type="text" class="form-control" v-model="user.address" id="c_address" name="c_address" placeholder="Street address">
		              </div>
		            </div>

		            <div class="form-group mt-3">
		              <input type="text" class="form-control" v-model="apartment_suite_unit" placeholder="Apartment, suite, unit etc. (optional)">
		            </div>

		            <div class="form-group row">
		              <div class="col-md-6">
		                <label for="c_state_country" class="text-black">State / Country <span class="text-danger">*</span></label>
		                <input type="text" class="form-control" v-model="user.state" id="c_state_country" name="c_state_country">
		              </div>
		              <div class="col-md-6">
		                <label for="c_postal_zip" class="text-black">Posta / Zip <span class="text-danger">*</span></label>
		                <input type="text" class="form-control" id="c_postal_zip" v-model="user.postal_code" name="c_postal_zip">
		              </div>
		            </div>

		            <div class="form-group row mb-5">
		              <div class="col-md-6">
		                <label for="c_email_address" class="text-black">Email Address <span class="text-danger">*</span></label>
		                <input type="text" class="form-control" v-model="user.email" id="c_email_address" name="c_email_address">
		              </div>
		              <div class="col-md-6">
		                <label for="c_phone" class="text-black">Phone <span class="text-danger">*</span></label>
		                <input type="text" class="form-control" v-model="user.phone_number" id="c_phone" name="c_phone" placeholder="Phone Number">
		              </div>
		            </div>

		            <div class="form-group">
		              <label for="c_order_notes" class="text-black">Order Notes</label>
		              <textarea name="c_order_notes" id="c_order_notes" v-model="order_notes" cols="30" rows="5" class="form-control" placeholder="Write your notes here..."></textarea>
		            </div>

		          </div>
		        </div>
		        <div class="col-md-6">

		          <div class="row mb-5">
		            <div class="col-md-12">
		              <h2 class="h3 mb-3 text-black">Coupon Code</h2>
		              <div class="p-3 p-lg-5 border bg-white">

		                <label for="c_code" class="text-black mb-3">Enter your coupon code if you have one</label>
		                <div class="input-group w-75 couponcode-wrap">
		                  <input type="text" class="form-control me-2" id="c_code" v-model="coupon_code" placeholder="Coupon Code" aria-label="Coupon Code" aria-describedby="button-addon2">
		                  <div class="input-group-append">
		                    <button class="btn btn-black btn-sm" type="button" id="button-addon2" @click="applyCoupon( { coupon_code: coupon_code, is_params  : true})">Apply</button>
		                  </div>
		                </div>

		              </div>
		            </div>
		          </div>

		          <div class="row mb-5">
		            <div class="col-md-12">
		              <h2 class="h3 mb-3 text-black">Your Order</h2>
		              <div class="p-3 p-lg-5 border bg-white">
		                <table class="table site-block-order-table mb-5">
		                  <thead>
		                    <th>Product</th>
							<th>Price</th>
							<th>Qty</th>
		                    <th>Total</th>
		                  </thead>
		                  <tbody>
		                    <tr v-for="product in products" :key="product.id">
								<td>{{ product.product_name }} <strong class="mx-2"></strong></td>
								<td>P{{ product.product_price }}</td>
								<td>{{ product.quantity }}</td>
								<td>P{{ product.total_price.toFixed(2) }}</td>
							</tr>
		                    <tr>
		                      <td class="text-black font-weight-bold"><strong>Cart Subtotal</strong></td>
							  <td></td>
							  <td></td>
		                      <td class="text-black"> {{ cart_sub_total_amount ? "P" + cart_sub_total_amount : 0 }}</td>
		                    </tr>
							<tr>
		                      <td class="text-black font-weight-bold"><strong>Discount Total</strong></td>
							  <td></td>
							  <td></td>
		                      <td class="text-black font-weight-bold">{{ discount ? "P" + discount : 0 }}</td>
		                    </tr>
		                    <tr>
		                      <td class="text-black font-weight-bold"><strong>Order Total</strong></td>
							  <td></td>
							  <td></td>
		                      <td class="text-black font-weight-bold"><strong>{{ order_total_amount ? "P" + order_total_amount : 0 }}</strong></td>
		                    </tr>
		                  </tbody>
		                </table>

						<div class="border p-3 mb-3">
							<h3 class="h6 mb-0">Method of Payment</h3>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="payment_method" id="cod" value="1" @click="mode_of_payment=1">
								<label class="form-check-label payment-label" for="cod">Cash On Delivery</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="payment_method" id="bank" value="2" @click="mode_of_payment=2">
								<label class="form-check-label payment-label" for="bank">Bank Transfer</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="payment_method" id="paypal" value="3" @click="mode_of_payment=3">
								<label class="form-check-label payment-label" for="paypal">Pay Pal</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="payment_method" id="gcash" value="4" @click="mode_of_payment=4">
								<label class="form-check-label payment-label" for="gcash">Gcash</label>
							</div>
						</div>

		                <div class="border p-3 mb-3">
		                  <h3 class="h6 mb-0"><a class="d-block" data-bs-toggle="collapse" href="#collapsebank" role="button" aria-expanded="false" aria-controls="collapsebank">Direct Bank Transfer</a></h3>

		                  <div class="collapse" id="collapsebank">
		                    <div class="py-2">
		                      <p class="mb-0">Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won’t be shipped until the funds have cleared in our account.</p>
		                    </div>
		                  </div>
		                </div>

		                <div class="border p-3 mb-3">
		                  <h3 class="h6 mb-0"><a class="d-block" data-bs-toggle="collapse" href="#collapsecheque" role="button" aria-expanded="false" aria-controls="collapsecheque">Cheque Payment</a></h3>

		                  <div class="collapse" id="collapsecheque">
		                    <div class="py-2">
		                      <p class="mb-0">Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won’t be shipped until the funds have cleared in our account.</p>
		                    </div>
		                  </div>
		                </div>

		                <div class="border p-3 mb-5">
		                  <h3 class="h6 mb-0"><a class="d-block" data-bs-toggle="collapse" href="#collapsepaypal" role="button" aria-expanded="false" aria-controls="collapsepaypal">Paypal</a></h3>

		                  <div class="collapse" id="collapsepaypal">
		                    <div class="py-2">
		                      <p class="mb-0">Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won’t be shipped until the funds have cleared in our account.</p>
		                    </div>
		                  </div>
		                </div>

		                <div class="form-group">
		                  <button class="btn btn-black btn-lg py-3 btn-block" @click="placeOrder()">Place Order</button>
		                </div>

		              </div>
		            </div>
		          </div>

		        </div>
		      </div>
		      <!-- </form> -->
		    </div>
		  </div>
				<!-- GCash Modal -->

		<div v-if="showGcashModal" class="gcash-modal-overlay">
			<div class="gcash-modal">
				<div class="gcash-modal-header">
				<img src="https://upload.wikimedia.org/wikipedia/commons/2/2d/GCash_logo.svg" alt="GCash Logo" class="gcash-logo" />
				</div>
				
				<div class="gcash-modal-body">
				<p class="gcash-amount">Amount to Pay: <strong>P{{ order_total_amount }}</strong></p>

				<label for="gcashNumber" class="gcash-label">GCash Number</label>
				<input type="text" id="gcashNumber" v-model="gcashNumber" class="gcash-input" placeholder="Enter your GCash number" />

				<div class="gcash-modal-actions">
					<button class="gcash-button pay" @click="confirmGcashPayment">OK</button>
					<button class="gcash-button cancel" @click="closeGcashModal">Cancel</button>
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
			isLoading: true,
			user: [],
			products: [],
			coupon_code : '',
			cart_sub_total_amount: 0,
			order_total_amount: 0,
			discount : 0,
			countries: [],
			selectedCountry: "",
			mode_of_payment: 0,
			landmark_company_building : '',
			apartment_suite_unit : '',
			order_notes : '',
			showGcashModal: false,
    		gcashNumber: "",
		}
	}, 
	mounted(){
		this.getCountry();
		this.getCheckoutDetails();
		this.getUser();
		setTimeout(() => {
			this.isLoading = false; 
		}, 1000);
	},
	methods: {
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
		async getUser(){
			try {
				const response = await apiRequest.getUser();
				if(response.data != undefined){
					this.user = response.data;
					this.selectedCountry = this.user.country;
				}
			} catch (error) {
				console.log(error);
			}
		},
		async fetchCheckoutDetails(formData, showAlertOnFailure = false) {
			try {
				const response = await apiRequest.getCheckoutDetails(formData);
				if (response.data.success === 'true') {
					this.updateCheckoutState(response.data);
					if (showAlertOnFailure) showAlert("success", "Success", response.data.message);
				} else if (showAlertOnFailure) {
					showAlert("error", "Oops!", response.data.message);
					this.updateCheckoutState(response.data);
				}
			} catch (error) {
				console.log(error);
			}
		},

		updateCheckoutState(data) {
			this.products = data.checkout;
			this.coupon_code = data.applied_coupon;
			this.cart_sub_total_amount = data.total_amount;
			this.order_total_amount = data.final_amount;
			this.discount = data.discount;
		},

		async getCheckoutDetails() {
			const user = JSON.parse(localStorage.getItem('user'));
			if (!user) return;

			const formData = new FormData();
			formData.append('user_id', user.id);

			await this.fetchCheckoutDetails(formData);
		},

		async applyCoupon(params) {
			const user = JSON.parse(localStorage.getItem('user'));
			if (!user) return;

			const formData = new FormData();
			formData.append('user_id', user.id);
			formData.append('coupon_code', params.coupon_code);
			formData.append('is_params', params.is_params);

			await this.fetchCheckoutDetails(formData, true);
		},
		prepareParams() {
			const user = JSON.parse(localStorage.getItem('user'));

			const formData = new FormData();

			formData.append('country', this.selectedCountry);
			formData.append('user_id', this.user.id);
			formData.append('first_name', this.user.first_name);
			formData.append('last_name', this.user.last_name);
			formData.append('landmark_company_building', this.landmark_company_building);
			formData.append('address', this.user.address);
			formData.append('apartment_suite_unit', this.apartment_suite_unit);
			formData.append('state_country', this.user.state);
			formData.append('postal_zip', this.user.postal_code);
			formData.append('email_address', this.user.email);
			formData.append('phone', this.user.phone_number);
			formData.append('order_notes', this.order_notes);
			formData.append('coupon_code', this.coupon_code);
			formData.append('product_details', JSON.stringify(this.products));
			formData.append('cart_subtotal', this.cart_sub_total_amount);
			formData.append('order_total', this.order_total_amount);
			formData.append('discount_total', this.discount);
			formData.append('mode_of_payment', this.mode_of_payment);
			formData.append('is_paid', 0);
			formData.append('gcash_number', this.gcashNumber);
			formData.append('email', user.email);

			return formData;
		}, 
		async placeOrder() {
			if (this.mode_of_payment == 4 && this.gcashNumber == '') {

				this.showGcashModal = true;
				return;
			}else{
				this.showGcashModal = false;
				this.submitOrder();
			}
		},
		async confirmGcashPayment() {
			if (!this.gcashNumber || this.gcashNumber.trim() === '') {
				alert("Please enter your GCash number.");
				return;
			}

			this.showGcashModal = false; 
		},
		closeGcashModal() {
			this.showGcashModal = false;
		},
		async submitOrder() {
			try {
			const response = await apiRequest.placeOrder(this.prepareParams());
				console.log(response);
			if (response.data.success == "true") {
				showAlert("success", "Success", response.data.message);
				if(response.data.redirect_url != undefined){
					window.open(response.data.redirect_url, '_blank');
				}
				this.$router.push("/thankyou");
			} else {
				showAlert("error", "Oops!", response.data.message);
			}
			} catch (error) {
			console.log(error);
			}
		}

	}
}
</script>

<style>
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

.form-check-input:checked {
	background-color: #3b5d50;
	border-color: #3b5d50;
}

/* Modal Overlay */
.gcash-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1050;
}

/* Modal Container */
.gcash-modal {
  width: 90%;
  max-width: 360px;
  background: #ffffff;
  border-radius: 15px;
  padding: 20px;
  text-align: center;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
  position: relative;
}

/* GCash Logo */
.gcash-logo {
  width: 120px;
  margin-bottom: 15px;
}

/* Payment Amount */
.gcash-amount {
  font-size: 1.2rem;
  font-weight: bold;
  margin-bottom: 15px;
  color: #007aff;
}

/* Input Label */
.gcash-label {
  display: block;
  font-weight: 600;
  text-align: left;
  margin-bottom: 5px;
  font-size: 0.9rem;
  color: #333;
}

/* Input Field */
.gcash-input {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1rem;
  background: #f9f9f9;
  outline: none;
  transition: border-color 0.3s ease-in-out;
}

.gcash-input:focus {
  border-color: #007aff;
  background: #fff;
}

/* Buttons */
.gcash-modal-actions {
  margin-top: 20px;
  display: flex;
  gap: 10px;
}

.gcash-button {
  flex: 1;
  padding: 12px;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  cursor: pointer;
  transition: 0.3s ease;
}

.gcash-button.pay {
  background: #007aff;
  color: white;
}

.gcash-button.cancel {
  background: #e74c3c;
  color: white;
}

.gcash-button:hover {
  opacity: 0.85;
}


</style>