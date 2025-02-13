<template>
    <!-- Start Hero Section -->
			<div class="hero">
				<div class="container">
					<div class="row justify-content-between">
						<div class="col-lg-5">
							<div class="intro-excerpt">
								<h1>Cart</h1>
							</div>
						</div>
						<div class="col-lg-7">
							
						</div>
					</div>
				</div>
			</div>
		<!-- End Hero Section -->

		

		<div class="untree_co-section before-footer-section">
            <div class="container">
              <div class="row mb-5" v-if="products.length > 0">
                <form class="col-md-12" method="post">
                  <div class="site-blocks-table">
                    <table class="table">
                      <thead>
                        <tr>
                          <th class="product-thumbnail">Image</th>
                          <th class="product-name">Product</th>
                          <th class="product-price">Price</th>
                          <th class="product-quantity">Quantity</th>
                          <th class="product-total">Total</th>
                          <th class="product-remove">Remove</th>
                        </tr>
                      </thead>
                      <tbody>
                          <tr v-for="(product, index) in products" :key="index">
                              <td class="product-thumbnail">
                                  <img :src="'data:image/png;base64,' + product.product_image" alt="Image" class="img-fluid">
                              </td>
                              <td class="product-name">
                                  <h2 class="h5 text-black">{{ product.product_name }}</h2>
                              </td>
                              <td>${{ product.product_price }}</td>
                              <td>
                                  <div class="input-group mb-3 d-flex align-items-center quantity-container" style="max-width: 120px;">
                                      <div class="input-group-prepend">
                                          <button class="btn btn-outline-black decrease" @click="decreaseQuantity(index)" type="button">&minus;</button>
                                      </div>
                                      <input type="text" class="form-control text-center quantity-amount" v-model="product.quantity" readonly>
                                      <div class="input-group-append">
                                          <button class="btn btn-outline-black increase" @click="increaseQuantity(index)" type="button">&plus;</button>
                                      </div>
                                  </div>
                              </td>
                              <td>${{ product.product_price * product.quantity }}</td>
                              <td><a class="btn btn-black btn-sm" @click="removeFromCart(product.product_id)">X</a></td>
                          </tr>
                      </tbody>
                    </table>
                  </div>
                </form>
              </div>
        
              <div class="row">
                <div class="col-md-6">
                  <div class="row mb-5">
                    <div class="col-md-6">
                      <router-link to="/shop" class="btn btn-outline-black btn-sm btn-block">Continue Shopping</router-link>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <label class="text-black h4" for="coupon">Coupon</label>
                      <p>Enter your coupon code if you have one.</p>
                    </div>
                    <div class="col-md-8 mb-3 mb-md-0">
                      <input type="text" class="form-control py-3"  v-model="couponCode"  id="coupon" placeholder="Coupon Code">
                    </div>
                    <div class="col-md-4">
                      <button class="btn btn-black"  @click="applyCoupon">Apply Coupon</button>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 pl-5">
                  <div class="row justify-content-end">
                    <div class="col-md-7">
                      <div class="row">
                        <div class="col-md-12 text-right border-bottom mb-5">
                          <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
                        </div>
                      </div>

                      <div class="row mb-3">
                        <div class="col-md-6">
                          <span class="text-black">Subtotal</span>
                        </div>
                        <div class="col-md-6 text-right">
                          <strong class="text-black">${{ subtotal }}</strong>
                        </div>
                      </div>
                       <!-- Coupon Discount Section -->
                      <div v-if="discountAmount > 0" class="row mb-3">
                        <div class="col-md-6">
                          <span class="text-black">Discounted by Coupon</span>
                        </div>
                        <div class="col-md-6 text-right">
                          <strong class="text-black">-${{ discountAmount.toFixed(2) }}</strong>
                        </div>
                      </div>
                      <div class="row mb-5">
                        <div class="col-md-6">
                          <span class="text-black">Total</span>
                        </div>
                        <div class="col-md-6 text-right">
                          <strong class="text-black">${{ total }}</strong>
                        </div>
                      </div>
        
                      <div class="row">
                        <div class="col-md-12">
                          <router-link class="btn btn-black btn-lg py-3 btn-block" to="/thankyou" >Proceed To Checkout</router-link>
                        </div>
                      </div>
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

export default{
  data(){
    return{
      products: [],
      couponCode: '',
      subtotal: 0,    
      discountAmount: 0,
      total: 0,
      couponData: null
    }
  },
  mounted(){
      this.fetchProducts();
  },
  computed: {
    subtotal() {
      return this.subtotal = this.products.reduce((total, product) => {
        return total + product.product_price * product.quantity;
      }, 0).toFixed(2);
    },

    total() {
      return (parseFloat(this.subtotal) - this.discountAmount).toFixed(2);
    },
  },
  methods: {
    async applyCoupon() {
      try {
        const formData = new FormData();
        formData.append('code', this.couponCode);
        const response = await apiRequest.applyCoupon(formData);
        if (response.data.success === 'true') {
            const coupon = response.data.coupon;
            this.couponData = coupon; 
            this.applyCouponDiscount(coupon);
        } else {
          this.discountAmount = 0;
            showAlert("error", "Oops!", response.data.message);
        }
      } catch (error) {
          console.error('Catch error', error);
      }
    },
    applyCouponDiscount(coupon) {

          let discountValueParsed = parseFloat(coupon.discount_value); 
          const subtotalParsed = parseFloat(this.subtotal);
          const minOrderAmmount = parseFloat(coupon.min_order_amount);

          let discountValue = 0;

          if (coupon.discount_type === 'percentage') {
            if (!isNaN(subtotalParsed) && subtotalParsed > 0 && subtotalParsed >= minOrderAmmount ) {
              discountValue = (subtotalParsed * discountValueParsed) / 100;
            }
          } else if (coupon.discount_type === 'fixed') {

            discountValue = discountValueParsed;
          }
          this.discountAmount = discountValue;
      },


      async fetchProducts(){
          try{
              const userData = localStorage.getItem('user');
              const user = JSON.parse(userData);

              const formData = new FormData();
              formData.append('user_id', user.id);

              const response = await apiRequest.getCartItems(formData);
              if(response.data.success == "true"){
                  this.products = response.data.products;
              }else{
                  console.error('Error fetching products', response.data);
              }
          }catch(error){
              console.error('Catch error ' , error);
          }
      },
      decreaseQuantity(index) {
          if (this.products[index].quantity > 1) {
              this.products[index].quantity--;
          }
      },
      increaseQuantity(index) {
          this.products[index].quantity++;
      },
      async removeFromCart(productId) {
          const userData = localStorage.getItem('user');
          const user = JSON.parse(userData);
          try{
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('user_id', user.id);

            const response = await apiRequest.removeFromCart(formData);
            if(response.data.success == "true"){
                this.fetchProducts();
                showAlert("success", "Success", response.data.message);
            }else{
                console.error('Error fetching products', response.data);
            }
          }catch(error){
            console.error('Catch error ' , error);
          }
      }
  }
}
</script>