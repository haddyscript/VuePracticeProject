

<template>
    <!-- Start Hero Section -->
			<div class="hero">
				<div class="container">
					<div class="row justify-content-between">
						<div class="col-lg-5">
							<div class="intro-excerpt">
								<h1>Services</h1>
								<p class="mb-4">Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique.</p>
								<p><a href="" class="btn btn-secondary me-2">Shop Now</a><a href="#" class="btn btn-white-outline">Explore</a></p>
							</div>
						</div>
						<div class="col-lg-7">
							<div class="hero-img-wrap">
								<img src="/images/couch.png" class="img-fluid">
							</div>
						</div>
					</div>
				</div>
			</div>
		<!-- End Hero Section -->

		<div v-if="isLoading" class="loader-container">
			<div class="loader"></div>
		</div>

		<div v-else> 
		<!-- Start Why Choose Us Section -->
		<div class="why-choose-section">
			<div class="container">
				
				
				<div class="row my-5">
					<div class="col-6 col-md-6 col-lg-3 mb-4">
						<div class="feature">
							<div class="icon">
								<img src="/images/truck.svg" alt="Image" class="imf-fluid">
							</div>
							<h3>Fast &amp; Free Shipping</h3>
							<p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
						</div>
					</div>

					<div class="col-6 col-md-6 col-lg-3 mb-4">
						<div class="feature">
							<div class="icon">
								<img src="/images/bag.svg" alt="Image" class="imf-fluid">
							</div>
							<h3>Easy to Shop</h3>
							<p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
						</div>
					</div>

					<div class="col-6 col-md-6 col-lg-3 mb-4">
						<div class="feature">
							<div class="icon">
								<img src="/images/support.svg" alt="Image" class="imf-fluid">
							</div>
							<h3>24/7 Support</h3>
							<p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
						</div>
					</div>

					<div class="col-6 col-md-6 col-lg-3 mb-4">
						<div class="feature">
							<div class="icon">
								<img src="/images/return.svg" alt="Image" class="imf-fluid">
							</div>
							<h3>Hassle Free Returns</h3>
							<p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
						</div>
					</div>

					<div class="col-6 col-md-6 col-lg-3 mb-4">
						<div class="feature">
							<div class="icon">
								<img src="/images/truck.svg" alt="Image" class="imf-fluid">
							</div>
							<h3>Fast &amp; Free Shipping</h3>
							<p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
						</div>
					</div>

					<div class="col-6 col-md-6 col-lg-3 mb-4">
						<div class="feature">
							<div class="icon">
								<img src="/images/bag.svg" alt="Image" class="imf-fluid">
							</div>
							<h3>Easy to Shop</h3>
							<p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
						</div>
					</div>

					<div class="col-6 col-md-6 col-lg-3 mb-4">
						<div class="feature">
							<div class="icon">
								<img src="/images/support.svg" alt="Image" class="imf-fluid">
							</div>
							<h3>24/7 Support</h3>
							<p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
						</div>
					</div>

					<div class="col-6 col-md-6 col-lg-3 mb-4">
						<div class="feature">
							<div class="icon">
								<img src="/images/return.svg" alt="Image" class="imf-fluid">
							</div>
							<h3>Hassle Free Returns</h3>
							<p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
						</div>
					</div>

				</div>
			
			</div>
		</div>
		<!-- End Why Choose Us Section -->

		<!-- Start Product Section -->
		<div class="product-section pt-0">
			<div class="container">
				<div class="row">

					<!-- Start Column 1 -->
					<div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
						<h2 class="mb-4 section-title">Crafted with excellent material.</h2>
						<p class="mb-4">Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. </p>
						<p><a href="#" class="btn">Explore</a></p>
					</div> 
					<!-- End Column 1 -->

					<!-- Start Column 2 -->
					<div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
						<a class="product-item" href="#">
							<img src="/images/product-1.png" class="img-fluid product-thumbnail">
							<h3 class="product-title">Nordic Chair</h3>
							<strong class="product-price">$50.00</strong>

							<span class="icon-cross">
								<img src="/images/cross.svg" class="img-fluid">
							</span>
						</a>
					</div> 
					<!-- End Column 2 -->

					<!-- Start Column 3 -->
					<div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
						<a class="product-item" href="#">
							<img src="/images/product-2.png" class="img-fluid product-thumbnail">
							<h3 class="product-title">Kruzo Aero Chair</h3>
							<strong class="product-price">$78.00</strong>

							<span class="icon-cross">
								<img src="/images/cross.svg" class="img-fluid">
							</span>
						</a>
					</div>
					<!-- End Column 3 -->

					<!-- Start Column 4 -->
					<div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
						<a class="product-item" href="#">
							<img src="/images/product-3.png" class="img-fluid product-thumbnail">
							<h3 class="product-title">Ergonomic Chair</h3>
							<strong class="product-price">$43.00</strong>

							<span class="icon-cross">
								<img src="/images/cross.svg" class="img-fluid">
							</span>
						</a>
					</div>
					<!-- End Column 4 -->

				</div>
			</div>
		</div>
		<!-- End Product Section -->
	</div>

</template>

<script>
export default {
	data() {
		return {
			isLoading: true,
			products: [],
			pagination: {},
			showModal: false,
      		electedProduct: {},
			quantity: 1,
		}
	},
	mounted() {
		setTimeout(() => {
			this.isLoading = false; 
		}, 1000);
	},
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
  border-top: 5px solid #3498db;
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