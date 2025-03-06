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
		              	<select v-model="selectedCountry" class="form-control">
							<option value="">Select a country</option>
							<option v-for="country in countries" :key="country.code" :value="country.code">
							{{ country.name }}
							</option>
						</select>
		            </div>
		            <div class="form-group row">
		              <div class="col-md-6">
		                <label for="c_fname" class="text-black">First Name <span class="text-danger">*</span></label>
		                <input type="text" class="form-control" v-model="user.first_name" id="c_fname" name="c_fname">
		              </div>
		              <div class="col-md-6">
		                <label for="c_lname" class="text-black">Last Name <span class="text-danger">*</span></label>
		                <input type="text" class="form-control" v-model="user.last_name" id="c_lname" name="c_lname">
		              </div>
		            </div>

		            <div class="form-group row">
		              <div class="col-md-12">
		                <label for="c_companyname" class="text-black">Land Mark / Company Name / Building Name </label>
		                <input type="text" class="form-control" id="c_companyname" name="c_companyname">
		              </div>
		            </div>

		            <div class="form-group row">
		              <div class="col-md-12">
		                <label for="c_address" class="text-black">Address <span class="text-danger">*</span></label>
		                <input type="text" class="form-control" v-model="user.address" id="c_address" name="c_address" placeholder="Street address">
		              </div>
		            </div>

		            <div class="form-group mt-3">
		              <input type="text" class="form-control" placeholder="Apartment, suite, unit etc. (optional)">
		            </div>

		            <div class="form-group row">
		              <div class="col-md-6">
		                <label for="c_state_country" class="text-black">State / Country <span class="text-danger">*</span></label>
		                <input type="text" class="form-control" v-model="user.state" id="c_state_country" name="c_state_country">
		              </div>
		              <div class="col-md-6">
		                <label for="c_postal_zip" class="text-black">Posta / Zip <span class="text-danger">*</span></label>
		                <input type="text" class="form-control" id="c_postal_zip" name="c_postal_zip">
		              </div>
		            </div>

		            <div class="form-group row mb-5">
		              <div class="col-md-6">
		                <label for="c_email_address" class="text-black">Email Address <span class="text-danger">*</span></label>
		                <input type="text" class="form-control" v-model="user.email" id="c_email_address" name="c_email_address">
		              </div>
		              <div class="col-md-6">
		                <label for="c_phone" class="text-black">Phone <span class="text-danger">*</span></label>
		                <input type="text" class="form-control" v-model="user.phone" id="c_phone" name="c_phone" placeholder="Phone Number">
		              </div>
		            </div>

		            <div class="form-group">
		              <label for="c_create_account" class="text-black" data-bs-toggle="collapse" href="#create_an_account" role="button" aria-expanded="false" aria-controls="create_an_account"><input type="checkbox" value="1" id="c_create_account"> Create an account?</label>
		              <div class="collapse" id="create_an_account">
		                <div class="py-2 mb-4">
		                  <p class="mb-3">Create an account by entering the information below. If you are a returning customer please login at the top of the page.</p>
		                  <div class="form-group">
		                    <label for="c_account_password" class="text-black">Account Password</label>
		                    <input type="email" class="form-control" id="c_account_password" name="c_account_password" placeholder="">
		                  </div>
		                </div>
		              </div>
		            </div>


		            <div class="form-group">
		              <label for="c_ship_different_address" class="text-black" data-bs-toggle="collapse" href="#ship_different_address" role="button" aria-expanded="false" aria-controls="ship_different_address"><input type="checkbox" value="1" id="c_ship_different_address"> Ship To A Different Address?</label>
		              <div class="collapse" id="ship_different_address">
		                <div class="py-2">

		                  <div class="form-group">
		                    <label for="c_diff_country" class="text-black">Country <span class="text-danger">*</span></label>
		                    <select id="c_diff_country" class="form-control">
		                      <option value="1">Select a country</option>    
		                      <option value="2">bangladesh</option>    
		                      <option value="3">Algeria</option>    
		                      <option value="4">Afghanistan</option>    
		                      <option value="5">Ghana</option>    
		                      <option value="6">Albania</option>    
		                      <option value="7">Bahrain</option>    
		                      <option value="8">Colombia</option>    
		                      <option value="9">Dominican Republic</option>    
		                    </select>
		                  </div>


		                  <div class="form-group row">
		                    <div class="col-md-6">
		                      <label for="c_diff_fname" class="text-black">First Name <span class="text-danger">*</span></label>
		                      <input type="text" class="form-control" id="c_diff_fname" name="c_diff_fname">
		                    </div>
		                    <div class="col-md-6">
		                      <label for="c_diff_lname" class="text-black">Last Name <span class="text-danger">*</span></label>
		                      <input type="text" class="form-control" id="c_diff_lname" name="c_diff_lname">
		                    </div>
		                  </div>

		                  <div class="form-group row">
		                    <div class="col-md-12">
		                      <label for="c_diff_companyname" class="text-black">Company Name </label>
		                      <input type="text" class="form-control" id="c_diff_companyname" name="c_diff_companyname">
		                    </div>
		                  </div>

		                  <div class="form-group row  mb-3">
		                    <div class="col-md-12">
		                      <label for="c_diff_address" class="text-black">Address <span class="text-danger">*</span></label>
		                      <input type="text" class="form-control" id="c_diff_address" name="c_diff_address" placeholder="Street address">
		                    </div>
		                  </div>

		                  <div class="form-group">
		                    <input type="text" class="form-control" placeholder="Apartment, suite, unit etc. (optional)">
		                  </div>

		                  <div class="form-group row">
		                    <div class="col-md-6">
		                      <label for="c_diff_state_country" class="text-black">State / Country <span class="text-danger">*</span></label>
		                      <input type="text" class="form-control" id="c_diff_state_country" name="c_diff_state_country">
		                    </div>
		                    <div class="col-md-6">
		                      <label for="c_diff_postal_zip" class="text-black">Postal / Zip <span class="text-danger">*</span></label>
		                      <input type="text" class="form-control" id="c_diff_postal_zip" v-model="user.postal_code" name="c_diff_postal_zip">
		                    </div>
		                  </div>

		                  <div class="form-group row mb-5">
		                    <div class="col-md-6">
		                      <label for="c_diff_email_address" class="text-black">Email Address <span class="text-danger">*</span></label>
		                      <input type="text" class="form-control" id="c_diff_email_address" name="c_diff_email_address">
		                    </div>
		                    <div class="col-md-6">
		                      <label for="c_diff_phone" class="text-black">Phone <span class="text-danger">*</span></label>
		                      <input type="text" class="form-control" id="c_diff_phone" name="c_diff_phone" placeholder="Phone Number">
		                    </div>
		                  </div>

		                </div>

		              </div>
		            </div>

		            <div class="form-group">
		              <label for="c_order_notes" class="text-black">Order Notes</label>
		              <textarea name="c_order_notes" id="c_order_notes" cols="30" rows="5" class="form-control" placeholder="Write your notes here..."></textarea>
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
							<th>Qty</th>
		                    <th>Total</th>
		                  </thead>
		                  <tbody>
		                    <tr v-for="product in products" :key="product.id">
								<td>{{ product.product_name }} <strong class="mx-2"></strong></td>
								<td>{{ product.quantity }}</td>
								<td>${{ product.total_price.toFixed(2) }}</td>
							</tr>
		                    <tr>
		                      <td class="text-black font-weight-bold"><strong>Cart Subtotal</strong></td>
							  <td></td>
		                      <td class="text-black"> {{ cart_sub_total_amount ? "$" + cart_sub_total_amount : 0 }}</td>
		                    </tr>
							<tr>
		                      <td class="text-black font-weight-bold"><strong>Discount Total</strong></td>
							  <td></td>
		                      <td class="text-black font-weight-bold">{{ discount ? "$" + discount : 0 }}</td>
		                    </tr>
		                    <tr>
		                      <td class="text-black font-weight-bold"><strong>Order Total</strong></td>
							  <td></td>
		                      <td class="text-black font-weight-bold"><strong>{{ order_total_amount ? "$" + order_total_amount : 0 }}</strong></td>
		                    </tr>
		                  </tbody>
		                </table>

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
		                  <button class="btn btn-black btn-lg py-3 btn-block" onclick="window.location='thankyou.html'">Place Order</button>
		                </div>

		              </div>
		            </div>
		          </div>

		        </div>
		      </div>
		      <!-- </form> -->
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
			countries: [
				{ code: "AF", name: "Afghanistan" },
				{ code: "AL", name: "Albania" },
				{ code: "DZ", name: "Algeria" },
				{ code: "AD", name: "Andorra" },
				{ code: "AO", name: "Angola" },
				{ code: "AG", name: "Antigua and Barbuda" },
				{ code: "AR", name: "Argentina" },
				{ code: "AM", name: "Armenia" },
				{ code: "AU", name: "Australia" },
				{ code: "AT", name: "Austria" },
				{ code: "AZ", name: "Azerbaijan" },
				{ code: "BS", name: "Bahamas" },
				{ code: "BH", name: "Bahrain" },
				{ code: "BD", name: "Bangladesh" },
				{ code: "BB", name: "Barbados" },
				{ code: "BY", name: "Belarus" },
				{ code: "BE", name: "Belgium" },
				{ code: "BZ", name: "Belize" },
				{ code: "BJ", name: "Benin" },
				{ code: "BT", name: "Bhutan" },
				{ code: "BO", name: "Bolivia" },
				{ code: "BA", name: "Bosnia and Herzegovina" },
				{ code: "BW", name: "Botswana" },
				{ code: "BR", name: "Brazil" },
				{ code: "BN", name: "Brunei" },
				{ code: "BG", name: "Bulgaria" },
				{ code: "BF", name: "Burkina Faso" },
				{ code: "BI", name: "Burundi" },
				{ code: "KH", name: "Cambodia" },
				{ code: "CM", name: "Cameroon" },
				{ code: "CA", name: "Canada" },
				{ code: "CV", name: "Cape Verde" },
				{ code: "TD", name: "Chad" },
				{ code: "CL", name: "Chile" },
				{ code: "CN", name: "China" },
				{ code: "CO", name: "Colombia" },
				{ code: "KM", name: "Comoros" },
				{ code: "CR", name: "Costa Rica" },
				{ code: "HR", name: "Croatia" },
				{ code: "CU", name: "Cuba" },
				{ code: "CY", name: "Cyprus" },
				{ code: "CZ", name: "Czech Republic" },
				{ code: "DK", name: "Denmark" },
				{ code: "DO", name: "Dominican Republic" },
				{ code: "EC", name: "Ecuador" },
				{ code: "EG", name: "Egypt" },
				{ code: "FI", name: "Finland" },
				{ code: "FR", name: "France" },
				{ code: "GA", name: "Gabon" },
				{ code: "DE", name: "Germany" },
				{ code: "GH", name: "Ghana" },
				{ code: "GR", name: "Greece" },
				{ code: "IN", name: "India" },
				{ code: "ID", name: "Indonesia" },
				{ code: "IE", name: "Ireland" },
				{ code: "IT", name: "Italy" },
				{ code: "JP", name: "Japan" },
				{ code: "KE", name: "Kenya" },
				{ code: "KR", name: "South Korea" },
				{ code: "KW", name: "Kuwait" },
				{ code: "LA", name: "Laos" },
				{ code: "LV", name: "Latvia" },
				{ code: "LB", name: "Lebanon" },
				{ code: "LY", name: "Libya" },
				{ code: "LT", name: "Lithuania" },
				{ code: "LU", name: "Luxembourg" },
				{ code: "MY", name: "Malaysia" },
				{ code: "MX", name: "Mexico" },
				{ code: "NG", name: "Nigeria" },
				{ code: "NO", name: "Norway" },
				{ code: "PK", name: "Pakistan" },
				{ code: "PH", name: "Philippines" },
				{ code: "PL", name: "Poland" },
				{ code: "PT", name: "Portugal" },
				{ code: "RO", name: "Romania" },
				{ code: "RU", name: "Russia" },
				{ code: "SA", name: "Saudi Arabia" },
				{ code: "SG", name: "Singapore" },
				{ code: "ES", name: "Spain" },
				{ code: "SE", name: "Sweden" },
				{ code: "CH", name: "Switzerland" },
				{ code: "AE", name: "United Arab Emirates" },
				{ code: "GB", name: "United Kingdom" },
				{ code: "US", name: "United States" }
			],
		}
	}, 
	mounted(){
		this.getCheckoutDetails();
		this.getUser();
		setTimeout(() => {
			this.isLoading = false; 
		}, 1000);
	},
	methods: {
		async getUser(){
			try {
				const response = await apiRequest.getUser();
				if(response.data != undefined){
					this.user = response.data;
					console.log("user data : ", this.user);
				}
			} catch (error) {
				console.log(error);
			}
		},
		async getCheckoutDetails(){
			try {
				const userData = localStorage.getItem('user');
				const user = JSON.parse(userData);

				const formData = new FormData();
				formData.append('user_id', user.id);

				const response = await apiRequest.getCheckoutDetails(formData);
				if(response.data.success === 'true'){
					this.products = response.data.checkout;
					this.coupon_code = response.data.applied_coupon;
					this.cart_sub_total_amount = response.data.total_amount;
					this.order_total_amount = response.data.final_amount;
					this.discount = response.data.discount;
				}
			} catch (error) {
				console.log(error);
			}
		},
		async applyCoupon(params) {
			try {
				const userData = localStorage.getItem('user');
				const user = JSON.parse(userData);

				const formData = new FormData();
				formData.append('user_id', user.id);
				formData.append('coupon_code', params.coupon_code);
				formData.append('is_params', params.is_params);

				const response = await apiRequest.getCheckoutDetails(formData);
				if(response.data.success === 'true'){
					showAlert("success", "Success", response.data.message);
					this.products = response.data.checkout;
					this.coupon_code = response.data.applied_coupon;
					this.cart_sub_total_amount = response.data.total_amount;
					this.order_total_amount = response.data.final_amount;
					this.discount = response.data.discount;
				}
				if(response.data.success === 'false'){
					showAlert("error", "Oops!", response.data.message);
					this.products = response.data.checkout;
					this.coupon_code = response.data.applied_coupon;
					this.cart_sub_total_amount = response.data.total_amount;
					this.order_total_amount = response.data.final_amount;
					this.discount = response.data.discount;
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

</style>